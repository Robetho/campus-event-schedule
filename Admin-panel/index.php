<?php 
  include('includes/session.php');
  include('includes/all-queries.php');
  $page_title = "Dashboard";
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
              <h4 class="page-title">Dashboard</h4>
              <div class="ms-auto text-end">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Normal
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
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <a href="users-lists"> 
                <div class="box bg-success text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-account-multiple"></i><br>
                    <i class=""><?= $count_users ?></i>
                  </h1>
                  <h6 class="text-white">View All Users</h6>
                </div></a>
              </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <a href="manage-department">
                <div class="box bg-info text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-book-multiple"></i><br>
                    <i class=""><?= $count_dpt ?></i>
                  </h1>
                  <h6 class="text-white">View registered Departments</h6>
                </div>
              </a>
              </div>
            </div>

            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <a href="programme-lists">
                <div class="box bg-cyan text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-home"></i><br>
                    <i class=""><?= $count_prog ?></i>
                  </h1>
                  <h6 class="text-white">View All programme</h6>
                </div>
                </a>
              </div>
            </div>
            <!-- Column -->
            <div class="col-md-6 col-lg-2 col-xlg-3">
              <div class="card card-hover">
                <a href="course-lists">
                <div class="box bg-info text-center">
                  <h1 class="font-light text-white">
                    <i class="mdi mdi-book-multiple"></i><br>
                    <i class=""><?= $count_course ?></i>
                  </h1>
                  <h6 class="text-white">View All Course</h6>
                </div>
              </a>
              </div>
            </div>

          </div>
          <!-- ============================================================== -->
          <!-- Sales chart -->
          <!-- ============================================================== -->
          
          <!-- ============================================================== -->
          <!-- Sales chart -->
          <!-- ============================================================== -->
          <!-- ============================================================== -->
          <!-- Recent comment and chats -->
          <!-- ============================================================== -->
          <div class="row">
            <!-- column -->
            
            
          </div>
          <!-- ============================================================== -->
          <!-- Recent comment and chats -->
          <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
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
  </body>
</html>
