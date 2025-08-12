<?php 
  include('includes/session.php');
  include('includes/all-queries.php');
  $page_title = "Users Lists";
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
                      Users Lists
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
                <div class="card-body">
                  <h5 class="card-title">Users 
                    <a href="add-new-user" type="button" class="btn btn-info"><i class="mdi mdi-plus"></i> Add New User </a>
                    <!-- <a href="import-user-file" type="button" class="btn btn-info"><i class="mdi mdi-upload"></i> Import File </a> -->
                  </h5>
                  <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>FullName</th>
                          <th>Username</th>
                          <th>Email</th>
                          <th>Gender</th>
                          <th>Role</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $i = 1;
                        foreach ($all_users as $value) {
                          $middlname = $value['middlename'];
                        ?>
                        <tr>
                          <td width="10"><?= $i++;?></td>
                          <td width="20%"><?= $value['lastname'] . ' ' . $middlname[0] .', ' . $value['firstname']?></td>
                          <td width="15%"><?= $value['username']?></td>
                          <td><?= $value['email']?></td>
                          <td><?= $value['gender']?></td>
                          <td><?= $value['role_as']?></td>
                        </tr>
                        <?php
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
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
