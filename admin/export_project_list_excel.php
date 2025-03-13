<?php
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;


include('../condb.php');


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


$fields = ['รหัสงาน', 'ชื่องาน', 'รายละเอียด', 'หมวดหมู่', 'ผู้รับมอบหมาย', 'สถานะ', 'วันที่เริ่มต้น', 'วันครบกำหนด', 'ความสำคัญ', 'ความคืบหน้า', 'หมายเหตุ'];
$columnIndex = 'A';

$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
        'size' => 14
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '4F81BD']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ],
    'borders' => [
        'addBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ]
];

foreach ($fields as $field) {
    $sheet->setCellValue($columnIndex . '1', $field);
    $sheet->getStyle($columnIndex . '1')->applyFromArray($headerStyle);
    $columnIndex++;
}

$lastColumn = 'K';

$query = $con->query("SELECT project_tasks.*, personel.personel_name
FROM project_tasks
INNER JOIN personel ON project_tasks.personel_id = personel.personel_id
ORDER BY project_tasks.job_id ASC
");

$rowNumber = 2;

// while ($row = $query->fetch_assoc()) {
//     echo "<pre>";
//     print_r($row);
//     echo "</pre>";
// }
// exit();

if ($query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $status = ($row['status'] == 1) ? 'Active' : 'Inactive';
        $data = [
            $row['job_id'],
            $row['job_name'],
            $row['description'],
            $row['category'],
            $row['personel_name'],
            $row['status'],
            $row['start_date'],
            $row['due_date'],
            $row['priority'],
            $row['progress'],
            $row['notes']
        ];

        $columnIndex = 'A';
        foreach ($data as $cellData) {
            $sheet->setCellValue($columnIndex . $rowNumber, $cellData);
            $columnIndex++;
        }
        $sheet->getStyle("A$rowNumber:$lastColumn$rowNumber")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        if ($rowNumber % 2 == 0) {
            $sheet->getStyle("A$rowNumber:$lastColumn$rowNumber")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9E1F2']
                ]
            ]);
        }
        $rowNumber++;
    }
}

foreach (range('A', $lastColumn) as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="job-data_' . date('d-m-Y') . '.xlsx"');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();

?>
