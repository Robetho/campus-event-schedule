<?php 
	include('../includes/session.php');

	//add new staff execution
	if (isset($_POST['add-new-user'])) {
		$firstname 		= $_POST['first-name'];
		$middlename 	= $_POST['middle-name']; 
		$lastname 		= $_POST['last-name'];
		$username 		= $_POST['username'];
		$email 			= $_POST['email'];
		$password 		= $_POST['password'];
		$c_password 	= $_POST['c_password'];
		$gender 		= $_POST['gender'];
		$dpt   			= $_POST['department-name'];
		$role_as 		= $_POST['role_as'];

		
		if (empty($firstname) || empty($middlename) || empty($lastname) || empty($email) || empty($username) || empty($password)
			|| empty($gender) || empty($c_password) || empty($role_as)) {
			$_SESSION['error'] = "Please fill all fields";
			header("location: ../add-new-user");
		}
		else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$_SESSION['error'] = "Invalid Email Format";
			header("location: ../add-new-user");
		}
		else if($c_password != $password){
			$_SESSION['error'] = "Comfirm Password can not match to password";
			header("location: ../add-new-user");
		}else{
			$hash_pass = password_hash($password, PASSWORD_DEFAULT);
			$upd = mysqli_query($conn, "INSERT INTO tbl_users(firstname, middlename, lastname, username, password, email, gender, department_name , role_as) 
					VALUES ('{$firstname}', '{$middlename}', '{$lastname}', '{$username}','{$hash_pass}','{$email}' ,'{$gender}', '{$dpt}' ,'{$role_as}')");
			if ($upd) {
				$_SESSION['success'] = "User Account Successfully created";
				header("location: ../users-lists");
			}else{
				$_SESSION['error'] = "Failed To add new user";
				header("location: ../add-new-user");
			}
		}

	}

	


	//update exist user
	if (isset($_POST['edit-user'])) {
		$userID 		= $_POST['user_id'];
		$firstname 		= $_POST['first-name'];
		$middlename 	= $_POST['middle-name']; 
		$lastname 		= $_POST['last-name'];
		$username 		= $_POST['username'];
		$phone			= $_POST['phone_number'];
		$email 			= $_POST['email'];
		$gender 		= $_POST['gender'];
		$dpt   			= $_POST['department-name'];
		$role_as 		= $_POST['role_as'];

		
		if (empty($firstname) || empty($middlename) || empty($lastname) || empty($email) || empty($phone) || empty($username) || empty($gender) || empty($role_as)) {
			$_SESSION['error'] = "Please fill all fields";
			header("location: ../edit-user-info?staff_id=$userID");
		}
		else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$_SESSION['error'] = "Invalid Email Format";
			header("location: ../edit-user-info?staff_id=$userID");
		}else{
			$upd = mysqli_query($conn, "UPDATE tbl_users SET firstname ='{$firstname}', middlename = '{$middlename}' , lastname = '{$lastname}', 
				username = '{$username}' , email = '{$email}', phone_number = '{$phone}' ,gender = '{$gender}', department_name = '{$dpt}' , role_as = '{$role_as}' WHERE id = '{$userID}'");
			if ($upd) {
				$_SESSION['success'] = "User Account Successfully Updated";
				header("location: ../users-lists");
			}else{
				$_SESSION['error'] = "Failed To Update user Account";
				header("location: ../users-lists");
			}
		}

	}

	

	
?>