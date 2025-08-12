<?php
	session_start();
	include 'database.php';

	if(!isset($_SESSION['admin']) || trim($_SESSION['admin']) == ''){
		$_SESSION['failed'] = "Please Login First";
		header('location: ../Auth/');
	}

	$sql = "SELECT * FROM tbl_users WHERE id = '".$_SESSION['admin']."'";
	$query = $conn->query($sql);
	$user = $query->fetch_assoc();
	
?>