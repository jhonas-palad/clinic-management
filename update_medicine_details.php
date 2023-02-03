<?php 
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';

if(isset($_POST['submit'])) {

  $medicineName = $_POST['medicine_name'];
  $medicineId = $_POST['medicine_id'];
  $total_capsules = $_POST['total_capsules'];  
  $expire_date = $_POST['expire_date'];

  $expireDateArr = explode("/", $expire_date);

  $cleanExpireDate = $expireDateArr[2] . '-' . $expireDateArr[0] . '-' . $expireDateArr[1];
  

  $query = "UPDATE `medicine_details` 
  set `medicine_name` = '$medicineName', 
  `total_capsules` = '$total_capsules',
  `expire_date` = '$cleanExpireDate' 
  where `id` = $medicineId;";

  try {

    $con->beginTransaction();

    $stmtUpdate = $con->prepare($query);
    $stmtUpdate->execute();

    $con->commit();

    $message = 'medicine details updated successfully.';

  }  catch(PDOException $ex) {
    $con->rollback();

    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit;
  }
  header("location:congratulation.php?goto_page=medicine_details.php&message=$message");
  exit;
}

$medicineId = $_GET['medicine_id'];
$medicineDetailId = $_GET['medicine_detail_id'];
$total_capsules = $_GET['total_capsules'];
$expire_date = $_GET['expire_date'];




$medicine = getMedicine($con, $medicineId);



?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php';?>
 <?php include './config/data_tables_css.php';?>
 <title>Update Medicine Details - Clinic's Patient Management System in PHP</title>

</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->

    <?php include './config/header.php';
include './config/sidebar.php';?>  
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Medicine Details</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Default box -->
        <div class="card card-outline card-primary rounded-0 shadow">
          <div class="card-header">
            <h3 class="card-title">Update Medicine Details</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
              
            </div>
          </div>
          <div class="card-body">
            <form method="post">

              <input type="hidden" name="medicine_id" 
              value="<?php echo $medicineDetailId;?>" />

              <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                  <!-- <label>Select Medicine</label>
                  <select id="medicine" name="medicine" class="form-control form-control-sm rounded-0" required="required">
                    <?php echo $medicines;?>
                  </select> -->
                  <label>Medicine Name</label>
                  <input type="text" id="medicine_name" name="medicine_name" value="<?= $medicine->medicine_name ?>"
                  class="form-control form-control-sm rounded-0" />
                </div>

                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                  <label>Packing</label>
                  <input id="packing" name="total_capsules" class="form-control form-control-sm rounded-0"  required="required" value="<?php echo $total_capsules;?>" />
                </div>

                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-10">
                  <div class="form-group">
                    <label>Expire Date</label>
                    <div class="input-group date" 
                      id="expire_date" 
                      data-target-input="nearest">
                      <input type="text" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#expire_date" value="<?= $medicine->expire_date ?>" name="expire_date" required="required" data-toggle="datetimepicker" autocomplete="off"/>
                      <div class="input-group-append" 
                        data-target="#expire_date" 
                        data-toggle="datetimepicker">
                        <div class="input-group-text">
                          <i class="fa fa-calendar"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-1 col-md-2 col-sm-4 col-xs-12">
                  <label>&nbsp;</label>
                  <button type="submit" id="submit" name="submit" 
                  class="btn btn-primary btn-sm btn-flat btn-block">Update</button>
                </div>
              </div>
            </form>
          </div>
          <!-- /.card-body -->
          
        </div>
        <!-- /.card -->

      </section>



      <!-- /.content-wrapper -->
    </div>

    <?php include './config/footer.php';

    $message = '';
    if(isset($_GET['message'])) {
      $message = $_GET['message'];
    }
    ?>  
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <?php include './config/site_js_links.php'; ?>
  <?php include './config/data_tables_js.php'; ?>
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <script>
    showMenuSelected("#mnu_medicines", "#mi_medicine_details");

    var message = '<?php echo $message;?>';
    $('#expire_date').datetimepicker({
      format:"L"
    });
    if(message !== '') {
      showCustomMessage(message);
    }


  </script>
</body>
</html>