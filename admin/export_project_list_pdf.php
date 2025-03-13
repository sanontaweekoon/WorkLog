<?php
require('../vendor/tecnickcom/tcpdf/tcpdf.php');

include('../condb.php');

$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setCreator(PDF_CREATOR);
$pdf->SetTitle('รายงานข้อมูลโปรเจค');
$pdf->SetAutoPageBreak(false, 10);
$pdf->AddPage();
$pdf->SetFont('thsarabunnew', '', 18);

$pdf->Cell(0, 10, 'รายงานข้อมูลโปรเจค', 0, 1, 'C');

$pdf->SetFont('thsarabunnew', 'B', 14);
$pdf->setFillColor(250, 250, 0);
$pdf->setTextColor(0, 0, 0);

$columns = [
    ['label' => 'รหัสงาน', 'width' => 12],
    ['label' => 'ชื่องาน', 'width' => 35],
    ['label' => 'รายละเอียด', 'width' => 40],
    ['label' => 'หมวดหมู่', 'width' => 25],
    ['label' => 'ผู้รับมอบหมาย', 'width' => 25],
    ['label' => 'สถานะ', 'width' => 20],
    ['label' => 'วันที่เริ่มต้น', 'width' => 25],
    ['label' => 'วันครบกำหนด', 'width' => 25],
    ['label' => 'ความสำคัญ', 'width' => 18],
    ['label' => 'ความคืบหน้า', 'width' => 18],
    ['label' => 'หมายเหตุ', 'width' => 35]
];

foreach ($columns as $col) {
    $pdf->Cell($col['width'], 10, $col['label'], 1, 0, 'C', true);
}
$pdf->Ln();

$pdf->SetFont('thsarabunnew', '', 14);
$pdf->setTextColor(0, 0, 0);

$query = $con->query("SELECT project_tasks.*, personel.personel_name
FROM project_tasks
INNER JOIN personel ON project_tasks.personel_id = personel.personel_id
ORDER BY project_tasks.job_id ASC
");

$fill = false;

while ($row = $query->fetch_assoc()) {
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

    $lineHeight = 7;
    $maxLines = 1;

    foreach ($columns as $index => $col) {
        $lines = $pdf->getNumLines($data[$index], $col['width']);
        if ($lines > $maxLines) {
            $maxLines = $lines;
        }
    }
    $rowHeight = $lineHeight * $maxLines;

    if ($pdf->GetY() + $rowHeight > $pdf->getPageHeight() - 10) {
        $pdf->AddPage();
        foreach ($columns as $col) {
            $pdf->Cell($col['width'], 10, $col['label'], 1, 0, 'C', true);
        }
        $pdf->Ln();
    }


    foreach ($columns as $index => $col) {
        $pdf->MultiCell($col['width'], $rowHeight, $data[$index], 1, 'C', $fill, 0);
    }

    $pdf->Ln();
    // $fill = !$fill;
}

$pdf->Output('project_report.pdf', 'D');
exit();
