<meta charset="utf-8">
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>


<?php
include('../condb.php');

$title = $_POST['title'];
$date = $_POST['date'];
$detail = $_POST['detail'];
$personel_id = $_POST['personel_id'];

if(empty($personel_id) || $personel_id == 0){
    die("เกิดข้อผิดพลาด: ไม่พบรหัสบุคลากร");
}
// var_dump($_POST);
// exit;

$sql = "INSERT INTO tbl_m_record (title, date, detail, personel_id) VALUES (?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt -> bind_param("sssi", $title, $date, $detail, $personel_id);

if($stmt -> execute()){
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: 'บันทึกข้อมูลเรียบร้อย!',
                showConfirmButton: true
            }).then(() => window.location = 'add_record.php');
        });
    </script>";

}else{
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด!',
                text: 'ไม่สามารถบันทึกข้อมูลได้',
                showConfirmButton: true
            }).then(() => window.location = 'add_record.php');
        });
    </script>";
}
?>