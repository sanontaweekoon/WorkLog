<?php
include('../condb.php');

if (isset($_POST["institution_id"])) {
    $institution_id = mysqli_real_escape_string($con, $_POST["institution_id"]);

    $query =  "SELECT * FROM personel WHERE institution_id = '$institution_id' AND personel_id != 1";
    $result = mysqli_query($con, $query);

    $output = '<option value="" selected disabled>--เลือกพนักงาน--</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= '<option value="' . $row['personel_id'] . '">' . $row['personel_name'] . '</option>';
    }
    echo $output;
}
?>
