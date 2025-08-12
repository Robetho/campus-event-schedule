<?php
	include("../includes/session.php");

	//add department info session
	if (isset($_POST['add-department'])) {
		$department_name		= $_POST['department-name'];

		$check_department 	= mysqli_query($conn, "SELECT department_name FROM tbl_departments WHERE department_name = '$department_name' ");

		if (empty($department_name)) {
			$_SESSION['error'] = "Please fill all fields";
			header("location: ../add-department");
		}else if (mysqli_num_rows($check_department) > 0) {
			$_SESSION['error'] = "department name already exists, please try another name";
			header("location: ../add-department");
		}else{
			$insert_info = mysqli_query($conn, "INSERT INTO tbl_departments (department_name) VALUES 
				('$department_name')");
			if ($insert_info) {
				$_SESSION['success'] = "department Information have been successfully registered";
				header("location: ../manage-department");
			}else{
				$_SESSION['error'] = "Failed to register department Information";
				header("location: ../add-department");
			}
		}
	}


	//update department info session
	if (isset($_POST['update-department'])) {
		$department_id        	= $_POST['department-id'];
		$department_name		= $_POST['department-name'];

		$check_department 	= mysqli_query($conn, "SELECT department_name FROM tbl_departments WHERE id <> '$department_id' ");

		if (empty($department_name)) {
			$_SESSION['error'] = "Please fill all fields";
			header("location: ../edit-department-info?department_id=$department_id");
		}else if (mysqli_num_rows($check_department) > 0) {
			$_SESSION['error'] = "department name or department Short name already exists, please try another";
			header("location: ../edit-department-info?department_id=$department_id");
		}else{
			$update_info = mysqli_query($conn, "UPDATE tbl_departments SET department_name = '$department_name' WHERE id = '$department_id' ");
			if ($update_info) {
				$_SESSION['success'] = "department Information have been successfully Updated";
				header("location: ../manage-department");
			}else{
				$_SESSION['error'] = "Failed to Update department Information";
				header("location: ../edit-department-info?department_id=$department_id");
			}
		}
	}


?>