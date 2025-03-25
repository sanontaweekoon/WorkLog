<head> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<?php
$query_personel = "SELECT * FROM personel";
$personel = mysqli_query($con, $query_personel);
$row_personel = mysqli_fetch_assoc($personel);
$totalRows_personel = mysqli_num_rows($personel);

$query_LastID = "SELECT * FROM project_tasks
ORDER BY job_id DESC";
$LastID = mysqli_query($con, $query_LastID) or die("Error in query: $query_LastID" . mysqli_error($con));
$row_LastID = mysqli_fetch_assoc($LastID);
$totalRows_LastID = mysqli_num_rows($LastID);

$query_institution = "SELECT * FROM institution";
$institution = mysqli_query($con, $query_institution);
// $row_institution = mysqli_fetch_assoc($institution);
$totalRows_institution = mysqli_num_rows($institution);

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
        font-size: 12px;
        transition: all 0.3s ease;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
        padding: 10px 20px;
        font-size: 12px;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .progress {
        height: 25px;
        background-color: #f3f3f3;
        border-radius: 5px;
        position: relative;
        margin-top: 10px;
    }

    .progress-bar {
        height: 100%;
        background-color: #28a745;
        border-radius: 5px;
        text-align: center;
        line-height: 25px;
        color: white;
        font-weight: bold;
    }

    @media (min-width: 768px) {
        .btn-container {
            text-align: left;
            margin-left: 200px;
        }
    }

    @media (max-width: 767px) {
        .btn-success {
            width: 100%;
        }
    }
</style>


<div class="container mt-4">
    <h4 class="mb-3 font-weight-bold">เพิ่มข้อมูลโปรเจกต์</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="project_form_add_db.php" method="POST" enctype="multipart/form-data" class="form-horizontal" id="add">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">รหัสโปรเจกต์</label>
                    <div class="col-md-6">
                        <?php
                        $newid = number_format(intval($row_LastID['job_id'])) + 1;
                        ?>
                        <input type="text" name="job_id" class="form-control bg-light" value="<?php echo $newid; ?>" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">ชื่อโปรเจค/งาน</label>
                    <div class="col-md-6">
                        <input type="text" name="job_name" class="form-control" placeholder="กรอกชื่อโปรเจค" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">รายละเอียด</label>
                    <div class="col-md-6">
                        <input type="text" name="description" class="form-control" placeholder="กรอกรายละเอียด" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">หมวดหมู่</label>
                    <div class="col-md-6">
                        <input type="text" name="category" class="form-control" placeholder="กรอกหมวดหมู่" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">แผนก</label>
                    <div class="col-md-6">
                        <select name="institution_id" id="institution_id" class="form-control">
                            <option value="" selected disabled>--เลือกแผนก--</option>
                            <?php
                            while ($row = mysqli_fetch_assoc($institution)) { ?>
                                <option value="<?php echo $row['institution_id']; ?>">
                                    <?php echo htmlspecialchars($row['institution_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">ผู้รับมอบหมาย</label>
                    <div class="col-md-6">
                        <select name="personel_id" id="personel_id" class="form-control">
                            <option value="" selected disabled>--เลือกพนักงาน--</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">สถานะ</label>
                    <div class="col-md-6">
                        <select name="status" id="status" class="form-control">
                            <option value="notStart">ยังไม่เริ่ม</option>
                            <option value="inProgress">กำลังดำเนินการ</option>
                            <option value="pendingReview">รอการตรวจสอบ</option>
                            <option value="waiting">รอข้อมูลเพิ่มเติม</option>
                            <option value="onHold">ถูกระงับ</option>
                            <option value="completed">เสร็จสิ้น</option>
                            <option value="cancelled">ยกเลิก</option>
                        </select>
                    </div>
                </div>

                <!-- วันที่เริ่มต้น -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">วันที่เริ่มต้น</label>
                    <div class="col-md-6">
                        <input type="datetime-local" name="start_date" class="form-control" value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                    </div>
                </div>

                <!-- วันครบกำหนด -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">วันครบกำหนด</label>
                    <div class="col-md-6">
                        <input type="datetime-local" name="due_date" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime('+7 days')); ?>" required>
                    </div>
                </div>

                <!-- ความสำคัญ -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">ความสำคัญ</label>
                    <div class="col-md-6">
                        <select name="priority" id="priority" class="form-control" required>
                            <option value="Critical">สูงมาก</option>
                            <option value="High">สูง</option>
                            <option value="Medium">กลาง</option>
                            <option value="Low">ต่ำ</option>
                        </select>
                    </div>
                </div>

                <!-- ความคืบหน้า -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">ความคืบหน้า <span id="progressValue">0%</span></label>
                    <div class="col-md-6">
                        <input type="range" name="progress" id="progressSlider" min="0" max="100" value="0" step="10">
                    </div>
                </div>

                <!-- หมายเหตุ -->
                <div class="form-group row">
                    <label class="col-md-2 col-form-label text-md-right">หมายเหตุ</label>
                    <div class="col-md-6">
                        <textarea name="notes" class="form-control" placeholder="หมายเหตุเพิ่มเติม" required></textarea>
                    </div>
                </div>

                <!-- ปุ่มเพิ่มโปรเจค -->
                <div class="form-group row">
                    <div class="col-md-6 offset-md-2 btn-container">
                        <button type="submit" id="btn" class="btn btn-success">
                            <i class="glyphicon glyphicon-plus"></i> เพิ่มโปรเจค
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#btn').hide();

        $('#institution_id').change(function() {
            var institution_id = $(this).val();
            if (institution_id != '') {
                $.ajax({
                    url: "fetch_personel.php",
                    method: "POST",
                    data: {
                        institution_id: institution_id
                    },
                    success: function(data) {
                        $('#personel_id').html(data);
                        $('#btn').hide();
                    }
                });
            } else {
                $('#personel_id').html('<option value="">--เลือกพนักงาน--</option>');
                $('#btn').hide();
            }
        });

        $('#personel_id').change(function() {
            if ($(this).val() != "NULL" && $(this).val() != "") {
                $('#btn').show();
            } else {
                $('#btn').hide();
            }
        });

        $("#progressSlider").on("input", function() {
            let value = $(this).val();
            $("#progressValue").text(value + "%");
        });
    });
</script>

<?php
mysqli_free_result($personel);
mysqli_free_result($LastID);
?>