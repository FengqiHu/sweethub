<?php
session_start();
include_once '../Component/Nav.php';
$inv_date = date("Y-m-d");
?>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">新增相册</h4>

                <form class="needs-validation" id="addForm" onsubmit="return false;" enctype="multipart/form-data" novalidate>
                    <div class="form-group mb-3">
                        <label for="validationCustom01">日期</label>
                        <input class="form-control col-sm-4" id="example-date" type="date" name="imgDatd"
                               class="form-control" placeholder="日期" value="<?php echo $inv_date ?>" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="validationCustom01">图片描述<span class="margin_left badge badge-success-lighten">尽量控制在50个字符以内 </span></label>
                        <input name="imgText" type="text" class="form-control" placeholder="请输入图片描述" value="" required>
                    </div>

                    <!-- 文件上传区域 -->
                    <div class="form-group mb-3" id="file_upload_area">
                        <label>上传图片</label>
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
                        <button class="btn btn-primary" type="submit">新增相册</button>
                    </div>
                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<script>
    // 当前上传类型
    let currentUploadType = 'file';

    // 切换上传方式
    function toggleUploadType(type) {
        currentUploadType = type;

        // 切换按钮样式
        $('.toggle-btn').removeClass('btn-primary').addClass('btn-outline-primary');
        if (type === 'file') {
            $('.toggle-btn:first').removeClass('btn-outline-primary').addClass('btn-primary');
            $('#file_upload_area').show();
            $('#url_input_area').hide();
            $('input[name="imgUrl"]').val('');
        } else {
            $('.toggle-btn:last').removeClass('btn-outline-primary').addClass('btn-primary');
            $('#file_upload_area').hide();
            $('#url_input_area').show();
            removeImage();
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
    $('#addForm').on('submit', function(e) {
        e.preventDefault();

        // 表单验证
        let imgText = $('input[name="imgText"]').val().trim();
        if (imgText.length == 0) {
            toastr["error"]("图片描述不能为空", "SweetHub");
            return false;
        }

        if (imgText.length > 50) {
            toastr["error"]("图片描述不能超过50个字符", "SweetHub");
            return false;
        }

        // 根据上传类型验证
        if (currentUploadType === 'file') {
            let fileInput = document.getElementById('imgFile');
            if (fileInput.files.length === 0) {
                toastr["error"]("请选择要上传的图片", "SweetHub");
                return false;
            }

            // 检查文件大小
            let fileSize = fileInput.files[0].size;
            let maxSize = 10 * 1024 * 1024; // 10MB
            if (fileSize > maxSize) {
                toastr["error"]("图片大小不能超过10MB", "SweetHub");
                return false;
            }
        } else {
            let imgUrl = $('input[name="imgUrl"]').val().trim();
            if (imgUrl.length === 0) {
                toastr["error"]("请输入图片URL", "SweetHub");
                return false;
            }
        }

        // 使用FormData处理表单
        var formData = new FormData(this);
        formData.append('uploadType', currentUploadType);

        // 显示加载提示
        toastr["info"]("正在添加相册...", "SweetHub");

        // 禁用提交按钮
        $('button[type="submit"]').prop('disabled', true).text('添加中...');

        $.ajax({
            url: '../posts/addLoveImgPost.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'text',
            success: function(response) {
                response = response.trim();

                if (response === 'success') {
                    toastr["success"]("添加相册成功！", "SweetHub");
                    // 重置表单
                    $('#addForm')[0].reset();
                    removeImage();
                    // 延迟跳转到列表页
                    setTimeout(function() {
                        location.href = 'loveImgList.php';
                    }, 1500);
                } else if (response === 'empty_fields') {
                    toastr["error"]("请填写完整信息", "SweetHub");
                    $('button[type="submit"]').prop('disabled', false).text('新增相册');
                } else if (response === 'invalid_format') {
                    toastr["error"]("不支持的文件格式", "SweetHub");
                    $('button[type="submit"]').prop('disabled', false).text('新增相册');
                } else if (response === 'file_too_large') {
                    toastr["error"]("图片大小不能超过10MB", "SweetHub");
                    $('button[type="submit"]').prop('disabled', false).text('新增相册');
                } else if (response === 'upload_failed') {
                    toastr["error"]("图片上传失败", "SweetHub");
                    $('button[type="submit"]').prop('disabled', false).text('新增相册');
                } else if (response === 'unauthorized') {
                    toastr["error"]("请先登录", "SweetHub");
                    setTimeout(function() {
                        location.href = '../login.php';
                    }, 1500);
                } else if (response === 'error') {
                    toastr["error"]("添加失败，请稍后重试", "SweetHub");
                    $('button[type="submit"]').prop('disabled', false).text('新增相册'); } else {
                    toastr["error"]("系统错误：" + response, "SweetHub");
                    $('button[type="submit"]').prop('disabled', false).text('新增相册');
                }
            },
            error: function(xhr, status, error) {
                toastr["error"]("网络错误，请稍后重试", "SweetHub");
                $('button[type="submit"]').prop('disabled', false).text('新增相册');
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

    .upload-type-toggle {
        margin-bottom: 20px;
    }

    .toggle-btn {
        margin-right: 10px;
    }
</style>

<?php
include_once '../Component/Footer.php';
?>

</body>
</html>