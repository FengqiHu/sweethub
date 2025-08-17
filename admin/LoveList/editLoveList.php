<?php
session_start();
include_once '../Component/Nav.php';
$id = $_GET['id'];
$icon = $_GET['icon'];
$name = $_GET['name'];
$imgurl = $_GET['imgurl'];
$inv_date = date("Y-m-d");
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">修改事件—— <?php echo $name ?></h4>
                <form class="needs-validation" id="editForm" onsubmit="return false;" enctype="multipart/form-data" novalidate>
                    <div class="form-group mb-3">
                        <label for="validationCustom01">事件标题</label>
                        <input type="text" name="eventname" class="form-control" id="validationCustom01"
                               placeholder="请输入事件标题" value="<?php echo $name ?>" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="validationCustom01">日期</label>
                        <input class="form-control col-sm-4" id="example-date" type="date" name="eventdate"
                               class="form-control" placeholder="日期" value="<?php echo $inv_date ?>" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="validationCustom01">完成状态</label>
                        <input type="checkbox" name="icon" id="switch3" value="<?php echo $icon ?>"
                               data-switch="success" onclick="myOnClickHandler(this)"
                                <?php if ($icon == 1) echo 'checked'; ?>>
                        <label id="switchurl" style="display:block;" for="switch3" data-on-label="Yes"
                               data-off-label="No"></label>
                    </div>

                    <!-- 当前图片显示区域 -->
                    <?php if ($imgurl && $imgurl != '0') { ?>
                        <div class="form-group mb-3" id="current_image_area">
                            <label>当前图片</label>
                                <img src="/admin/static/listImg/<?php echo htmlspecialchars($imgurl); ?>"
                                     alt="当前图片" style="max-width: 100px; max-height: 100px; display: block;">
                        </div>
                    <?php } ?>

                    <!-- 文件上传区域 -->
                    <div class="form-group mb-3" id="img_upload_area"
                         style="<?php echo $icon == 0 ? 'display:none;' : ''; ?>">
                        <label>上传新图片（可选）</label>
                        <div class="file-upload-wrapper" id="uploadArea">
                            <input type="file" id="imgFile" name="imgFile" class="file-upload-input"
                                   accept="image/jpg,image/jpeg,image/gif,image/png,image/bmp,image/webp"
                                   onchange="handleFileSelect(event)">
                            <div class="file-upload-design" id="uploadDesign">
                                <svg class="file-upload-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="file-upload-text">点击上传图片</p>
                                <p class="file-upload-subtext">或拖拽文件到此处</p>
                            </div>
                            <div class="preview-area" id="previewArea" style="display: none;">
                                <img id="previewImg" src="" alt="预览图片">
                                <button type="button" class="btn btn-sm btn-danger remove-btn" onclick="removeImage()">
                                    <i class="fas fa-times"></i> 移除
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">支持格式：jpg, jpeg, gif, png, bmp, webp（最大10MB）</small>
                    </div>

                    <div class="form-group mb-3 text_right">
                        <input name="id" value="<?php echo $id ?>" type="hidden">
                        <input name="oldImgUrl" value="<?php echo $imgurl ?>" type="hidden">
                        <button class="btn btn-primary" type="submit" id="listupda">提交修改</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function myOnClickHandler(obj) {
        var input = document.getElementById("switch3");
        var imgUploadArea = document.getElementById("img_upload_area");

        if (obj.checked) {
            console.log("打开");
            input.value = "1";
            imgUploadArea.style.display = "block";
        } else {
            console.log("关闭");
            input.value = "0";
            imgUploadArea.style.display = "none";
            removeImage(); // 清除已选择的图片
        }
    }

    // 文件选择处理
    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            displayPreview(file);
        }
    }

    // 显示预览
    function displayPreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('uploadDesign').style.display = 'none';
            document.getElementById('previewArea').style.display = 'flex';
        };
        reader.readAsDataURL(file);
    }

    // 移除图片
    function removeImage() {
        document.getElementById('imgFile').value = '';
        document.getElementById('uploadDesign').style.display = 'block';
        document.getElementById('previewArea').style.display = 'none';
        document.getElementById('previewImg').src = '';
    }

    // 拖拽功能
    const uploadArea = document.getElementById('uploadArea');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        uploadArea.classList.add('dragover');
    }

    function unhighlight(e) {
        uploadArea.classList.remove('dragover');
    }

    uploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                const fileInput = document.getElementById('imgFile');
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
                displayPreview(file);
            } else {
                toastr["error"]("请上传图片文件！", "SweetHub");
            }
        }
    }

    // 表单提交处理
    $('#editForm').on('submit', function(e) {
        e.preventDefault();

        let eventname = $('input[name="eventname"]').val().trim();
        if (eventname.length == 0) {
            toastr["error"]("事件标题不能为空", "SweetHub");
            return false;
        }

        var formData = new FormData(this);

        toastr["info"]("正在修改事件...", "SweetHub");

        $('#listupda').prop('disabled', true).text('修改中...');

        $.ajax({
            url: '../posts/editLoveListPost.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'text',
            success: function(response) {
                console.log("Response:", response);
                response = response.trim();

                if (response === 'success') {
                    toastr["success"]("修改事件成功！", "SweetHub");
                    setTimeout(function() {
                        window.location.href = 'loveList.php';
                    }, 1500);
                } else if (response === 'empty_fields') {
                    toastr["error"]("请填写完整信息", "SweetHub");
                    $('#listupda').prop('disabled', false).text('提交修改');
                } else if (response === 'invalid_format') {
                    toastr["error"]("不支持的文件格式", "SweetHub");
                    $('#listupda').prop('disabled', false).text('提交修改');
                } else if (response === 'file_too_large') {
                    toastr["error"]("图片大小不能超过10MB", "SweetHub");
                    $('#listupda').prop('disabled', false).text('提交修改');
                } else if (response === 'upload_failed') {
                    toastr["error"]("图片上传失败", "SweetHub");
                    $('#listupda').prop('disabled', false).text('提交修改');
                } else if (response === 'unauthorized') {
                    toastr["error"]("请先登录", "SweetHub");
                    setTimeout(function() {
                        window.location.href = '../login.php';
                    }, 1500);
                } else {
                    toastr["error"]("系统错误：" + response, "SweetHub");
                    $('#listupda').prop('disabled', false).text('提交修改');
                }
            },
            error: function(xhr, status, error) {
                console.log("Ajax Error:", error);
                toastr["error"]("网络错误，请稍后重试", "SweetHub");
                $('#listupda').prop('disabled', false).text('提交修改');
            }
        });
    });
</script>

<style>
    .file-upload-wrapper {
        position: relative;
        width: 100%;
        height: 200px;
        border: 2px dashed #ccc;
        border-radius: 8px;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .file-upload-wrapper.dragover {
        border-color: #007bff;
        background: #e7f3ff;
    }

    .file-upload-input {
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }

    .file-upload-design {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        pointer-events: none;
    }

    .file-upload-icon {
        width: 48px;
        height: 48px;
        color: #6c757d;
        margin-bottom: 10px;
    }

    .file-upload-text {
        font-size: 16px;
        color: #495057;
        margin-bottom: 5px;
    }

    .file-upload-subtext {
        font-size: 14px;
        color: #6c757d;
    }

    .preview-area {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        position: relative;
    }

    .preview-area img {
        max-width: 100%;
        max-height: 180px;
        object-fit: contain;
    }

    .remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
    }
</style>

<?php
include_once '../Component/Footer.php';
?>
</body>
</html>