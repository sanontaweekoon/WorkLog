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

<?php
$query_lastID = "SELECT * FROM institution ORDER BY institution_id DESC";
$lastID = mysqli_query($con, $query_lastID) or die("Error in query: $query_lastID" . mysqli_error($con));
$row_lastID = mysqli_fetch_assoc($lastID);
?>

<div class="container mt-4">
  <h4 class="mb-3 font-weight-bold">เพิ่มข้อมูลหน่วยงาน</h4>

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="institution_form_add_db.php" method="POST" enctype="multipart/form-data" class="form-horizontal">

        <!-- รหัสแผนก -->
        <div class="form-group row">
          <label class="col-md-2 col-form-label text-md-right">รหัสแผนก</label>
          <div class="col-md-6">
            <?php
            $newid = number_format(intval($row_lastID['institution_id'])) + 1;
            ?>
            <input type="text" name="institution_id" value="<?php echo $newid; ?>" class="form-control bg-light" readonly>
          </div>
        </div>

        <!-- ชื่อแผนก -->
        <div class="form-group row">
          <label class="col-md-2 col-form-label text-md-right">ชื่อแผนก</label>
          <div class="col-md-6">
            <input type="text" name="institution_name" class="form-control" placeholder="กรอกชื่อแผนก" required>
          </div>
        </div>

        <!-- ผู้จัดการ -->
        <div class="form-group row">
          <label class="col-md-2 col-form-label text-md-right">ผู้จัดการ</label>
          <div class="col-md-6">
            <input type="text" name="manager" class="form-control" placeholder="ชื่อผู้จัดการ" required>
          </div>
        </div>

        <!-- ข้อมูลการติดต่อ -->
        <div class="form-group row">
          <label class="col-md-2 col-form-label text-md-right">ข้อมูลการติดต่อ</label>
          <div class="col-md-6">
            <input type="text" name="institution_contact" class="form-control" placeholder="เบอร์โทร / อีเมล" required>
          </div>
        </div>

        <!-- ปุ่มเพิ่มข้อมูล -->
        <div class="form-group row">
          <div class="col-md-6 offset-md-2 btn-container text-center">
            <button type="submit" class="btn btn-success btn-block">
              <i class="glyphicon glyphicon-plus"></i> เพิ่มข้อมูล
            </button>
            <a href="institution.php" class="btn btn-default btn-block">
              <i class="glyphicon glyphicon-arrow-left"></i> ย้อนกลับ
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>