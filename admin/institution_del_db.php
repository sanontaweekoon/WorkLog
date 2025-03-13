<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<?php
include('../condb.php');

$institution_id = isset($_GET["ID"]) ? intval($_GET["ID"]) : 0;

if ($institution_id > 0) {
    $sql = "DELETE FROM institution WHERE institution_id=$institution_id";
    $result = mysqli_query($con, $sql);
    mysqli_close($con);

    if ($result) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: 'การดำเนินการเสร็จสมบูรณ์',
                    showConfirmButton: true,
                    timer: 3000
                }).then(function() {
                    window.location = 'institution.php';
                });
            });
        </script>";
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'การดำเนินการไม่สำเร็จ',
                    showConfirmButton: true,
                    timer: 3000 
                }).then(function() {
                    window.location = 'institution.php';
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
                showConfirmButton: true,
                timer: 3000
            }).then(function() {
                window.location = 'institution.php';
            });
        });
    </script>";
}
?>
