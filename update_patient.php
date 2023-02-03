<?php
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';

try {
    $id = $_GET['id'];

    $q = $con->prepare("DESCRIBE past_medical_history;");
    $q->execute();
    $past_medical_fields = array_values($q->fetchAll(PDO::FETCH_COLUMN));
    array_shift($past_medical_fields);
    $past_medical_len = (int) count($past_medical_fields);

    $q = $con->prepare("DESCRIBE family_history;");
    $q->execute();
    $family_history_fields = array_values($q->fetchAll(PDO::FETCH_COLUMN));
    array_shift($family_history_fields);
    $family_history_len = (int) count($family_history_fields);

    
    $q = $con->prepare("DESCRIBE immunization;");
    $q->execute();
    $immunization_fields = array_values($q->fetchAll(PDO::FETCH_COLUMN));
    array_shift($immunization_fields);
    $immunization_len = (int) count($immunization_fields);


    $past_field_col_vals = [];
    foreach($past_medical_fields as $past_field){
      array_push($past_field_col_vals, 'past_medical_history.' . $past_field . ' as `' . $past_field . '_pm`');
    }
    $past_field_col_qstr = implode(', ', $past_field_col_vals);

    $family_history_col_vals = [];
    foreach($family_history_fields as $family_history_field){
      array_push($family_history_col_vals, 'family_history.' . $family_history_field . ' as `' . $family_history_field . '_fh`');
    }
    $family_history_col_qstr = implode(', ', $family_history_col_vals);

    $family_history_relation_col_vals = [];
    foreach($family_history_fields as $family_history_field){
      array_push($family_history_relation_col_vals, 'family_history_relation.' . $family_history_field . ' as `' . $family_history_field . '_fhr`');
    }
    $family_history_relation_col_qstr = implode(', ', $family_history_relation_col_vals);

    $immunization_col_vals = [];
    foreach($immunization_fields as $immunization_field){
      array_push($immunization_col_vals, "date_format(immunization." . $immunization_field . ", '%m/%d/%Y')  as `" . $immunization_field . "`");
    }
    $immunization_col_qstr = implode(', ', $immunization_col_vals);



  $query ="SELECT `patient_id`,`family_history_id`, `past_medical_history_id`, immunization_id ,`patient_name`, `address`, `course`, date_format(`date_of_birth`, '%m/%d/%Y') as `date_of_birth`,  `phone_number`, `gender`, `complaint`, $past_field_col_qstr, $family_history_col_qstr, $family_history_relation_col_qstr, $immunization_col_qstr
          FROM `health_record`
          INNER JOIN `patients` ON health_record.patient_id = patients.id
          INNER JOIN `past_medical_history` ON past_medical_history.id = health_record.past_medical_history_id
          INNER JOIN `immunization` ON immunization.id = health_record.immunization_id
          INNER JOIN `family_history` ON family_history.id = health_record.family_history_id
          INNER JOIN `family_history_relation` ON family_history_relation.id = family_history.id
          WHERE patient_id = $id";

    $stmtPatient1 = $con->prepare($query);
    $stmtPatient1->execute();
    $row = $stmtPatient1->fetch(PDO::FETCH_ASSOC);

    $gender = $row['gender'];

    $dob = $row['date_of_birth']; 
} catch(PDOException $ex) {

  echo $ex->getMessage();
  echo $ex->getTraceAsString();
  exit;
}

if (isset($_POST['save_Patient'])) {

  $hiddenId = $_POST['patient_id'];

  $patientName = trim($_POST['patient_name']);
  $address = trim($_POST['address']);
  $course = trim($_POST['course']);

  $dateBirth = trim($_POST['date_of_birth']);
  $dateArr = explode("/", $dateBirth);

  $dateBirth = $dateArr[2] . '-' . $dateArr[0] . '-' . $dateArr[1];

  $phoneNumber = trim($_POST['phone_number']);

  $patientName = ucwords(strtolower($patientName));
  $address = ucwords(strtolower($address));

  $gender = $_POST['gender'];
  $complaint = trim($_POST['complaint']);

  $past_medical_set_values = [];
  foreach ($past_medical_fields as $past_medical_field) {
    array_push($past_medical_set_values, "`$past_medical_field` = '" . $_POST[$past_medical_field . '_pm'] . "'");
  }

  $family_history_set_values = [];
  foreach ($family_history_fields as $family_history_field) {
    array_push($family_history_set_values, "`$family_history_field` = '" . $_POST[$family_history_field] . "'");
  }

  $family_relation_set_values = [];
  foreach ($family_history_fields as $family_history_field) {
    array_push($family_relation_set_values, "`$family_history_field` = '" . $_POST[$family_history_field . '_relation'] . "'");
  }

  $immunization_set_values = [];
  foreach ($immunization_fields as $immunization_field) {
    array_push($immunization_set_values, "`$immunization_field` = '" . formatDateInsert($_POST[$immunization_field]) . "'");
  }


  $family_history_id = $_POST['family_history_id'];
  $past_medical_history_id = $_POST['past_medical_history_id'];
  $immunization_id = $_POST['immunization_id'];


  if (
    $patientName != '' && $address != '' &&
    $course != '' && $dateBirth != '' && $phoneNumber != '' && $gender != '' && $complaint != ''
  ) {
    $query = "update `patients` 
      set `patient_name` = '$patientName', 
      `address` = '$address', 
      `course` = '$course', 
      `date_of_birth` = '$dateBirth', 
      `phone_number` = '$phoneNumber', 
      `gender` = '$gender',
      `complaint` = '$complaint'
    where `id` = $hiddenId;";

    $past_medical_q = "UPDATE `past_medical_history`
      SET " . implode(', ', $past_medical_set_values). " WHERE `id` = $past_medical_history_id ";
    
    $family_history_q = "UPDATE `family_history`
    SET " . implode(', ', $family_history_set_values). " WHERE `id` =  $family_history_id";

    $family_history_relation_q = "UPDATE `family_history_relation`
    SET " . implode(', ', $family_relation_set_values). " WHERE `id` =  $family_history_id";
 
    $immunization_q = "UPDATE `immunization`
    SET " . implode(', ', $immunization_set_values). " WHERE `id` =  $immunization_id";
 
    try {

    $con->beginTransaction();

    $stmtPatient = $con->prepare($query);
    $stmtPatient->execute();

    $stmtPastMedical = $con->prepare($past_medical_q);
    $stmtPastMedical->execute();

    $stmtFamilyHistory = $con->prepare($family_history_q);
    $stmtFamilyHistory->execute();

    $stmtFamilyHistoryRel = $con->prepare($family_history_relation_q);
    $stmtFamilyHistoryRel->execute();

    $stmtImmunization = $con->prepare($immunization_q);
    $stmtImmunization->execute();

    $con->commit();

    $message = 'Patient updated successfully.';

    } catch(PDOException $ex) {
      $con->rollback();

        echo $ex->getMessage();
        echo $ex->getTraceAsString();
        exit;
      }
    }
    header("Location:congratulation.php?goto_page=patients.php&message=$message");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php';?>

 <?php include './config/data_tables_css.php';?>

  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <title>Update Pateint Details - Clinic's Patient Management System in PHP</title>

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
            <h1>Patients</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
     <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
          <h3 class="card-title">Update Patients</h3>
          
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            
          </div>
        </div>
        <div class="card-body">
          <form method="post">
            <input type="hidden" name="patient_id" 
            value="<?php echo $row['patient_id'];?>">
            <input type="hidden" name="family_history_id" 
            value="<?php echo $row['family_history_id'];?>">
            <input type="hidden" name="past_medical_history_id" 
            value="<?php echo $row['past_medical_history_id'];?>">
            <input type="hidden" name="immunization_id" 
            value="<?php echo $row['immunization_id'];?>">
          
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
              <label>Patient Name</label>
              <input type="text" id="patient_name" name="patient_name" required="required"
                class="form-control form-control-sm rounded-0" value="<?php echo $row['patient_name'];?>" />
              </div>
              <br>
              <br>
              <br>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Address</label> 
                <input type="text" id="address" name="address" required="required"
                class="form-control form-control-sm rounded-0" value="<?php echo $row['address'];?>" />
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Course</label>
                <input type="text" id="course" name="course" required="required"
                class="form-control form-control-sm rounded-0" value="<?php echo $row['course'];?>" />
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <div class="form-group">
                  <label>Date of Birth</label>
                    <div class="input-group date" 
                    id="date_of_birth" 
                    data-target-input="nearest">
                        <input type="text" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#date_of_birth" name="date_of_birth" 
                        value="<?php echo $dob;?>" />
                        <div class="input-group-append" 
                        data-target="#date_of_birth" 
                        data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Complaints</label>
                <input type="text" id="complaint" name="complaint" required="required"
                class="form-control form-control-sm rounded-0" value="<?php echo $row['complaint'];?>" />
              </div>
                </div>
              
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" required="required"
                class="form-control form-control-sm rounded-0" value="<?php echo $row['phone_number'];?>" />
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
              <label>Gender</label>
                <!-- $gender -->

                <select class="form-control form-control-sm rounded-0" id="gender" 
                name="gender">
                 <?php echo getGender($gender);?>
                </select>
                
              </div>
              </div>
              <div class="clearfix">&nbsp;</div>
                        <strong>Past Medical History: Has the child suffered from any of the following</strong>
                        <div class="row">
                        <div class="col">
                            <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Disease</th>
                                            <th scope="col">Yes</th>
                                            <th scope="col" class="border-right">No</th>
                                            <th scope="col" >Disease</th>
                                            <th scope="col">Yes</th>
                                            <th scope="col">No</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for($i = 0; $i < $past_medical_len; $i+=2): ?>
                                            <tr class="border-bottom">
                                                <td><?= makeTitle($past_medical_fields[$i], '_') ?></td>
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" 
                                                                name="<?= $past_medical_fields[$i] . '_pm' ?>" 
                                                                id="<?= $past_medical_fields[$i] . '_pm' ?>" 
                                                                value="yes" <?php if($row[$past_medical_fields[$i] . '_pm'] == 'yes' ){echo "checked";}?> 
                                                                required>
                                                    </div>
                                                </td>
                                                <td class="border-right">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" 
                                                                name="<?= $past_medical_fields[$i] . '_pm' ?>" 
                                                                id="<?=$past_medical_fields[$i] . '_pm' ?>" 
                                                                value="no" <?php if($row[$past_medical_fields[$i] . '_pm'] == 'no' ){echo "checked";}?> 
                                                                required>
                                                    </div>
                                                </td>
                                                <?php if($i + 1 < $past_medical_len): ?>
                                                    <td><?= makeTitle($past_medical_fields[$i + 1], '_') ?></td>
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" 
                                                                    name="<?= $past_medical_fields[$i + 1] . '_pm' ?>" 
                                                                    id="<?= $past_medical_fields[$i + 1] . '_pm' ?>" 
                                                                    value="yes" <?php if($row[$past_medical_fields[$i + 1] . '_pm'] == 'yes' ){echo "checked";}?> 
                                                                    required>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" 
                                                                    name="<?= $past_medical_fields[$i + 1] . '_pm' ?>" 
                                                                    id="<?= $past_medical_fields[$i + 1] . '_pm' ?>" 
                                                                    value="no" <?php if($row[$past_medical_fields[$i + 1] . '_pm'] == 'no' ){echo "checked";}?> 
                                                                    required>
                                                            
                                                        </div>
                                                    </td>
                                                <?php endif; ?>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>
                          </div>
                        </div>
                        <div class="clearfix">&nbsp;</div>
                        <strong>Family History</strong>
                        <div class="row">
                            <div class="col">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Disease</th>
                                            <th scope="col">Yes</th>
                                            <th scope="col">No</th>
                                            <th scope="col" class="border-right">Relation</th>
                                            <th scope="col">Disease</th>
                                            <th scope="col">Yes</th>
                                            <th scope="col">No</th>
                                            <th scope="col">Relation</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for($i = 0 ; $i < $family_history_len ; $i+=2): ?>
                                            <tr class="border-bottom">
                                                <td><?= makeTitle($family_history_fields[$i], '_') ?></td>
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" 
                                                                name="<?= $family_history_fields[$i] ?>" 
                                                                id="<?= $family_history_fields[$i] ?>" 
                                                                value="yes" <?php if($row[$family_history_fields[$i] . '_fh'] == 'yes' ){echo "checked";}?> 
                                                                required>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" 
                                                                name="<?= $family_history_fields[$i] ?>" id="<?= $family_history_fields[$i] ?>" 
                                                                value="no" <?php if($row[$family_history_fields[$i] . '_fh'] == 'no' ){echo "checked";}?> 
                                                                required>
                                                    </div>
                                                </td>
                                                <td class="border-right">
                                                    <input type="text" id="<?= $family_history_fields[$i] . '_relation'?>" 
                                                          name="<?= $family_history_fields[$i] . '_relation'?>" 
                                                          value="<?= $row[$family_history_fields[$i] . '_fhr'] ?>" 
                                                          required
                                                          class="form-control form-control-sm rounded-0"/>
                                                </td>
                                                <?php if($i + 1 < $family_history_len): ?>
                                                    <td><?= makeTitle($family_history_fields[$i + 1], '_') ?></td>
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" 
                                                                    name="<?= $family_history_fields[$i + 1] ?>" id="<?= $family_history_fields[$i + 1] ?>" 
                                                                    value="yes" <?php if($row[$family_history_fields[$i + 1] . '_fh'] == 'yes' ){echo "checked";}?> required>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" 
                                                              name="<?= $family_history_fields[$i + 1] ?>" 
                                                              id="<?= $family_history_fields[$i + 1] ?>" 
                                                              value="no" <?php if($row[$family_history_fields[$i + 1] . '_fh'] == 'no' ){echo "checked";}?> 
                                                              
                                                              required>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="<?= $family_history_fields[$i + 1] . '_relation'?>" 
                                                                name="<?= $family_history_fields[$i + 1] . '_relation'?>" 
                                                                value="<?= $row[$family_history_fields[$i+1] . '_fhr'] ?>" 
                                                                required
                                                                class="form-control form-control-sm rounded-0"/>
                                                    </td>
                                                <?php endif ?>
                                            </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
              <div class="clearfix">&nbsp;</div>
              <strong>Immunization</strong>
                <div class="row">
                    <div class="col">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Immunization</th>
                                    <th scope="col" class="border-right">Dates</th>
                                    <th scope="col">Immunization</th>
                                    <th scope="col">Dates</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php for($i = 0 ; $i < $immunization_len; $i+=2): ?>
                                    <tr class="border-bottom">
                                        <td><?= makeTitle($immunization_fields[$i], '_') ?></td>
                                        <td class="border-right">
                                            <div class="input-group date"
                                                id="<?= $immunization_fields[$i] ?>"
                                                data-target-input="nearest">
                                                <input type="text" class="form-control form-control-sm rounded-0 datetimepicker-input" 
                                                    data-target="#<?= $immunization_fields[$i] ?>" 
                                                    name="<?= $immunization_fields[$i] ?>"
                                                    data-toggle="datetimepicker" autocomplete="off" 
                                                    value="<?= $row[$immunization_fields[$i]]?>"
                                                    />
                                                <div class="input-group-append"
                                                    data-target="#<?= $immunization_fields[$i] ?>"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </td>
                                        <?php if($i + 1 < $immunization_len): ?>
                                            <td><?= makeTitle($immunization_fields[$i + 1], '_') ?></td>
                                            <td>
                                                <div class="input-group date"
                                                    id="<?= $immunization_fields[$i+1] ?>"
                                                    data-target-input="nearest">
                                                    <input type="text" class="form-control form-control-sm rounded-0 datetimepicker-input" 
                                                    data-target="#<?= $immunization_fields[$i+1] ?>" 
                                                        name="<?= $immunization_fields[$i + 1] ?>"
                                                        data-toggle="datetimepicker" autocomplete="off" 
                                                        value="<?= $row[$immunization_fields[$i + 1]]?>"
                                                        />
                                                    <div class="input-group-append"
                                                        data-target="#<?= $immunization_fields[$i+1] ?>"
                                                        data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </td>
                                        <?php endif ?>
                                    </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
              <div class="clearfix">&nbsp;</div>
              <div class="row">
                <div class="col-lg-11 col-md-10 col-sm-10">&nbsp;</div>
              <div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">
                <button type="submit" id="save_Patient" 
                name="save_Patient" class="btn btn-primary btn-sm btn-flat btn-block">Save</button>
              </div>
            </div>
          </form>
        </div>
        
      </div>
    </section>
     <br/>
     <br/>
     <br/>

 
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<?php 
 include './config/footer.php';

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
  showMenuSelected("#mnu_patients", "#mi_patients");

  var message = '<?php echo $message;?>';

  if(message !== '') {
    showCustomMessage(message);
  }
  $('#date_of_birth').datetimepicker({
        format: 'L'
    });
    $('#dpt_opv_i').datetimepicker({
            format: 'L'
        });
        $('#dpt_opv_ii').datetimepicker({
            format: 'L'
        });
        $('#dpt_opv_iii').datetimepicker({
            format: 'L'
        });
        $('#dpt_opv_booster_i').datetimepicker({
            format: 'L'
        });
        $('#dpt_opv_booster_ii').datetimepicker({
            format: 'L'
        });
        $('#hib_i').datetimepicker({
            format: 'L'
        });
        $('#hib_ii').datetimepicker({
            format: 'L'
        });
        $('#hib_iii').datetimepicker({
            format: 'L'
        });
        $('#anti_measios').datetimepicker({
            format: 'L'
        });
        $('#anti_hepit_b_i').datetimepicker({
            format: 'L'
        });
        $('#anti_hepit_b_ii').datetimepicker({
            format: 'L'
        });
        $('#anti_hepit_b_iii').datetimepicker({
            format: 'L'
        });
        $('#mmr').datetimepicker({
            format: 'L'
        });
        $('#anti_chicken_pox').datetimepicker({
            format: 'L'
        });
        $('#anti_hepepititis_a_i').datetimepicker({
            format: 'L'
        });
        $('#anti_hepepititis_a_ii').datetimepicker({
            format: 'L'
        });
        $('#anti_hepepititis_a_iii').datetimepicker({
            format: 'L'
        });
        $('#anti_typhoid_fever').datetimepicker({
            format: 'L'
        });
        $('#others').datetimepicker({
            format: 'L'
        });

      
    
   $(function () {
    $("#all_patients").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#all_patients_wrapper .col-md-6:eq(0)');
    
  });

</script>
</body>
</html>