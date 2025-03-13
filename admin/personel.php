<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap">
<?php include('header.php');?>
<body class="hold-transition skin-green sidebar-mini">
  <div class="wrapper">
    <!-- Main Header -->
    <?php include('menutop.php');?>
    <?php include('menu_l.php');?>
    <div class="content-wrapper">
      <section class="content-header">
      <h1>ข้อมูลบุคลากร
      <a href="personel.php?act=add" class="btn-info btn-sm"><i class="fa fa-plus-circle"></i> เพิ่มบุคลากร</a>
      </h1>
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
                    if($act == 'add'){
                        include('personel_add_form.php');
                    }elseif ($act == 'edit'){
                        include('personel_edit_form.php');
                    }else{
                        include('personel_list.php');
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
<?php include('footerjs.php');?>