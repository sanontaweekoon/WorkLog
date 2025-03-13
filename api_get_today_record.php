<?php
include('../condb.php');

// $today = date("Y-m-d"); // วันที่วันนี้

$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '2000-01-01';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date("Y-m-d"); // ค่าเริ่มต้นคือวันนี้

$sql = "SELECT r.title, r.date, r.detail, p.personel_name, p.color, p.text_color 
        FROM tbl_m_record AS r 
        JOIN personel AS p ON r.personel_id = p.personel_id 
        WHERE r.date BETWEEN '$startDate' AND '$endDate' 
        ORDER BY r.date DESC";


$result = $con->query($sql);

$base_url = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) ? "http://localhost/worklog/member/uploads/" : "http://stockappst.com/member/uploads/"; //ต้องเปลี่ยนเวลาที่ขึ้น server จริง

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
            "description" => $detail
        ]

    ];
}

header("Content-Type: application/json");
echo json_encode($data);
