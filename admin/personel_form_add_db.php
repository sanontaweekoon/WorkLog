<meta charset="utf-8">
<?php
include('../condb.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $personel_id = $_POST['personel_id'];
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $personel_name = mysqli_real_escape_string($con, $_POST['personel_name']);
    $personel_call = mysqli_real_escape_string($con, $_POST['personel_call']);
    $institution_id = mysqli_real_escape_string($con, $_POST['institution_id']);
    $personel_level = mysqli_real_escape_string($con, $_POST['personel_level']);
    $profile_picture = "default.png";
}

// **ตรวจสอบและอัปโหลดรูป**
if (!empty($_FILES["profile_picture"]["name"])) {
    $target_dir = "uploads/personel/"; // โฟลเดอร์เก็บรูปภาพ
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true); // สร้างโฟลเดอร์ถ้ายังไม่มี
    }

    $file_ext = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
    $allowed_ext = array("jpg", "jpeg", "png", "gif");

    if (in_array($file_ext, $allowed_ext)) {
        $file_name = time() . "_" . uniqid() . "." . $file_ext;
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = $file_name; // ใช้ชื่อไฟล์ที่อัปโหลดแทน default.png
        }
    } else {
        echo "<script>alert('อัปโหลดได้เฉพาะไฟล์ JPG, JPEG, PNG, GIF เท่านั้น'); window.history.back();</script>";
        exit();
    }
}

//ฟังก์ชันสุ่มสีพื้นหลังและสีตัวอักษร
function getRandomColor()
{
    $r = mt_rand(0, 255);
    $g = mt_rand(0, 255);
    $b = mt_rand(0, 255);

    $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

    //แปลงค่าสีเป็น HEX
    $bgColor = sprintf("#%02X%02X%02X", $r, $g, $b);

    // กำหนดสีตัวอักษร (ให้ constast กับพืนหลัง)
    $textColor = ($luminance > 0.5) ? "#000000" : "#FFFFFF";

    return [$bgColor, $textColor];
}

// สุ่มสีเมื่อเพิ่ม user ใหม่
list($color, $textColor) = getRandomColor();

$sql = "INSERT INTO personel
        (
        personel_id,
        personel_name,
        personel_call,
        institution_id,
        personel_level,
        username,
        password,
        color,
        text_color,
        profile_picture
        )
        VALUES
        (
        '$personel_id',
        '$personel_name',
        '$personel_call',
        '$institution_id',
        '$personel_level',
        '$username',
        '$password',
        '$color',
        '$textColor',
        '$profile_picture'
        )";

$result = mysqli_query($con, $sql) or die("Error query $sql" . mysqli_error($con));

mysqli_close($con);
if ($result) {
    echo "<script type='text/javascript'>";
    echo "alert('เพิ่มข้อมูลเรียบร้อยแล้ว');";
    echo "window.location = 'personel.php';";
    echo "</script>";
} else {
    echo "<script type='text/javascript'>";
    echo "window.location = 'personel.php';";
    echo "</script>";
}
?>