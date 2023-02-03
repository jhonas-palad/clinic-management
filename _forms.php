<?php 
include './config/connection.php';




?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php';?>
 <title>Dashboard - Clinic's Patient Management System in PHP</title>
<style>
  .dark-mode .bg-fuchsia, .dark-mode .bg-maroon {
    color: #fff!important;
}
</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->

<?php 

include './config/header.php';
include './config/sidebar.php';
?>  
  <!-- Content Wrapper. Contains page content -->
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
    <section class="d-flex flex-column content">
      <div class="d-flex">
        <div class="input-group col-md-10 rounded">
          <input type="search" class="form-control rounded" aria-label="Search" aria-describedby="search-addon" />
          <button type="button" class="btn btn-outline-primary">Search</button>
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModalCenter">Add</button>
        </div>
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form class="modal-body">
              <div class="form-group">
                <label for="template_title" class="col-sm-2 control-label">Title:</label>
                <div class="col">
                  <input type="email" class="form-control" id="template_title" placeholder="Enter the title of template">
                </div>
              </div>
              <div class="form-group">
                <label class="form-label" for="customFile">Template File</label>
                <input type="file" class="form-control" id="customFile" />
              </div>
              
          
            </form>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </div>
      </div>
      
      <form>
        <label for=""></label>
      </form>

      <a href="./templates/DOC_TEMPLATE.docx" class="d-block p-2 bg-primary text-white my-1" download>Discharge Slip</a>
  



    </section>

    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include './config/footer.php';?>  
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php include './config/site_js_links.php';?>

<button class="btn btn-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="all_patients" type="button">
<span>Copy</span></button>

</body>
</html>