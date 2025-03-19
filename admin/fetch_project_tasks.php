<?php
include('../condb.php');

header("Content-Type: application/json; charset=UTF-8");

$sql = "SELECT job_id AS id, job_name AS title, start_date AS start, due_date AS end, 
                 p.color, p.text_color, p.personel_name AS owner_name, pt.personel_id
          FROM project_tasks pt
          LEFT JOIN personel p ON pt.personel_id = p.personel_id";

$result = mysqli_query($con, $sql);
$events = [];

while ($row = mysqli_fetch_assoc($result)) {
    // ตรวจสอบว่าค่า start และ end ไม่เป็น null
    $start = !empty($row['start']) ? $row['start'] : null;
    $end = !empty($row['end']) ? $row['end'] : null;

    // ถ้าเวลาสิ้นสุดเป็น 00:00:00 ให้ถือว่าเป็น All-Day Event
    $allDay = false;
    if ($end && strpos($end, '00:00:00') !== false) {
        $allDay = true;
    }

    $events[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $start,
        'end' => $end,
        'color' => $row['color'] ?? '#007bff', // กำหนดค่าเริ่มต้น
        'textColor' => $row['text_color'] ?? '#ffffff',
        'allDay' => $allDay,
        'personel_id' => $row['personel_id'],
        'owner_name' => $row['owner_name'] ?? "ไม่ระบุ",  // ถ้าไม่มีชื่อให้ใส่ "ไม่ระบุ"
        'source' => 'project_tasks'
    ];
}

echo json_encode($events, JSON_UNESCAPED_UNICODE);
?>
