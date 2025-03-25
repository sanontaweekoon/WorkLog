<?php
include('../condb.php');

header('Content-Type: application/json');

// ตรวจสอบว่ามีข้อมูลถูกส่งมาหรือไม่
if (!isset($_POST['id']) || !isset($_POST['allDay'])) {
    echo json_encode(["status" => "error", "message" => "ข้อมูลไม่ครบถ้วน"]);
    exit;
}

$eventId = intval($_POST['id']);
$isAllDay = intval($_POST['allDay']);

if ($isAllDay) {
    // ลบจากตาราง all_day_events
    $sql = "DELETE FROM all_day_events WHERE event_id = ?";
} else {
    // ลบจากตาราง timed_events
    $sql = "DELETE FROM timed_events WHERE event_id = ?";
}

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $eventId);

if ($stmt->execute()) {
    // ตรวจสอบว่ามีการลบข้อมูลออกจริงหรือไม่
    if ($stmt->affected_rows > 0) {
        echo json_encode(["status" => "success", "message" => "ลบกิจกรรมสำเร็จ"]);
    } else {
        echo json_encode(["status" => "error", "message" => "ไม่พบกิจกรรมนี้"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดในการลบ: " . $stmt->error]);
}

$stmt->close();
$con->close();
?>
