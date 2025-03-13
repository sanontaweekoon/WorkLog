<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap">

<style>
    /*default */
    .status-box {
        display: inline-block;
        padding: 5px 10px;
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 14px;
        color: #333;
    }

    /*notStart ยังไม่เริ่ม */
    .status-box-notstart {
        background-color: #808080;
        border: 1px solid #ccc;
        color: #333;
    }

    /*inProgress กำลังดำเนินการ */
    .status-box-inProgress {
        background: #e3f2fd;
        border: 1px solid #90caf9;
        color: #0d47a1;
    }

    /*pendingReview รอการตรวจสอบ */
    .status-box-pendingReview {
        background-color: #fff3e0;
        border: 1px solid #ffa726;
        color: #ef6c00;
    }

    /*pendingReview รอข้อมูลเพิ่มเติม */
    .status-box-waiting {
        background-color: #fffde7;
        border: 1px solid #fff59d;
        color: #f9a825;
    }

    /*pendingReview ถูกระงับ */
    .status-box-onHold {
        background-color: #f5f5f5;
        border: 1px solid #9e9e9e;
        color: #616161;
    }

    /*pendingReview เสร็จสิ้น */
    .status-box-completed {
        background-color: #e8f5e9;
        border: 1px solid #66bb6a;
        color: #2e7d32;
    }

    /*pendingReview ยกเลิก*/
    .status-box-cancelled {
        background-color: #ffebee;
        border: 1px solid #ef5350;
        color: #c62828;
    }

    /*pendingReview ไม่ทราบค่า */
    .status-box-notdefine {
        background-color: #778899;
        border: 1px solid #ccc;
        color: #333;
    }

    @media (max-width: 768px) {
        table.dataTable thead {
            display: none;
        }

        table.dataTable tbody tr {
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            padding: 10px;
            background: #fff;
        }

        table.dataTable tbody td {
            display: flex;
            justify-content: space-between;
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            position: relative;
            word-wrap: break-word;
            white-space: normal;
        }

        table.dataTable tbody td:last-child {
            border-bottom: none;
        }

        table.dataTable tbody td::before {
            content: attr(data-label);
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
        }
    }

    #myBtn {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 99;
        font-size: 16px;
        width: 45px;
        height: 45px;
        border: none;
        outline: none;
        background-color: rgba(200, 200, 200, 0.2);
        /* สีเทาอ่อนมาก */
        color: rgba(50, 50, 50, 0.6);
        /* สีเทาเข้มขึ้นเล็กน้อย */
        cursor: pointer;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease-in-out;
    }

    #myBtn i {
        font-size: 14px;
        /* ไอคอนเล็กลง */
    }

    #myBtn:hover {
        background-color: rgba(180, 180, 180, 0.6);
        /* เปลี่ยนเป็นเทาเข้มขึ้นเล็กน้อยเมื่อโฮเวอร์ */
        color: rgba(30, 30, 30, 0.7);
    }
</style>

<?php
$query_project = "SELECT * 
FROM project_tasks as pr,
personel as pe
WHERE pr.personel_id = pe.personel_id
ORDER BY pr.personel_id DESC
" or die("Error:" . mysqli_error($con));

$result = mysqli_query($con, $query_project);
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<!-- jQuery และ DataTables -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>


<div class="table-responsive">
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr class='warning'>
                <th>รหัส</th>
                <th>ชื่อโปรเจค</th>
                <th>รายละเอียด</th>
                <th>หมวดหมู่</th>
                <th>ผู้รับมอบหมาย</th>
                <th>สถานะ</th>
                <th>วันที่เริ่มต้น</th>
                <th>วันครบกำหนด</th>
                <th>ความสำคัญ</th>
                <th>ความคืบหน้า</th>
                <th>หมายเหตุ</th>
                <th>แก้ไข</th>
                <th>ลบ</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($row = mysqli_fetch_array($result)) {
                $priorityText = "";
                switch ($row["priority"]) {
                    case "Critical":
                        $priorityText = "สูงมาก";
                        break;
                    case "High":
                        $priorityText = "สูง";
                        break;
                    case "Medium":
                        $priorityText = "กลาง";
                        break;
                    case "Low":
                        $priorityText = "ต่ำ";
                        break;
                    default:
                        $priorityText = "ไม่ทราบค่า";
                        break;
                }

                $statusText = "";
                switch ($row["status"]) {
                    case "notStart":
                        $statusText = "ยังไม่เริ่ม";
                        $statusClass = "status-box-notstart";
                        break;
                    case "inProgress":
                        $statusText = "กำลังดำเนินการ";
                        $statusClass = "status-box-inProgress";
                        break;
                    case "pendingReview":
                        $statusText = "รอการตรวจสอบ";
                        $statusClass = "status-box-pendingReview";
                        break;
                    case "waiting":
                        $statusText = "รอข้อมูลเพิ่มเติม";
                        $statusClass = "status-box-waiting";
                        break;
                    case "onHold":
                        $statusText = "ถูกระงับ";
                        $statusClass = "status-box-onHold";
                        break;
                    case "completed":
                        $statusText = "เสร็จสิ้น";
                        $statusClass = "status-box-completed";
                        break;
                    case "cancelled":
                        $statusText = "ยกเลิก";
                        $statusClass = "status-box-cancelled";
                        break;
                    default:
                        $statusText = "ไม่ทราบค่า";
                        $statusClass = "status-box-notdefine";
                        break;
                }
            ?>

                <tr>
                    <td data-label="รหัส"><?= $row["job_id"]; ?></td>
                    <td data-label="ชื่อโปรเจค"><?= $row["job_name"]; ?></td>
                    <td data-label="รายละเอียด"><?= $row["description"]; ?></td>
                    <td data-label="หมวดหมู่"><?= $row["category"]; ?></td>
                    <td data-label="ผู้รับมอบหมาย"><?= $row["personel_name"]; ?></td>
                    <td data-label="สถานะ">
                        <span class='status-box <?= $statusClass; ?>'><?= $statusText; ?></span>
                    </td>
                    <td data-label="วันที่เริ่มต้น">
                        <span style='color:green'><?= date('d/m/Y H:i:s', strtotime($row["start_date"])); ?></span>
                    </td>
                    <td data-label="วันครบกำหนด">
                        <span style='color:red'><?= date('d/m/Y H:i:s', strtotime($row["due_date"])); ?></span>
                    </td>
                    <td data-label="ความสำคัญ"><?= $priorityText; ?></td>
                    <td data-label="ความคืบหน้า"><?= $row["progress"]; ?> %</td>
                    <td data-label="หมายเหตุ"><?= $row["notes"]; ?></td>
                    <td data-label="แก้ไข">
                        <a href='project.php?act=edit&ID=<?= $row["job_id"]; ?>' class='btn btn-warning btn-xs'>
                            <i class='fa fa-wrench'></i> แก้ไข
                        </a>
                    </td>
                    <td data-label="ลบ">
                        <a href='project_del_db.php?ID=<?= $row["job_id"]; ?>'
                            onclick="return confirm('ยืนยันการลบ?')"
                            class='btn btn-danger btn-xs'>
                            <i class='fa fa-trash'></i> ลบ
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#example1').DataTable({
            responsive: true, // ✅ เปิดโหมด Responsive
            paging: true,
            lengthChange: true,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            columnDefs: [{
                    className: 'control',
                    orderable: false,
                    targets: 0
                },
                {
                    responsivePriority: 1,
                    targets: 1
                },
                {
                    responsivePriority: 2,
                    targets: -2
                },
                {
                    responsivePriority: 3,
                    targets: -1
                },
                {
                    targets: "_all",
                    visible: true
                }
            ]
        });
    });
</script>

<?php mysqli_close($con); ?>