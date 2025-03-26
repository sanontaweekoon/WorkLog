<?php
session_start();
include('../condb.php');

$personel_id = $_SESSION['personel_id'];

$sql = "SELECT tbl_m_record.*, personel.personel_name
        FROM tbl_m_record
        INNER JOIN personel ON tbl_m_record.personel_id = personel.personel_id
        WHERE tbl_m_record.personel_id = '$personel_id'
        ORDER BY tbl_m_record.date DESC
        ";

$result = mysqli_query($con, $sql);
$data = [];

while ($row = mysqli_fetch_assoc($result)){
    $data[] = $row;
}
echo json_encode($data);
?>