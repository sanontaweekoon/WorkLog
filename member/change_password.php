<?php include('header.php'); ?>

<?php
session_start();
include('../condb.php');

if (!$con) {
    die("Database connection failed!");
}

if (!isset($_SESSION['personel_id']) || empty($_SESSION['personel_id'])) {
    die("Error: User ID is not set or is empty.");
}

$personel_id = (int)$_SESSION['personel_id'];

// var_dump($personel_id);

$sql = "SELECT personel_id, password FROM personel WHERE personel_id = ?";
$stmt = $con->prepare($sql);

if (!$stmt) {
    die("Prepare statement failed: " . $con->error);
}

$stmt->bind_param("i", $personel_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<style>
    .form-group {
        margin-bottom: 15px;
    }

    .col-form-label {
        font-weight: bold;
        text-align: left !important;
        /* บังคับให้ข้อความชิดซ้าย */
        display: flex;
        align-items: center;
        /* ทำให้ label อยู่กึ่งกลาง */
    }

    .form-control {
        height: 30px;
        font-size: 16px;
    }

    .btn-success,
    .btn-default {
        width: 100%;
        font-size: 14px;
        padding: 12px;
        font-weight: bold;
    }

    /* ปรับขนาดฟอร์มใน Desktop */
    @media (min-width: 768px) {
        .btn-container {
            text-align: left;
            margin-left: 200px;
        }
    }

    /* ปรับให้ปุ่มเต็มจอใน Mobile */
    @media (max-width: 767px) {

        .btn-success,
        .btn-default {
            width: 100%;
        }
    }
</style>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include('menutop.php'); ?>
        <?php include('menu_l.php'); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h2 id="title-top">เปลี่ยนรหัสผ่านใหม่</h2>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="box-body">
                                        <form action="change_password_db.php" method="POST" enctype="multipart/form-data" name="add" class="form-horizontal" id="add" onsubmit="return validateForm()">

                                            <div class="form-group row">
                                                <div class="col-sm-2 col-form-label">รหัสผ่านเดิม</div>
                                                <div class="col-sm-3">
                                                    <input type="password" name="old_password" id="old_password" class="form-control" required>
                                                    <span id="error-msg" style="color: red;"></span>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-2 col-form-label">รหัสผ่านใหม่</div>
                                                <div class="col-sm-3">
                                                    <input type="password" name="new_password" id="new_password" class="form-control" required>
                                                    <span id="error-msg" style="color: red;"></span>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-2 col-form-label">รหัสผ่านใหมอีกครั้ง</div>
                                                <div class="col-sm-3">
                                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                                                    <span id="error-msg" style="color: red;"></span>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-3">
                                                    <input type="hidden" name="personel_id" value="<?php echo $_SESSION['personel_id']; ?>">
                                                    <button type="submit" class="btn btn-success" id="btn" style="margin-bottom: 8px"><i class='fas fa-key'></i> เปลี่ยนรหัสผ่านใหม่</button>
                                                    <a href='profile.php' class='btn btn-default' id='btn'><i class='far fa-arrow-alt-circle-left'></i> ย้อนกลับ </a>
                                                </div>
                                            </div>
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