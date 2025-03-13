<?php
include('../condb.php');
$institution_id = mysqli_real_escape_string($con, $_POST['institution_id']);
$institution_name = mysqli_real_escape_string($con, $_POST['institution_name']);
$manager = mysqli_real_escape_string($con, $_POST['manager']);
$institution_contact = mysqli_real_escape_string($con, $_POST['institution_contact']);

$sql = "INSERT INTO institution 
        (
        institution_id,
        institution_name,
        institution_contact,
        manager
        ) 
        VALUES 
        (
        '$institution_id',
        '$institution_name',
        '$institution_contact',
        '$manager'
        )
        ";

$result = mysqli_query($con, $sql) or die("Error in query: $sql " . mysqli_error($con));

mysqli_close($con);

if ($result) {
    echo "<script type='text/javascript'>";
    echo "alert('แก้ไขข้อมูลเรียบร้อยแล้ว');";
    echo "window.location = 'institution.php';";
    echo "</script>";
} else {
    echo "<script type='text/javascript'>";
    echo "window.location = 'institution.php';";
    echo "</script>";
}
?>