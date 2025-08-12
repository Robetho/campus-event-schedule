<?php
	session_start();
	include 'database.php';

	if(!isset($_SESSION['teacher']) || trim($_SESSION['teacher']) == ''){
		$_SESSION['failed'] = "Please Login First";
		header('location: ../Auth/');
	}

	$sql = "SELECT * FROM tbl_users WHERE id = '".$_SESSION['teacher']."'";
	$query = $conn->query($sql);
	$teacher = $query->fetch_assoc();
	
?>