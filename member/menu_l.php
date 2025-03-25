<style>
  .pull-left.image img {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ddd;
    margin-left: 0px;
  }
</style>
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel">
      <div class="pull-left image">
        <?php
        include('../condb.php'); // เชื่อมต่อฐานข้อมูล

        // ตรวจสอบ session ว่ามีการล็อกอินหรือไม่
        if (isset($_SESSION['personel_id'])) {
          $personel_id = $_SESSION['personel_id'];

          // คิวรีข้อมูลบุคลากร
          $query_user = "SELECT profile_picture, personel_name FROM personel WHERE personel_id = '$personel_id'";
          $result_user = mysqli_query($con, $query_user);
          $user = mysqli_fetch_assoc($result_user);

          // กำหนดรูปโปรไฟล์ (ถ้าไม่มีให้ใช้ default)
          $profile_picture = !empty($user['profile_picture']) ? "../admin/uploads/personel/" . $user['profile_picture'] : "../admin/uploads/personel/default.png";
        } else {
          $profile_picture = "uploads/personel/default.png"; // กรณีไม่มี session
          $user['personel_name'] = "Guest"; // ตั้งชื่อเป็น Guest ถ้าไม่ได้ล็อกอิน
        }
        ?>
        <img src="<?= $profile_picture; ?>" class="profile-img" alt="User Image" loading="lazy">
      </div>
      <div class="pull-left info">
        <p><?php echo "ยินดีต้อนรับ" ?></p>
        <p>คุณ <?php echo $personel_name; ?></p>
        <!-- Status -->

      </div>
    </div>
    <ul class="sidebar-menu" data-widget="tree">

      <li class="header">กิจกกรรม(Event)</li>
      <li>
        <a href="calendar.php"><i class="fa fa-edit"></i>
          <span>ข้อมูลการบันทึกกิจกรรม</span>
        </a>
      </li>
      <li class="header">การทำงาน</li>
      <li>
        <a href="index2.php"><i class="fa fa-edit"></i>
          <span>ข้อมูลบันทึกการทำงาน</span>
        </a>
      </li>

      <li class="header">สำหรับพนักงาน</li>
      <li>
        <a href="add_record.php"><i class="fa fa-edit"></i>
          <span>บันทึกการทำงาน</span>
        </a>
      </li>

      <li>
        <a href="recording_list.php"><i class="fa fa-edit"></i>
          <span>ผลการบันทึกการทำงาน</span>
        </a>
      </li>

      <li class="header">ข้อมูลส่วนตัว</li>

      <li>
        <a href="profile.php"><i class="fa fa-edit"></i>
          <span>ข้อมูลบุคลากร</span>
        </a>
      </li>

      <li>
        <a href="change_password.php"><i class="fa fa-edit"></i>
          <span>เปลี่ยนรหัสผ่าน</span>
        </a>
      </li>

      <li>
        <a href="../logout.php" onclick="return confirm('คุณต้องการออกจากระบบหรือไม่ ?');"><i class='fas fa-sign-out-alt'></i>
          <span>ออกจากระบบ</span>
        </a>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>