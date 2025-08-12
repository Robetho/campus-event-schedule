<?php
	include("../includes/session.php");

	//add course info session
	if (isset($_POST['add-course'])) {
		$course_name		= $_POST['course-name'];
		$course_code  	= $_POST['course-code'];

		$check_course 	= mysqli_query($conn, "SELECT course_name FROM tbl_courses WHERE course_name = '$course_name' ");
		$check_code 	= mysqli_query($conn, "SELECT course_code FROM tbl_courses WHERE course_name = '$course_name' ");

		if (empty($course_name) || empty($course_code)) {
			$_SESSION['error'] = "Please fill all fields";
			header("location: ../add-new-course");
		}else if (!preg_match("/[ a-zA-Z]+$/", $course_name)) {
			$_SESSION['error'] = "course name require lowercase, uppercase and white space";
			header("location: ../add-new-course");
		}else if (mysqli_num_rows($check_course) > 0) {
			$_SESSION['error'] = "course name already exists, please try another name";
			header("location: ../add-new-course");
		}else if (mysqli_num_rows($check_code) > 0) {
			$_SESSION['error'] = "course Code already exists, please try another Code";
			header("location: ../add-new-course");
		}else{
			$insert_info = mysqli_query($conn, "INSERT INTO tbl_courses (course_name, course_code) VALUES 
				('$course_name', '$course_code')");
			if ($insert_info) {
				$_SESSION['success'] = "Course Information have been successfully registered";
				header("location: ../course-lists");
			}else{
				$_SESSION['error'] = "Failed to register Course Information";
				header("location: ../add-new-course");
			}
		}
	}


	//update course info session
	if (isset($_POST['update-course'])) {
		$course_id        	= $_POST['course-id'];
		$course_name		= $_POST['course-name'];
		$course_code  		= $_POST['course-code'];

		$check_course 	= mysqli_query($conn, "SELECT course_name, course_code FROM tbl_courses WHERE c_id <> '$course_id' ");

		if (empty($course_name) || empty($course_code)) {
			$_SESSION['error'] = "Please fill all fields";
			header("location: ../edit-course-info?course_id=$course_id");
		}else if (!preg_match("/[ a-zA-Z]+$/", $course_name)) {
			$_SESSION['error'] = "course name require lowercase, uppercase and white space";
			header("location: ../edit-course-info?course_id=$course_id");
		}else if (mysqli_num_rows($check_course) > 0) {
			$_SESSION['error'] = "Course name or Course Short name already exists, please try another";
			header("location: ../edit-course-info?course_id=$course_id");
		}else{
			$update_info = mysqli_query($conn, "UPDATE tbl_courses SET course_name = '$course_name', course_code = '$course_code' WHERE c_id = '$course_id' ");
			if ($update_info) {
				$_SESSION['success'] = "course Information have been successfully Updated";
				header("location: ../course-lists");
			}else{
				$_SESSION['error'] = "Failed to Update Course Information";
				header("location: ../edit-course-info?course_id=$course_id");
			}
		}
	}


?>