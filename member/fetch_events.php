<?php
session_start();
include('../condb.php');

header('Content-Type: application/json');

if (!isset($_SESSION['personel_id'])) {
    echo json_encode(["status" => "error", "message" => "กรุณาเข้าสู่ระบบก่อน"]);
    exit;
}

$logged_in_user = $_SESSION['personel_id'];
$events = [];

// ดึงข้อมูล All-Day Events ของทุกคน
$sqlAllDay = "SELECT a.event_id, a.title, a.start_date, a.end_date, a.color, a.text_color, a.personel_id, p.personel_name 
              FROM all_day_events a 
              LEFT JOIN personel p ON a.personel_id = p.personel_id";

$resultAllDay = $con->query($sqlAllDay);
if (!$resultAllDay) {
    die(json_encode(["status" => "error", "message" => "SQL Error: " . $con->error]));
}

while ($row = $resultAllDay->fetch_assoc()) {
    $events[] = [
        "id" => $row['event_id'],
        "title" => $row['title'],
        "start" => $row['start_date'],
        "end" => $row['end_date'],
        "allDay" => true,
        "color" => $row['color'],
        "textColor" => $row['text_color'],
        "personel_id" => $row['personel_id'],
        "personel_name" => $row['personel_name'],
        "isOwner" => ($row['personel_id'] == $logged_in_user) // เช็คว่าเป็นเจ้าของหรือไม่
    ];
}

// ดึงข้อมูล Timed Events ของทุกคน
$sqlTimed = "SELECT t.event_id, t.title, t.start_datetime, t.end_datetime, t.color, t.text_color, t.personel_id, p.personel_name 
             FROM timed_events t 
             LEFT JOIN personel p ON t.personel_id = p.personel_id";

$resultTimed = $con->query($sqlTimed);
if (!$resultTimed) {
    die(json_encode(["status" => "error", "message" => "SQL Error: " . $con->error]));
}

while ($row = $resultTimed->fetch_assoc()) {
    $events[] = [
        "id" => $row['event_id'],
        "title" => $row['title'],
        "start" => $row['start_datetime'],
        "end" => $row['end_datetime'],
        "allDay" => false,
        "color" => $row['color'],
        "textColor" => $row['text_color'],
        "personel_id" => $row['personel_id'],
        "personel_name" => $row['personel_name'],
        "isOwner" => ($row['personel_id'] == $logged_in_user) // เช็คว่าเป็นเจ้าของหรือไม่
    ];
}

$con->close();

// ตรวจสอบค่าก่อนส่งกลับ
error_log(json_encode($events));

echo json_encode($events, JSON_UNESCAPED_UNICODE);
?>
