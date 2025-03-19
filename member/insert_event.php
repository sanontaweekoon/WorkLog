<?php
session_start();
include('../condb.php');

header('Content-Type: application/json');

if (!isset($_SESSION['personel_id'])) {
    echo json_encode(["status" => "error", "message" => "กรุณาเข้าสู่ระบบก่อน"]);
    exit;
}

$personel_id = $_SESSION['personel_id'];
$title = $_POST['title'] ?? 'ไม่มีชื่อกิจกรรม';
$event_type = $_POST['event_type'] ?? 'timed'; // ค่าเริ่มต้นเป็น Timed Event

// ดึงสีของผู้ใช้จากฐานข้อมูล
$sqlColor = "SELECT COALESCE(color, '#007bff') AS color, COALESCE(text_color, '#ffffff') AS textColor FROM personel WHERE personel_id = ?";
$stmtColor = $con->prepare($sqlColor);
$stmtColor->bind_param("i", $personel_id);
$stmtColor->execute();
$resultColor = $stmtColor->get_result();
$userColor = $resultColor->fetch_assoc();
$stmtColor->close();

$color = $userColor['color'] ?? '#007bff';
$textColor = $userColor['textColor'] ?? '#ffffff';


//กรณี Allday Event (กิจกกรรมตลอดทั้งวัน)
if ($event_type === 'all_day') {
    $start_date = $_POST['start_date'] ?? date('Y-m-d');
    $end_date = $_POST['end_date'] ?? $start_date;

    if ($start_date === $end_date) {
        $end_date = $start_date;
    }

    $sql = "INSERT INTO all_day_events (title, start_date, end_date, color, text_color, personel_id, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssi", $title, $start_date, $end_date, $color, $textColor, $personel_id);

    // กรณี Timed Event
} else {
    $start = $_POST['start'] ?? date('Y-m-d 08:00:00');
    $end = $_POST['end'] ?? date('Y-m-d 17:00:00');

    $sql = "INSERT INTO timed_events (title, start_datetime, end_datetime, color, text_color, personel_id, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssi", $title, $start, $end, $color, $textColor, $personel_id);
}

// คำสั่ง SQL
if ($stmt->execute()) {
    $event_id = $stmt->insert_id;
    echo json_encode([
        "status" => "success",
        "message" => "เพิ่มกิจกรรมสำเร็จ",
        "event" => [
            "id" => $event_id,
            "title" => $title,
            "color" => $color,
            "textColor" => $textColor,
            "personel_id" => $personel_id,
            "start" => $start_date ?? $start,
            "end" => $end_date ?? $end
        ]
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาด: " . $stmt->error]);
}

$stmt->close();
$con->close();
?>
