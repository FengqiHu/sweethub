<?php
session_start();
include_once '../Component/Nav.php';

$id = $_GET['id'];
include_once '../dbConfig/connect.php';
$loveImg = "select * from loveImg WHERE id=$id limit 1";
$resImg = mysqli_query($connect, $loveImg);
$Imglist = mysqli_fetch_array($resImg);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">修改相册—— ID：<?php echo $Imglist['id'] ?></h4>

                <form class="needs-validation" id="updateForm" onsubmit="return false;" enctype="multipart/form-data" novalidate>
                    <div class="form-group mb-3">
                        <label for="validationCustom01">日期</label>
                        <input class="form-control col-sm-4" id="example-date" type="date" name="imgDatd"
                               class="form-control" placeholder="日期" value="<?php echo $Imglist['imgDatd'] ?>"
                               required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="validationCustom01">图片描述</label>
                        <input name="imgText" type="text" class="form-control" placeholder="请输入图片描述"
                               value="<?php echo $Imglist['imgText'] ?>" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="validationCustom01">当前图片</label>
                        <div>
                            <img src="/admin/static/albumImg/<?php echo $Imglist['imgUrl'] ?>" alt="当前图片"
                                 style="max-width: 200px; max-height: 200px;">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>上传新图片（不选择则保留原图）</label>
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
                        <small class="form-text text-muted">支持格式：jpg, jpeg, gif, png, bmp, webp</small>
                    </div>

                    <!-- 隐藏原图片URL，用于在不上传新图片时保留 -->
                    <input type="hidden" name="oldImgUrl" value="<?php echo $Imglist['imgUrl'] ?>">

                    <div class="form-group mb-3 text_right">
                        <input name="id" value="<?php echo $id ?>" type="hidden">
                        <button class="btn btn-primary" type="submit">更新相册</button>
                    </div>
                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<script>
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

    // 阻止默认拖拽行为
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // 高亮拖拽区域
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

    // 处理拖拽文件
    uploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                // 将文件赋值给input
                const fileInput = document.getElementById('imgFile');
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;

                // 显示预览
                displayPreview(file);
            } else {
                toastr["error"]("请上传图片文件！", "SweetHub");
            }
        }
    }

    // 表单提交处理
    $('#updateForm').on('submit', function(e) {
        e.preventDefault();

        // 表单验证
        let imgText = $('input[name="imgText"]').val().trim();
        if (imgText.length == 0) {
            toastr["error"]("描述不能为空", "SweetHub");
            return false;
        }

        // 检查文件大小
        let fileInput = document.getElementById('imgFile');
        if (fileInput.files.length > 0) {
            let fileSize = fileInput.files[0].size;
            let maxSize = 10 * 1024 * 1024; // 10MB
            if (fileSize > maxSize) {
                toastr["error"]("图片大小不能超过10MB", "SweetHub");
                return false;
            }
        }

        // 使用FormData处理包含文件的表单
        var formData = new FormData(this);

        // 禁用提交按钮
        $('button[type="submit"]').prop('disabled', true).text('更新中...');

        $.ajax({
            url: '../posts/editLoveImgPost.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'text',
            success: function(response) {
                response = response.trim(); // 去除可能的空格

                if (response === 'success') {
                    toastr["success"]("更新相册成功！", "SweetHub");
                    // 延迟跳转到列表页
                    setTimeout(function() {
                        location.href = 'loveImgList.php';
                    }, 1500);
                } else if (response === 'empty_fields') {
                    toastr["error"]("请填写完整信息", "SweetHub");
                    $('button[type="submit"]').prop('disabled', false).text('更新相册');
                } else if (response === 'invalid_format') {
                    toastr["error"]("不支持的文件格式", "SweetHub");
                    $('button[type="submit"]').prop('disabled', false).text('更新相册');
                } else if (response === 'file_too_large') {
                    toastr["error"]("图片大小不能超过10MB", "SweetHub");
                    $('button[type="submit"]').prop('disabled', false).text('更新相册');
                } else if (response === 'upload_failed') {
                    toastr["error"]("图片上传失败", "SweetHub");
                    $('button[type="submit"]').prop('disabled', false).text('更新相册');
                } else if (response === 'unauthorized') {
                    toastr["error"]("请先登录", "SweetHub");
                    setTimeout(function() {
                        location.href = '../login.php';
                    }, 1500);
                } else if (response === 'error') {
                    toastr["error"]("更新失败，请稍后重试", "SweetHub");
                    $('button[type="submit"]').prop('disabled', false).text('更新相册');
                } else {
                    toastr["error"]("系统错误：" + response, "SweetHub");
                    $('button[type="submit"]').prop('disabled', false).text('更新相册');
                }
            },
            error: function(xhr, status, error) {
                toastr["error"]("网络错误，请稍后重试", "SweetHub");
                // 重新启用提交按钮
                $('button[type="submit"]').prop('disabled', false).text('更新相册');
            }
        });
    });
</script>
<style>
    .file-upload-wrapper {
        position: relative;
        width: 100%;
        height: 200px;
        border: 2px dashed #4a5568;
        border-radius: 8px;
        cursor: pointer;
        overflow: hidden;
        transition: all 0.3s ease;
        background: #f8f9fa;

    }

    .file-upload-wrapper:hover {
        border-color: #007bff;
        background-color: rgba(0, 123, 255, 0.05);
    }

    .file-upload-wrapper.dragover {
        border-color: #6395f6;
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
        color: #4a5568;
        margin-bottom: 8px;
    }

    .file-upload-text {
        font-size: 16px;
        font-weight: 500;
        color: #495057;
        margin-bottom: 4px;
    }

    .file-upload-subtext {
        font-size: 14px;
        color: #718096;
    }

    .preview-area {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .preview-area img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .remove-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        border-radius: 0.4rem;
    }
</style>
<?php
include_once '../Component/Footer.php';
?>
</body>
</html>