<meta charset="utf-8">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<?php
session_start();
include('../condb.php');

if (!isset($_SESSION['personel_id'])) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด!',
            text: 'กรุณาเข้าสู่ระบบก่อน',
            showConfirmButton: true
        }).then(() => window.location = 'login.php');
    </script>";
    exit();
}

$personel_id = mysqli_real_escape_string($con, $_POST['personel_id']);
$personel_name = mysqli_real_escape_string($con, $_POST['personel_name']);
$personel_call = mysqli_real_escape_string($con, $_POST['personel_call']);
$old_profile_picture = mysqli_real_escape_string($con, $_POST['old_profile_picture']);
$profile_picture = $old_profile_picture;

if (!empty($_FILES["profile_picture"]["name"])) {
    $target_dir = "../admin/uploads/personel/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); //สร้างโฟลเดอร์ถ้ายังไม่มี
    }

    $file_ext = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
    $allowed_ext = array("jpg", "jpeg", "png", "gif");

    if (in_array($file_ext, $allowed_ext)) {
        $file_name = "profile_" . $personel_id . "_" . time() . "." . $file_ext;
        $target_file = $target_dir . $file_name;

        // ✅ ลบไฟล์เก่าถ้ามี
        if (!empty($old_profile_picture) && file_exists($target_dir . $old_profile_picture) && $old_profile_picture !== "default.png") {
            unlink($target_dir . $old_profile_picture);
        }

        // ✅ อัปโหลดไฟล์ใหม่
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = $file_name; // อัปเดตชื่อไฟล์ใหม่
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: 'อัปโหลดได้เฉพาะไฟล์ JPG, JPEG, PNG, GIF เท่านั้น',
                showConfirmButton: true
            }).then(() => window.history.back());
        </script>";
        exit();
    }
}

$sql = "UPDATE personel SET
        personel_name = '$personel_name',
        personel_call = '$personel_call',
        profile_picture = '$profile_picture'
        WHERE personel_id = '$personel_id'";

$result = mysqli_query($con, $sql);

mysqli_close($con);

if ($result) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: 'แก้ไขข้อมูลเรียบร้อยแล้ว',
                showConfirmButton: true
            }).then(() => window.location = 'profile.php');
        });
    </script>";
} else {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: 'ไม่สามารถแก้ไขข้อมูลได้',
                showConfirmButton: true
            }).then(() => window.location = 'profile.php');
        });
    </script>";
}
?>
