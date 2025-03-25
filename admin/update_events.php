<?php
session_start();
include('../condb.php');

header('Content-Type: application/json');

if (!isset($_SESSION['personel_id'])) {
    echo json_encode(["status" => "error", "message" => "กรุณาเข้าสู่ระบบก่อน"]);
    exit;
}


$event_id = $_POST['id'] ?? null;
$start = $_POST['start'] ?? null;
$end = $_POST['end'] ?? null;
$allDay = $_POST['allDay'] ?? 0;


if (!$event_id || !$start || !$end) {
    echo json_encode(["status" => "error", "message" => "ข้อมูลไม่ครบถ้วน"]);
    exit;
}

try {
    if ($allDay == 1) {
        // อัปเดต All-day Event
        $sql = "UPDATE all_day_events SET start_date = ?, end_date = ? WHERE event_id = ?";
    } else {
        // อัปเดต Timed Event
        $sql = "UPDATE timed_events SET start_datetime = ?, end_datetime = ? WHERE event_id = ?";
    }

    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssi", $start, $end, $event_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "อัปเดตกิจกรรมสำเร็จ"]);
    } else {
        echo json_encode(["status" => "error", "message" => "SQL Error: " . $stmt->error]);
    }

    $stmt->close();
    $con->close();
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
}
?>