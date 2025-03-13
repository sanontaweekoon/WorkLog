<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<?php
session_start();
include('../condb.php');

if (!isset($_SESSION['personel_id'])) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: 'กรุณาเข้าสู่ระบบก่อนทำรายการ',
                showConfirmButton: true
            }).then(function() {
                window.location = 'index.php';
            });
        });
    </script>";
    exit();
}

$personel_id = isset($_GET["ID"]) ? intval($_GET["ID"]) : 0;
$current_user_id = $_SESSION['personel_id'];

if ($personel_id > 0) {
    if ($personel_id == $current_user_id) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'ล้มเหลว!',
                    text: 'ไม่สามารถลบตัวเองได้!',
                    showConfirmButton: true
                }).then(function() {
                    window.location = 'personel.php';
                });
            });
        </script>";
        exit();
    }


    $sql = "DELETE FROM personel WHERE personel_id = $personel_id";
    $result = mysqli_query($con, $sql);

    mysqli_close($con);

    if ($result) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: 'ลบข้อมูลเรียบร้อย',
                        showConfirmButton: true
                    }).then(function() {
                        window.location = 'personel.php';
                    });
                });
            </script>";
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ลบข้อมูลไม่สำเร็จ',
                        showConfirmButton: true
                    }).then(function() {
                        window.location = 'personel.php';
                    });
                });
            </script>";
    }
} else {
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'ข้อมูลไม่ถูกต้อง!',
                    text: 'ไม่พบข้อมูลที่ต้องการลบ',
                    showConfirmButton: true
                }).then(function() {
                    window.location = 'personel.php';
                });
            });
        </script>";
}
?>