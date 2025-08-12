<?php 
	include('../includes/session.php');

	if (isset($_POST['change-password'])) {
		$userID 		= $_POST['user_id'];
		$curr_password 	= $_POST['current-password'];
		$new_password 	= $_POST['new-password']; 
		$c_password 	= $_POST['comfirm-password'];


		if (empty($curr_password) || empty($new_password) || empty($c_password)) {
			$_SESSION['error'] = "Please fill all fields";
			header("location: ../change-password");
		}
		else if ($c_password != $new_password) {
			$_SESSION['error'] = "Comfimr Password Can not  match to New Password";
			header("location: ../change-password");
		}else{
			$verify_password = password_verify($curr_password, $user['password']);
			if (!$verify_password) {
				$_SESSION['error'] = "Current Password Can not Match";
				header("location: ../change-password");
			}else{
				$hashed_pass = password_hash($new_password, PASSWORD_DEFAULT);
				$upd = mysqli_query($conn, "UPDATE tbl_users SET password = '{$hashed_pass}' WHERE id = '{$userID}'");
				if ($upd) {
					session_destroy();
					session_start();
					$_SESSION['success'] = "Password Changed Successfully. Please Login again";
					header("location: ../../Auth/");
				}else{
					$_SESSION['error'] = "Failed To Update Password";
					header("location: ../change-password");
				}
			}
		}

	}

?>