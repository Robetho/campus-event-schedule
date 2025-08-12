<?php
  if(isset($_SESSION['error'])){
    echo "
        <div class='alert alert-danger alert-dismissible'>
        <h4><i class='icon fa fa-warning'></i> Error!</h4>
          ".$_SESSION['error']."
        </div>
        ";
    unset($_SESSION['error']);
  }
  if(isset($_SESSION['success'])){
    echo "
        <div class='alert alert-success alert-dismissible'>
        <h4><i class='icon fa fa-check'></i> Success!</h4>
            ".$_SESSION['success']."
        </div>
      ";
    unset($_SESSION['success']);
  }
  if(isset($_SESSION['loggedIn'])){
    echo "
        <div class='alert alert-success alert-dismissible'>
          ".$_SESSION['loggedIn']."
        </div>
      ";
    unset($_SESSION['loggedIn']);
  } 
?>