<?php 
	include('../includes/session.php');

	if (isset($_POST['update-profile'])) {
		$userID 		= $_POST['user_id'];
		$firstname 		= $_POST['first-name'];
		$middlename 	= $_POST['middle-name']; 
		$lastname 		= $_POST['last-name'];
		$email 			= $_POST['email'];

		$check_email 	= mysqli_query($conn, "SELECT email FROM tbl_users WHERE id != {$userID}");
		$verify_email 	= mysqli_num_rows($check_email);

		if (empty($firstname) || empty($middlename) || empty($lastname) || empty($email)) {
			$_SESSION['error'] = "Please fill all fields";
			header("location: ../my-profile");
		}
		else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$_SESSION['error'] = "Invalid Email Format";
			header("location: ../my-profile");
		}else{
			$upd = mysqli_query($conn, "UPDATE tbl_users SET firstname = '{$firstname}', middlename = '{$middlename}', lastname = '{$lastname}' 
					WHERE id = '{$userID}'");
			if ($upd) {
				$_SESSION['success'] = "Profile Information Updated Successfully";
				header("location: ../my-profile");
			}else{
				$_SESSION['error'] = "Failed To Update Your Profile";
				header("location: ../my-profile");
			}
		}

	}

?>