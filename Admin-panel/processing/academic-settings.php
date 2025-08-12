<?php
	include("../includes/session.php");

	//add course info session
	if (isset($_POST['add-academic'])) {
		$academic_year	= $_POST['academic-year'];
		$semester 	    = $_POST['semester'];

		$check_year     = mysqli_query($conn, "SELECT academic_year FROM tbl_academic_session WHERE academic_year = '$academic_year' ");
		if (empty($academic_year) || empty($semester)) {
			$_SESSION['error'] = "Please fill all fields";
			header("location: ../add-new-academic");
		}else if (mysqli_num_rows($check_year) > 0) {
			$_SESSION['error'] = "Academic Year already exists, please try another";
			header("location: ../add-new-academic");
		}else{
			mysqli_query($conn, "UPDATE tbl_academic_session SET status = 'inactive' WHERE status = 'inactive' " );
			$insert = mysqli_query($conn, "INSERT INTO tbl_academic_session (academic_year, semester) VALUES 
					('$academic_year', '$semester')");

			if ($insert) {
				$_SESSION['success'] = "Academic Session have been successfully registered";
				header("location: ../academic-settings");
			}else{
				$_SESSION['error'] = "Failed to add Academic Session. Try again";
				header("location: ../add-new-academic");
			}
		}
	}

	if (isset($_POST['update-academic'])) {
		$academic_id	= $_POST['id'];
		$academic_year	= $_POST['academic-year'];
		$semester 	    = $_POST['semester'];

		$check_year     = mysqli_query($conn, "SELECT academic_year FROM tbl_academic_session WHERE id <> '$academic_id' ");
		if (empty($academic_year) || empty($semester)) {
			$_SESSION['error'] = "Please fill all fields";
			header("location: ../edit-academic-info?academic_id=$academic_id");
		}else if (mysqli_num_rows($check_year) > 0) {
			$_SESSION['error'] = "Academic Year already exists, please try another";
			header("location: ../edit-academic-info?academic_id=$academic_id");
		}else{
			$update = mysqli_query($conn, "UPDATE tbl_academic_session SET academic_year ='$academic_year', semester = '$semester' WHERE id = '{$academic_id}' ");

			if ($update) {
				$_SESSION['success'] = "Academic Session have been successfully Updated";
				header("location: ../academic-settings");
			}else{
				$_SESSION['error'] = "Failed to Update Academic Session. Try again";
				header("location: ../edit-academic-info?academic_id=$academic_id");
			}
		}
	}



?>