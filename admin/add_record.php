<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.snow.css">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0-dev.4/dist/quill.min.js"></script>
<script src="../dist/js/resize_image.js"></script>

<?php
include('header.php');
include('../condb.php');

$personel_id = isset($_SESSION['personel_id']) ? $_SESSION['personel_id'] : 0;
?>

<body class="hold-transition skin-green sidebar-mini">
    <div class="wrapper">
        <?php include('menutop.php'); ?>
        <?php include('menu_l.php'); ?>
        <div class="content-wrapper">
            <section class="content-header">
                <h2 id="title-top">บันทึกการทำงาน</h2>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-body">
                                <form action="record_save.php" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="title">ชื่อเรื่อง</label>
                                        <input type="text" name="title" id="title" class="form-control" placeholder="กรุณากรอกชื่อเรื่อง" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="date">วันที่</label>
                                        <input type="date" name="date" id="date" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editor">รายละเอียด</label>
                                        <div id="editor-container" style="height: 300px;"></div>
                                        <input type="hidden" name="detail" id="hidden-editor">
                                    </div>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-folder"></i> บันทึกข้อมูล</button>
                                    <a href="recording_list.php" class="btn btn-default"><i class='far fa-arrow-alt-circle-left'></i> ย้อนกลับ</a>
                                    <button type="button" class="btn btn-default" id="reset-form"><i class="fa fa-undo"></i> รีเซ็ตข้อมูล</button>
                                    <input type="hidden" name="personel_id" value="<?php echo $personel_id; ?>">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <?php include('footerjs.php'); ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var quill = new Quill('#editor-container', {
                theme: 'snow',
                placeholder: 'พิมพ์รายละเอียดที่นี่...',
                modules: {
                    toolbar: [
                        [{
                            header: [1, 2, false]
                        }],
                        ['bold', 'italic', 'underline'],
                        ['blockquote', 'code-block'],
                        [{
                            list: 'ordered'
                        }, {
                            list: 'bullet'
                        }],
                        ['link', 'image']
                    ]
                }
            });

            document.getElementById("title").addEventListener("input", function() {
                sessionStorage.setItem("title", this.value);
            });

            document.getElementById("date").addEventListener("input", function() {
                sessionStorage.setItem("date", this.value);
            });

            quill.on("text-change", function() {
                sessionStorage.setItem("editorContent", quill.root.innerHTML);
            });

            function updateHiddenInput() {
                let content = quill.root.innerHTML.trim(); // เอาข้อมูลจาก Quill Editor
                document.querySelector('#hidden-editor').value = content; // อัปเดตลง input hidden
                return true;
            }

            // ดักจับ event ก่อนส่งฟอร์ม
            document.querySelector("form").onsubmit = function() {
                updateHiddenInput(); // อัปเดตค่าก่อนส่ง
                sessionStorage.clear(); // ล้างค่าออกหลังจากส่งฟอร์ม
            };

            // ล้างข้อมูลเมื่อกดปุ่มรีเซ็ต 
            document.querySelector(".btn-default[href='recording_list.php']").addEventListener("click", function() {
                sessionStorage.clear();
            });

            document.getElementById("reset-form").addEventListener("click", function() {
                if (confirm("คุณแน่ใจหรือไม่ว่าต้องการล้างข้อมูลทั้งหมด?")) {
                    document.querySelector("form").reset();
                    quill.setContents([]);
                    sessionStorage.clear();
                }
            });



            // ฟังก์ชันอัปโหลดรูปภาพ
            quill.getModule('toolbar').addHandler('image', function() {
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
                                let range = quill.getSelection();
                                quill.insertEmbed(range.index, 'image', result.url);
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
        });
    </script>
</body>

</html>