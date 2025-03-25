<?php
include('../condb.php');

// $today = date("Y-m-d"); // วันที่วันนี้
$startDate = (isset($_GET['start_date']) ? $_GET['start_date'] : '2000-01-01');
$endDate = (isset($_GET['end_date']) ? $_GET['end_date'] : date("Y-m-d", strtotime("+1 year")));

// รับค่า ID ของแผนก
$institution_id = isset($_GET['institution_id']) ? mysqli_real_escape_string($con, $_GET['institution_id']) : null;
$personel_id = isset($_GET['personel_id']) ? mysqli_real_escape_string($con, $_GET['personel_id']) : null;

if ($institution_id == 2 && $personel_id !== "all") {
    // ถ้าล็อคอินเข้ามาเป็นแผนกที่ 2 ขึ้นไปให้ แสดงโชว์แค่แผนกของตัวเอง
    $sql = "SELECT
            r.title,
            r.date,
            r.detail,
            p.personel_name,
            p.color,
            p.text_color
            FROM
            tbl_m_record AS r
            JOIN personel AS p
            ON r.personel_id = p.personel_id
            WHERE r.date BETWEEN '$startDate' AND '$endDate'
    ";
} else {
    // แสดงข้อมูลทั้งหมดสำหรับแผนกที่ 1
    $sql = "SELECT
            r.title,
            r.date,
            r.detail,
            p.personel_name,
            p.color,
            p.text_color 
            FROM tbl_m_record AS r 
            JOIN personel AS p ON r.personel_id = p.personel_id 
            WHERE r.date BETWEEN '$startDate' AND '$endDate'
    ";
}


// กรองตามแผนก ถ้ามีค่าที่ส่งมา
if (!empty($institution_id)) {
    // กรองกรณีที่ไม่มีแผนก หรือแผนกที่ไม่ระบุ
    $sql .= " AND p.institution_id = '$institution_id'";
}

// กรองตามบุคคล ถ้ามีค่าที่ส่งมาR
if (!empty($personel_id) && $personel_id !== 'all') {
    $sql .= " AND p.personel_id = '$personel_id'";
}

$sql .= " ORDER BY r.date DESC";

$result = $con->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "SQL ERROR", "message" => mysqli_error($con)]);
    exit;
}

$base_url = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) ? "http://localhost/worklog/uploads/" : "http://172.16.124.115/worklog/uploads/"; //ต้องเปลี่ยนเวลาที่ขึ้น server จริง

$data = [];
while ($row = $result->fetch_assoc()) {
    // ปรับ URL ของภาพในรายละเอียด
    $detail = (!empty($row['detail'])) ? str_replace(
        'src="uploads/',
        'src="' . $base_url,
        htmlspecialchars_decode($row['detail'])
    ) : "ไม่มีรายละเอียด";

    $data[] = [
        "title" => $row['title'],
        "start" => $row['date'],
        "backgroundColor" => $row['color'], // สีพื้นหลัง
        "textColor" => $row['text_color'], // สีตัวอักษร
        "extendedProps" => [
            "personel_name" => $row['personel_name'],
            "institution" => $row['institution'] ?? "ไม่ระบุ",
            "personel_id" => $row['personel_id'],
            "description" => $detail ?? "ไม่มีรายละเอียด"
        ]
    ];
}

header("Content-Type: application/json");
echo json_encode($data);
