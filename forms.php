<?php
include './config/connection.php';

$message = '';
if(isset($_POST['save_template'])) {
    $target_dir = 'templates/';
    $message = '';
    $template_name = trim($_POST['template_name']);
    $template_name = ucwords(strtolower($template_name));
    $current_file = current($_FILES);
    $template_file = $current_file['tmp_name'];
    $template_basename = $current_file['name'];
    $extension = '';

    if($dot_index = strrpos($template_basename, '.')){
        $extension = substr($template_basename, $dot_index);
        $template_basename = substr($template_basename, 0, $dot_index);

    }

    $unique_template_name = uniqid($template_basename);
    $dest_path = $target_dir . $unique_template_name . $extension;

    if($template_name != '') {
        $query = "INSERT INTO `template_forms`(`title`, `file_name`)
   VALUES('$template_name', '$dest_path');";

        try {

            $con->beginTransaction();

            $stmtMedicine = $con->prepare($query);
            $stmtMedicine->execute();

            $con->commit();

            move_uploaded_file($template_file, $dest_path);

            $message = 'Template form added successfully.';
        }catch(PDOException $ex) {
            $con->rollback();

            echo $ex->getMessage();
            echo $ex->getTraceAsString();
            exit;
        }

    } else {
        $message = 'Empty form can not be submitted.';
    }
    header("Location:congratulation.php?goto_page=forms.php&message=$message");
    exit;
}



try {
    $query = "select `id`, `title`, `file_name` from `template_forms` 
  order by `id` asc;";
    $stmt = $con->prepare($query);
    $stmt->execute();

} catch(PDOException $ex) {
    echo $ex->getMessage();
    echo $e->getTraceAsString();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include './config/site_css_links.php';?>


    <?php include './config/data_tables_css.php';?>
    <title>Forms - Clinic's Patient Management System in PHP</title>

    <style>
        .user-img{
            width:3em;
            width:3em;
            object-fit:cover;
            object-position:center center;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<!-- Site wrapper -->
<div class="wrapper">
    <?php include './config/header.php';
    include './config/sidebar.php';?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Forms</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="card card-outline card-primary rounded-0 shadow">
                <div class="card-header">
                    <h3 class="card-title">Add Form Template</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                                <label>Template Name</label>
                                <input type="text" id="template_name" name="template_name" required="required"
                                       class="form-control form-control-sm rounded-0" />
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                                <label>Template File</label>
                                <input type="file" id="template_file" name="template_file" required="required"
                                       class="form-control form-control-sm rounded-0" />
                            </div>
                            <div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">
                                <label>&nbsp;</label>
                                <button type="submit" id="save_template"
                                        name="save_template" class="btn btn-primary btn-sm btn-flat btn-block">Save</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <!-- /.card -->
        </section>
        <section class="content">
            <!-- Default box -->
            <div class="card card-outline card-primary rounded-0 shadow">
                <div class="card-header">
                    <h3 class="card-title">All Template Forms</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <a href="../templates/DOC_TEMPLATE.docx" download>test</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row table-responsive">

                        <table id="all_medicines"
                               class="table table-striped dataTable table-bordered dtr-inline"
                               role="grid" aria-describedby="all_medicines_info">
                            <colgroup>
                                <col width="10%">
                                <col width="80%">
                                <col width="10%">
                            </colgroup>

                            <thead>
                            <tr>
                                <th class="text-center">S.No</th>
                                <th>Template Name</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php
                            $serial = 0;
                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $serial++;
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $serial;?></td>
                                    <td><?php echo $row['title'];?></td>
                                    <td class="text-center">
                                        <a href="<?php echo $row['file_name'];?>"
                                           class="btn btn-primary btn-sm btn-flat">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm btn-flat" data-toggle="modal" data-target="#form_delete<?php echo $row['id']?>">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>


                                </tr>
                                <!-- Modal -->
                                <div class="modal fade" id="form_delete<?php echo $row['id']?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete template</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete <?php echo $row['title']?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <form method="POST" action="remove_form.php">
                                                    <input type="hidden" name="form_id" value="<?php echo $row['id']?>"/>
                                                    <input type="hidden" name="form_filename" value="<?php echo $row['file_name']?>"/>
                                                    <button type="submit" class="btn btn-danger" name="form_remove">Continue</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- /.card-footer-->
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
</div>

<!-- ./wrapper -->

<?php include './config/site_js_links.php'; ?>
<?php include './config/data_tables_js.php'; ?>


<script>
    showMenuSelected("#mnu_forms", "");

    var message = '<?php echo $message;?>';

    if(message !== '') {
        showCustomMessage(message);
    }


    $(document).ready(function() {

        $("#user_name").blur(function() {
            var userName = $(this).val().trim();
            $(this).val(userName);

            if(userName !== '') {
                $.ajax({
                    url: "ajax/check_user_name.php",
                    type: 'GET',
                    data: {
                        'user_name': userName
                    },
                    cache:false,
                    async:false,
                    success: function (count, status, xhr) {
                        if(count > 0) {
                            showCustomMessage("This user name exists. Please choose another username");
                            $("#save_user").attr("disabled", "disabled");

                        } else {
                            $("#save_user").removeAttr("disabled");
                        }
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        showCustomMessage(errorMessage);
                    }
                });
            }

        });
    });
</script>
</body>
</html>