<style>
    #box-list {
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
        padding: 20px;
        margin-bottom: 30px;
        font-size: 16px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    td {
        padding: 6px;
        vertical-align: middle;
        font-size: 16px;
        color: #555;
        text-align: left;
        /* เพิ่มการจัดตำแหน่งซ้าย */
    }

    th {
        text-align: left;
        padding: 12px;
        background-color: #f1f1f1;
        color: #333;
        font-weight: bold;
    }

    #f-right {
        text-align: right;
        font-weight: bold;
        color: #333;
    }


    .box-list table td,
    .box-list table th {
        text-align: left;
        /* จัดตำแหน่งข้อความด้านซ้าย */
    }

    .box-list table tr:not(:first-child) {
        border-top: 1px solid #ddd;
        /* เพิ่มเส้นขอบระหว่างแถว */
    }

    .box-list table td:first-child {
        width: 10%;
        /* เพิ่มช่องว่างสำหรับข้อมูลที่แสดงในแถวแรก */
        font-weight: bold;
        /* ทำให้ข้อความในคอลัมน์แรกหนาขึ้น */
    }

    .box-list table td:last-child {
        width: 90%;
        /* เพิ่มช่องว่างสำหรับข้อมูลที่แสดงในคอลัมน์สุดท้าย */
    }

    .profile-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ddd;
        margin-bottom: 5px;
        margin-left: 40px;
    }
</style>

<?php
session_start();
include('../condb.php');

if (!isset($_SESSION['personel_id'])) {
    header("Location: index.php");
    exit();
}

$personel_id = $_SESSION['personel_id'];

$query = "SELECT p.*, i.institution_name 
FROM personel p 
LEFT JOIN institution i 
ON p.institution_id = i.institution_id 
WHERE p.personel_id = '$personel_id'";

$result = mysqli_query($con, $query) or die("Error:" . mysqli_error($con));

if ($row_rsmem = mysqli_fetch_assoc($result)) {
    $profile_picture = !empty($row_rsmem["profile_picture"]) ? "../admin/uploads/personel/" . $row_rsmem["profile_picture"] : "../admin/uploads/personel/default.png";

?>

    <div class='box-list'>
        <img src="<?= $profile_picture; ?>" class="profile-img" alt="Profile Picture" loading="lazy">

        <table width='100%'>
            <tbody>
                <tr>
                    <td id='f-right'">
                        <strong>Username</strong>
                    </td>
                    <?php
                    echo "<td>" . $row_rsmem['username'] . "</td>";
                    ?>
                </tr>

                <tr>
                    <td id='f-right'>
                        <strong>ชื่อ</strong>
                    </td>
                    <?php
                    echo "<td>" . $row_rsmem['personel_name'] . "</td>";
                    ?>
                </tr>

                <tr>
                    <td id='f-right'>
                        <strong>เบอร์โทร</strong>
                    </td>
                    <?php
                    echo "<td>" . $row_rsmem['personel_call'] . "</td>";
                    ?>
                </tr>

                <tr>
                    <td id='f-right'>
                        <strong>แผนก</strong>
                    </td>
                    <?php
                    echo "<td>" . $row_rsmem['institution_name'] . "</td>";
                    ?>
                </tr>

            </tbody>
        </table>

        <div style='text-align: left; margin-top: 10px; margin-left: 10px;'>
            <a href='profile.php?act=edit&ID=<?php echo $personel_id; ?>' class='btn btn-success' id='btn'><i class=" fa fa-edit"></i> แก้ไข </a>
                        <a href='add_record.php' class='btn btn-default' id='btn'><i class='far fa-arrow-alt-circle-left'></i> ย้อนกลับ </a>
    </div>
    </div>
<?php
} else {
    echo "<p style='color:red;'>ไม่พบข้อมูลบุคลากร</p>";
}
?>
</form>