<?php 
  include('includes/session.php');
  include('includes/all-queries.php');
  $page_title = "View Timetable";
  // error_reporting(); // Ni bora kutotumia error_reporting() bila parameters, au tumia E_ALL kwa debugging.
                     // Kwa production, unaweza kutumia error_reporting(0); au kuisahau kabisa ili mfumo ufuata php.ini
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta
      name="keywords"
      content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Matrix lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Matrix admin lite design, Matrix admin lite dashboard bootstrap 5 dashboard template"
    />
    <meta
      name="description"
      content="Matrix Admin Lite Free Version is powerful and clean admin dashboard template, inpired from Bootstrap Framework"
    />
    <meta name="robots" content="noindex,nofollow" />
    <title>
        <?php 
            if ($page_title != '') {
                echo $page_title;
            }else{
              echo "Teacher Session and Signing Management System";
            }
        ?>
    </title>
    <?php include('partials/header.php');?>
  </head>

  <body>
    
    <div
      id="main-wrapper"
      data-layout="vertical"
      data-navbarbg="skin5"
      data-sidebartype="full"
      data-sidebar-position="absolute"
      data-header-position="absolute"
      data-boxed-layout="full"
    >
      <?php include('partials/top-navbar.php');?>
      <?php include('partials/sidebar.php');?>
      <div class="page-wrapper">
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title">Normal</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      View Timetable
                    </li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <div class="container-fluid">
            <?php include('includes/message-alert.php');?>
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">View Timetable </h5>
                  <?php
                    $sql = mysqli_query($conn, "SELECT DISTINCT(programme_id) FROM tbl_programme_timetable");
                    $programme_ids = [];
                    if (mysqli_num_rows($sql) > 0) {
                      while ($rows = mysqli_fetch_assoc($sql)) {
                            $programme_ids[] = $rows['programme_id'];
                      }
                    }
                    $time_slots = ["7:00-9:00", "9:00-11:00", "11:00-13:00", "13:00-15:00", "15:00-17:00", "17:00-19:00","19:00-21:00"];
                    $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
                    ?>
                    
                    <?php foreach ($programme_ids as $prog_id) {
                        $check_info = mysqli_query($conn, "SELECT * FROM tbl_programmes WHERE prog_id = '$prog_id' ");
                        $info       = mysqli_fetch_assoc($check_info);

                        echo "<h3>Programme Name: ".htmlspecialchars($info['programme_name']) ."</h3>";
                      
                      ?>
                        
                        <table class="table table-bordered">
                            <tr>
                              <th>Day</th>
                              <?php foreach($time_slots as $slot){ echo "<th>$slot</th>";}?>
                            </tr>
                              <?php
                                foreach($days as $day){
                                  echo "<tr>";
                                  echo "<td>$day</td>";

                                  foreach ($time_slots as $slot) {
                                      // --- MABADILIKO YAMEFANYIKA HAPA ---
                                      $sql_slot_entry = mysqli_query($conn, "SELECT course_name, room_name 
                                                                               FROM tbl_programme_timetable 
                                                                               WHERE programme_id = '$prog_id'
                                                                               AND day = '$day' 
                                                                               AND time_slot = '$slot' 
                                                                               LIMIT 1"); // <-- Hii ndio key: LIMIT 1

                                      if (mysqli_num_rows($sql_slot_entry) > 0) {
                                          $slot_entry_data = mysqli_fetch_assoc($sql_slot_entry);

                                          echo "<td style='background-color: #94b8b8; color: white;'>{$slot_entry_data['course_name']}<br><small>{$slot_entry_data['room_name']}</small></td>";
                                      } else {
                                          echo "<td style='background-color: #F2DEDE; color: #A94442;'><span>FREE</span></td>";
                                      }
                                  }
                                  echo "</tr>";
                                }
                              ?>
                        </table>
                   <?php 
                    }
                  ?>
                    
                  
                    
                  
                    
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            </div>
        </div>
        <?php include('partials/footer-copyright-text.php');?>
        </div>
      </div>
    <?php include('partials/footer-script.php');?>
  </body>
</html>