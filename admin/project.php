<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap">


<?php include('header.php'); ?>

<body class="hold-transition skin-green sidebar-mini">
  <div class="wrapper">
    <!-- Main Header -->
    <?php include('menutop.php'); ?>
    <?php include('menu_l.php'); ?>
    <div class="content-wrapper">
      <section class="content-header">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 6px">
          <div style="display: flex; align-items: center; gap: 6px;">
            <h3 style="margin: 0;">ข้อมูลโปรเจค</h3>
            <a href="project.php?act=add" class="btn btn-info btn-sm" data-toggle="tooltip" title="เพิ่มโปรเจค">
              <i class="fa fa-plus-circle"></i> เพิ่มโปรเจค
            </a>
          </div>
          <div style="display: flex; gap: 6px;">
            <a href="export_project_list_excel.php" class="btn btn-success btn-sm" data-toggle="tooltip" title="Export เป็นไฟล์ Excel">
              <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="export_project_list_pdf.php" class="btn btn-danger btn-sm" data-toggle="tooltip" title="Export เป็นไฟล์ PDF">
              <i class="fa fa-file-pdf"></i> PDF
            </a>
          </div>
        </div>
      </section>


      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="row">
                <div class="col-sm-12">
                  <div class="box-body">
                    <?php
                    $act = $_GET['act'];
                    if ($act == 'add') {
                      include('project_add_form.php');
                    } elseif ($act == 'edit') {
                      include('project_edit_form.php');
                    } else {
                      include('project_list.php');
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
</body>

</html>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});

</script>

<?php include('footerjs.php'); ?>