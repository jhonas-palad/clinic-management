<?php


use ClickSend\Model\SmsMessage;
require_once './vendor/autoload.php';
include './config/connection.php';
include './common_service/common_functions.php';
// Configure HTTP basic authorization: BasicAuth
// username: hiruzen2497
// password: 9DA29B8C-B496-760D-BF13-B5E2B7825BAD
$config = ClickSend\Configuration::getDefaultConfiguration()
    ->setUsername('premioalak')
    ->setPassword('500E4170-B9A7-B410-D9E4-CAF1CD2E1859');
$apiInstance = new ClickSend\Api\SMSApi(new GuzzleHttp\Client(),$config);


$message = '';
try {
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


} catch(PDOException $ex) {
    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit;
}


if(isset($_POST['sendMessageBtn'])) {

    $patientNumber = $_POST['messagePhoneNumber'];
    $patientname = $_POST['messagePatientName'];


    $patientLastname = trim($patientname);

    $name = trim($patientname);
    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim( preg_replace('#'.preg_quote($last_name,'#').'#', '', $name ) );



    try {
        $msg = new SmsMessage();
        $msg->setBody("Hello Mr/Mrs '".$last_name."',  '".$patientname."' is currently admitted to University of Batangas Clinic");
        $msg->setTo($patientNumber);
        $msg->setSource("sdk");
// \ClickSend\Model\SmsMessageCollection | SmsMessageCollection model
        $sms_messages = new \ClickSend\Model\SmsMessageCollection();
        $sms_messages->setMessages([$msg]);
        $result = $apiInstance->smsSendPost($sms_messages);

        $message = 'Emergency message has been sent.';
    } catch(PDOException $e) {
        echo $e->getMessage();

    }

    header("Location:congratulation.php?goto_page=patients.php&message=$message");

}
if (isset($_POST['save_Patient'])) {
    $patientName = trim($_POST['patient_name']);
    $address = trim($_POST['address']);
    $course = trim($_POST['course']);

    $dateBirth = trim($_POST['date_of_birth']);
    $todays_time = $_POST['todays_time'];
    $dateArr = explode("/", $dateBirth);

    $dateBirth = $dateArr[2].'-'.$dateArr[0].'-'.$dateArr[1];

    $phoneNumber = trim($_POST['phone_number']);

    $patientName = ucwords(strtolower($patientName));
    $address = ucwords(strtolower($address));
    
    $past_medical_values = [];

    foreach($past_medical_fields as $past_medical_field){
        array_push($past_medical_values, $_POST[$past_medical_field . '_pm']);
    }
    $past_medical_values_str = implode("', '" , $past_medical_values);

    $family_history_values = [];
    foreach($family_history_fields as $family_history_field){
        array_push($family_history_values, $_POST[$family_history_field]);
    }
    $family_history_values_str = implode("', '" , $family_history_values);

    $family_history_rel_values = [];
    foreach($family_history_fields as $family_history_field){
        array_push($family_history_rel_values, $_POST[$family_history_field . '_relation']);
    }
    $family_history_rel_str = implode("', '" , $family_history_rel_values);

    $immunization_values = [];
    foreach($immunization_fields as $immunization_field){
        array_push($immunization_values, formatDateInsert($_POST[$immunization_field] ?? '00/00/0000'));
    }
    $immunization_str = implode("', '" , $immunization_values);




    $gender = $_POST['gender'];
    $complaint = trim($_POST['complaint']);
    if ($patientName != '' && $address != '' &&
        $course != '' && $dateBirth != '' && $phoneNumber != '' && $gender != '' && $complaint != '') {
        $query = "INSERT INTO `patients`(`patient_name`, `address`, `course`, `date_of_birth`, todays_time, `phone_number`, `gender`, `complaint`)
                    VALUES('$patientName', '$address', '$course', '$dateBirth', '$todays_time', '$phoneNumber', '$gender', '$complaint');";
        try {

            $con->beginTransaction();
            $stmtPatient = $con->prepare($query);
            $stmtPatient->execute();

            $latestPatientId = $con->lastInsertId();

            $past_medical_query = "INSERT INTO `past_medical_history`(" . implode(',', $past_medical_fields) . ") 
                                VALUES('$past_medical_values_str')" ;

            $pastMedicalRecordStmt = $con->prepare($past_medical_query);
            $pastMedicalRecordStmt->execute();

            $latestPastMedicalID = $con->lastInsertId();

            $family_history_query = "INSERT INTO `family_history`(" . implode(',', $family_history_fields) . ") 
                                VALUES('$family_history_values_str')" ;

            $familyHistoryStmt = $con->prepare($family_history_query);
            $familyHistoryStmt->execute();

            $latestFamilyHistoryId = $con->lastInsertId();

            $family_history_relation_query = "INSERT INTO `family_history_relation`( id, " . implode(',', $family_history_fields) . ") 
                                        VALUES($latestFamilyHistoryId, '$family_history_rel_str')" ;
            $familyHistoryRelStmt = $con->prepare($family_history_relation_query);
            $familyHistoryRelStmt->execute();

            $latestFamilyHistoryRelId = $con->lastInsertId();

            $immunization_query = "INSERT INTO `immunization`(" . implode(',', $immunization_fields) . ") 
                                        VALUES('$immunization_str')" ;
            $immunizationStmt = $con->prepare($immunization_query);
            $immunizationStmt->execute();
            $latestImmunizationId = $con->lastInsertId();

            $health_record_query = "INSERT INTO `health_record`(`patient_id`, `past_medical_history_id`, `family_history_id`, `immunization_id`)
                                    VALUES($latestPatientId, $latestPastMedicalID, $latestFamilyHistoryId, $latestImmunizationId)";
            $healthRecordStmt = $con->prepare($health_record_query);
            $healthRecordStmt->execute();
                
            $con->commit();

            $message = 'patient added successfully.';
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



try {

    $query = "SELECT  patients.*, date_format(`date_of_birth`, '%d %b %Y') as `date_of_birth`, 
`phone_number`, `gender`, `complaint`
FROM `patients` order by `patient_name` asc;";

    $stmtPatient1 = $con->prepare($query);
    $stmtPatient1->execute();

} catch(PDOException $ex) {
    echo $ex->getMessage();
    echo $ex->getTraceAsString();
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include './config/site_css_links.php';?>

    <?php include './config/data_tables_css.php';?>

    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <title>Patients - Clinic's Patient Management System in PHP</title>

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
                    <h3 class="card-title">Add Patients</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>

                    </div>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                                <label>Patient Name</label>
                                <input type="text" id="patient_name" name="patient_name" required="required"
                                       class="form-control form-control-sm rounded-0"/>
                            </div>
                            <br>
                            <br>
                            <br>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                                <label>Address</label>
                                <input type="text" id="address" name="address" required="required"
                                       class="form-control form-control-sm rounded-0"/>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                                <label>Course</label>
                                <input type="text" id="course" name="course" required="required"
                                       class="form-control form-control-sm rounded-0"/>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                                <div class="form-group">
                                    <label>Today's Date</label>
                                    <div class="input-group date"
                                         id="date_of_birth"
                                         data-target-input="nearest">
                                        <input type="text" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#date_of_birth" name="date_of_birth"
                                               data-toggle="datetimepicker" autocomplete="off" />
                                        <div class="input-group-append"
                                             data-target="#date_of_birth"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                                <div class="form-group">
                                    <label>Today's Time</label>
                                    <input type="time" class="form-control form-control-sm rounded-0" data-target="#todays_time" name="todays_time" autocomplete="off" />
                                </div>
                            </div>


                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                                <label>Phone Number</label>
                                <input type="text" id="phone_number" name="phone_number" required="required"
                                       class="form-control form-control-sm rounded-0"/>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                                <label>Gender</label>
                                <select class="form-control form-control-sm rounded-0" id="gender"
                                        name="gender">
                                    <?php echo getGender();?>
                                </select>

                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                                <label>Complaints</label>
                                <input type="text" id="complaint" name="complaint" required="required"
                                       class="form-control form-control-sm rounded-0"/>
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
                                                        <input class="form-check-input" type="radio" name="<?= $past_medical_fields[$i] . '_pm' ?>" id="<?= $past_medical_fields[$i] . '_pm' ?>" value="yes" required>
                                                    </div>
                                                </td>
                                                <td class="border-right">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="<?= $past_medical_fields[$i] . '_pm' ?>" id="<?=$past_medical_fields[$i] . '_pm' ?>" value="no" required>
                                                    </div>
                                                </td>
                                                <?php if($i + 1 < $past_medical_len): ?>
                                                    <td><?= makeTitle($past_medical_fields[$i + 1], '_') ?></td>
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="<?= $past_medical_fields[$i + 1] . '_pm' ?>" id="<?= $past_medical_fields[$i + 1] . '_pm' ?>" value="yes" required>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="<?= $past_medical_fields[$i + 1] . '_pm' ?>" id="<?= $past_medical_fields[$i + 1] . '_pm' ?>" value="no" required>
                                                            
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
                                                        <input class="form-check-input" type="radio" name="<?= $family_history_fields[$i] ?>" id="<?= $family_history_fields[$i] ?>" value="yes" required>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="<?= $family_history_fields[$i] ?>" id="<?= $family_history_fields[$i] ?>" value="no" required>
                                                    </div>
                                                </td>
                                                <td class="border-right">
                                                    <input type="text" id="<?= $family_history_fields[$i] . '_relation'?>" name="<?= $family_history_fields[$i] . '_relation'?>" required="required"
                                                    class="form-control form-control-sm rounded-0"/>
                                                </td>
                                                <?php if($i + 1 < $family_history_len): ?>
                                                    <td><?= makeTitle($family_history_fields[$i + 1], '_') ?></td>
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="<?= $family_history_fields[$i + 1] ?>" id="<?= $family_history_fields[$i + 1] ?>" value="yes" required>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="<?= $family_history_fields[$i + 1] ?>" id="<?= $family_history_fields[$i + 1] ?>" value="no" required>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" id="<?= $family_history_fields[$i + 1] . '_relation'?>" name="<?= $family_history_fields[$i + 1] . '_relation'?>" required="required"
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
                                                        <input type="text" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#<?= $immunization_fields[$i] ?>" name="<?= $immunization_fields[$i] ?>"
                                                            data-toggle="datetimepicker" autocomplete="off" />
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
                                                            <input type="text" class="form-control form-control-sm rounded-0 datetimepicker-input" data-target="#<?= $immunization_fields[$i+1] ?>" name="<?= $immunization_fields[$i + 1] ?>"
                                                                data-toggle="datetimepicker" autocomplete="off" />
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
                            <div class="col-lg-11 col-md-10 col-sm-10 xs-hidden">&nbsp;</div>

                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
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

        <section class="content">
            <!-- Default box -->
            <div class="card card-outline card-primary rounded-0 shadow">
                <div class="card-header">
                    <h3 class="card-title">Total Patients</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row table-responsive">
                        <table id="all_patients"
                               class="table table-striped dataTable table-bordered dtr-inline"
                               role="grid" aria-describedby="all_patients_info">

                            <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Patient Name</th>
                                <th>Address</th>
                                <th>Course</th>
                                <th>Date Time</th>
                                <th>Phone Number</th>
                                <th>Gender</th>
                                <th>Complaints</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            $count = 0;
                            while($row =$stmtPatient1->fetch(PDO::FETCH_ASSOC)){
                                $count++;
                                ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $row['patient_name'];?></td>
                                    <td><?php echo $row['address'];?></td>
                                    <td><?php echo $row['course'];?></td>
                                    <td><?php echo $row['date_of_birth'] .' '. date('h:i a',strtotime($row['todays_time']));?></td>
                                    <td><?php echo $row['phone_number'];?></td>
                                    <td><?php echo $row['gender'];?></td>
                                    <td><?php echo $row['course'];?></td>
                                    <td style="display: flex;">
                                        <a href="update_patient.php?id=<?php echo $row['id'];?>" class = "btn btn-primary btn-sm btn-flat">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="" method="POST">
                                            <input type="hidden" value="<?=$row['phone_number'];?>" name="messagePhoneNumber">
                                            <input type="hidden" value="<?=$row['patient_name'];?>" name="messagePatientName">
                                            <button type="submit" name="sendMessageBtn" class="btn btn-danger" title="Send emergency message"><i class="fa fa-paper-plane" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- /.card-footer-->
            </div>
            <!-- /.card -->


        </section>
    </div>
    <!-- /.content -->

    <!-- /.content-wrapper -->
    <?php
    include './config/footer.php';

    $message = '';
    if(isset($_GET['message'])) {
        $message = $_GET['message'];
    }
    ?>
    <!-- /.control-sidebar -->


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