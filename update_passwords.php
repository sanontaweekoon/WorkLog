<?php
include('./condb.php');

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// ดึง personel_id และ password จากฐานข้อมูล
$sql = "SELECT personel_id, password FROM personel";
$result = mysqli_query($con, $sql);

if (!$result) {
    die("Error retrieving data: " . mysqli_error($con));
}

$updated_count = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $personel_id = $row['personel_id'];
    $old_password = $row['password'];

    // 🔍 Debug: แสดงรหัสผ่านก่อน Hash
    echo "📌 personel_id: $personel_id, Old Password: $old_password <br>";

    // ✅ เช็คว่ารหัสเป็น Plain Text หรือ Hash โดยใช้ความยาวของรหัสผ่าน
    if (strlen($old_password) >= 60) { 
        echo "⚠️ Already Hashed - Skipping personel_id: $personel_id <br>";
        continue;
    }

    // 🔒 Hash รหัสผ่านใหม่
    $hashed_password = password_hash($old_password, PASSWORD_DEFAULT);

    // 🔍 Debug: แสดงรหัสที่ถูก Hash แล้ว
    echo "🔒 Hashed Password: $hashed_password <br>";

    // อัปเดตรหัสผ่านในฐานข้อมูล
    $update_sql = "UPDATE personel SET password = ? WHERE personel_id = ?";
    $stmt = $con->prepare($update_sql);
    $stmt->bind_param("si", $hashed_password, $personel_id);

    if ($stmt->execute()) {
        $updated_count++;
        echo "✅ Updated personel_id: $personel_id <br>";
    } else {
        echo "❌ Update failed for personel_id: $personel_id - Error: " . mysqli_error($con) . "<br>";
    }

    $stmt->close();
}

mysqli_close($con);

echo "<br>✅ อัปเดตรหัสผ่านเรียบร้อยแล้ว จำนวน: " . $updated_count . " รายการ";
?>
