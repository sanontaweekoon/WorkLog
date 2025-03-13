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

$sql = "INSERT INTO project_tasks
        (
        job_id,
        job_name,
        description,
        category,
        personel_id,
        status,
        start_date,
        due_date,
        priority,
        progress,
        notes
        )
        VALUES
        (
        '$job_id',
        '$job_name',
        '$description',
        '$category',
        '$personel_id',
        '$status',
        '$start_date',
        '$due_date',
        '$priority',
        '$progress',
        '$notes'
        )";

        $result = mysqli_query($con, $sql) or die ("Error in query: $sql " . mysqli_error($con));

        mysqli_close($con);

        if($result){
            echo "<script type='text/javascript'>";
	        echo "alert('เพิ่มข้อมูลเรียบร้อยแล้ว');";
	        echo "window.location = 'project.php'; ";
	        echo "</script>";
        }else{
            echo "<script type='text/javascript'>";
	        echo "window.location = 'project.php'; ";
	        echo "</script>";
        }
?>