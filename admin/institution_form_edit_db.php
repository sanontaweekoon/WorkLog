<meta charset="utf-8">
<?php
include('../condb.php');
$institution_id = $_POST['institution_id'];
$institution_name = $_POST['institution_name'];
$manager = $_POST['manager'];
$institution_contact = $_POST['institution_contact'];


$sql = "UPDATE institution 
        SET 
        institution_id = '$institution_id',
        institution_name = '$institution_name',
        manager = '$manager',
        institution_contact = '$institution_contact'
        WHERE 
        institution_id = '$institution_id'
        ";
        $result = mysqli_query($con, $sql) or die ("Error in query: $sql" . mysqli_error($con));
        mysqli_close($con);


        if($result){
            echo "<script type='text/javascript'>";
            echo "alert('แก้ไขข้อมูลเรียบร้อยแล้ว');";
            echo "window.location = 'institution.php';";
            echo "</script>";
        }else{
            echo "<script type='text/javascript'>";
            echo "window.location = 'institution.php';";
            echo "</script>";
        }
?>
