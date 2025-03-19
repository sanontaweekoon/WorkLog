<?php
include('../condb.php');
require '../vendor/autoload.php'; // โหลด PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '2000-01-01';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date("Y-m-d");

// ดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT r.title, r.date, r.detail, p.personel_name 
        FROM tbl_m_record AS r 
        JOIN personel AS p ON r.personel_id = p.personel_id 
        WHERE r.date BETWEEN '$startDate' AND '$endDate' 
        ORDER BY r.date DESC";

$result = $con->query($sql);

// สร้างไฟล์ Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// ตั้งค่าหัวข้อ
$sheet->setCellValue('A1', 'ชื่อเรื่อง');
$sheet->setCellValue('B1', 'วันที่');
$sheet->setCellValue('C1', 'โดย');
$sheet->setCellValue('D1', 'รายละเอียด');

// ใส่ข้อมูลใน Excel
$rowNum = 2;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, $row['title']);
    $sheet->setCellValue('B' . $rowNum, date("d/m/Y", strtotime($row['date'])));
    $sheet->setCellValue('C' . $rowNum, $row['personel_name']);
    $sheet->setCellValue('D' . $rowNum, strip_tags(htmlspecialchars_decode($row['detail'])));
    $rowNum++;
}

// ตั้งค่าหัวข้อไฟล์ให้ดาวน์โหลด
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="worklog_export_' . date('Ymd') . '.xlsx"');

// สร้างไฟล์ Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit();
?>
