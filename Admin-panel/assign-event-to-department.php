<?php 
  include('includes/session.php');
  $page_title = "Assign Event To Department";
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
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
              echo "Campus Event and Scheduling Platform";
            }
        ?>
    </title>
    <!-- Favicon icon -->
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
      <!-- ============================================================== -->
      <!-- Topbar header - style you can find in pages.scss -->
      <!-- ============================================================== -->
      <?php include('partials/top-navbar.php');?>
      <!-- ============================================================== -->
      <!-- End Topbar header -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      <?php include('partials/sidebar.php');?>
      <!-- ============================================================== -->
      <!-- End Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Page wrapper  -->
      <!-- ============================================================== -->
      <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
          <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
              <h4 class="page-title">Normal</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Authentication</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Assign Event To Department
                    </li>
                  </ol>
                </nav>
              </div>
            </div>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
           <?php include('includes/message-alert.php');?>
          <!-- ============================================================== -->
          <!-- Sales Cards  -->
          <!-- ============================================================== -->
          <div class="row">
            <div class="col-12">
                <div class="card">
                <form class="form-horizontal" method="POST" action="processing/process-event-assignment">
                  <div class="card-body">
                    <h4 class="card-title">Assign Event To Department</h4>
                    <div class="form-group row" >
                      <label class="col-sm-3 text-end control-label col-form-label">Select Department <span class="required" style="color: red;">*</span></label>
                      <div class="col-sm-9">
                        <select name="department-name" id="department-name"  class="form-control">
                          <option disabled selected> ~~ Select Department ~~</option>
                          <?php
                            $select = mysqli_query($conn, "SELECT * FROM tbl_departments");
                            while ($dpt = mysqli_fetch_assoc($select)) {
                                ?>
                                  <option value="<?= $dpt['department_name']?>"><?= $dpt['department_name']?></option>
                                <?php
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                    <?php
                    $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
                    ?>
                    <div class="form-group row" >
                      <label class="col-sm-3 text-end control-label col-form-label">Select Day <span class="required" style="color: red;">*</span></label>
                      <div class="col-sm-9">
                        <select name="day" required onchange="fetchFreeSlots(this.value)" id="day"  class="form-control">
                          <option value="">--Select Day--</option>
                            <?php foreach ($days as $day) { echo "<option value='$day'>$day</option>"; } ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row" >
                      <label class="col-sm-3 text-end control-label col-form-label">Select Time Slot <span class="required" style="color: red;">*</span></label>
                      <div class="col-sm-9">
                        <select name="time_slot" id="time_slots" required  class="form-control">
                          <option disabled selected> ~~ Select Time ~~</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row" >
                      <label class="col-sm-3 text-end control-label col-form-label">Select Free Room <span class="required" style="color: red;">*</span></label>
                      <div class="col-sm-9">
                        <select name="room_name" id="room_names" required  class="form-control">
                          <option disabled selected> ~~ Select Room ~~</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-3 text-end control-label col-form-label" for="event-name">Event Name<span class="required" style="color: red;">*</span>
                      </label>
                      <div class="col-sm-9">
                        <input type="text" id="event-name"  name="event-name" class="form-control ">
                      </div>
                    </div>
                  </div>
                  <div class="border-top">
                    <div class="card-body">
                      <button type="submit" class="btn btn-primary">
                        Assign Task
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- column -->
            
            
          </div>
        </div>
        <!-- footer -->
        <!-- ============================================================== -->
        <?php include('partials/footer-copyright-text.php');?>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!-- End Page wrapper  -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <?php include('partials/footer-script.php');?>
    <script>
        function fetchFreeSlots(day) {
            let xhr = new XMLHttpRequest();
            xhr.open("GET", "fetch_free_slots.php?day=" + day, true);
            xhr.onload = function () {
                if (this.status == 200) {
                    let data = JSON.parse(this.responseText);
                    
                    let timeSelect = document.getElementById("time_slots");
                    timeSelect.innerHTML = "<option value=''>--Select Time Slot--</option>";
                    data.time_slots.forEach(slot => {
                        timeSelect.innerHTML += `<option value="${slot}">${slot}</option>`;
                    });

                    let roomSelect = document.getElementById("room_names");
                    roomSelect.innerHTML = "<option value=''>--Select Venue--</option>";
                    data.rooms.forEach(room => {
                        roomSelect.innerHTML += `<option value="${room}">${room}</option>`;
                    });
                }
            };
            xhr.send();
        }
    </script>
    <!-- <script>
        function updateOptions() {
            const selectedrole = document.getElementById("role_as").value;

            const parent_info = document.getElementById("department");
            const parent_field1 = document.getElementById("department-name");

            if (selectedrole === "Teacher") {
              parent_info.style.display = "block";
            }else if(selectedrole === "Admin"){
              parent_info.style.display = "none";
            }
            

            

        }
    </script> -->
  </body>
</html>
