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

$sql = "SELECT * FROM institution WHERE institution_id=$personel_id";
$result = mysqli_query($con, $sql) or die("Error in query: $sql" . mysqli_error($con));
$row = mysqli_fetch_array($result);

// echo $sql;

// echo '<pre>';
// print_r($row);
// echo '</pre>';
?>

<div class="container mt-4">
  <h4 class="mb-3 font-weight-bold">แก้ไขข้อมูลหน่วยงาน</h4>

  <div class="card shadow-sm">
    <div class="card-body">
      <form action="institution_form_edit_db.php" method="POST" enctype="multipart/form-data" class="form-horizontal">

        <!-- ชื่อแผนก -->
        <div class="form-group row">
          <label class="col-md-2 col-form-label text-md-right">ชื่อแผนก</label>
          <div class="col-md-6">
            <input type="text" name="institution_name" class="form-control"
              value="<?php echo $row['institution_name']; ?>">
          </div>
        </div>

        <!-- ผู้จัดการ -->
        <div class="form-group row">
          <label class="col-md-2 col-form-label text-md-right">ผู้จัดการ</label>
          <div class="col-md-6">
            <input type="text" name="manager" class="form-control"
              value="<?php echo $row['manager']; ?>">
          </div>
        </div>

        <!-- ข้อมูลการติดต่อ -->
        <div class="form-group row">
          <label class="col-md-2 col-form-label text-md-right">ข้อมูลการติดต่อ</label>
          <div class="col-md-6">
            <input type="text" name="institution_contact" class="form-control"
              value="<?php echo $row['institution_contact']; ?>">
          </div>
        </div>

        <!-- ปุ่มบันทึก -->
        <div class="form-group row">
          <div class="col-md-6 offset-md-2 btn-container text-center">
            <input type="hidden" name="institution_id" value="<?php echo $personel_id; ?>" />
            <button type="submit" class="btn btn-success btn-block">
            <i class="glyphicon glyphicon-floppy-disk"></i> บันทึก
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