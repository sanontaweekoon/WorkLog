<meta charset="utf-8">
<?php
include('../condb.php');

$job_id = $_POST['job_id'];
$job_name = $_POST['job_name'];
$description = $_POST['description'];
$category = $_POST['category'];
$personel_id = $_POST['personel_id'];
$status = $_POST['status'];
$start_date = $_POST['start_date'];
$due_date = $_POST['due_date'];
$priority = $_POST['priority'];
$progress = $_POST['progress'];
$notes = $_POST['notes'];

$sql = "UPDATE project_tasks SET
        job_id = '$job_id',
        job_name = '$job_name',
        description = '$description',
        category = '$category',
        personel_id = '$personel_id',
        status = '$status',
        start_date = '$start_date',
        due_date = '$due_date',
        priority = '$priority',
        progress = '$progress',
        notes = '$notes'
        WHERE job_id = '$job_id'
";

$result = mysqli_query($con, $sql) or die ("Error in query: $sql " . mysqli_error($con));

// echo $sql;
// exit();

mysqli_close($con);

	if($result){
	echo "<script type='text/javascript'>";
	echo "alert('แก้ไขข้อมูลเรียบร้อยแล้ว');";
	echo "window.location = 'project.php'; ";
	echo "</script>";
	}else{
	echo "<script type='text/javascript'>";
	echo "window.location = 'project.php'; ";
	echo "</script>";
	}
?>