<meta charset="utf-8">

<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<?php
session_start();
include('../condb.php');

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['personel_id']) || empty($_SESSION['personel_id'])) {
    die("Error: User ID is not set in session");
}

$personel_id = filter_var($_POST['personel_id'], FILTER_VALIDATE_INT);
if ($personel_id === false) {
    die("Error: Invalid User ID");
}

// echo "<pre>";
// var_dump($_POST);
// echo "</pre>";
// exit();

$old_password = trim($_POST['old_password']);
$new_password = trim($_POST['new_password']);
$confirm_password = trim($_POST['confirm_password']);

$sql = "SELECT password FROM personel WHERE personel_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $personel_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: 'ไม่พบบัญชีผู้ใช้ในระบบ',
                showConfirmButton: true
            }).then(() => {
                window.location = 'profile.php';
                });
            });
    </script>");
}

if (!password_verify($old_password, $row['password'])) {
    die("<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'รหัสผ่านเดิมไม่ถูกต้อง!',
                text: 'กรุณากรอกใหม่อีกครั้ง',
                showConfirmButton: true
            }).then(() => {
                window.location = 'profile.php';
            });
        });
    </script>");
}

if ($new_password !== $confirm_password) {
    die("<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'รหัสผ่านไม่ตรงกัน!',
                text: 'กรุณากรอกให้ตรงกัน',
                showConfirmButton: true
            }).then(() => {
                window.location = 'profile.php';
            });
        });
    </script>");
}

$new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

$sql = "UPDATE personel SET password = ? WHERE personel_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("si", $new_password_hashed, $personel_id);

if ($stmt->execute()) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: 'เปลี่ยนรหัสเรียบร้อยแล้ว',
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
            text: 'เกิดข้อผิดพลาดไม่สามารถเปลี่ยนรหัสผ่านได้',
            showConfirmButton: true
        }).then(() => window.location = 'profile.php');
    });
</script>";
}

$stmt->close();
$con->close();
?>