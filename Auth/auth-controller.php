<?php
    session_start();
    include 'includes/database.php';
    
    if(isset($_POST['login'])){
        $username = trim($_POST['username']);
         $password = trim($_POST['password']);
   
        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'All Fields are required.';
        }else{
                $sql = "SELECT * FROM tbl_users WHERE username = '$username'";
                $query = $conn->query($sql);
        
                if($query->num_rows < 1){
                    $_SESSION['error'] = "Cannot find User with the Username '" . $username ."'";
                }
                else{
                    $row = $query->fetch_assoc();
                    if(password_verify($password, $row['password'])){
                        if ($row['role_as'] =="Admin") {
                            $_SESSION['admin'] = $row['id'];
                            $_SESSION['loggedIn'] = "Logged In Successfuly";
                            
                        }else if ($row['role_as'] =="Teacher") {
                            $_SESSION['teacher'] = $row['id'];
                            $_SESSION['loggedIn'] = "Logged In Successfuly";
                        }else if ($row['role_as'] =="Student") {
                            $_SESSION['student'] = $row['id'];
                            $_SESSION['loggedIn'] = "Logged In Successfuly";
                        }
                        else{
                            $_SESSION['error'] = 'Incorrect Login Details';
                        }
                }else{
                    $_SESSION['error'] = 'Incorrect password';
                }
            }
        }
    }
    

    header('location: index');

?>