<style>
    .form-group {
        margin-bottom: 15px;
    }

    .col-form-label {
        font-weight: bold;
        text-align: right;
    }

    .btn-success {
        background-color: #28a745;
        border: none;
        padding: 10px 15px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    /* ปรับตำแหน่งปุ่มใน Desktop */
    @media (min-width: 768px) {
        .btn-container {
            text-align: left;
            margin-left: 200px;
        }
    }

    /* ปรับให้ปุ่มขยายเต็มจอใน Mobile */
    @media (max-width: 767px) {
        .btn-success {
            width: 100%;
        }
    }
</style>

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<?php
$query_institution = "SELECT * FROM institution";
$institution = mysqli_query($con, $query_institution);

// $row_institution = mysqli_fetch_assoc($institution);
$totalRows_institution = mysqli_num_rows($institution);

$query_LastID = "SELECT * FROM personel
ORDER BY personel_id DESC LIMIT 1";

$LastID = mysqli_query($con, $query_LastID) or die("Error in query: $query_LastID" . mysqli_error($con));
$row_LastID = mysqli_fetch_assoc($LastID);
//$totalRows_LastID = mysqli_num_rows($LastID);
?>
<div class="container mt-4">
    <h4 class="mb-3 font-weight-bold">เพิ่มบุคลากร</h4>

    <div class="card-body">
        <form action="personel_form_add_db.php" method="POST" enctype="multipart/form-data" name="add" class="form-horizontal" id="add" onsubmit="return validateForm()">
            <!-- รหัสพนักงาน -->
            <div class="form-group row">
                <label class="col-md-2 col-form-label text-md-right">รหัสพนักงาน</label>
                <div class="col-sm-6">
                    <?php $newid = ($row_LastID) ? intval($row_LastID['personel_id']) + 1 : 1; ?>
                    <input type="text" name="personel_id" class="form-control bg-light" value="<?php echo $newid; ?>" readonly>
                </div>
            </div>

            <!-- Username -->
            <div class="form-group row">
                <label class="col-md-2 col-form-label text-md-right">Username</label>
                <div class="col-md-6">
                    <input type="text" name="username" class="form-control validate-input" required minlength="3" placeholder="กรุณากรอกชื่อผู้ใช้งาน">
                    <span class="error-msg text-danger"></span>
                </div>
            </div>

            <!-- Password -->
            <div class="form-group row">
                <label class="col-md-2 col-form-label text-md-right">Password</label>
                <div class="col-md-6">
                    <input type="password" name="password" class="form-control validate-input" required minlength="3" placeholder="กรุณากรอกรหัสผ่าน">
                    <span class="error-msg text-danger"></span>
                </div>
            </div>

            <!-- ระดับผู้ใช้งาน -->
            <div class="form-group row">
                <label class="col-md-2 col-form-label text-md-right">ระดับผู้ใช้งาน</label>
                <div class="col-md-6">
                    <select name="personel_level" class="form-control">
                        <option value="member">บุคลากร</option>
                        <option value="admin">ผู้ดูแลระบบ</option>
                    </select>
                </div>
            </div>

            <!-- แผนก -->
            <div class="form-group row">
                <label class="col-md-2 col-form-label text-md-right">แผนก</label>
                <div class="col-md-6">
                    <select name="institution_id" class="form-control">
                        <?php
                        while ($row = mysqli_fetch_assoc($institution)) { ?>
                            <option value="<?php echo $row['institution_id']; ?>">
                                <?php echo htmlspecialchars($row['institution_name']); ?>
                            </option>
                        <?php } ?>
                    </select>

                </div>
            </div>

            <!-- ชื่อบุคลากร -->
            <div class="form-group row">
                <label class="col-md-2 col-form-label text-md-right">ชื่อบุคลากร</label>
                <div class="col-md-6">
                    <input type="text" name="personel_name" class="form-control validate-input" data-pattern="^[ก-๙a-zA-Z\s]+$" placeholder="กรุณากรอกชื่อ">
                    <span class="error-msg text-danger"></span>
                </div>
            </div>

            <!-- ข้อมูลการติดต่อ -->
            <div class="form-group row">
                <label class="col-md-2 col-form-label text-md-right">ข้อมูลการติดต่อ</label>
                <div class="col-md-6">
                    <input type="text" name="personel_call" class="form-control validate-input" data-pattern="^[0-9]+$" placeholder="กรุณากรอกเบอร์ติดต่อ"> 
                    <span class="error-msg text-danger"></span>
                </div>
            </div>

            <!-- อัปโหลดรูปภาพ -->
            <div class="form-group row">
                <label class="col-md-2 col-form-label text-md-right">รูปภาพ</label>
                <div class="col-md-6">
                    <input type="file" name="profile_picture" id="profile_picture" class="form-control" accept="image/*" onchange="previewImage(event)">
                    <br>
                    <img id="imagePreview" src="uploads/default.png" alt="Profile Preview" width="100" height="100" style="border-radius: 50%; display: none;" loading="lazy">
                </div>
            </div>

            <!-- ปุ่มเพิ่มข้อมูล -->
            <div class="form-group row">
                <div class="col-md-6 offset-md-2 btn-container text-center">
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="glyphicon glyphicon-plus"></i> เพิ่มข้อมูล
                    </button>
                    <a href="personel.php" class="btn btn-default btn-block">
                        <i class="glyphicon glyphicon-arrow-left"></i> ย้อนกลับ
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>


<?php
mysqli_free_result($institution);
mysqli_free_result($LastID);
?>

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
        let reader = new FileReader();
        reader.onload = function() {
            let output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>