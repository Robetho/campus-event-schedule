<?php
	include 'database.php';

	if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
		$_SESSION['failed'] = "Please Login First";
		header('location: ../Auth/');
	}



	//Query to view all users
	$user_sql = "SELECT * FROM tbl_users";
	$all_users = $conn->query($user_sql);
	
?>