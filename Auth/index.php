<?php
   session_start();
   include('includes/database.php');
   $page_title = 'Campus Event and Scheduling Platform';
   
   if(isset($_SESSION['admin'])){
      header('location: ../Admin-panel/');
    }

    if(isset($_SESSION['student'])){
      header('location: ../Student-panel/');
    }
    if(isset($_SESSION['teacher'])){
      header('location: ../staff-panel/');
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>
         <?php 
            if ($page_title != '') {
               echo $page_title;
            }else{
               echo "Campus Event and Scheduling Platform";
            }
         ?>
      </title>
      <link rel="icon" href="images/logo/Nit_Logo.png" type="image/png" />
      <!-- bootstrap css -->
      <link rel="stylesheet" href="css/bootstrap.min.css" />
      <!-- site css -->
      <link rel="stylesheet" href="style.css" />
      <!-- responsive css -->
      <link rel="stylesheet" href="css/responsive.css" />
      <!-- color css -->
      <link rel="stylesheet" href="css/colors.css" />
      <!-- select bootstrap -->
      <link rel="stylesheet" href="css/bootstrap-select.css" />
      <!-- scrollbar css -->
      <link rel="stylesheet" href="css/perfect-scrollbar.css" />
      <!-- custom css -->
      <link rel="stylesheet" href="css/custom.css" />
      <!-- calendar file css -->
      <link rel="stylesheet" href="js/semantic.min.css" />
      <link rel="stylesheet" href="login-css/styles.css">
      <style>
          form {
              display: flex;
              flex-direction: column;
            }

        #downloadForm label {
          margin-bottom: 5px;
        }

        #downloadForm .prog {
          padding: 8px;
          margin-bottom: 15px;
          border: 1px solid #ddd;
          border-radius: 4px;
          width: 60%;
        }
        #downloadForm button {
          padding: 10px;
          background: #3498db;
          color: white;
          border: none;
          cursor: pointer;
          border-radius: 4px;
          width: 30%;
        }

        #downloadForm button:hover {
            background: #2980b9;
        }
      </style>
</head>
<body>
  <div class="container">
    <header >
      <div class="logo">
        <img src="images/logo/Nit_Logo.png" alt="School Logo">
      </div>
      <div class="header-content">
        <h1 style="color: white;"><?= $page_title;?> [ CESP ]</h1>
      </div>
      <div class="academic-year">
        
        <p id="date" style="color: white;"></p>
      </div>
    </header>

    <main>
      <div class="welcome-section">
        <h2>Welcome to CESP</h2><BR>
        <p></p>
        <div class="features">
          <h3>TIMETABLE MASTER</h3>
          <ul>
            <li>Generate class timetable</li>
            <li>Assign a department timetable</li>
          </ul>
          <h3>STAFF</h3>
          <ul>
            <li>View list Event assigned</li>
          </ul>
          <h3>Other (Special for Student only)</h3><br>
          <form id="downloadForm" action="download_timetable_pdf.php" method="POST">
                <label for="username">Choose Your Programme</label>
                <select class="prog" name="programme_id" required>
                    <option value="">-- Choose Programme --</option>
                    <?php
                        $result = $conn->query("SELECT * FROM tbl_programmes");
                        while ($row = $result->fetch_assoc()) {
                        echo "<option value='".$row['prog_id']."'>".$row['programme_name']."</option>";
                        }
                    ?>
                </select>
                <button type="submit">Download Timetable</button>
          </form>
          <ul>
             

          </ul> 
        </div>
        
      </div>

      <div class="login-section" style="height: 40vh;">
        <h2>Login</h2>
        <p class="instruction">Please Login to continue</p>
         <?php 
            if (isset($_SESSION['success'])) {
               echo "
                  <div class='alert alert-success' role='alert'>
                     ".$_SESSION['success']."
                  </div>
                  ";

               unset($_SESSION['success']);
            } 
            if (isset($_SESSION['error'])) {
                echo "
                     <div class='alert alert-danger' role='alert'>
                        ".$_SESSION['error']."
                     </div>
                  ";

               unset($_SESSION['error']);
            }
            if (isset($_SESSION['failed'])) {
                echo "
                     <div class='alert alert-danger' role='alert'>
                        ".$_SESSION['failed']."
                     </div>
                  ";

               unset($_SESSION['failed']);
            }
            if(isset($_SESSION['logout'])){
               echo "
                  <p style='color: red;'>".$_SESSION['logout']."</p>";
                  unset($_SESSION['logout']);
            }
            if (isset($_GET['message']) && $_GET['message'] === 'logged_out') {
                echo "<div class='alert alert-danger'>Logged out successfully.</div>";
            }
         ?>

        <form id="loginForm" method="POST" action="auth-controller">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Username" >
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Password" >
          <br>
          <button type="submit" name="login">Login</button>
        </form>
      </div>
    </main>
<br>
    <footer>
  <p class="left" style="color: white;">&copy; 2025 NIT</p>
  <p class="right" style="color: white;">Designed and Developed by: NIT DEVELOPER</p>
</footer>

  </div>

  <script src="login-css/script.js"></script>
</body>
</html>
