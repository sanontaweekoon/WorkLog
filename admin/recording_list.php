<?php
include('../condb.php');
include('header.php');

// ตรวจสอบว่ามี session ของผู้ใช้หรือไม่
if (!isset($_SESSION['personel_id'])) {
    header("Location: login.php");
    exit();
}

$logged_in_user = $_SESSION['personel_id'];

$limit = 21; //รายการต่อหน้า
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $limit;

$count_query = "SELECT COUNT(*) AS total FROM tbl_m_record";
$count_result = mysqli_query($con, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];
$total_pages = ceil($total_records /  $limit);

$sql = "SELECT tbl_m_record.*, personel.personel_name 
        FROM tbl_m_record
        INNER JOIN personel ON tbl_m_record.personel_id = personel.personel_id
        ORDER BY tbl_m_record.date DESC, tbl_m_record.id DESC
        LIMIT $start, $limit
        ";

$result = $con->query($sql);
?>

<!-- CSS ปรับแต่งการ์ด -->
<style>
    /* ปรับแต่งปุ่มแก้ไข */
    .edit-btn[disabled] {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .row::before,
    .row::after {
        content: none !important;
        display: none !important;
    }

    .feed-card {
        display: flex;
        flex-direction: column;
        background: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 15px;
        transition: transform 0.3s ease;
        height: 100%;
    }

    .card-body {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .feed-card:hover {
        transform: translateY(-5px);
    }

    .feed-card img {
        max-width: 100%;
        height: 200px;
        object-fit: cover;
        margin-top: 10px;
    }

    .feed-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .feed-header h4 {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        max-width: 70%;
    }

    .feed-card img {
        width: 100%;
        max-height: 250px;
        object-fit: cover;
    }

    .feed-card .btn {
        width: 100%;
    }

    .card-text {
        flex-grow: 1;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
        /* จำกัดให้แสดงแค่ 1 บรรทัด */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
        /* ป้องกันข้อความไม่ถูกตัด */
        height: auto;
        max-height: 1.2em;
        /* กำหนดให้ข้อความสูงสุดแค่ 1 บรรทัด */
        line-height: 1.2em;
        margin-top: 5px;
    }

    .modal-body img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 10px auto;
    }

    textarea {
        width: 100%;
        min-height: 200px;
        resize: vertical;
    }

    #back-to-top {
        position: fixed;
        bottom: 20px;
        right: 20px;
        display: none;
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 15px;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
        z-index: 1000;
    }

    #back-to-top:hover {
        background-color: #218838;
    }

    .row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        /* กำหนดให้การ์ดมีขนาดขั้นต่ำ 300px และขยายเต็มที่ */
        gap: 15px;
        /* ระยะห่างระหว่างการ์ด */
        margin-left: 1px;
        margin-right: 1px;
        justify-content: start;
        /* ทำให้การ์ดเริ่มจากซ้ายสุด */
        align-items: stretch;
        /* ป้องกันความสูงไม่เท่ากัน */
    }

    .col-md-4 {
        display: block;
        /* ป้องกัน flexbox ทำให้การ์ดไม่จัดเรียงถูกต้อง */

        width: 100%;
    }

    @media (max-width: 992px) {
        .feed-card {
            flex: 1 1 calc(100% - 10px);
            max-width: calc(100% - 10px);
        }
    }

    @media (max-width: 576px) {
        .feed-card {
            flex: 1 1 100%;
            max-width: 100%;
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

    /* ปรับปุ่มลบให้ใหญ่ขึ้น (ขนาด 20px) */
    .swal-delete-btn {
        font-size: 20px !important;
        padding: 12px 25px !important;
        background-color: #d33 !important;
        /* สีแดง */
        border-radius: 8px !important;
        font-weight: bold !important;
    }

    .swal-delete-btn:hover {
        background-color: #b02a2a !important;
        /* สีแดงเข้ม */
    }

    /* ปรับปุ่มยกเลิกให้มีขนาดเท่ากัน */
    .swal-cancel-btn {
        font-size: 20px !important;
        padding: 12px 25px !important;
        background-color: #6c757d !important;
        /* สีเทา */
        border-radius: 8px !important;
        font-weight: bold !important;
    }

    .swal-cancel-btn:hover {
        background-color: #5a6268 !important;
        /* สีเทาเข้ม */
    }

    .pagination a {
        margin: 0 5px;
        padding: 8px 12px;
        border: 1px solid #ccc;
        color: #333;
        text-decoration: none;
        background-color: #f8f8f8;
        transition: 0.3s;
    }

    .pagination a:hover {
        background-color: #06C755;
        color: white;
    }

    .pagination a.active {
        background-color: #06C755;
        color: white;
        font-weight: bold;
    }

    .pagination span {
        margin: 0 5px;
        color: #888;
    }
</style>

<!-- เรียกใช้งาน SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include('menutop.php'); ?>
        <?php include('menu_l.php'); ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h2 id="title-top">ผลการบันทึกการทำงาน</h2>

                <div class="filter-buttons">
                    <button class="btn btn-success btn-sm" id="showAll">แสดงทั้งหมด</button>
                    <button class="btn btn-default btn-sm" id="showMine">แสดงเฉพาะของฉัน</button>
                </div>

                <input type="text" id="searchInput" class="form-control" placeholder="ค้นหาหัวข้อ / รายละเอียด" style="width:100%; margin-top:20px">
            </section>

            <section class="content">
                <div class="row">
                    <?php while ($row = $result->fetch_assoc()) {
                        $is_owner = ($row['personel_id'] == $_SESSION['personel_id']);
                    ?>
                        <div class="feed-card" data-personel-id="<?php echo $row['personel_id']; ?>">
                            <div class="feed-header">
                                <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                                <?php
                                $formattedDate = date("d/m/Y", strtotime($row['date']));
                                ?>
                                <span class="text-muted"><i class="far fa-calendar-alt"></i> <?php echo $formattedDate; ?></span>
                            </div>

                            <?php
                            $imagePath = "../uploads/default.png"; // รูปเริ่มต้น

                            if (preg_match('/<img.*?src=["\'](?:\.\.\/)?uploads\/([^"\']+)["\']/', $row['detail'], $match)) {
                                $imagePath = "../uploads/" . $match[1]; // ใช้เฉพาะภาพแรก
                            }
                            ?>
                            <img src="<?php echo $imagePath; ?>" alt="Image" class="img-fluid" loading="lazy">

                            <div class="card-body">
                                <p class="card-text"><?php echo html_entity_decode(strip_tags($row['detail'])); ?></p>
                                <button class="btn btn-success btn-sm mt-auto" data-toggle="modal" data-target="#detailModal<?php echo $row['id']; ?>"> ดูรายละเอียด</button>
                            </div>
                        </div>

                        <button onclick="topFunction()" id="myBtn" title="Go to top" style="margin-bottom: 50px"><i class='fas fa-arrow-alt-circle-up'></i></button>
                        <button onclick="bottomFunction()" id="myBtn" title="Go to Bottom"><i class='fas fa-arrow-alt-circle-down'></i></button>

                        <!-- Modal แสดงรายละเอียด -->
                        <div class="modal fade" id="detailModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalTitle<?php echo $row['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTitle<?php echo $row['id']; ?>">
                                            รายละเอียด: <?php echo htmlspecialchars($row['title']); ?>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">

                                        <div id="view-mode-<?php echo $row['id']; ?>">
                                            <p><strong>ชื่อเรื่อง:</strong> <?php echo htmlspecialchars($row['title']); ?></p>
                                            <p><strong>วันที่บันทึก:</strong> <?php echo date("d/m/Y", strtotime($row['date'])); ?></p>
                                            <p><strong>ผู้สร้างบันทึก:</strong> <?php echo htmlspecialchars($row['personel_name']); ?></p>
                                            <p><strong>รายละเอียด:</strong> <?php echo html_entity_decode($row['detail']); ?></p>
                                            <p><strong>แก้ไขล่าสุด</strong>
                                                <?php echo $row['last_updated'] ? date("d/m/Y H:i", strtotime($row['last_updated'])) : "ยังไม่มีการแก้ไข"; ?></p>

                                            <?php if ($is_owner) { ?>
                                                <div class="edit-btn-container">
                                                    <button class="btn btn-dark edit-btn" onclick="toggleEditMode(<?php echo $row['id']; ?>)">แก้ไข</button>
                                                    <button class="btn btn-danger delete-btn" onclick="deleteRecord(<?php echo $row['id']; ?>)">ลบ</button>
                                                </div>
                                            <?php } ?>
                                        </div>

                                        <form action="record_update.php" method="POST" id="edit-mode-<?php echo $row['id']; ?>" style="display: none;">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                                            <div class="form-group">
                                                <label>ชื่อเรื่อง</label>
                                                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($row['title']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>วันที่บันทึก</label>
                                                <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($row['date']); ?>" required>
                                            </div>

                                            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.snow.css">
                                            <script src="https://cdn.jsdelivr.net/npm/quill@2.0.0-dev.4/dist/quill.min.js"></script>

                                            <!-- Container สำหรับ Quill Editor -->
                                            <div class="form-group">
                                                <label>รายละเอียด</label>
                                                <div id="editor-container-<?php echo $row['id']; ?>" style="height: 200px;"></div>
                                                <input type="hidden" name="detail" id="hidden-editor-<?php echo $row['id']; ?>">
                                                <input type="file" id="upload-image" accept="image/*" style="display: none;">
                                            </div>

                                            <script>
                                                var quill<?php echo $row['id']; ?> = new Quill('#editor-container-<?php echo $row['id']; ?>', {
                                                    theme: 'snow',
                                                    modules: {
                                                        toolbar: [
                                                            ['bold', 'italic', 'underline'],
                                                            [{
                                                                'list': 'ordered'
                                                            }, {
                                                                'list': 'bullet'
                                                            }],
                                                            ['link', 'image']
                                                        ]
                                                    }
                                                });

                                                // โหลดข้อมูลจากฐานข้อมูลลงใน Quill.js
                                                quill<?php echo $row['id']; ?>.root.innerHTML = <?php echo json_encode($row['detail']); ?>;

                                                // ปุ่มอัปโหลดรูปภาพ
                                                quill<?php echo $row['id']; ?>.getModule('toolbar').addHandler('image', function() {
                                                    document.getElementById('upload-image').click();
                                                })

                                                // เมื่อฟอร์มถูกส่ง ให้เก็บค่ารายละเอียด
                                                document.querySelector("#edit-mode-<?php echo $row['id']; ?>").onsubmit = function() {
                                                    let currentContent = quill<?php echo $row['id']; ?>.root.innerHTML;
                                                    let originalContent = <?php echo json_encode(html_entity_decode($row['detail'])); ?>;

                                                    if (currentContent.trim() === originalContent.trim()) {
                                                        alert("ไม่มีการเปลี่ยนแปลงข้อมูล");
                                                        event.preventDefault();
                                                        return false;
                                                    }
                                                    document.getElementById("hidden-editor-<?php echo $row['id']; ?>").value = currentContent;
                                                };

                                                // บีบอัดรูปภาพก่อนอัปโหลด
                                                quill<?php echo $row['id']; ?>.getModule('toolbar').addHandler('image', function() {
                                                    let input = document.createElement('input');
                                                    input.setAttribute('type', 'file');
                                                    input.setAttribute('accept', 'image/*');

                                                    document.body.appendChild(input);
                                                    input.click();

                                                    input.onchange = async function() {
                                                        let file = input.files[0];

                                                        if (!file) {
                                                            console.error("No file selected.");
                                                            document.body.removeChild(input);
                                                            return;
                                                        }

                                                        resizeImage(file, 800, 800, 0.7, async function(resizedBlob) {
                                                            let formData = new FormData();
                                                            formData.append('image', resizedBlob, file.name);

                                                            try {
                                                                let response = await fetch('upload_image.php', {
                                                                    method: 'POST',
                                                                    body: formData
                                                                });

                                                                let result = await response.json();

                                                                if (result.success) {
                                                                    let range = quill<?php echo $row['id']; ?>.getSelection();
                                                                    quill<?php echo $row['id']; ?>.insertEmbed(range.index, 'image', result.url);
                                                                } else {
                                                                    alert('อัปโหลดรูปไม่สำเร็จ: ' + result.message);
                                                                }
                                                            } catch (error) {
                                                                console.error('Error uploading image:', error);
                                                                alert('เกิดข้อผิดพลาดในการอัปโหลด');
                                                            }
                                                            document.body.removeChild(input);
                                                        });
                                                    };
                                                });

                                                //ฟังก์ชันบีบอัดรูปก่อนอัปโหลด
                                                function resizeImage(file, maxWidth, maxHeight, quality, callback) {
                                                    let reader = new FileReader();
                                                    reader.readAsDataURL(file);
                                                    reader.onload = function(event) {
                                                        let img = new Image();
                                                        img.src = event.target.result;

                                                        img.onload = function() {
                                                            let canvas = document.createElement("canvas");
                                                            let ctx = canvas.getContext("2d");

                                                            let scale = Math.min(maxWidth / img.width, maxHeight / img.height);
                                                            canvas.width = img.width * scale;
                                                            canvas.height = img.height * scale;

                                                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                                                            canvas.toBlob(callback, "image/jpeg", quality);
                                                        };
                                                    };
                                                }

                                                // อัปโหลดรูปไปยังเซิร์ฟเวอร์
                                                function uploadImageToServer(imageBase64) {
                                                    let formData = new FormData();
                                                    formData.append('image', imageBase64);

                                                    fetch('upload_image.php', {
                                                            method: 'POST',
                                                            body: formData
                                                        })
                                                        .then(response => response.json())
                                                        .then(result => {
                                                            if (result.success) {
                                                                let range = quill.getSelection();
                                                                quill.insertEmbed(range.index, 'image', result.message);
                                                            }
                                                        })
                                                        .catch(error => {
                                                            console.error('เกิดข้อผิดพลาดขึ้น');
                                                        })
                                                }

                                                function deleteRecord(recordId) {
                                                    Swal.fire({
                                                        title: "<h2>ลบบันทึกนี้หรือไม่</h2>",
                                                        html: '<p style="font-size: 18px;">บันทึกนี้จะไม่สามารถดูได้อีกถ้าหากลบไปแล้ว !</p>',
                                                        icon: "warning",
                                                        showCancelButton: true,
                                                        confirmButtonText: "ลบเลย!",
                                                        cancelButtonText: "ยกเลิก",
                                                        width: '500px',
                                                        customClass: {
                                                            confirmButton: 'swal-delete-btn',
                                                            cancelButton: 'swal-cancel-btn'
                                                        }
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            $.ajax({
                                                                url: "delete_record.php",
                                                                type: "POST",
                                                                dataType: "json",
                                                                data: {
                                                                    id: recordId
                                                                },
                                                                success: function(response) {
                                                                    if (response.status === "success") {
                                                                        Swal.fire({
                                                                            title: "ลบสำเร็จ",
                                                                            text: response.message,
                                                                            icon: "success",
                                                                            timer: 1500,
                                                                            showConfirmButton: false
                                                                        }).then(() => {
                                                                            location.reload();
                                                                        });
                                                                    } else {
                                                                        Swal.fire("ผิดพลาด!", response.message, "error");
                                                                    }
                                                                },
                                                                error: function() {
                                                                    Swal.fire("ผิดพลาด!", "ไม่สามารถลบบันทึกได้", "error");
                                                                }
                                                            });
                                                        }
                                                    });
                                                }
                                            </script>
                                            <div class="modal-footer">
                                                <?php if ($is_owner) { ?>
                                                    <button type="submit" class="btn btn-success"><i class="fa fa-folder"></i> บันทึกการแก้ไข</button>
                                                <?php } ?>
                                                <button type="button" class="btn btn-secondary" onclick="toggleEditMode(<?php echo $row['id']; ?>)">ยกเลิก</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php
                $adjacents = 2;
                $start_page = ($page > $adjacents) ? $page - $adjacents : 1;
                $end_page = ($page < $total_pages - $adjacents) ? $page + $adjacents : $total_pages;
                ?>

                <div class="pagination" style="text-align: center; margin-top: 20px;">
                    <?php if ($page > 1): ?>
                        <a href="?page=1">&laquo;</a>
                    <?php endif; ?>

                    <?php if ($start_page > 1): ?>
                        <span>...</span>
                    <?php endif; ?>

                    <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                        <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($end_page < $total_pages): ?>
                        <span>...</span>
                    <?php endif; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $total_pages ?>">&raquo;</a>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <script>
        function toggleEditMode(id) {
            let viewMode = document.getElementById("view-mode-" + id);
            let editMode = document.getElementById("edit-mode-" + id);

            if (!viewMode || !editMode) {
                console.error("ไม่พบ element ของ viewMode หรือ editMode:", id);
                return;
            }
            if (viewMode.style.display === "none") {
                viewMode.style.display = "block";
                editMode.style.display = "none";
            } else {
                viewMode.style.display = "none";
                editMode.style.display = "block";
            }
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const showAllBtn = document.getElementById("showAll");
            const showMineBtn = document.getElementById("showMine");
            const currentUser = "<?php echo $_SESSION['personel_id']; ?>"; // ดึงค่า session ของผู้ใช้
            const allCards = document.querySelectorAll(".feed-card");

            function applyUserFilter() {
                allCards.forEach(card => {
                    if (card.getAttribute("data-personel-id") === currentUser) {
                        card.style.display = "block";
                    } else {
                        card.style.display = "none";
                    }
                });
            }

            // แสดงทั้งหมด
            showAllBtn.addEventListener("click", function() {
                const url = new URL(window.location.href);
                url.searchParams.set("filter", "all");
                url.searchParams.set("page", "1");
                window.location.href = url.toString();
            });

            // แสดงเฉพาะของฉัน
            showMineBtn.addEventListener("click", function() {
                const url = new URL(window.location.href);
                url.searchParams.set("filter", "mine");
                url.searchParams.set("page", "1"); // รีเซ็ตหน้า
                window.location.href = url.toString();
            });

            // ตรวจสอบ filter จาก URL เพื่อ apply หลังโหลด
            const urlParams = new URLSearchParams(window.location.search);
            const filter = urlParams.get("filter");

            if (filter === "mine") {
                applyUserFilter();
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("searchInput");
            const cards = document.querySelectorAll(".feed-card");

            searchInput.addEventListener("keyup", function() {
                let searchValue = this.value.toLowerCase();
                let visibleCards = 0;
                let lastVisibleCard = null;

                cards.forEach(function(card) {
                    let title = card.querySelector("h4").textContent.toLowerCase();
                    let descriptionElement = card.querySelector(".card-text"); // ส่วนรายละเอียด
                    let description = descriptionElement ? descriptionElement.textContent.toLowerCase() : "";

                    if (title.includes(searchValue) || description.includes(searchValue)) {
                        card.style.display = "flex";
                        visibleCards++;
                        lastVisibleCard = card;
                    } else {
                        card.style.display = "none";
                    }
                });

                if (visibleCards === 1 && lastVisibleCard) {
                    lastVisibleCard.style.maxWidth = "400px";
                    lastVisibleCard.style.margin = "0 0";
                } else {
                    cards.forEach(function(card) {
                        card.style.maxWidth = "";
                        card.style.margin = "";
                    });
                }
            });
        });

        // Get the button
        let mybutton = document.getElementById("myBtn");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {
            scrollFunction()
        };

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function bottomFunction() {
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: 'smooth'
            });
        }
    </script>


    <?php include('footerjs.php'); ?>
</body>