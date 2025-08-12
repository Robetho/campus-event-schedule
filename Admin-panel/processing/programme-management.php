<?php
	include("../includes/session.php");

	//add programme info session
	if (isset($_POST['add-programme'])) {
		$prog_name		= $_POST['programme-name'];
		$prog_short		= $_POST['programme-short-name'];
		$prog_capacity  = $_POST['programme-capacity'];

		$check_prog 	= mysqli_query($conn, "SELECT * FROM tbl_programmes WHERE programme_name = '$prog_name' ");

		if (empty($prog_name) || empty($prog_short) || empty($prog_capacity)) {
			$_SESSION['error'] = "Please fill all fields";
			header("location: ../add-new-programme");
		}else if ($prog_capacity <= '0') {
			$_SESSION['error'] = "Programme Capacity is too short";
			header("location: ../add-new-programme");
		}else if (!preg_match("/[ a-zA-Z]+$/", $prog_name)) {
			$_SESSION['error'] = "Programme name require lowercase, uppercase and white space";
			header("location: ../add-new-programme");
		}else if (!preg_match("/[ a-zA-Z]+$/", $prog_short)) {
			$_SESSION['error'] = "Programme Short name require lowercase, uppercase and white space";
			header("location: ../add-new-programme");
		}else if (mysqli_num_rows($check_prog) > 0) {
			$_SESSION['error'] = "Programme name already exists, please try another name";
			header("location: ../add-new-programme");
		}else{
			$insert_info = mysqli_query($conn, "INSERT INTO tbl_programmes (programme_name, programme_short_name, programme_capacity) VALUES 
				('$prog_name', '$prog_short', '$prog_capacity')");
			if ($insert_info) {
				$_SESSION['success'] = "Programme Information have been successfully registered";
				header("location: ../programme-lists");
			}else{
				$_SESSION['error'] = "Failed to register Programme Information";
				header("location: ../add-new-programme");
			}
		}
	}


	//update programme info session
	if (isset($_POST['update-programme'])) {
		$prog_id        = $_POST['prog_id'];
		$prog_name		= $_POST['programme-name'];
		$prog_short		= $_POST['programme-short-name'];
		$prog_capacity  = $_POST['programme-capacity'];

		$check_prog 	= mysqli_query($conn, "SELECT programme_name, programme_short_name FROM tbl_programmes WHERE prog_id <> '$prog_id' ");

		if (empty($prog_name) || empty($prog_short) || empty($prog_capacity)) {
			$_SESSION['error'] = "Please fill all fields";
			header("location: ../edit-programme-info?prog_id=$prog_id");
		}else if ($prog_capacity <= '0') {
			$_SESSION['error'] = "Programme Capacity is too short";
			header("location: ../edit-programme-info?prog_id=$prog_id");
		}else if (!preg_match("/[ a-zA-Z]+$/", $prog_name)) {
			$_SESSION['error'] = "Programme name require lowercase, uppercase and white space";
			header("location: ../edit-programme-info?prog_id=$prog_id");
		}else if (!preg_match("/[ a-zA-Z]+$/", $prog_short)) {
			$_SESSION['error'] = "Programme Short name require lowercase, uppercase and white space";
			header("location: ../edit-programme-info?prog_id=$prog_id");
		}else if (mysqli_num_rows($check_prog) > 0) {
			$_SESSION['error'] = "Programme name or Programme Short name already exists, please try another";
			header("location: ../edit-programme-info?prog_id=$prog_id");
		}else{
			$update_info = mysqli_query($conn, "UPDATE tbl_programmes SET programme_name = '$prog_name', programme_short_name = '$prog_short', programme_capacity = '$prog_capacity' WHERE prog_id = '$prog_id' ");
			if ($update_info) {
				$_SESSION['success'] = "Programme Information have been successfully Updated";
				header("location: ../programme-lists");
			}else{
				$_SESSION['error'] = "Failed to Update Programme Information";
				header("location: ../edit-programme-info?prog_id=$prog_id");
			}
		}
	}


?>