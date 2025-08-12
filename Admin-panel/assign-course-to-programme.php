<?php 
  include('includes/session.php');
  $page_title = "Assign course to programme";
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
                    <li class="breadcrumb-item"><a href="#">Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                      Assign course to programme
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
                <form class="form-horizontal" method="POST" action="processing/course-assignment-management">
                  <div class="card-body">
                    <h4 class="card-title">Assign course to programme</h4>
                    <div class="form-group row">
                      <label
                        for="lname"
                        class="col-sm-3 text-end control-label col-form-label"
                        >Select Programme <span style="color: red;">*</span></label
                      >
                                <div class="col-sm-9">
                                  <select class="select2 form-select shadow-none" required style="width: 100%; height: 10vh" name="programme_id">
                                    <option disabled selected> ~~ Select One ~~</option>
                                    <?php
                                    $prog = mysqli_query($conn, "SELECT * FROM tbl_programmes");
                                    while ($key = mysqli_fetch_assoc($prog)) {
                                      ?>
                                        <option value="<?= $key['prog_id']?>"><?= $key['programme_name']?> (Capacity: <?= $key['programme_capacity']?> )</option>
                                      <?php
                                    }
                                    ?>
                                  </select>
                                </div>
                              </div>
                      <div class="form-group row">
                        <label
                        for="lname"
                        class="col-sm-3 text-end control-label col-form-label"
                        >Select Multiple Course <span style="color: red;">*</span></label
                      >
                              <div class="col-sm-9">
                                <select class="select2 form-select shadow-none mt-3" required multiple="multiple" style="height: 36px; width: 100%" name="course_id[]" >
                                    <option disabled> ~~ Select One ~~</option>
                                    <?php
                                    $prog = mysqli_query($conn, "SELECT * FROM tbl_courses");
                                    while ($key = mysqli_fetch_assoc($prog)) {
                                      ?>
                                        <option value="<?= $key['c_id']?>"><?= $key['course_name']?></option>
                                      <?php
                                    }
                                    ?>
                                  </select>
                              </div>
                            </div>
                    
                  </div>
                  <div class="border-top">
                    <div class="card-body">
                      <button type="submit" name="assign-course-to-programme" class="btn btn-primary">
                        Save Data
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
   
  <script>
      
        function addRow() {
  $("#addRowBtn").button("loading");

  var tableLength = $("#teacherTable tbody tr").length;

  var tableRow;
  var arrayNumber;
  var count;

  if(tableLength > 0) {   
    tableRow = $("#teacherTable tbody tr:last").attr('id');
    arrayNumber = $("#teacherTable tbody tr:last").attr('class');
    count = tableRow.substring(3);  
    count = Number(count) + 1;
    arrayNumber = Number(arrayNumber) + 1;          
  } else {
    // no table row
    count = 1;
    arrayNumber = 0;
  }

  $.ajax({
    url: 'fetchProgrames.php',
    type: 'post',
    dataType: 'json',
    success:function(response) {
      $("#addRowBtn").button("reset");      

      var tr = '<tr id="row'+count+'" class="'+arrayNumber+'">'+                
        '<td>'+
          '<div class="form-group">'+
            '<div class="col-sm-12">'+
                '<select class="select2 form-select shadow-none form-control" name="programme_id[]" id="programme_id'+count+'" onchange="getprogrammeData('+count+')" >'+
            '<option value="">~~SELECT~~</option>';
            // console.log(response);
            $.each(response, function(index, value) {
              tr += '<option value="'+value[0]+'" >'+value[1]+'</option>';             
            });
                          
          tr += '</select>'+
            '</div>'+
          '</div>'+
        '</td>'+
        '<td>'+
          '<div class="form-group">'+
            '<div class="col-sm-12">'+
               '<select class="select2 form-select shadow-none form-control" name="course_id[]" multiple="multiple">'+
            '<option value="">~~ Select subject ~~</option>'+
            '<?php 
                           $sql = mysqli_query($conn, " SELECT * from tbl_courses");
              while($row=mysqli_fetch_assoc($sql)){
                             $id = $row['c_id'];
                             $course = $row['course_name'];
             ?>'+
                '<option value="<?php echo $id ?>"><?php echo $course ?> </option>'+
                '<?php
                              }
                    mysqli_close($conn);
            ?>'+
                          
          '</select>'+
            '</div>'+
          '</div>'+
        '</td>'+
        '<td>'+
          '<button class="btn btn-primary removeteacherRowBtn" type="button" onclick="addRow('+count+')"><i class="fa fa-plus"></i></button>'+
        '</td>'+
        '<td>'+
          '<button class="btn btn-danger removeteacherRowBtn" type="button" onclick="removeteacherRow('+count+')"><i class="fa fa-trash"></i></i></button>'+
        '</td>'+

      '</tr>';
      if(tableLength > 0) {             
        $("#teacherTable tbody tr:last").after(tr);
      } else {        
        $("#teacherTable tbody").append(tr);
      }   

    } // /success
  }); // get the product data

}


    function removeteacherRow(row = null) {
      if(row) {
        $("#row"+row).remove();

      } else {
        alert('error! Refresh the page again');
      }
  }
    </script>
</html>
