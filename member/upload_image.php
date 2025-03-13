<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$uploadDir = __DIR__ . '/uploads/'; 

// ตรวจสอบว่ามีโฟลเดอร์หรือไม่ ถ้าไม่มีให้สร้าง
if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
    echo json_encode(["success" => false, "message" => "ไม่สามารถสร้างโฟลเดอร์อัปโหลด"]);
    exit;
}

// ตรวจสอบว่ามีไฟล์ถูกส่งมาหรือไม่
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["success" => false, "message" => "ไม่มีไฟล์ถูกส่งมาหรือไฟล์มีข้อผิดพลาด"]);
    exit;
}

$file = $_FILES['image'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["success" => false, "message" => "เกิดข้อผิดพลาดในการอัปโหลดไฟล์", "error_code" => $file['error']]);
    exit;
}

$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(["success" => false, "message" => "ประเภทไฟล์ไม่ถูกต้อง"]);
    exit;
}

$fileName = uniqid() . "_" . basename($file['name']);
$uploadFile = $uploadDir . $fileName;

if (!is_writable($uploadDir)) {
    echo json_encode(["success" => false, "message" => "โฟลเดอร์ uploads ไม่มีสิทธิ์เขียน"]);
    exit;
}

// ย้ายไฟล์ที่อัปโหลดไปยังโฟลเดอร์ uploads
if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
    // ส่ง URL กลับ (ปรับ URL ให้เข้าถึงได้จาก frontend)
    echo json_encode(["success" => true, "url" => 'uploads/' . $fileName]);
} else {
    echo json_encode(["success" => false, "message" => "อัปโหลดไม่สำเร็จ"]);
}
?>
