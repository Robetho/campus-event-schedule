<?php
include("../includes/session.php");
if (isset($_POST['generate-timetable'])) {
    $programme_id = $_POST['programme_id'];
    $program = explode(',', $programme_id);
  
    $all_timetables = [];
    mysqli_query($conn, "DELETE FROM tbl_programme_timetable WHERE programme_id IN ('".implode(',', $program)."') ");
    foreach ($program as $prog) {
        $prog = trim($prog);
        
        // Pata programme details
        $sql = "SELECT programme_capacity,programme_name FROM tbl_programmes WHERE prog_id = '$prog'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $rows = $result->fetch_assoc();
            $programme_capacity = $rows['programme_capacity'];
            $programme_name      = $rows['programme_name'];
        }
        

        // Pata rooms zinazotosha programme capacity
        $sql = "SELECT * FROM tbl_rooms WHERE room_capacity >= '$programme_capacity'";
        $result = $conn->query($sql);
        
        $rooms = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rooms[] = $row;
            }
        }

        // Pata masomo ya programme
        $sql = "SELECT course_id, course_name FROM tbl_assigned_course_to_programme WHERE programme_id = '$prog'";
        $result = $conn->query($sql);
        
        $courses = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $courses[] = $row;
            }
        }

        // Define time slots and days
        $time_slots = ["7:00-9:00", "9:00-11:00", "11:00-13:00", "13:00-15:00", "15:00-17:00", "17:00-19:00","19:00-21:00"];
        $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];

        $programme_timetable = [];
        $used_slots = [];
        $used_rooms = [];

        foreach ($courses as $course) {
            $count = 0;
            while ($count < 2) { // Ensure each course appears twice per week
                $day = $days[array_rand($days)];
                $slot = $time_slots[array_rand($time_slots)];

                if (in_array($day . $slot, $used_slots[$course['course_name']] ?? [] )) {
                    continue;
                }

                // Chagua chumba kinachofaa
                foreach ($rooms as $room) {
                    if (!isset($used_rooms[$day][$slot][$room['id']])) {
                        $programme_timetable[] = [
                            'course_name' => $course['course_name'],
                            'day' => $day,
                            'time_slot' => $slot,
                            'room_name' => $room['room_name']
                        ];
                        $academic = mysqli_query($conn, "SELECT * FROM tbl_academic_session WHERE status = 'active' ");
                        $info = mysqli_fetch_assoc($academic);
                        $a_year = $info['academic_year'];
                        $semester = $info['semester'];
                        $insert = mysqli_query($conn, "INSERT INTO tbl_programme_timetable (programme_id, programme_name, course_name, room_name, day, time_slot, academic_year, semester) VALUES ('$prog','$programme_name' ,'{$course['course_name']}', '{$room['room_name']}', '$day','$slot', '$a_year', '$semester') ");
                        $used_slots[$day][$slot] = true;
                        $used_rooms[$day][$slot][$room['id']] = true;
                        $count++;
                        break;
                    }
                }
            }
        }

        // Save timetable
        $all_timetables[$prog] = $programme_timetable;
    }

    $_SESSION['timetable'] = $all_timetables;

    // Redirect to display page
    header("Location: ../view-timetable.php");
    exit();
}
?>
