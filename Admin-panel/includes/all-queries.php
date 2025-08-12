<?php
	include 'database.php';

	//Query to Count and view all users
	$all_users 		= mysqli_query($conn, "SELECT * FROM tbl_users");
	$count_users  	= mysqli_num_rows($all_users);


	//Query to Count and view all programme
	$all_prog 	= mysqli_query($conn, "SELECT * FROM tbl_programmes");
	$count_prog	= mysqli_num_rows($all_prog);

	// //Query to Count and view all Subjects
	$all_course 	= mysqli_query($conn, "SELECT * FROM tbl_courses");
	$count_course  	= mysqli_num_rows($all_course);


	$all_room 	= mysqli_query($conn, "SELECT * FROM tbl_rooms");
	$count_room  	= mysqli_num_rows($all_room);
	
	$all_academic 	= mysqli_query($conn, "SELECT * FROM tbl_academic_session ORDER BY id DESC");

	$all_dpt 	= mysqli_query($conn, "SELECT * FROM tbl_departments");
	$count_dpt  	= mysqli_num_rows($all_dpt);
	
?>