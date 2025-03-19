<head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<?php
$ID = mysqli_real_escape_string($con, $_GET['ID']);

// ดึงข้อมูลโปรเจกต์จากฐานข้อมูล
$sql = "SELECT 
            pr.*, 
            pe.*, 
            ins.institution_id, 
            ins.institution_name
        FROM project_tasks AS pr
        INNER JOIN personel AS pe ON pr.personel_id = pe.personel_id
        INNER JOIN institution AS ins ON pe.institution_id = ins.institution_id
        WHERE pr.job_id = '$ID'
        ORDER BY pr.personel_id ASC";

$result = mysqli_query($con, $sql) or die("Error in query: $sql " . mysqli_error($con));
$row = mysqli_fetch_array($result);

// ดึงข้อมูลแผนกทั้งหมด
$query_institution = "SELECT * FROM institution";
$institution = mysqli_query($con, $query_institution);

// ดึงข้อมูลพนักงานที่เกี่ยวข้อง
$query_personel = "SELECT * FROM personel WHERE institution_id = '" . $row['institution_id'] . "'";
$personel = mysqli_query($con, $query_personel);

// แปลงสถานะเป็นภาษาไทย
$statusOptions = [
    "notStart" => "ยังไม่เริ่ม",
    "inProgress" => "กำลังดำเนินการ",
    "pendingReview" => "รอการตรวจสอบ",
    "waiting" => "รอข้อมูลเพิ่มเติม",
    "onHold" => "ถูกระงับ",
    "completed" => "เสร็จสิ้น",
    "cancelled" => "ยกเลิก"
];

$statusText = $statusOptions[$row["status"]] ?? "ไม่ทราบค่า";

// แปลงวันที่ให้เข้ากับ input[type="datetime-local"]
$start_date = date("Y-m-d\TH:i", strtotime($row['start_date']));
$due_date = date("Y-m-d\TH:i", strtotime($row['due_date']));
?>


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

<div class="container mt-4">
    <h4 class="mb-3 font-weight-bold">แก้ไขข้อมูลโปรเจกต์</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="project_form_edit_db.php" method="POST" enctype="multipart/form-data" class="form-horizontal" id="editProjectForm">

                <!-- รหัสโปรเจกต์ -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">รหัสโปรเจกต์</label>
                    <div class="col-md-6">
                        <input type="text" name="job_id" class="form-control bg-light" value="<?php echo $ID; ?>" readonly>
                    </div>
                </div>


                <!-- ชื่อโปรเจค/งาน -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">ชื่อโปรเจค/งาน</label>
                    <div class="col-md-6">
                        <input type="text" name="job_name" class="form-control" value="<?php echo $row['job_name']; ?>" required>
                    </div>
                </div>

                <!-- รายละเอียด -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">รายละเอียด</label>
                    <div class="col-md-6">
                        <input type="text" name="description" class="form-control" value="<?php echo $row['description']; ?>" required>
                    </div>
                </div>

                <!-- หมวดหมู่ -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">หมวดหมู่</label>
                    <div class="col-md-6">
                        <input type="text" name="category" class="form-control" value="<?php echo $row['category']; ?>" required>
                    </div>
                </div>

                <!-- แผนก -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">แผนก</label>
                    <div class="col-md-6">
                        <select name="institution_id" id="institution_id" class="form-control">
                            <?php while ($row_inst = mysqli_fetch_assoc($institution)) { ?>
                                <option value="<?= $row_inst['institution_id'] ?>" <?= ($row_inst['institution_id'] == $row['institution_id']) ? "selected" : "" ?>>
                                    <?= $row_inst['institution_name'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <!-- ผู้รับมอบหมาย -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">ผู้รับมอบหมาย</label>
                    <div class="col-md-6">
                        <select name="personel_id" id="personel_id" class="form-control">
                            <?php while ($row_pers = mysqli_fetch_assoc($personel)) { ?>
                                <option value="<?= $row_pers['personel_id'] ?>" <?= ($row_pers['personel_id'] == $row['personel_id']) ? "selected" : "" ?>>
                                    <?= $row_pers['personel_name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <!-- สถานะ -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">สถานะ</label>
                    <div class="col-md-6">
                        <select name="status" class="form-control">
                            <?php foreach ($statusOptions as $value => $label) { ?>
                                <option value="<?= $value ?>" <?= ($row['status'] == $value) ? "selected" : "" ?>><?= $label ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <!-- วันที่เริ่มต้น -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">วันที่เริ่มต้น</label>
                    <div class="col-md-6">
                        <input type="datetime-local" name="start_date" class="form-control" value="<?= $row['start_date']; ?>" required>
                    </div>
                </div>

                <!-- วันครบกำหนด -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">วันครบกำหนด</label>
                    <div class="col-md-6">
                        <input type="datetime-local" name="due_date" class="form-control" value="<?= $row['due_date']; ?>" required>
                    </div>
                </div>

                <!-- ความสำคัญ -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">ความสำคัญ</label>
                    <div class="col-md-6">
                        <select name="priority" id="priority" class="form-control" required>
                            <option value="Critical" <?= ($row['priority'] == 'Critical') ? 'selected' : ''; ?>>สูงมาก</option>
                            <option value="High" <?= ($row['priority'] == 'High') ? 'selected' : ''; ?>>สูง</option>
                            <option value="Medium" <?= ($row['priority'] == 'Medium') ? 'selected' : ''; ?>>กลาง</option>
                            <option value="Low" <?= ($row['priority'] == 'Low') ? 'selected' : ''; ?>>ต่ำ</option>
                        </select>
                    </div>
                </div>


                <!-- ความคืบหน้า -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">
                        ความคืบหน้า <span id="progressValue"><?= isset($row['progress']) ? $row['progress'] : '0'; ?>%</span>
                    </label>
                    <div class="col-md-6">
                        <input type="range" name="progress" id="progressSlider"
                            min="0" max="100" step="10"
                            value="<?= isset($row['progress']) ? $row['progress'] : '0'; ?>">
                    </div>
                </div>


                <!-- หมายเหตุ -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">หมายเหตุ</label>
                    <div class="col-md-6">
                        <textarea name="notes" class="form-control" rows="3" required><?= $row['notes']; ?></textarea>
                    </div>
                </div>

                <!-- ปุ่มบันทึก -->
                <div class="form-group row">
                    <div class="col-md-6 offset-md-2 btn-container text-center">
                        <input type="hidden" name="personel_id" value="<?php echo $row['personel_id']; ?>">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="glyphicon glyphicon-floppy-disk"></i> บันทึก
                        </button>
                        <a href="project.php" class="btn btn-default btn-block">
                            <i class="glyphicon glyphicon-arrow-left"></i> ย้อนกลับ
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    var slider = document.getElementById("progressSlider");
    var output = document.getElementById("progressValue");

    // อัปเดตค่าแสดงผลเมื่อเลื่อน slider
    slider.oninput = function() {
        output.innerHTML = this.value + "%";
    };

    $("#institution_id").change(function() {
        var institution_id = $(this).val();
        $.ajax({
            url: "get_personel.php",
            method: "POST",
            data: {
                institution_id: institution_id
            },
            success: function(data) {
                $("#personel_id").html(data);
            }
        });
    });
</script>