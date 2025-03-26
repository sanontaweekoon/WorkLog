<?php
ob_start();  // เปิด Output Buffering ป้องกัน Headers Sent Error
session_start();

include('../condb.php'); // เชื่อมต่อฐานข้อมูล

if (!isset($_SESSION['personel_id']) || empty($_SESSION['personel_id'])) {
  echo "เกิดข้อผิดพลาด: ไม่พบข้อมูลบุคลากร กรุณาเข้าสู่ระบบใหม่";
  exit();
}

$personel_id = $_SESSION['personel_id'];  // ค่า personel_id จาก session

$query_user = "SELECT personel_name, institution_id, personel_level FROM personel WHERE personel_id = '$personel_id'";
$result_user = mysqli_query($con, $query_user);

if (!$result_user) {
  die("Error in query: " . mysqli_error($con));
}

$user = mysqli_fetch_assoc($result_user);
$personel_name = $user['personel_name'] ?? "Guest";
$institution_id = $user['institution_id'] ?? null;
$personel_level = $user['personel_level'] ?? 'member'; // ตรวจสอบระดับผู้ใช้

$query_institution = "SELECT institution_id, institution_name FROM institution WHERE institution_id = '$institution_id'"; // ใช้ institution_id ของผู้ใช้
$result_institution = mysqli_query($con, $query_institution);
$institution = mysqli_fetch_assoc($result_institution);

// ถ้าผู้ใช้งานเป็น admin ดึงข้อมูลแผนกและพนักงานมาแสดง
if ($personel_level === 'admin') {
  // ดึงข้อมูลแผนกทั้งหมดจากฐานข้อมูล
  $query_institution = "SELECT institution_id, institution_name FROM institution";
  $result_institution = mysqli_query($con, $query_institution);
}
?>

<style>
  .fc-toolbar-title {
    color: #ffffff !important;
    font-weight: bold;
  }

  .fc-theme-standard .fc-toolbar {
    background-color: #06C755;
    color: white;
    padding: 10px;
    border-radius: 8px;
  }

  .fc-button {
    background-color: white !important;
    color: #06C755 !important;
    border-radius: 6px !important;
    border: 1px solid #06C755 !important;
  }

  .fc-button:hover {
    background-color: #DFF8E6 !important;
  }

  .fc-theme-standard .fc-daygrid-day {
    background-color: #F7F7F7;
    color: #333333 !important;
  }

  .fc-day-today {
    background-color: #FFFFFF !important;
  }

  .fc-event {
    /* background-color: #06C755 !important;
    color: white !important; */
    border: none;
    border-radius: 5px;
    padding: 5px;
  }

  .fc-event:hover {
    filter: brightness(1.2);
    transform: scale(1.02);
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    cursor: pointer;
    transition: all 0.3s ease-in-out;
  }

  .fc-prev-button,
  .fc-next-button {
    background-color: #F7F7F7 !important;
    color: #06C755 !important;
    border-radius: 50%;
    padding: 8px;
  }

  .fc-daygrid-day-number {
    color: #222D31 !important;
    font-weight: bold;
    font-size: 14px;
  }

  .fc .fc-col-header-cell-cushion,
  .fc-list-day-side-text,
  .fc-list-day-text {
    color: #333333 !important;
    font-weight: bold;
    font-size: 14px;
  }

  .fc-theme-standard .fc-scrollgrid {
    border-color: #ddd !important;
  }

  .fc-list-event-title,
  .fc-list-event-time,
  .fc-list-event-graphic {
    background-color: #F7F7F7;
    color: #222D31;
    cursor: pointer;
  }

  .modal-body img {
    max-width: 100%;
    /* ป้องกันรูปใหญ่เกินไป */
    height: auto;
    /* คงอัตราส่วนเดิม */
    display: block;
    /* ให้ชิดซ้าย */
    margin: 10px auto;
    /* จัดให้อยู่ตรงกลาง */
    max-height: 400px;
    /* จำกัดความสูง */
    object-fit: contain;
    /* ป้องกันภาพถูกครอบผิดสัดส่วน */
  }

  .filter-section {
    display: flex;
    justify-content: space-between;
    /* แยกฝั่งปุ่มและช่องกรอกข้อมูล */
    align-items: center;
    gap: 10px;
    padding: 10px 0;
    flex-wrap: wrap;
    /* รองรับจอเล็ก */
  }

  .date-group {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .date-group input {
    height: 40px;
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
  }

  .button-group {
    display: flex;
    gap: 10px;
  }

  .button-group button {
    background-color: #222D31;
    color: white;
    border: none;
    font-weight: bold;
    padding: 8px 15px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .button-group button:hover {
    background-color: #04A045;
  }

  @media (max-width: 768px) {
    .filter-section {
      flex-direction: column;
      align-items: flex-start;
    }

    .date-group,
    .button-group {
      width: 100%;
      justify-content: space-between;
    }

    .date-group input,
    .button-group button {
      width: 100%;
    }
  }


  /* ปรับการจัดวางปุ่มใน Header ของ FullCalendar */
  .fc-header-toolbar {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    /* เพิ่มระยะห่างระหว่างปุ่ม */
  }

  /* ปรับปุ่ม Prev, Next, Today */
  .fc-toolbar-chunk {
    display: flex;
    align-items: center;
    gap: 8px;
    /* เพิ่มช่องว่าง */
  }

  /* ปรับปุ่ม Month, Week, List */
  .fc-button-group {
    display: flex;
    gap: 6px;
  }

  /* ปรับขนาดปุ่มให้ดูดีขึ้น */
  .fc-button {
    padding: 6px 12px;
    font-size: 14px;
  }

  /* ปรับ Layout สำหรับ Mobile */
  @media (max-width: 768px) {
    .fc-header-toolbar {
      flex-direction: column;
      align-items: center;
    }

    .fc-toolbar-chunk,
    .fc-button-group {
      justify-content: center;
      width: 100%;
    }

    .fc-button {
      width: 100%;
    }
  }

  @media (max-width: 768px) {
    .filter-section {
      flex-direction: column;
    }

    .date-group {
      flex-direction: column;
    }

    .form-group {
      width: 100%;
      margin-bottom: 10px;
    }

    .button-group {
      flex-direction: column;
      width: 100%;
    }

    .button-group button {
      width: 100%;
      margin-bottom: 10px;
    }
  }
</style>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<?php include('header.php'); ?>

<body class="hold-transition skin-green sidebar-mini">
  <div class="wrapper">
    <!-- Main Header -->
    <?php include('menutop.php'); ?>
    <?php include('menu_l.php'); ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>ข้อมูลบันทึกการทำงาน</h1>
        </h1>
      </section>

      <section class="content">
        <!-- ส่วนของการค้นหา-->
        <div class="filter-section d-flex flex-wrap align-items-center mb-3">
          <div class="date-group d-flex flex-wrap mb-3 mb-md-0">
            <div class="form-group mr-3">
              <label for="startDate">วันที่เริ่มต้น:</label>
              <input type="date" id="startDate" onchange="filterRecords()">
            </div>

            <div class="form-group">
              <label for="endDate">วันที่สิ้นสุด:</label>
              <input type="date" id="endDate" onchange="filterRecords()">
            </div>

            <div class="form-group mr-3 w-100">
              <!-- ตรวจสอบระดับผู้ใช้ -->
              <?php if ($personel_level === 'admin') : ?>
                <!-- ถ้าเป็น Admin ให้แสดงฟอร์มกรองแผนก -->
                <!-- ฟอร์มกรองแผนก -->
                <select id="institutionFilter" class="form-control">
                  <option value="<?= $institution['institution_id'] ?>" selected><?= $institution['institution_name'] ?></option>
                  <?php while ($row = mysqli_fetch_assoc($result_institution)): ?>
                    <?php if ($row['institution_id'] != $institution['institution_id']): ?>
                      <option value="<?= $row['institution_id'] ?>"><?= $row['institution_name'] ?></option>
                    <?php endif ?>
                  <?php endwhile ?>
                </select>

              <?php else : ?>
                <!-- ถ้าไม่ใช่ Admin ให้ส่งค่า institution_id และ personel_id ของผู้ใช้ -->
                <input type="text" id="institutionFilter" class="form-control" value="<?php echo $institution_id; ?>" readonly />
                <input type="text" id="personelFilter" class="form-control" value="<?php echo $personel_id ?>" readonly />
              <?php endif; ?>
            </div>

            <!-- เลือกพนักงาน (จะยังไม่แสดงจนกว่าผู้ใช้จะเลือกแผนก) -->
            <div class="form-group w-100" id="personelSelectDiv">
              <select id="personelFilter" class="form-control" onchange="filterRecords()">
                <option value="all">เลือกพนักงานทั้งหมด</option>
                <!-- พนักงานจะถูกโหลดหลังจากเลือกแผนก -->
              </select>
            </div>
          </div>

          <div class="button-group d-flex justify-content-between w-100">
            <button id="refreshCalendar"><span class="glyphicon">&#xe031;</span></button>

            <button onclick="exportToExcel()"><i class="fas fa-file-excel"></i> Export Excel</button>

            <script>
              function exportToExcel() {
                let startDate = document.getElementById('startDate').value;
                let endDate = document.getElementById('endDate').value;

                if (!startDate || !endDate) {
                  alert("กรุณาเลือกวันที่เริ่มต้นและสิ้นสุดก่อน Export Excel");
                  return;
                }
                window.location.href = `export_records.php?start_date=${startDate}&end_date=${endDate}`;
              }
            </script>
          </div>
        </div>
        </>

        <!-- ส่วนของใครลงบันทึกไปแล้วบ้าง -->
        <div class="schedule-summary">
          <h3>ปฏิทินการบันทึกงาน</h3>
          <div id="calendar"></div>

          <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title" id="modal-title">รายละเอียดการบันทึก</h4>
                </div>
                <div class="modal-body">
                  <p><strong>📅 วันที่:</strong> <span id="modal-date"></span></p>
                  <p><strong>👤 โดย:</strong> <span id="modal-personnel"></span></p>
                  <p><strong>📝 รายละเอียด:</strong></p>
                  <div id="modal-description"></div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
</body>

</html>

<script>
  function filterRecords() {
    let startDate = document.getElementById("startDate").value;
    let endDate = document.getElementById("endDate").value;
    let institutionId = document.getElementById("institutionFilter").value;
    let personelId = document.getElementById("personelFilter").value;

    let url = "api_get_today_record.php?";

    if (institutionId) url += `institution_id=${institutionId}`;
    if (personelId && personelId !== "all") url += `&personel_id=${personelId}`;
    if (startDate || endDate) url += `&start_date=${startDate}&end_date=${endDate}`;

    console.log("Fetching from URL:", url);
    calendar.removeAllEvents();
    calendar.setOption('events', url);
    calendar.refetchEvents(); // โหลดอีเวนต์ใหม่
  }

  function filterPersonel() {
    let institutionId = document.getElementById("institutionFilter").value;
    let personelSelect = document.getElementById("personelFilter");

    if (!institutionId) {
      personelSelect.style.display = "none";
      return;
    }

    // รีเซ็ตตัวเลือกพนักงาน
    personelSelect.innerHTML = '<option value="all">เลือกพนักงานทั้งหมด</option>';

    if (!institutionId) return;

    // ใช้ Fetch API เพื่อดึงข้อมูลพนักงานจากฐานข้อมูล
    fetch(`get_personel.php?institution_id=${institutionId}`)
      .then(response => response.json())
      .then(data => {
        data.forEach(personel => {
          personelSelect.innerHTML += `<option value="${personel.personel_id}">${personel.personel_name}</option>`;
        });
        personelSelect.style.display = "block"; // แสดง select พนักงาน

        const apiUrl = `api_get_today_record.php?institution_id=${institutionId}`;
        filterRecords(apiUrl);
      })
      .catch(error => {
        console.error("Error fetching personnel data:", error);
      });
  }

  let calendar;
  document.addEventListener('DOMContentLoaded', function() {
    filterPersonel(); //โหลดพนักงานทันทีเมื่อเปิดหน้าเว็บ
    document.getElementById("institutionFilter").addEventListener("change", filterPersonel);

    // ฝั่ง FullCalendar
    let calendarEl = document.getElementById('calendar');
    // กำหนดค่าให้ตัวแปร global
    calendar = new FullCalendar.Calendar(calendarEl, {
      themeSystem: 'standard',
      initialView: 'dayGridMonth',
      locale: 'th',
      height: 'auto',
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,listWeek'
      },
      dayMaxEventRows: 3,

      // การตั้งค่า events
      events: function(info, successCallback, failureCallback) {
        let url = `api_get_today_record.php?start_date=${info.startStr}&end_date=${info.endStr}`;

        let institutionId = document.getElementById("institutionFilter").value;
        let personelId = document.getElementById("personelFilter").value;

        if (institutionId) {
          url += `&institution_id=${institutionId}`;
        }
        if (personelId && personelId !== "all") { // ถ้าไม่เลือก "พนักงานทั้งหมด"
          url += `&personel_id=${personelId}`;
        }
        console.log("Fetching from URL: ", url);

        fetch(url)
          .then(response => response.json())
          .then(data => successCallback(data))
          .catch(error => failureCallback(error));
      },

      eventClick: function(info) {
        // ตั้งค่าข้อมูลลงใน modal
        document.getElementById('modal-title').textContent = info.event.title;
        document.getElementById('modal-date').textContent = info.event.start.toLocaleDateString();
        document.getElementById('modal-personnel').textContent = info.event.extendedProps.personel_name || "ไม่ระบุ";
        document.getElementById('modal-description').innerHTML = info.event.extendedProps.description || "ไม่มีรายละเอียด";

        // แสดง Modal ด้วย Bootstrap 3
        $('#eventModal').modal('show');
      },

      eventDidMount: function(info) {
        if (info.event.backgroundColor) {
          info.el.style.backgroundColor = info.event.backgroundColor;
        }
        if (info.event.textColor) {
          info.el.style.color = info.event.textColor;
        }
      }
    });
    calendar.render();

    function filterInstitutionAndPersonel() {
      let institutionId = document.getElementById("institutionFilter").value;
      let personelId = "all"; // เลือกพนักงานทั้งหมด

      // เริ่มต้น URL ตามแผนกที่เลือก
      let url = `api_get_today_record.php?institution_id=${institutionId}`;
      filterRecords(url);
    }

    // รีเฟรชปฏิทินเมื่อกดปุ่ม refresh
    document.getElementById("refreshCalendar").addEventListener("click", function() {
      document.getElementById("startDate").value = "";
      document.getElementById("endDate").value = "";

      // รีเซ็ตแผนก
      const institutionSelect = document.getElementById("institutionFilter");
      if (institutionSelect.tagName.toLowerCase() === "select"){
        institutionSelect.selectedIndex = 0; // กลับไปที่ option แรก
        filterPersonel(); // โหลดพนักงานใหม่ตามแผนก
      }

      const personelSelect = document.getElementById("personelFilter");
      personelSelect.innerHTML = '<option value="all">เลือกพนักงานทั้งหมด</option>';
      personelSelect.style.display = "none";

      calendar.refetchEvents();
    });

    filterInstitutionAndPersonel();

    document.getElementById("institutionFilter").addEventListener("change", filterPersonel);

    setTimeout(function() {
      let toggleButton = document.querySelector(".sidebar-toggle");
      if (toggleButton) {
        toggleButton.addEventListener("click", function() {
          setTimeout(function() {
            calendar.updateSize(); // ปรับขนาดปฏิทินให้เต็มพื้นที่
          }, 300);
        });
      } else {
        console.error("Sidebar Toggle Button (.sidebar-toggle) not found in DOM.");
      }
    }, 500);
  });
</script>

<?php include('footerjs.php'); ?>