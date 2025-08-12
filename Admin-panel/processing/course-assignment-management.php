<?php
include("../includes/session.php");

//add course info session
if (isset($_POST['assign-course-to-programme'])) {
    $prog_id = $_POST['programme_id'];
    $course_ids = $_POST['course_id']; // Tunaita $course_ids kwa sababu ni array

    // --- DEBUGGING START ---
    error_log("Course Assignment: Received request for Programme ID: " . $prog_id);
    error_log("Course Assignment: Received Course IDs: " . implode(', ', $course_ids));
    // --- DEBUGGING END ---

    if (empty($prog_id) || empty($course_ids)) {
        $_SESSION['error'] = "Please select a Programme and at least one Course.";
        header("location: ../assign-course-to-programme");
        exit(); // Muhimu ku-exit baada ya header redirect
    } else {
        // Start a transaction for atomicity
        mysqli_begin_transaction($conn);

        try {
            // 1. Futa rekodi zote zilizopo za programme hii kwanza
            $delete_sql = "DELETE FROM tbl_assigned_course_to_programme WHERE programme_id = ?";
            $stmt_delete = mysqli_prepare($conn, $delete_sql);
            if (!$stmt_delete) {
                throw new Exception("Error preparing delete statement: " . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt_delete, "i", $prog_id);
            if (!mysqli_stmt_execute($stmt_delete)) {
                throw new Exception("Error deleting existing course assignments: " . mysqli_error($conn));
            }
            mysqli_stmt_close($stmt_delete);

            // --- DEBUGGING START ---
            error_log("Course Assignment: Deleted existing assignments for Programme ID: " . $prog_id);
            // --- DEBUGGING END ---

            // 2. Ingiza upya masomo yote yaliyochaguliwa
            $prog_info = mysqli_query($conn, "SELECT programme_name FROM tbl_programmes WHERE prog_id = '$prog_id'");
            if (!$prog_info || mysqli_num_rows($prog_info) == 0) {
                throw new Exception("Programme with ID {$prog_id} not found.");
            }
            $info = mysqli_fetch_assoc($prog_info);
            $prog_name = mysqli_real_escape_string($conn, $info['programme_name']);

            $insert_sql = "INSERT INTO tbl_assigned_course_to_programme (programme_id, programme_name, course_id, course_name, course_code) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = mysqli_prepare($conn, $insert_sql);
            if (!$stmt_insert) {
                throw new Exception("Error preparing insert statement: " . mysqli_error($conn));
            }

            foreach ($course_ids as $course_id_single) { // Badilisha course_id kuwa course_id_single kwa uwazi
                $course_id_single = trim($course_id_single);

                $select_course = mysqli_query($conn, "SELECT course_name, course_code FROM tbl_courses WHERE c_id = '$course_id_single'");
                if (!$select_course || mysqli_num_rows($select_course) == 0) {
                    // Optional: Log a warning if a selected course ID doesn't exist
                    error_log("WARNING: Selected course ID '{$course_id_single}' not found for assignment to programme {$prog_id}. Skipping.");
                    continue; // Skip this course and go to the next
                }
                $course_info = mysqli_fetch_assoc($select_course);

                $c_name = mysqli_real_escape_string($conn, $course_info['course_name']);
                $c_code = mysqli_real_escape_string($conn, $course_info['course_code']);

                mysqli_stmt_bind_param($stmt_insert, "issss", $prog_id, $prog_name, $course_id_single, $c_name, $c_code);
                if (!mysqli_stmt_execute($stmt_insert)) {
                    throw new Exception("Error inserting course '{$c_name}' for programme '{$prog_name}': " . mysqli_error($conn));
                }
                // --- DEBUGGING START ---
                error_log("Course Assignment: Successfully assigned Course ID: {$course_id_single} ('{$c_name}') to Programme ID: {$prog_id} ('{$prog_name}').");
                // --- DEBUGGING END ---
            }

            mysqli_stmt_close($stmt_insert);

            // Commit the transaction if all operations were successful
            mysqli_commit($conn);
            $_SESSION['success'] = "Course Information has been successfully assigned to programme.";
            header("location: ../assigned-course-to-programme");
            exit();

        } catch (Exception $e) {
            // Rollback the transaction in case of any error
            mysqli_rollback($conn);
            $_SESSION['error'] = "Failed to assign Course Information: " . $e->getMessage();
            error_log("Course Assignment Error: " . $e->getMessage() . " at line " . $e->getLine() . " in " . $e->getFile());
            header("location: ../assign-course-to-programme");
            exit();
        }
    }
}
?>