<?php 
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';

if(isset($_POST['submit'])) {

  $patientId = $_POST['patient'];
  $visitDate = $_POST['visit_date'];
  $nextVisitDate = $_POST['next_visit_date'];
  $bp = $_POST['bp'];
  $weight = $_POST['weight'];
  $disease = $_POST['disease'];


  $quantities = $_POST['quantities'];

  $visitDateArr = explode("/", $visitDate);

  $medicineCapsulesLeft = $_POST['medicineCapsulesLeft'];
  $medicineCapsulesQty = $_POST['medicineCapsulesQty'];
  //Medicine IDs to be updated
  $medicineIdsToUpdate = array_keys($medicineCapsulesQty);
  
  $visitDate = $visitDateArr[2].'-'.$visitDateArr[0].'-'.$visitDateArr[1];

  if($nextVisitDate != '') {
    $nextVisitDateArr = explode("/", $nextVisitDate);
    $nextVisitDate = $nextVisitDateArr[2].'-'.$nextVisitDateArr[0].'-'.$nextVisitDateArr[1];
  }


  try {

    $con->beginTransaction();

      //first to store a row in patient visit

    $queryVisit = "INSERT INTO `patient_visits`(`visit_date`, 
    `next_visit_date`, `bp`, `weight`, `disease`, `patient_id`) 
    VALUES('$visitDate', 
    nullif('$nextVisitDate', ''), 
    '$bp', '$weight', '$disease', $patientId);";
    $stmtVisit = $con->prepare($queryVisit);
    $stmtVisit->execute();

    $lastInsertId = $con->lastInsertId();//latest patient visit id

    foreach ($medicineIdsToUpdate as $medId) {
      $totalLeft = $medicineCapsulesLeft[$medId];
      $capsuleQuantity = $medicineCapsulesQty[$medId];

      $queryMedicationHistory = "INSERT INTO `patient_medication_history`(
      `patient_visit_id`, `medicine_detail_id`, `quantity`)
      VALUES($lastInsertId, $medId, $capsuleQuantity);";

      $stmtDetails = $con->prepare($queryMedicationHistory);
      $stmtDetails->execute();

      $updateMedicineQuery = "UPDATE `medicine_details` SET `total_capsules` = $totalLeft
      WHERE `medicine_details`.`id` = $medId";

      $stmtMedicine = $con->prepare($updateMedicineQuery);
      $stmtMedicine->execute();
    }

    $con->commit();

    $message = 'Patient Medication stored successfully.';

  }catch(PDOException $ex) {
    $con->rollback();

    echo $ex->getTraceAsString();
    echo $ex->getMessage();
    exit;
  }

  header("location:congratulation.php?goto_page=new_prescription.php&message=$message");
  exit;
}
$patients = getPatients($con);
$medicines = getMedicines($con);

?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php' ?>

 <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
 <link rel="stylesheet" type='' href="plugins/admincss/admin.css" />
 <title>New Prescription - Clinic's Patient Management System in PHP</title>

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
              <h1>New Prescription</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="card card-outline card-primary rounded-0 shadow">
          <div class="card-header">
            <h3 class="card-title">Add New Prescription</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <!-- best practices-->
            <form method="post">
              <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                  <label>Select Patient</label>
                  <select id="patient" name="patient" class="form-control form-control-sm rounded-0" 
                  required="required">
                  <?php echo $patients;?>
                  </select>
                </div>


              <div class="col-lg-3 col-md-3 col-sm-4 col-xs-10">
                <div class="form-group">
                  <label>Visit Date</label>
                  <div class="input-group date" 
                  id="visit_date" 
                  data-target-input="nearest">
                  <input type="text" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#visit_date" name="visit_date" required="required" data-toggle="datetimepicker" autocomplete="off"/>
                  <div class="input-group-append" 
                  data-target="#visit_date" 
                  data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
              </div>
            </div>
          </div>
          


          <div class="col-lg-3 col-md-3 col-sm-4 col-xs-10">
            <div class="form-group">
              <label>Next Visit Date</label>
              <div class="input-group date" 
              id="next_visit_date" 
              data-target-input="nearest">
              <input type="text" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#next_visit_date" name="next_visit_date" data-toggle="datetimepicker" autocomplete="off"/>
              <div class="input-group-append" 
              data-target="#next_visit_date" 
              data-toggle="datetimepicker">
              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
            </div>
          </div>
        </div>
      </div>

      <div class="clearfix">&nbsp;</div>

      <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <label>BP</label>
        <input id="bp" class="form-control form-control-sm rounded-0" name="bp" required="required" />
      </div>
      
      <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
        <label>Weight</label>
        <input id="weight" name="weight" class="form-control form-control-sm rounded-0" required="required" />
      </div>

      <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
        <label>Disease</label>
        <input id="disease" required="required" name="disease" class="form-control form-control-sm rounded-0" />
      </div>


    </div>

    <div class="col-md-12"><hr /></div>
    <div class="clearfix">&nbsp;</div>

    <div class="row">
     <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <label>Select Medicine</label>
      <select id="medicine" class="form-control form-control-sm rounded-0">
        <?php echo $medicines;?>
      </select>
    </div>

    <div  class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
     <p id="packing" value="">No medicine selected</p>
    </div>

    <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
      <label>Quantity</label>
      <input id="quantity" type="number" min="1" max="10" class="form-control form-control-sm rounded-0" />
    </div>

    <div class="col-lg-1 col-md-1 col-sm-6 col-xs-12">
      <label>&nbsp;</label>
      <button id="add_to_list" type="button" class="btn btn-primary btn-sm btn-flat btn-block">
        <i class="fa fa-plus"></i>
      </button>
    </div>

  </div>

  <div class="clearfix">&nbsp;</div>
  <div class="row table-responsive">
    <table id="medication_list" class="table table-striped table-bordered">
      <colgroup>
        <col width="10%">
        <col width="50%">
        <col width="10%">
        <col width="10%">
        <col width="15%">
        <col width="5%">
      </colgroup>
      <thead class="bg-primary">
        <tr>
          <th>S.No</th>
          <th>Medicine Name</th>
          <th>QTY</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody id="current_medicines_list">

      </tbody>
    </table>
  </div>
  <div id="medicineInputs">
    <!-- Hidden inputs -->
  </div>

  <div class="clearfix">&nbsp;</div>
  <div class="row">
    <div class="col-md-10">&nbsp;</div>
    <div class="col-md-2">
      <button type="submit" id="submit" name="submit" 
      class="btn btn-primary btn-sm btn-flat btn-block">Save</button>
    </div>
  </div>
</form>

</div>

</div>
<!-- /.card -->

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include './config/footer.php';
$message = '';
if(isset($_GET['message'])) {
  $message = $_GET['message'];
}
?>  
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php include './config/site_js_links.php';
?>

<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<script>
  var serial = 1;
  showMenuSelected("#mnu_patients", "#mi_new_prescription");

  var message = '<?php echo $message;?>';

  if(message !== '') {
    showCustomMessage(message);
  }

  var medicineCountCache = {
    decrementCount: function(medID, newVal) {
      let {totalQuantity, totalCapsules} = this[medID];
      newVal = parseInt(newVal);

      if(isNaN(newVal) || newVal < 0){
        showCustomMessage("Please enter a number and should a positve value");
        return false;
      } 
      
      if(newVal > totalCapsules){

        showCustomMessage("Not enough stocks");
        return false;
      }
      
      this[medID].totalQuantity = totalQuantity + newVal;
      this[medID].totalCapsules = totalCapsules - newVal;
      console.log(`decrementCount ${this[medID].totalQuantity} ${this[medID].totalCapsules}`);
      return true;
    },
    incrementCount: function(medID, newVal) {
      let {totalQuantity, totalCapsules} = this[medID];
      newVal = parseInt(newVal);
      this[medID].totalQuantity = totalQuantity - newVal;
      this[medID].totalCapsules = totalCapsules + newVal;
      console.log(`incrementCount ${this[medID].totalQuantity} ${this[medID].totalCapsules}`);

    }
  };

  $(document).ready(function() {
    
    $('#medication_list').find('td').addClass("px-2 py-1 align-middle")
    $('#medication_list').find('th').addClass("p-1 align-middle")
    $('#visit_date, #next_visit_date').datetimepicker({
      format: 'L'
    });


    $("#medicine").change(function() {

      // var medicineId = $("#medicine").val();
      let medicineId = $(this).val();
      const htmlText = `Total left: `;
      const packing = $("#packing");
      if(medicineId !== '') {
        if(medicineId in medicineCountCache){
            packing.attr('value', medicineCountCache[medicineId].totalCapsules);
            packing.html(htmlText + medicineCountCache[medicineId].totalCapsules);
        }
        else{
            $.ajax({
              url: "ajax/get_packings.php",
              type: 'GET', 
              data: {
                'medicine_id': medicineId
              },
              cache:false,
              async:false,
              success: function (data, status, xhr) {
                packing.attr('value', data);
                packing.html(htmlText + data);
                medicineCountCache[medicineId] = {totalQuantity: 0, totalCapsules: parseInt(data)};
              },
              error: function (jqXhr, textStatus, errorMessage) {
                showCustomMessage(errorMessage);
              }
            });
        }
        
      }
      else{
        packing.attr("value", "");
        packing.html("No medicine selected");
      }
    });


    $("#add_to_list").click(function() {
      let medicineId = $("#medicine").val();
     
      if(medicineId === ''){
        showCustomMessage('Please select a medicine');
        return;
      }
      var medicineName = $("#medicine option:selected").text();
      
      var quantity = $("#quantity").val().trim();

      if(medicineCountCache.decrementCount(medicineId, quantity) === false){
        return;
      }

      var oldData = $("#current_medicines_list").html();

      if(medicineName !== '' && packing !== '' && quantity !== '') {
        
        
        // inputs = inputs + '<input type="hidden" name="quantities[]" value="'+quantity+'" />';
        // if($("input[]"))
        const medCapsulesLeftQuery = $(`[name="medicineCapsulesLeft[${medicineId}]"]`);
        const medCapsulesQtyQuery = $(`[name="medicineCapsulesQty[${medicineId}]"]`);
        if(medCapsulesLeftQuery.length > 0 && medCapsulesQtyQuery.length > 0){
          medCapsulesLeftQuery.val(medicineCountCache[medicineId].totalCapsules);
          medCapsulesQtyQuery.val(medicineCountCache[medicineId].totalQuantity);
        }else{
          const input1 = `<input type="hidden" name="medicineCapsulesLeft[${medicineId}]" value="${medicineCountCache[medicineId].totalCapsules}" /> `;
          const input2 = `<input type="hidden" name="medicineCapsulesQty[${medicineId}]" value="${medicineCountCache[medicineId].totalQuantity}" />`;
          $("#medicineInputs").append(input1 + input2);
        }

        var tr = `<tr medid=${medicineId} quantity=${quantity}>`;
        tr = tr + '<td class="px-2 py-1 align-middle">'+serial+'</td>';
        tr = tr + '<td class="px-2 py-1 align-middle">'+medicineName+'</td>';
        tr = tr + '<td class="px-2 py-1 align-middle">'+quantity+'</td>';

        tr = tr + '<td class="px-2 py-1 align-middle text-center"><button type="button" class="btn btn-outline-danger btn-sm rounded-0" onclick="deleteCurrentRow(this);"><i class="fa fa-times"></i></button></td>';
        tr = tr + '</tr>';
        oldData = oldData + tr;
        serial++;

        $("#current_medicines_list").html(oldData);

        $("#medicine").val('');

        $("#packing").attr('value', '');
        $("#packing").html('No medicine selected');

        $("#quantity").val('');

      } else {
        showCustomMessage('Please fill all fields.');
      }

    });

  });

  function deleteCurrentRow(obj) {
    const parent = obj.parentNode.parentNode;
    const medId = parent.getAttribute('medid');
    const quantity = parent.getAttribute('quantity');
    var rowIndex = obj.parentNode.parentNode.rowIndex;
    medicineCountCache.incrementCount(medId, quantity);
    document.getElementById("medication_list").deleteRow(rowIndex);

    const medCapsulesLeftQuery = $(`[name="medicineCapsulesLeft[${medId}]"]`);
    const medCapsulesQtyQuery = $(`[name="medicineCapsulesQty[${medId}]"]`);
    if(medicineCountCache[medId].totalQuantity === 0){
      medCapsulesLeftQuery.remove();
      medCapsulesQtyQuery.remove();
    }
    else{
      medCapsulesLeftQuery.val(medicineCountCache[medId].totalCapsules);
      medCapsulesQtyQuery.val(medicineCountCache[medId].totalQuantity);
    }
  }
</script>
</body>
</html>