<?php 
  include('includes/session.php');
  $page_title = "My Profile";
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
                      My Profile
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
            <!-- <div class="col-3">
                <div class="card">
                <form class="form-horizontal">
                  <div class="card-body">
                    <h4 class="card-title">Profile Picture</h4>
                    
                  </div>
                </form>
              </div>
            </div> -->
            <div class="col-12">
                <div class="card">
                <form class="form-horizontal" method="POST" action="processing/update-profile">
                  <div class="card-body">
                    <h4 class="card-title">Profile</h4>
                    <div class="form-group row">
                      <div class="col-sm-10">
                          <input type="hidden" class="form-control" name="user_id" value="<?= $teacher['id']?>" id="fname" placeholder="Lastname" />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        for="lname"
                        class="col-sm-2 text-end control-label col-form-label"
                        >Username <span style="color: red;">*</span></label
                      >
                      <div class="col-sm-10">
                          <input type="text" class="form-control" name="username" value="<?= $teacher['username']?>" disabled id="fname" placeholder="Lastname" />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        for="fname"
                        class="col-sm-2 text-end control-label col-form-label"
                        >First Name <span style="color: red;">*</span></label
                      >
                      <div class="col-sm-10">
                        <input type="text" class="form-control" name="first-name" id="fname" value="<?= $teacher['firstname']?>" placeholder="Firstname" />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        for="lname"
                        class="col-sm-2 text-end control-label col-form-label"
                        >Middle Name <span style="color: red;">*</span></label
                      >
                      <div class="col-sm-10">
                          <input type="text" class="form-control" name="middle-name" value="<?= $teacher['middlename']?>" id="fname" placeholder="Middlename" />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        for="lname"
                        class="col-sm-2 text-end control-label col-form-label"
                        >Last Name <span style="color: red;">*</span></label
                      >
                      <div class="col-sm-10">
                          <input type="text" class="form-control" name="last-name" value="<?= $teacher['lastname']?>" id="fname" placeholder="Lastname" />
                      </div>
                    </div>
                    <div class="form-group row">
                      <label
                        for="lname"
                        class="col-sm-2 text-end control-label col-form-label"
                        >Email <span style="color: red;">*</span></label
                      >
                      <div class="col-sm-10">
                          <input type="email" class="form-control" name="email" value="<?= $teacher['email']?>" id="fname" placeholder="Email" />
                      </div>
                    </div>
                  </div>
                  <div class="border-top">
                    <div class="card-body">
                      <button type="submit" name="update-profile" class="btn btn-primary">
                        Update Profile
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
  </body>
</html>

