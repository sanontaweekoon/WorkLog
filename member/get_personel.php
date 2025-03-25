<?php
include('../condb.php');

// ตรวจสอบว่ามีการส่ง institution_id มาหรือไม่
if (isset($_GET['institution_id'])) {
    $institution_id = mysqli_real_escape_string($con, $_GET['institution_id']);
    
    // ตรวจสอบว่า institution_id ถูกต้องหรือไม่
    if (empty($institution_id)) {
        echo json_encode([]); // ส่งค่าผลลัพธ์เป็น array ว่าง
        exit();
    }

    // สร้าง query เพื่อนำข้อมูลพนักงานที่เกี่ยวข้องกับแผนกนั้น ๆ
    $query = "SELECT personel_id, personel_name FROM personel WHERE institution_id = '$institution_id' AND personel_id != 1";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Error in query: " . mysqli_error($con));
    }

    $personels = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $personels[] = $row;
    }

    // ส่งข้อมูลพนักงานเป็น JSON
    echo json_encode($personels);
} else {
    echo json_encode([]); // ถ้าไม่มี institution_id ส่ง array ว่าง
}
?>
