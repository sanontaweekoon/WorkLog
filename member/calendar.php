<meta charset='utf-8' />

<style>
  .fc-event {
    cursor: pointer !important;
    position: relative;
  }

  .fc-event .fc-resizer {
    cursor: ew-resize !important;
    width: 10px;
    height: 10px;
    background: red;
    /* ทำให้เห็นง่ายขึ้น */
    position: absolute;
    bottom: 0;
    right: 0;
    border-radius: 50%;
  }

  .custom-confirm-btn {
    background-color: #28a745 !important;
    color: white !important;
    font-size: 16px !important;
    padding: 10px 20px !important;
    border-radius: 8px !important;
    border: none !important;
  }

  .custom-delete-btn {
    background-color: #c82333 !important;
    color: white !important;
    font-size: 16px !important;
    padding: 10px 20px !important;
    border-radius: 8px !important;
    border: none !important;
  }

  .custom-confirm-btn:hover {
    background-color: #218838 !important;
  }

  .custom-cancel-btn {
    background-color: #76818D !important;
    color: white !important;
    font-size: 16px !important;
    padding: 10px 20px !important;
    border-radius: 8px !important;
    border: none !important;
  }

  .custom-cancel-btn:hover {
    background-color: #2C3E50 !important;
  }


  /* ปรับให้ SweetAlert ใหญ่ขึ้น */
  .swal-wide {
    font-size: 18px !important;
    width: 500px !important;
  }

  /* ปรับขนาดและสไตล์ของ Title */
  .swal-title {
    font-size: 24px !important;
    font-weight: bold;
  }

  /* ปรับขนาดและสไตล์ของ Content */
  .swal-content {
    font-size: 18px !important;
    text-align: center;
  }

  /* ปรับขนาดปุ่มและเปลี่ยนเป็นสีเขียว */
  .swal-confirm-button {
    font-size: 18px !important;
    padding: 10px 20px !important;
    background-color: #28a745 !important;
    color: white !important;
    border-radius: 8px !important;
  }

  /* ปรับปุ่มลบให้ใหญ่ขึ้น และใช้สีแดง */
  .swal-delete-btn {
    font-size: 18px !important;
    padding: 10px 20px !important;
    background-color: #d33 !important;
    /* สีแดง */
    border-radius: 6px !important;
  }

  .swal-delete-btn:hover {
    background-color: #c82333 !important;
  }

  /* ปรับปุ่มยกเลิกให้เหมือนปุ่มก่อนหน้า */
  .swal-cancel-btn {
    font-size: 18px !important;
    padding: 10px 20px !important;
    background-color: #6c757d !important;
    /* สีเทา */
    border-radius: 6px !important;
  }

  .swal-cancel-btn:hover {
    background-color: #5a6268 !important;
  }
</style>

<?php
include('header.php');

session_start();
if (!isset($_SESSION['personel_id'])) {
  $_SESSION['personel_id'] = null; // หรือกำหนดค่า ID ตามที่ต้องการ
}

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.0/main.min.css">
<!-- เรียกใช้งาน SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js" defer></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- Bootstrap -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<body class="hold-transition skin-green sidebar-mini">
  <div class="wrapper">
    <!-- Main Header -->
    <?php include('menutop.php'); ?>
    <?php include('menu_l.php'); ?>

    <div class="content-wrapper">
      <section class="content-header">
        <h1>ข้อมูลการบันทึกกิจกรรม</h1>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-body">
                <div class="calendar"></div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <?php include('footerjs.php'); ?>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.querySelector('.calendar');

      var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'th',
        timeZone: 'Asia/Bangkok',
        initialView: 'dayGridMonth',
        selectable: true,
        editable: true,
        eventResizableFromStart: true,
        eventDurationEditable: true,
        longPressDelay: 500,
        eventOverlap: true,
        dayMaxEventRows: 4,

        eventTimeFormat: {
          hour: '2-digit',
          minute: '2-digit',
          meridiem: 'short',
          omitZeroMinute: false
        },

        eventSources: [{
          url: 'fetch_events.php', // โหลดข้อมูลจาก all_day_events และ timed_events
          type: 'GET',
          failure: function() {
            console.error('โหลดข้อมูลกิจกรรมล้มเหลว');
            alert('โหลดข้อมูลกิจกรรมล้มเหลว');
          }
        }],

        select: function(info) {
          createEvent(info);
        },

        eventClick: function(info) {
          showEventDetails(info.event);
        },

        eventDrop: function(info) {
          let userId = "<?php echo $_SESSION['personel_id']; ?>"; // ID ผู้ใช้ปัจจุบัน
          let eventOwner = info.event.extendedProps.personel_id; // ID เจ้าของกิจกรรม

          if (info.event.extendedProps.source === 'project_tasks' || userId != eventOwner) {
            Swal.fire({
              title: '<h2 style="font-size: 24px;">คำเตือน</h2>',
              html: '<p style="font-size: 18px;">คุณไม่มีสิทธิ์แก้ไขกิจกรรมของผู้อื่น</p>',
              icon: 'warning',
              confirmButtonText: 'ตกลง',
              confirmButtonColor: '#28a745',
              width: '500px',
              padding: '20px',
              customClass: {
                popup: 'swal-wide',
                title: 'swal-title',
                content: 'swal-content',
                confirmButton: 'swal-confirm-button'
              }
            }).then(() => {
              info.revert(); // ย้อนกลับไปที่เดิม
            });
            return;
          }

          // ถ้าไม่ใช่ project_tasks ให้ทำการอัปเดตข้อมูล
          updateEvent(info.event);
        },

        eventResize: function(info) {
          let userId = "<?php echo $_SESSION['personel_id']; ?>"; // ดึง personel_id ของผู้ใช้ที่ล็อกอิน
          let eventOwner = info.event.extendedProps.personel_id; // personel_id ของเจ้าของ event

          if (userId != eventOwner) {
            Swal.fire({
              title: '<h2 style="font-size: 24px;">คำเตือน</h2>',
              html: '<p style="font-size: 18px;">คุณไม่มีสิทธิ์แก้ไขกิจกรรมของผู้อื่น</p>',
              icon: 'warning',
              customClass: {
                confirmButton: 'custom-confirm-btn'
              },
              confirmButtonText: 'ตกลง',
              confirmButtonColor: '#28a745',
              width: '500px',
              padding: '20px'
            }).then(() => {
              info.revert();
            });
            return;
          }
          eventResize(info.event);
        },

        eventDidMount: function(info) {
          let userId = "<?php echo $_SESSION['personel_id']; ?>"; // ID ผู้ใช้ปัจจุบัน
          let eventOwner = info.event.extendedProps.personel_id; // ID เจ้าของกิจกรรม
          let isOwner = info.event.extendedProps.isOwner; // เช็คว่าเป็นเจ้าของกิจกรรมหรือไม่
          let ownerName = info.event.extendedProps.owner_name || "ไม่ระบุ";

          // เพิ่ม tooltip แสดงว่าใครเป็นเจ้าของกิจกรรม
          $(info.el).attr("title", `เจ้าของกิจกรรม: ${ownerName}`);

          // ถ้ากิจกรรมเป็นของตัวเอง ให้เปลี่ยนสไตล์ให้ดูแตกต่าง
          if (userId == eventOwner) {
            info.el.style.border = "2px solid #ffcc00"; // กำหนดเส้นขอบสีเหลืองให้เห็นชัด
          }
        }
      });

      calendar.render();

      // สร้าง Evnet ใหม่
      function createEvent(info) {
        Swal.fire({
          title: `<span style="font-size: 28px; font-weight: bold;">เลือกประเภทกิจกรรม</span>`,
          html: `
          <div style="font-size: 16px; text-align: center;">
            <p><b style="color: #0FC863;">All-day Event</b> : กิจกรรมที่เกิดขึ้นตลอดวัน เช่น ลากิจ ลาพักร้อน วันหยุด</p>
            <p><b style="color: #76818D;">Timed Event</b> : กิจกรรมที่ระบุช่วงเวลาแน่ชัด เช่น ประชุม นัดหมาย</p>
          </div>
        `,
          icon: 'question',
          width: '600px',
          showCancelButton: true,
          confirmButtonText: 'All-day Event',
          cancelButtonText: 'Timed Event',
          customClass: {
            confirmButton: 'custom-confirm-btn',
            cancelButton: 'custom-cancel-btn'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            createAllDayEvent(info);
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            createTimedEvent(info);
          }
        });
      }

      // ฟังก์ชันสำหรับ All-day event
      function createAllDayEvent(info) {
        let title = prompt("กรุณากรอกชื่อกิจกรรมตลอดวัน");
        if (!title) return;

        let userColor = sessionStorage.getItem("user_color") || "#007bff";
        let userTextColor = sessionStorage.getItem("user_text_color") || "#ffffff";

        let start_date = info.startStr;
        let end_date = new Date(info.end);

        // ลบ 1 วัน "เฉพาะกรณีที่ start_date == end_date" เท่านั้น
        if (start_date === end_date.toISOString().split("T")[0]) {
          end_date.setDate(end_date.getDate() - 1);
        }
        end_date = end_date.toISOString().split("T")[0];

        $.ajax({
          url: 'insert_event.php',
          type: 'POST',
          dataType: 'json',
          data: {
            title: title,
            event_type: "all_day",
            start_date: start_date,
            end_date: end_date,
            color: userColor,
            textColor: userTextColor
          },
          success: function(response) {
            if (response.status === 'success') {
              calendar.refetchEvents();
              Swal.fire({
                title: '<h2 style="font-size: 24px;">สำเร็จ!</h2>',
                text: response.message,
                icon: 'success',
                showConfirmButton: true,
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#28a745',
                width: '500px',
                padding: '20px',
                customClass: {
                  popup: 'swal-wide',
                  title: 'swal-title',
                  content: 'swal-content',
                  confirmButton: 'swal-confirm-button'
                }
              });
            } else {
              Swal.fire('ผิดพลาด!', response.message, 'error');
            }
          },
          error: function(xhr) {
            Swal.fire('ผิดพลาด!', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error');
          }
        });
      }

      // ฟังก์ชันสำหรับ Timed event
      function createTimedEvent(info) {
        let title = prompt("กรุณากรอกชื่อกิจกรรมที่มีเวลา:");
        if (!title) return;

        let startTime = prompt("เวลาเริ่มต้น (เช่น 08:00):", "08:00");
        let endTime = prompt("เวลาสิ้นสุด (เช่น 17:00):", "17:00");

        if (!startTime || !endTime) {
          Swal.fire('ผิดพลาด!', 'กรุณากรอกเวลาให้ถูกต้อง', 'error');
          return;
        }

        let start = `${info.startStr} ${startTime}:00`;
        let end = `${info.startStr} ${endTime}:00`;

        let userColor = sessionStorage.getItem("user_color") || "#007bff";
        let userTextColor = sessionStorage.getItem("user_text_color") || "#ffffff";

        $.ajax({
          url: 'insert_event.php',
          type: 'POST',
          data: {
            title: title,
            event_type: "timed",
            start: start,
            end: end,
            color: userColor,
            textColor: userTextColor
          },
          dataType: 'json',
          success: function(response) {
            if (response.status === 'success') {
              calendar.refetchEvents();
              Swal.fire({
                title: '<h2 style="font-size: 24px;">สำเร็จ!</h2>',
                text: response.message,
                icon: 'success',
                showConfirmButton: true,
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#28a745',
                width: '500px',
                padding: '20px',
                customClass: {
                  popup: 'swal-wide',
                  title: 'swal-title',
                  content: 'swal-content',
                  confirmButton: 'swal-confirm-button'
                }
              });
            } else {
              Swal.fire('ผิดพลาด!', response.message, 'error');
            }
          },
          error: function(xhr) {
            Swal.fire('ผิดพลาด!', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error')
          }
        });
      }

      // แสดงรายละเอียด Event
      function showEventDetails(event) {
        let userId = "<?php echo $_SESSION['personel_id']; ?>"; // ดึง personel_id ของผู้ใช้ที่ล็อคอิน
        let eventOwner = event.extendedProps.personel_id; // personel_id ของเจ้าของ event
        
        let ownerName = event.extendedProps.source === "project_tasks"
         ? event.extendedProps.owner_name || "ไม่ระบุ"
         : event.extendedProps.personel_name || "ไม่ระบุ";
        
        let isMyEvent = userId == eventOwner; // ตรวจสอบว่าเป็นกิจกรรมของตัวเองมั้ย

        let startDate = new Date(event.start);
        let endDate = event.end ? new Date(event.end) : new Date(event.start);

        //ถ้าเป็น Timed Event ต้องลบ 7 ชั่วโมงออก (เนื่องจากฐานข้อมูลอาจเก็บเป็น UTC)
        if (event.allDay) {
          endDate.setDate(endDate.getDate() - 1);
        } else {
          startDate.setHours(startDate.getHours() - 7);
          endDate.setHours(endDate.getHours() - 7);
        }

        let isAllDay = event.allDay || startDate.getHours() === 0 && startDate.getMinutes() === 0; // เช็คว่าเป็น All Day Event

        // แปลงเป็นวันที่-เวลาไทย
        function formatThaiDate(date, isAllDayEvent = false) {
          let options = {
            year: "numeric",
            month: "long",
            day: "numeric",
            hour: isAllDayEvent ? undefined : "2-digit",
            minute: isAllDayEvent ? undefined : "2-digit",
            hour12: false,
            timeZone: "Asia/Bangkok"
          };

          return date.toLocaleDateString("th-TH", options);
        }

        let startText = formatThaiDate(startDate, isAllDay);
        let endText = formatThaiDate(endDate, isAllDay);

        // สร้างปุ่มถ้ากิจกกรมนั้นเป็นของเรา ให้แสดง "แก้ไข" และ "ลบ"
        let buttons = isMyEvent ? {
          confirmButtonText: "ปิด",
          showDenyButton: true,
          denyButtonText: "ลบ"
        } : {
          confirmButtonText: "ปิด"
        };

        Swal.fire({
          title: '<span style="font-size: 28px; font-weight: bold;">รายละเอียดกิจกรรม</span>',
          html: `
            <div style="font-size: 20px; text-align: center;">
            <p><b>โครงการ:</b> ${event.title} </p>
            <p><b>เริ่มต้น:</b> ${startText} </p>
            <p><b>สิ้นสุด:</b> ${endText}</p>
            <p><b>เจ้าของกิจกรรม:</b> ${ownerName} </p>
          </div> 
          `,
          icon: 'info',
          width: '500px',
          showCancelButton: buttons.showCancelButton,
          confirmButtonText: buttons.confirmButtonText,
          showDenyButton: buttons.showDenyButton,
          denyButtonText: buttons.denyButtonText,
          customClass: {
            confirmButton: 'custom-confirm-btn',
            cancelButton: 'custom-cancel-btn',
            denyButton: 'custom-delete-btn'
          }
        }).then((result) => {
          if (result.isDenied) {
            deleteEvent(event.id, event.allDay);
          }
        });
      }

      function deleteEvent(eventId, isAllDay) {
        Swal.fire({
          title: "<h3 style='font-size: 24px;'>ลบกิจกรรมนี้หรือไม่?</h3>",
          html: '<p style="font-size: 18px;">กิจกรรมนี้จะไม่สามารถดูได้อีกถ้าหากลบไปแล้ว !</p>',
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "ใช่, ลบเลย!",
          cancelButtonText: "ยกเลิก",
          width: '450px',
          customClass: {
            confirmButton: 'swal-delete-btn',
            cancelButton: 'swal-cancel-btn'
          }
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: "delete_event.php",
              type: "POST",
              dataType: "json",
              data: {
                id: eventId,
                allDay: isAllDay ? 1 : 0
              },
              success: function(response) {
                if (response.status === "success") {
                  Swal.fire({
                    title: '<h2 style="font-size: 24px;">สำเร็จ!</h2>',
                    text: response.message,
                    icon: 'success',
                    showConfirmButton: true,
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#28a745',
                    width: '500px',
                    padding: '20px',
                    customClass: {
                      popup: 'swal-wide',
                      title: 'swal-title',
                      content: 'swal-content',
                      confirmButton: 'swal-confirm-button'
                    }
                  }).then(() => {
                    calendar.refetchEvents(); // รีเฟรชเฉพาะเมื่อการลบสำเร็จ
                  });
                } else {
                  Swal.fire("ผิดพลาด!", response.message, "error");
                }
              },
              error: function() {
                Swal.fire("ผิดพลาด!", "ไม่สามารถลบกิจกรรมได้", "error");
              }
            });
          }
        });
      }

      // อัปเดต Evnet ที่มีอยู่
      function updateEvent(event) {
        if (!event.start) {
          console.error("ค่า event.start เป็น null หรือ undefined");
          return;
        }

        let startDate = event.start instanceof Date ? event.start : new Date(event.start);
        let endDate = event.end instanceof Date ? event.end : new Date(event.start);

        if (event.allDay) {
          // ถ้าเป็น All-day Event อัปเดตเฉพาะ `start_date` และ `end_date`
          var start = startDate.toISOString().split("T")[0]; // แค่วันที่
          var end = endDate.toISOString().split("T")[0];
        } else {
          // ถ้าเป็น Timed Event ต้องแปลงให้ตรงกับฐานข้อมูล
          var start = formatToDatabaseTime(startDate);
          var end = formatToDatabaseTime(endDate);
        }

        let data = {
          id: event.id,
          start: event.startStr,
          end: event.endStr,
          allDay: event.allDay ? 1 : 0
        };

        $.ajax({
          url: 'update_events.php',
          type: 'POST',
          dataType: 'json',
          data: data,
          success: function(response) {
            if (response.status === "success") {
              Swal.fire({
                title: '<h2 style="font-size: 24px;">สำเร็จ!</h2>',
                text: response.message,
                icon: 'success',
                showConfirmButton: true,
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#28a745',
                width: '500px',
                padding: '20px',
                customClass: {
                  popup: 'swal-wide',
                  title: 'swal-title',
                  content: 'swal-content',
                  confirmButton: 'swal-confirm-button'
                }
              });
              calendar.refetchEvents();
            } else {
              Swal.fire("ผิดพลาด!", response.message, "error");
              calendar.refetchEvents();
            }
          },
          error: function(xhr) {
            Swal.fire("ผิดพลาด!", "เกิดข้อผิดพลาดในการบันทึกข้อมูล", "error");
            calendar.refetchEvents();
          }
        });


        function formatToDatabaseTime(date) {
          let offset = 7 * 60 * 60 * 1000; // GMT+7
          let localDate = new Date(date.getTime() + offset);
          return localDate.toISOString().slice(0, 19).replace("T", " ");
        }
      }

      function eventResize(event) {
        if (!event.id) {
          console.error("Error: Event ไม่มี ID");
          return;
        }

        let startDate = new Date(event.start);
        let endDate = event.end ? new Date(event.end) : new Date(event.start); // กรณีไม่มี end ใช้ start แทน

        let start = startDate.toISOString().slice(0, 19).replace("T", " ");
        let end = endDate.toISOString().slice(0, 19).replace("T", " ");

        if (event.allDay) {
          // ถ้าเป็น All-Day Event อัปเดตเป็นวันที่เท่านั้น
          start = startDate.toISOString().split("T")[0];
          end = endDate.toISOString().split("T")[0];
        } else {
          // ถ้าเป็น Timed Event ต้องลบ 7 ชั่วโมงก่อนอัปเดต
          endDate.setHours(endDate.getHours() - 7);
          end = endDate.toISOString().slice(0, 19).replace("T", " ");
        }

        $.ajax({
          url: 'update_events.php',
          type: 'POST',
          dataType: 'json',
          data: {
            id: event.id,
            start: start,
            end: end,
            allDay: event.allDay ? 1 : 0
          },
          success: function(response) {
            if (response.status === "success") {
              Swal.fire({
                title: '<h2 style="font-size: 24px;">สำเร็จ!</h2>',
                text: response.message,
                icon: 'success',
                showConfirmButton: true,
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#28a745',
                width: '500px',
                padding: '20px',
                customClass: {
                  popup: 'swal-wide',
                  title: 'swal-title',
                  content: 'swal-content',
                  confirmButton: 'swal-confirm-button'
                }
              });
              calendar.refetchEvents();
            } else {
              Swal.fire("ผิดพลาด!", response.message, "error");
              calendar.refetchEvents();
            }
          },
          error: function(xhr) {
            Swal.fire("ผิดพลาด!", "เกิดข้อผิดพลาดในการบันทึกข้อมูล", "error");
            calendar.refetchEvents();
          }
        });
      }

      // query ข้อมูลจาก project_tasks
      $.ajax({
        url: 'fetch_project_tasks.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
          let formattedEvents = response.map(event => {
            let startDate = new Date(event.start);
            let endDate = event.end ? new Date(event.end) : new Date(event.start);

            let isAllDay = event.allDay || (startDate.getHours() === 0 && startDate.getMinutes() === 0);

            // *กรณีเป็น All-Day Event → ไม่ต้องใส่เวลา**
            let displayTitle = isAllDay ?
              event.title :
              `${event.title}`;

            return {
              id: event.id,
              title: displayTitle,
              start: event.start,
              end: event.end,
              allDay: isAllDay,
              color: event.color,
              textColor: event.textColor,
              source: 'project_tasks',
              personel_id: event.personel_id,
              owner_name: event.owner_name || "ไม่ระบุ" // เพิ่ม owner_name
            };
          });

          calendar.addEventSource(formattedEvents);
        },
        error: function() {
          alert('เกิดข้อผิดพลาดในการโหลดข้อมูล');
        }
      });

      //ฟังก์ชันแปลงเวลาให้อยู่ในรูปแบบ HH:mm น.
      function formatTime(date) {
        let hours = date.getHours().toString().padStart(2, '0');
        let minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes} น.`; // แสดงเวลาแบบไทย
      }

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
</body>

</html>