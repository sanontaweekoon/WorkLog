<?php
$query = "SELECT * FROM 
`institution` 
ORDER BY institution_id ASC" or die("Error:" . mysqli_error($con));
$result = mysqli_query($con, $query);
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<!-- jQuery และ DataTables -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<style>
    @media (max-width: 768px) {
    table.dataTable thead {
        display: none; /* ซ่อนหัวตาราง */
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
    }

    table.dataTable tbody td:last-child {
        border-bottom: none;
    }

    table.dataTable tbody td::before {
        content: attr(data-label); /* แสดง Label ของคอลัมน์ */
        font-weight: bold;
        color: #333;
        text-transform: uppercase;
    }
}

</style>

<div class="table-responsive">
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr class='success'>
                <th width='5%'>รหัส</th>
                <th width='25%'>ชื่อแผนก</th>
                <th width='25%'>ผู้จัดการ</th>
                <th>ข้อมูลการติดต่อ</th>
                <th width='5%'>แก้ไข</th>
                <th width='5%'>ลบ</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_array($result)) { ?>
                <tr>
                    <td data-label="รหัส"><?= $row["institution_id"]; ?></td>
                    <td data-label="ชื่อแผนก"><?= $row["institution_name"]; ?></td>
                    <td data-label="ผู้จัดการ"><?= $row["manager"]; ?></td>
                    <td data-label="ข้อมูลการติดต่อ"><?= $row["institution_contact"]; ?></td>
                    <td data-label="แก้ไข">
                        <a href='institution.php?act=edit&ID=<?= $row["institution_id"]; ?>' class='btn btn-warning btn-xs'>
                            <i class='fa fa-wrench'></i> แก้ไข
                        </a>
                    </td>
                    <td data-label="ลบ">
                        <a href='institution_del_db.php?ID=<?= $row["institution_id"]; ?>' 
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