<meta charset="utf-8">
<?PHP
session_start();
include('../condb.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $personel_id = mysqli_real_escape_string($con, $_POST['personel_id']);
    $personel_name = mysqli_real_escape_string($con, $_POST['personel_name']);
    $institution_id = mysqli_real_escape_string($con, $_POST['institution_id']);
    $personel_call = mysqli_real_escape_string($con, $_POST['personel_call']);
    $personel_level = mysqli_real_escape_string($con, $_POST['personel_level']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = trim($_POST['password']);
}

$sql = "UPDATE personel SET 
personel_name = ?, 
personel_call = ?, 
personel_level = ?, 
institution_id = ?";

$params = [$personel_name, $personel_call, $personel_level, $institution_id];
$types = "ssss";

if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql .= ", password = ?";
    $params[] = $hashed_password;
    $types .= "s";
}

$sql .= " WHERE personel_id = ?";
$params[] = $personel_id;
$types .= "i";


$stmt = $con->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo "<script>";
    echo "alert('แก้ไขข้อมูลสำเร็จ!');";
    echo "window.location='personel.php';";
    echo "</script>";
} else {
    echo "<script>";
    echo "alert('เกิดข้อผิดพลาด! โปรดลองอีกครั้ง');";
    echo "window.history.back();";
    echo "</script>";
}

$stmt->close();
$con->close();
?>