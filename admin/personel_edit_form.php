<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap">
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


    .btn-primary:hover {
        background-color: #0056b3;
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

<?php
$personel_id = mysqli_real_escape_string($con, $_GET['ID']);
$sql = "
SELECT p.*, i.institution_name 
FROM personel as p
INNER JOIN institution as i ON p.institution_id = i.institution_id
WHERE p.personel_id = $personel_id
";
$result = mysqli_query($con, $sql) or die("Error in query: $sql" . mysqli_error($con));
$row = mysqli_fetch_array($result);

$query_institution = "SELECT * FROM institution";
$result_institution = mysqli_query($con, $query_institution);
?>

<div class="container mt-4">
    <h4 class="mb-3 font-weight-bold">แก้ไขข้อมูลบุคลากร</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="personal_form_edit_db.php" method="POST" enctype="multipart/form-data" class="form-horizontal" id="editForm" onsubmit="return validateForm()">

                <!-- Username -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">Username</label>
                    <div class="col-md-6">
                        <input type="text" name="username" class="form-control bg-light" value="<?php echo $row['username']; ?>" readonly>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">Password</label>
                    <div class="col-md-6">
                        <input type="password" name="password" class="form-control" placeholder="กรอกรหัสใหม่ (ถ้าต้องการเปลี่ยน)" autocomplete="new-password">
                        <span class="error-msg text-danger"></span>
                    </div>
                </div>

                <!-- ระดับผู้ใช้งาน -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">ระดับผู้ใช้งาน</label>
                    <div class="col-md-6">
                        <select name="personel_level" class="form-control" required>
                            <option value="<?php echo $row['personel_level']; ?>">
                                <?php echo ($row['personel_level'] == 'member') ? 'บุคลากร' : 'ผู้ดูแลระบบ'; ?>
                            </option>
                            <option value="">--เลือกใหม่--</option>
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
                            <option value="<?php echo $row['institution_id'] ?>" selected><?php echo $row['institution_name'] ?></option>
                            <option value="">--เลือกใหม่--</option>
                            <?php while ($inst = mysqli_fetch_assoc($result_institution)) { ?>
                                <option value="<?php echo $inst['institution_id'] ?>"><?php echo $inst['institution_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <!-- ชื่อบุคลากร -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">ชื่อบุคลากร</label>
                    <div class="col-md-6">
                        <input type="text" name="personel_name" class="form-control validate-input" value="<?php echo $row['personel_name'] ?>" data-pattern="^[ก-๙a-zA-Z\s]+$">
                        <span class="error-msg text-danger"></span>
                    </div>
                </div>

                <!-- ข้อมูลการติดต่อ -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">ข้อมูลการติดต่อ</label>
                    <div class="col-md-6">
                        <input type="text" name="personel_call" class="form-control validate-input" value="<?php echo $row['personel_call'] ?>" data-pattern="^[0-9]+$">
                        <span class="error-msg text-danger"></span>
                    </div>
                </div>

                <!-- ปุ่มบันทึก -->
                <div class="form-group row">
                    <div class="col-md-6 offset-md-2 btn-container text-center">
                    <input type="hidden" name="personel_id" value="<?php echo $row['personel_id']; ?>">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="glyphicon glyphicon-floppy-disk"></i> บันทึก
                        </button>
                        <a href="personel.php" class="btn btn-default btn-block">
                            <i class="glyphicon glyphicon-arrow-left"></i> ย้อนกลับ
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
mysqli_free_result($result);
mysqli_free_result($result_institution);
?>

<script>
    document.querySelectorAll(".validate-input").forEach(input => {
        input.addEventListener("input", function() {
            validateInput(this);
        })
    });

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
</script>