<?php 
session_start();
include("condb.php");

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

if(isset($_POST['username']) && isset($_POST['password'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM personel WHERE username = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $hashed_password = $row["password"]; 

        if (password_verify($password, $hashed_password)) {
            $_SESSION["personel_id"] = $row["personel_id"];
            $_SESSION["personel_name"] = $row["personel_name"];
            $_SESSION["personel_level"] = $row["personel_level"];

            if ($_SESSION["personel_level"] == "admin") {    
                Header("Location: admin/");
                exit();
            } elseif ($_SESSION["personel_level"] == "member") { 
                Header("Location: member/");
                exit();
            }
        } else {
            echo "<script>";
            echo "alert('รหัสผ่านไม่ถูกต้อง');"; 
            echo "window.history.back();";
            echo "</script>";
        }
    } else {
        echo "<script>";
        echo "alert('ไม่พบบัญชีผู้ใช้');"; 
        echo "window.history.back();";
        echo "</script>";
    }

    $stmt->close();
} else {
    Header("Location: index.php"); 
}

$con->close();
?>
