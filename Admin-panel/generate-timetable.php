<?php 
  include('includes/session.php');
  $page_title = "Generate Programme Timetable";
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
              echo "Campus Event and Scheduling Platform";
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
                      Generate Programme Timetable
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
                <form class="form-horizontal" method="POST" action="processing/generate-programme-timetable">
                  <div class="card-body">
                    <h4 class="card-title">Generate Programme Timetable</h4>
                    <?php
                    $id = []; // Initialize array to ensure it's always an array
                    // ****************************************************************************
                    // KWA KUTUMIA DISTINCT HAPA ILI KUEPUSHA ID KUJIRUDIA
                    // ****************************************************************************
                    $select_distinct_programmes = mysqli_query($conn, "SELECT DISTINCT programme_id FROM tbl_assigned_course_to_programme");
                    if( $select_distinct_programmes && mysqli_num_rows($select_distinct_programmes) > 0){
                      while( $row = mysqli_fetch_assoc($select_distinct_programmes)){
                        $id[] = $row['programme_id'];
                      }
                    }
                    ?>
                    <div class="form-group row">
                      <label
                        for="fname"
                        class="col-sm-3 text-end control-label col-form-label"
                        ><span style="color: red;">*</span></label
                      >
                      <div class="col-sm-9">
                        <input type="hidden" class="form-control" name="programme_id" value="<?php echo implode(',', $id);?>" readonly />
                        <?php if (empty($id)): ?>
                            <small class="form-text text-danger">No programmes with assigned courses found. Please assign courses first.</small>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  <div class="border-top">
                    <div class="card-body">
                      <button type="submit" name="generate-timetable" class="btn btn-primary">
                        Generate Timetable </button>
                    </div>
                  </div>
                </form>
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