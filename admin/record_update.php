

<?php
include('../condb.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['image'])) {
    header("Content-Type: application/json");
    
    $imageData = $_POST['image'];

    if (strpos($imageData, 'data:image/') === 0) {
        $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
        $imageData = base64_decode($imageData);

        //สร้างชื่อไฟล์แบบสุ่ม
        $filename = 'upload/' . uniqid() . '.jpg';

        //บันทึกไฟล์
        if (file_put_contents($filename, $imageData)) {
            echo json_encode(['success' => true, 'url' => $filename]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ถูกต้อง']);
    }
    exit(); // หยุดการทำงานหลังจากอัปโหลดรูป
}

//ตรวจสอบว่าเป็นการอัปเดตข้อมูล
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //ตรวจสอบว่ามีค่าที่ต้องการอัปเดต


    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";
    // exit(); 

    if (!isset($_POST['detail']) || trim($_POST['detail']) == "") {
        die("เกิดข้อผิดพลาด: ไม่มีข้อมูลใน detail");
    }

    $id = $_POST['id'];
    $title = $_POST['title'];
    $date = $_POST['date'];
    $detail = htmlspecialchars_decode($_POST['detail']);
    $last_updated = date("Y-m-d H:i:s"); // เวลาปัจจุบัน

    // ตรวจสอบข้อมูล
    if (empty($id) || empty($title) || empty($date) || empty($detail)) {
        die("กรุณากรอกข้อมูลให้ครบถ้วน");
    }
    
    //คำสั่ง SQL
    $sql = "UPDATE tbl_m_record SET title = ?, date =?, detail = ?, last_updated = NOW() WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssi", $title, $date, $detail, $id);

    if ($stmt->execute()) {
        echo "<script>alert('แก้ไขข้อมูลสำเร็จ'); window.location.href='recording_list.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
