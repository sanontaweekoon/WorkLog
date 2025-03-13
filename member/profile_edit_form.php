<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap">

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<style>
    .profile-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ddd;
    }

    /* จัดตำแหน่งรูปใน Desktop ให้ตรงกับกล่องข้อความ */
    @media (min-width: 768px) {
        .profile-container {
            display: flex;
            align-items: center;
            /* จัดให้อยู่ตรงกลางแนวตั้ง */
            justify-content: flex-start;
            /* ชิดซ้าย */
        }

        .profile-img {
            margin-right: 600px;
            /* เพิ่มระยะห่างจากกล่องข้อความ */
        }
    }

    /* จัดให้อยู่ตรงกลางใน Mobile */
    @media (max-width: 767px) {
        .profile-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            /* จัดให้อยู่ตรงกลาง */
            text-align: center;
        }

        .profile-img {
            margin-bottom: 15px;
            /* เพิ่มระยะห่างด้านล่าง */
        }
    }

    .form-group label {
        text-align: left !important;
        display: block;
        /* บังคับให้อยู่คนละบรรทัดกับ Input */
        font-weight: bold;
    }

    @media (min-width: 768px) {
        .form-group label {
            text-align: right !important;
            display: inline-block;
            width: 150px;
            /* ปรับขนาดให้เหมาะสม */
        }
    }
</style>

<?php
$personel_id = mysqli_real_escape_string($con, $_GET['ID']);

$sql = "SELECT * FROM personel WHERE personel_id = $personel_id";
$result = mysqli_query($con, $sql) or die("Error in query: $sql" . mysqli_error($con));
$row = mysqli_fetch_array($result);


// ตรวจสอบว่ามีรูปภาพหรือไม่
$profile_picture = !empty($row["profile_picture"]) ? "../admin/uploads/personel/" . $row["profile_picture"] : "../admin/uploads/personel/default.png";

// extract($row);
// echo $sql;
// echo '<pre>';
// print_r($row);
// echo '</pre>';
?>

<h4>แก้ไขข้อมูล</h4>
<form action="profile_form_edit_db.php" method="POST" enctype="multipart/form-data" name="add" class="form-horizontal" id="add" onsubmit="return validateForm()">
    <!-- แสดงรูปโปรไฟล์ -->
    <div class="form-group text-center">
        <img id="profilePreview" src="<?= !empty($row['profile_picture']) ? '../admin/uploads/personel/' . $row['profile_picture'] : '../admin/uploads/personel/default.png'; ?>"
            class="profile-img" alt="Profile Picture">
    </div>

    <!-- อัปโหลดรูป -->
    <div class="form-group">
        <label class="col-sm-2 col-form-label">อัปโหลดรูป</label>
        <div class="col-sm-3" align="left">
            <input type="file" name="profile_picture" class="form-control-file" accept="image/*" onchange="previewImage(event)">
        </div>
    </div>

    <input type="hidden" name="old_profile_picture" value="<?= $row['profile_picture']; ?>">

    <div class="form-group">
        <label class="col-sm-2 col-form-label">ชื่อ Login</label>
        <div class="col-sm-3" align="left">
            <input type="text" name="username" id="username" class="form-control" value="<?php echo $row['username']; ?>" readonly>
            <span id="error-msg" style="color: red;"></span>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 col-form-label">ชื่อ</label>
        <div class="col-sm-3" align="left">
            <input type="text" name="personel_name" id="personel_name" class="form-control validate-input" value="<?php echo $row['personel_name']; ?>" data-pattern="^[ก-๙a-zA-Z\s]+$">
            <span id="error-msg" style="color: red;"></span>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 col-form-label">เบอร์โทร</label>
        <div class="col-sm-3" align="left">
            <input type="text" name="personel_call" id="personel_call" class="form-control validate-input" value="<?php echo $row['personel_call']; ?>" data-pattern="^[0-9]+$">
            <span id="error-msg" style="color: red;"></span>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-4">
            <input type="hidden" name="personel_id" value="<?php echo $row['personel_id']; ?>">
            <button type="submit" class="btn btn-success" id="btn"><i class="fa fa-edit"></i> ยืนยันการแก้ไข</button>
            <a href='profile.php' class='btn btn-default' id='btn'><i class='far fa-arrow-alt-circle-left'></i> ย้อนกลับ </a>
        </div>
    </div>

    <script>
        document.querySelectorAll(".validate-input").forEach(input => {
            input.addEventListener("input", function() {
                validateInput(this);
            })
        })

        function validateInput(input) {
            var pattern = new RegExp(input.dataset.pattern);
            var errorMsg = input.nextElementSibling;

            if (!pattern.test(input.value)) {
                errorMsg.innerText = "ห้ามใช้อักขระพิเศษ!";
                input.style.borderColor = "red";
            } else {
                errorMsg.innerText = "";
                input.style.borderColor = "green";
            }
        }

        function validateForm() {
            let isValid = true;
            document.querySelectorAll(".validate-input").forEach(input => {
                validateInput(input);
                if (input.style.borderColor === "red") {
                    isValid = false;
                }
            });
            return isValid;
        }

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('profilePreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

</form>