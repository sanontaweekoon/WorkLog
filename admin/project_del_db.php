<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<?php
include('../condb.php');

$job_id = isset($_GET["ID"]) ? intval($_GET["ID"]) : 0;

if ($job_id > 0) {
    $sql = "DELETE FROM project_tasks WHERE job_id=$job_id";
    $result = mysqli_query($con, $sql) or die("Error in query: $sql" . mysqli_error($con));
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
                window.location = 'project.php';
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
                window.location = 'project.php';
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
            window.location = 'project.php';
        });
    });
</script>";
}

?>