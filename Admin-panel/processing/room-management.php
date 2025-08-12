<?php
	include("../includes/session.php");

	//add room info session
	if (isset($_POST['add-room'])) {
		$room_name		= $_POST['room-name'];
		$room_capacity  = $_POST['room-capacity'];

		$check_room 	= mysqli_query($conn, "SELECT room_name FROM tbl_rooms WHERE room_name = '$room_name' ");

		if (empty($room_name) || empty($room_capacity)) {
			$_SESSION['error'] = "Please fill all fields";
			header("location: ../add-new-room");
		}else if ($room_capacity <= '0') {
			$_SESSION['error'] = "room Capacity is too short";
			header("location: ../add-new-room");
		}else if (mysqli_num_rows($check_room) > 0) {
			$_SESSION['error'] = "room name already exists, please try another name";
			header("location: ../add-new-room");
		}else{
			$insert_info = mysqli_query($conn, "INSERT INTO tbl_rooms (room_name, room_capacity) VALUES 
				('$room_name', '$room_capacity')");
			if ($insert_info) {
				$_SESSION['success'] = "room Information have been successfully registered";
				header("location: ../room-lists");
			}else{
				$_SESSION['error'] = "Failed to register room Information";
				header("location: ../add-new-room");
			}
		}
	}


	//update room info session
	if (isset($_POST['update-room'])) {
		$room_id        = $_POST['room-id'];
		$room_name		= $_POST['room-name'];
		$room_capacity 	= $_POST['room-capacity'];

		//$check_room 	= mysqli_query($conn, "SELECT room_name FROM tbl_rooms WHERE id <> $room_id ");

		if (empty($room_name) || empty($room_capacity)) {
			$_SESSION['error'] = "Please fill all fields";
			header("location: ../edit-room-info?room_id=$room_id");
		}else if ($room_capacity <= '0') {
			$_SESSION['error'] = "room Capacity is too short";
			header("location: ../edit-room-info?room_id=$room_id");
		}

		// else if (mysqli_num_rows($check_room) > 0) {
		// 	$_SESSION['error'] = "room name already exists, please try another";
		// 	header("location: ../edit-room-info?room_id=$room_id");
		// }
		else{
			$update_info = mysqli_query($conn, "UPDATE tbl_rooms SET room_name = '$room_name', room_capacity = '$room_capacity' WHERE id = '$room_id' ");
			if ($update_info) {
				$_SESSION['success'] = "room Information have been successfully Updated";
				header("location: ../room-lists");
			}else{
				$_SESSION['error'] = "Failed to Update room Information";
				header("location: ../edit-room-info?room_id=$room_id");
			}
		}
	}


?>