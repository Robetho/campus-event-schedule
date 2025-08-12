<?php
include("../includes/session.php"); // Hakikisha path ni sahihi

// Define global constants for time slots and days
$time_slots = ['7:00-9:00', '9:00-11:00', '11:00-13:00', '13:00-15:00', '15:00-17:00', '17:00-19:00', '19:00-21:00'];
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

// Enable more detailed error logging for debugging the algorithm
ini_set('log_errors', 'On');
ini_set('error_log', __DIR__ . '/../logs/timetable_generation_errors.log'); // Hakikisha folder ya 'logs' ipo na ina ruhusa ya kuandika
error_reporting(E_ALL);

if (isset($_POST['generate-timetable'])) {
    $programme_id_string = $_POST['programme_id'];
    $program_ids_array = array_filter(array_map('trim', explode(',', $programme_id_string))); // Clean and filter empty values

    error_log("Timetable Generation: Starting for Program IDs: " . implode(', ', $program_ids_array) . " at " . date('Y-m-d H:i:s'));

    // Start a transaction for atomicity.
    mysqli_begin_transaction($conn);

    try {
        if (empty($program_ids_array)) {
            throw new Exception("No programmes selected for timetable generation.");
        }

        // Delete existing timetables for the selected programmes.
        $escaped_program_ids = array_map(function($id) use ($conn) {
            return mysqli_real_escape_string($conn, $id);
        }, $program_ids_array);
        
        $delete_sql = "DELETE FROM tbl_programme_timetable WHERE programme_id IN ('" . implode("','", $escaped_program_ids) . "')";
        error_log("Timetable Generation: Executing delete SQL: " . $delete_sql);
        if (!mysqli_query($conn, $delete_sql)) {
            throw new Exception("Error deleting existing timetables: " . mysqli_error($conn));
        }
        error_log("Timetable Generation: Existing timetables deleted for selected programs.");

        // Fetch ALL rooms once globally.
        // Order by capacity helps in allocating smaller rooms to smaller classes first, potentially leaving larger ones for larger classes.
        $sql_all_rooms = "SELECT id, room_name, room_capacity FROM tbl_rooms ORDER BY room_capacity ASC"; 
        $result_all_rooms = $conn->query($sql_all_rooms);
        if (!$result_all_rooms) {
            throw new Exception("Error fetching rooms: " . mysqli_error($conn));
        }
        $all_available_rooms = $result_all_rooms->fetch_all(MYSQLI_ASSOC);
        error_log("Timetable Generation: Fetched " . count($all_available_rooms) . " available rooms.");

        if (empty($all_available_rooms)) {
            throw new Exception("No rooms found in the system. Please add rooms before generating a timetable.");
        }

        // Get the current active academic session.
        $academic_query = mysqli_query($conn, "SELECT academic_year, semester FROM tbl_academic_session WHERE status = 'active'");
        if (!$academic_query) {
            throw new Exception("Error fetching academic session: " . mysqli_error($conn));
        }
        $academic_info = mysqli_fetch_assoc($academic_query);
        $active_academic_year = $academic_info['academic_year'] ?? null;
        $active_semester = $academic_info['semester'] ?? null;

        if (empty($active_academic_year) || empty($active_semester)) {
            throw new Exception("No active academic session found. Please set an active academic session in the system.");
        }
        error_log("Timetable Generation: Active Academic Session: {$active_academic_year}, Semester: {$active_semester}");

        // --- GLOBAL ALLOCATIONS FOR THIS GENERATION RUN ---
        // This tracks all rooms allocated across ALL selected programs during this single generation process.
        // Format: $global_allocations[day][time_slot][room_id] = true (occupied)
        $global_allocations = [];
        foreach ($days as $day_init) {
            foreach ($time_slots as $slot_init) {
                $global_allocations[$day_init][$slot_init] = [];
            }
        }
        error_log("Timetable Generation: Initialized global allocations array.");

        // Prepare an array to hold all timetable entries before a final bulk insert
        $timetable_entries_to_insert = [];
        $overall_warnings = []; // To collect warnings for all programs

        // --- Iterate through each selected program to gather its timetable entries ---
        foreach ($program_ids_array as $prog_id_single) {
            $prog_id_single = (int) $prog_id_single; // Cast to integer for safety
            error_log("Timetable Generation: Processing Programme ID: {$prog_id_single}");

            // Get detailed information for the current program
            $sql_prog = "SELECT programme_capacity, programme_name FROM tbl_programmes WHERE prog_id = '$prog_id_single'";
            $result_prog = $conn->query($sql_prog);
            if (!$result_prog) {
                throw new Exception("Error fetching programme details for ID {$prog_id_single}: " . mysqli_error($conn));
            }

            if ($result_prog->num_rows > 0) {
                $programme_data = $result_prog->fetch_assoc();
                $programme_capacity = $programme_data['programme_capacity'];
                $programme_name = mysqli_real_escape_string($conn, $programme_data['programme_name']);
                error_log("Timetable Generation: Programme '{$programme_name}' (ID: {$prog_id_single}) has capacity {$programme_capacity}.");
            } else {
                $overall_warnings[] = "Programme with ID '{$prog_id_single}' not found. Skipping timetable generation for it.";
                error_log("Timetable Generation: WARNING - Programme ID '{$prog_id_single}' not found. Skipping.");
                continue;
            }

            // Filter rooms suitable for this program.
            $suitable_rooms_for_program = array_filter($all_available_rooms, function($room) use ($programme_capacity) {
                return $room['room_capacity'] >= $programme_capacity;
            });
            $suitable_rooms_for_program = array_values($suitable_rooms_for_program); // Re-index
            error_log("Timetable Generation: Found " . count($suitable_rooms_for_program) . " suitable rooms for programme '{$programme_name}'.");

            if (empty($suitable_rooms_for_program)) {
                $overall_warnings[] = "No suitable rooms (with capacity >= {$programme_capacity}) found for programme '{$programme_name}' (ID: {$prog_id_single}). Skipping timetable generation for it.";
                error_log("Timetable Generation: WARNING - No suitable rooms for '{$programme_name}'. Skipping.");
                continue;
            }

            // Get all distinct courses assigned to this specific program.
            $sql_courses = "SELECT DISTINCT course_id, course_name FROM tbl_assigned_course_to_programme WHERE programme_id = '$prog_id_single'";
            $result_courses = $conn->query($sql_courses);
            if (!$result_courses) {
                throw new Exception("Error fetching courses for programme ID {$prog_id_single}: " . mysqli_error($conn));
            }
            $courses_for_program = $result_courses->fetch_all(MYSQLI_ASSOC);
            error_log("Timetable Generation: Fetched " . count($courses_for_program) . " distinct courses for programme '{$programme_name}'.");

            if (empty($courses_for_program)) {
                $overall_warnings[] = "No courses assigned to programme '{$programme_name}' (ID: {$prog_id_single}). Skipping timetable generation for it.";
                error_log("Timetable Generation: WARNING - No courses assigned to '{$programme_name}'. Skipping.");
                continue;
            }

            // --- Scheduling algorithm for the current program's courses ---
            // Each course needs 2 sessions. We represent this by adding each course twice to the initial list.
            $courses_to_schedule = [];
            foreach ($courses_for_program as $course) {
                // Add course twice to signify it needs two sessions
                $courses_to_schedule[] = ['course_id' => $course['course_id'], 'course_name' => $course['course_name']];
                $courses_to_schedule[] = ['course_id' => $course['course_id'], 'course_name' => $course['course_name']];
            }
            
            // This will track the actual number of sessions scheduled for each course
            $course_actual_scheduled_count = array_fill_keys(array_column($courses_for_program, 'course_id'), 0);
            
            // This will track days a course has been scheduled on to enforce "once per day"
            $course_scheduled_days = [];
            foreach ($courses_for_program as $course_data) {
                $course_scheduled_days[$course_data['course_id']] = [];
            }

            // Shuffle courses initially for fairer distribution
            shuffle($courses_to_schedule); 

            $max_scheduling_attempts = count($courses_to_schedule) * count($days) * count($time_slots) * count($suitable_rooms_for_program) * 2; 
            $current_attempt = 0;
            $initial_sessions_needed = count($courses_to_schedule);

            error_log("Timetable Generation: Starting scheduling loop for '{$programme_name}'. Total sessions needed: {$initial_sessions_needed}. Max attempts: {$max_scheduling_attempts}");

            // Loop until all sessions are scheduled or max attempts reached
            // The logic here is a greedy algorithm with randomization for a better chance of success.
            while (!empty($courses_to_schedule) && $current_attempt < $max_scheduling_attempts) {
                $progress_in_this_pass = false; // Flag to check if any course was scheduled in this iteration
                $next_pass_courses_to_schedule = []; // Courses that couldn't be scheduled in this pass

                // Prioritize courses that have fewer sessions scheduled
                usort($courses_to_schedule, function($a, $b) use ($course_actual_scheduled_count) {
                    return $course_actual_scheduled_count[$a['course_id']] <=> $course_actual_scheduled_count[$b['course_id']];
                });

                foreach ($courses_to_schedule as $course_item) {
                    $current_course_id = $course_item['course_id'];
                    $current_course_name = mysqli_real_escape_string($conn, $course_item['course_name']);

                    // If this specific session for this course has already been added to entries_to_insert, skip.
                    // This is covered by removing from courses_to_schedule, but a safeguard.
                    if ($course_actual_scheduled_count[$current_course_id] >= 2) {
                        continue; // Already scheduled its two sessions, skip.
                    }

                    $session_successfully_scheduled = false;
                    
                    // Shuffle potential days, time slots, and rooms for this attempt for variety
                    $shuffled_days = $days;
                    shuffle($shuffled_days);
                    $shuffled_time_slots = $time_slots;
                    shuffle($shuffled_time_slots);
                    $shuffled_rooms = $suitable_rooms_for_program;
                    shuffle($shuffled_rooms); // Randomize rooms to avoid always picking the same one first

                    foreach ($shuffled_days as $day_to_try) {
                        // CONSTRAINT 1: A course must not be scheduled on the same day twice.
                        if (in_array($day_to_try, $course_scheduled_days[$current_course_id])) {
                            //error_log("Timetable Generation: Skipping day {$day_to_try} for course {$current_course_id} - already scheduled on this day for Programme {$prog_id_single}.");
                            continue; 
                        }

                        foreach ($shuffled_time_slots as $slot_to_try) {
                            foreach ($shuffled_rooms as $room_data) {
                                $room_id_to_try = $room_data['id'];
                                $room_name_to_try = mysqli_real_escape_string($conn, $room_data['room_name']);
                                
                                // CONSTRAINT 2: Is this room available GLOBALLY for this day/time slot?
                                if (!isset($global_allocations[$day_to_try][$slot_to_try][$room_id_to_try])) {
                                    // Found a suitable and globally available slot and room!
                                    
                                    // Mark the room as globally occupied for this day/time/room combination
                                    $global_allocations[$day_to_try][$slot_to_try][$room_id_to_try] = true;
                                    
                                    // Update local tracking for the current course.
                                    $course_actual_scheduled_count[$current_course_id]++;
                                    $course_scheduled_days[$current_course_id][] = $day_to_try; // Add the day for this course

                                    $session_successfully_scheduled = true; 
                                    $progress_in_this_pass = true; // Mark that progress was made

                                    // Prepare data for bulk insert later
                                    $timetable_entries_to_insert[] = [
                                        'programme_id' => $prog_id_single,
                                        'programme_name' => $programme_name,
                                        'course_id' => $current_course_id,
                                        'course_name' => $current_course_name,
                                        'room_id' => $room_id_to_try,
                                        'room_name' => $room_name_to_try,
                                        'day' => mysqli_real_escape_string($conn, $day_to_try),
                                        'time_slot' => mysqli_real_escape_string($conn, $slot_to_try),
                                        'academic_year' => mysqli_real_escape_string($conn, $active_academic_year),
                                        'semester' => mysqli_real_escape_string($conn, $active_semester)
                                    ];
                                    error_log("Timetable Generation: Scheduled '" . $current_course_name . "' ({$current_course_id}) for '{$programme_name}' at {$day_to_try} / {$slot_to_try} in {$room_name_to_try}. Sessions scheduled for this course: {$course_actual_scheduled_count[$current_course_id]}.");
                                    
                                    break 3; // Break out of room, time, and day loops
                                }
                            } // End foreach shuffled_rooms
                        } // End foreach shuffled_time_slots
                    } // End foreach shuffled_days

                    // If a session for this course was NOT successfully scheduled in this pass,
                    // add it back to the list for the next attempt.
                    if (!$session_successfully_scheduled) {
                        $next_pass_courses_to_schedule[] = $course_item;
                        //error_log("Timetable Generation: Course {$current_course_id} in Programme {$prog_id_single} failed to schedule session in this pass. Remaining attempts: " . ($max_scheduling_attempts - $current_attempt));
                    }
                } // End foreach ($courses_to_schedule as $course_item)

                $courses_to_schedule = $next_pass_courses_to_schedule; // Update list for next pass
                $current_attempt++; // Increment overall attempts
                
                // Crucial check: If no progress was made in this pass, and there are still courses
                // that need sessions, it means we've hit a deadlock or resource limitation.
                // Stop trying to prevent infinite loops.
                if (!$progress_in_this_pass && !empty($courses_to_schedule)) {
                    error_log("Timetable Generation: No progress made in this pass for '{$programme_name}'. Breaking scheduling loop. Remaining courses to schedule: " . count($courses_to_schedule));
                    foreach ($courses_to_schedule as $c_item) {
                        // Check if the original course needs more sessions (not this specific item which might be a duplicate)
                        if ($course_actual_scheduled_count[$c_item['course_id']] < 2) {
                            $overall_warnings[] = "Warning: Failed to schedule all 2 required sessions for '" . htmlspecialchars($c_item['course_name']) . "' in programme '" . htmlspecialchars($programme_name) . "' due to resource constraints or scheduling conflicts. Scheduled: " . $course_actual_scheduled_count[$c_item['course_id']] . " of 2 sessions.";
                            error_log("Timetable Generation: WARNING - Course '" . $c_item['course_name'] . "' for '{$programme_name}' failed to get all sessions. Scheduled: {$course_actual_scheduled_count[$c_item['course_id']]}.");
                        }
                    }
                    break; // Break the while loop as no more progress is possible
                }
            } // End while (!empty($courses_to_schedule) ...)

            // Final check after scheduling loop for a programme: Report any courses that didn't get 2 sessions.
            foreach ($courses_for_program as $course_data) {
                $course_id = $course_data['course_id'];
                if ($course_actual_scheduled_count[$course_id] < 2) {
                     // Only add warning if it hasn't been added already by the break condition
                    $already_warned = false;
                    foreach ($overall_warnings as $warning_msg) {
                        if (strpos($warning_msg, "'" . htmlspecialchars($course_data['course_name']) . "' in programme '" . htmlspecialchars($programme_name) . "'") !== false) {
                            $already_warned = true;
                            break;
                        }
                    }
                    if (!$already_warned) {
                        $overall_warnings[] = "Warning: Course '" . htmlspecialchars($course_data['course_name']) . "' in programme '" . htmlspecialchars($programme_name) . "' (ID: {$prog_id_single}) only has {$course_actual_scheduled_count[$course_id]} of 2 required sessions scheduled.";
                        error_log("Timetable Generation: FINAL CHECK WARNING - Course '" . $course_data['course_name'] . "' for '{$programme_name}' only has {$course_actual_scheduled_count[$course_id]} of 2 sessions.");
                    }
                } elseif ($course_actual_scheduled_count[$course_id] > 2) {
                    // This is a critical error if it happens, indicating over-scheduling
                    $overall_warnings[] = "ERROR: Course '" . htmlspecialchars($course_data['course_name']) . "' in programme '" . htmlspecialchars($programme_name) . "' (ID: {$prog_id_single}) was scheduled {$course_actual_scheduled_count[$course_id]} times, more than the required 2 sessions. This indicates a logic error in the algorithm.";
                    error_log("Timetable Generation: CRITICAL ERROR - Course '" . $course_data['course_name'] . "' for '{$programme_name}' was OVER-SCHEDULED: {$course_actual_scheduled_count[$course_id]} sessions.");
                }
            }
            error_log("Timetable Generation: Finished processing Programme ID: {$prog_id_single}. Total entries gathered for this program: " . count(array_filter($timetable_entries_to_insert, fn($entry) => $entry['programme_id'] == $prog_id_single)));

        } // End foreach ($program_ids_array as $prog_id_single)

        // --- BULK INSERT ALL GATHERED TIMETABLE ENTRIES ---
        if (!empty($timetable_entries_to_insert)) {
            $values_sql = [];
            foreach ($timetable_entries_to_insert as $entry) {
                $values_sql[] = "('" . $entry['programme_id'] . "', " .
                                "'" . $entry['programme_name'] . "', " .
                                "'" . $entry['course_id'] . "', " .
                                "'" . $entry['course_name'] . "', " .
                                "'" . $entry['room_id'] . "', " .
                                "'" . $entry['room_name'] . "', " .
                                "'" . $entry['day'] . "', " .
                                "'" . $entry['time_slot'] . "', " .
                                "'" . $entry['academic_year'] . "', " .
                                "'" . $entry['semester'] . "')";
            }
            $insert_all_sql = "INSERT INTO tbl_programme_timetable 
                                (programme_id, programme_name, course_id, course_name, room_id, room_name, day, time_slot, academic_year, semester) 
                                VALUES " . implode(",", $values_sql);
            
            error_log("Timetable Generation: Attempting bulk insert of " . count($timetable_entries_to_insert) . " entries.");
            if (!mysqli_query($conn, $insert_all_sql)) {
                throw new Exception("Error during bulk insert of timetable entries: " . mysqli_error($conn));
            }
            error_log("Timetable Generation: Bulk insert completed successfully.");
        } else {
            error_log("Timetable Generation: No timetable entries to insert for any selected program.");
            if (empty($overall_warnings)) { // Only if no specific warnings were issued.
                 $overall_warnings[] = "No timetable entries could be generated for the selected programmes. Please check if courses are assigned, rooms are available, and academic session is active.";
            }
        }

        // If all operations completed without throwing an exception, commit all changes.
        mysqli_commit($conn);
        
        $_SESSION['success'] = "Timetable generation process completed successfully.";
        
        if (!empty($overall_warnings)) {
            // Append new warnings to existing ones if any, or create a new array
            // Using a unique key to prevent overwriting existing session messages
            $_SESSION['generation_warnings'] = $overall_warnings; 
            error_log("Timetable Generation: Warnings generated: " . implode('; ', $overall_warnings));
        }

    } catch (Exception $e) {
        // If an exception occurs at any point, roll back all database changes.
        mysqli_rollback($conn);
        $_SESSION['error'] = "An error occurred during timetable generation: " . $e->getMessage();
        // Log the error with more details for debugging
        error_log("Timetable Generation FATAL ERROR: " . $e->getMessage() . " at line " . $e->getLine() . " in " . $e->getFile(), 0);
    }

    header("Location: ../view-timetable.php");
    exit();
} else {
    // Redirect if accessed directly without POST request
    $_SESSION['error'] = "Invalid request to generate timetable.";
    header("Location: ../index.php"); // Or wherever your form is
    exit();
}
?>