<?php
include('header.php');
?>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include('menutop.php'); ?>
        <?php include('menu_l.php'); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h2 id="title-top">ข้อมูลส่วนตัว</h2>
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
                                        if ($act == 'edit') {
                                            include('profile_edit_form.php');
                                        } else {
                                            include('profile_data.php');
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
<?php include('footerjs.php'); ?>