<?php
session_start();
include('../condb.php');

header("Content-Type: application/json");

if(!isset($_SESSION['personel_id'])){
    echo json_encode(["status" => "error", "message" => "กรุณาเข้าสู่ระบบ"]);
    exit();
}

$personel_id = $_SESSION['personel_id'];
$record_id = $_POST['id'] ?? null;

if (!$record_id){
    echo json_encode(["status" => "error", "message" => "ไม่มี ID ที่ต้องการลบ"]);
    exit();
}

// ตรวจสอบว่าบันทึกเป็นของเราหรือไม่
$sqlCheck = "SELECT * FROM tbl_m_record WHERE id = ? AND personel_id = ?";
$stmtCheck = $con->prepare($sqlCheck);
$stmtCheck->bind_param("ii", $record_id, $personel_id);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultChek->num_rows === 0){
    echo json_encode(["status" => "error", "message" => "คุณไม่มีสิทธิ์ลบบันทึกนี้"]);
    exit();
}

// เริ่มทำการลบ
$sqlDelete = "DELETE FROM tbl_m_record WHERE id = ? ";
$stmtDelete = $con->prepare($sqlDelete);
$stmtDelete->bind_param("i", $record_id);

if($stmtDelete->execute()){
    echo json_encode(["status" => "success", "message" => "ลบบันทึกสำเร็จ"]);
}else{
    echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดในการลบ"]);
}

$stmtDelete->close();
$con->close();
?>