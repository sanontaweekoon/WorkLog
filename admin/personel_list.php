<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap">
<?php
$query_personel = "SELECT * 
                   FROM personel as p, institution as i
                   WHERE p.institution_id = i.institution_id
                   ORDER BY p.institution_id DESC";

$result = mysqli_query($con, $query_personel);
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<!-- jQuery และ DataTables -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<style>
    .profile-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .profile-img:hover {
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        table.dataTable thead {
            display: none;
            /* ซ่อนหัวตาราง */
        }

        table.dataTable tbody tr {
            display: table-row;
            /* ให้ DataTables คิดว่าแต่ละ <tr> คือ 1 แถว */
            border: 1px solid #ddd;
            margin-bottom: 10px;
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

    .close {
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 30px;
        color: white;
        cursor: pointer;
    }

    .modal-content {
        max-width: 90%;
        max-height: 90%;
        border-radius: 10px;
    }

    /* MODAL STYLING */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        justify-content: center;
        align-items: center;
    }
</style>

<div id="imageModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<div class="table-responsive">
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr class='danger'>
                <th width='5%'>รหัส</th>
                <th width='5%'>รูป</th>
                <th width='25%'>ชื่อบุคลากร</th>
                <th width='15%'>แผนก</th>
                <th width='15%'>ผู้จัดการ</th>
                <th width='25%'>ข้อมูลการติดต่อ</th>
                <th width='5%'>แก้ไข</th>
                <th width='5%'>ลบ</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_array($result)) {
                $profile_picture = !empty($row["profile_picture"]) ? "uploads/personel/" . $row["profile_picture"] : "uploads/personel/default.png";
            ?>
                <tr>
                    <td data-label="รหัส"><?= $row["personel_id"]; ?></td>
                    <td data-label="รูป">
                        <img src="<?= $profile_picture; ?>" class="profile-img" alt="Profile" onclick="openModal(this)">
                    </td>
                    <td data-label="ชื่อบุคลากร"><?= $row["personel_name"]; ?></td>
                    <td data-label="แผนก"><?= $row["institution_name"]; ?></td>
                    <td data-label="ผู้จัดการ"><?= $row["manager"]; ?></td>
                    <td data-label="ข้อมูลการติดต่อ"><?= $row["personel_call"]; ?></td>
                    <td data-label="แก้ไข">
                        <a href='personel.php?act=edit&ID=<?= $row["personel_id"]; ?>' class='btn btn-warning btn-xs'>
                            <i class='fa fa-wrench'></i> แก้ไข
                        </a>
                    </td>
                    <td data-label="ลบ">
                        <a href='personel_del_db.php?ID=<?= $row["personel_id"]; ?>'
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
    function openModal(imgElement) {
        let modal = document.getElementById("imageModal");
        let modalImg = document.getElementById("modalImage");

        modal.style.display = "flex";
        modalImg.src = imgElement.src;
    }

    document.querySelector(".close").addEventListener("click", function() {
        document.getElementById("imageModal").style.display = "none";
    });

    // ปิด Modal เมื่อคลิกพื้นที่นอกภาพ
    window.onclick = function(event) {
        let modal = document.getElementById("imageModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };

    $(document).ready(function() {
        $('#example1').DataTable({
            responsive: {
                details: {
                    type: 'column'
                }
            },
            paging: true, //  เปิดระบบแบ่งหน้า
            pageLength: 10, // กำหนดค่าเริ่มต้น 10 entries
            lengthMenu: [10, 25, 50, 100], //  ให้เลือกจำนวนแถวที่แสดง
            lengthChange: true, //  เปิดให้เปลี่ยนจำนวนแถว
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
                } //  บังคับให้แสดงทุกคอลัมน์
            ]
        });
    });
</script>

<?php mysqli_close($con); ?>