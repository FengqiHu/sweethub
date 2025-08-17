<?php
session_start();
include_once 'Nav.php';
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">基本设置</h4>
                <form class="needs-validation" id="basicSettingForm" method="post" onsubmit="return false;" novalidate>
                    <div class="form-group mb-3">
                        <label for="validationCustom01">站点标题</label>
                        <input type="text" class="form-control" placeholder="请输入站点标题"
                               name="title" value="<?php echo $text['title'] ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom02">站点LOGO</label>
                        <input type="text" class="form-control" placeholder="请填写站点LOGO文字"
                               name="logo" value="<?php echo $text['logo'] ?>" required>

                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom03">站点文案</label>
                        <input type="text" class="form-control" placeholder="显示在顶部的文案"
                               name="writing" value="<?php echo $text['writing'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="validationCustom06">是否关闭头像背景高斯模糊</label>
                        <select class="form-control" id="example-select" name="WebBlur">
                            <option value="1" <?php if ($diy['Blurkg'] == "1") { ?> selected <?php } ?>>开启</option>
                            <option value="2" <?php if ($diy['Blurkg'] == "2") { ?> selected <?php } ?> >关闭</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="validationCustom07">是否开启前端无刷新加载</label>
                        <select class="form-control" id="example-select" name="WebPjax">
                            <option value="1" <?php if ($diy['Pjaxkg'] == "1") { ?> selected <?php } ?>>开启</option>
                            <option value="2" <?php if ($diy['Pjaxkg'] == "2") { ?> selected <?php } ?> >关闭</option>
                        </select>
                    </div>
                    <div class="form-group mb-3 text_right">
                        <button class="btn btn-primary" type="button" onclick="submitBasicSetting()" id="editbasicSettingPost">
                            提交修改
                        </button>
                    </div>
                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">情侣配置</h4>
                <form class="needs-validation" action="posts/editCoupleSettingPost.php" method="post" onsubmit="return submitCoupleForm()" novalidate>
                    <div class="form-group mb-3">
                        <label for="validationCustom01">男主Nanme</label>
                        <input type="text" class="form-control" placeholder="请输入男主Name"
                               name="boy" value="<?php echo $text['boy'] ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom02">女主Name</label>
                        <input type="text" class="form-control" placeholder="请输入女主Name"
                               name="girl" value="<?php echo $text['girl'] ?>" required>

                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom03">男主QQ</label>
                        <input type="text" class="form-control" placeholder="请输入男主QQ（用于显示头像）"
                               name="boyimg" value="<?php echo $text['boyimg'] ?>" required>

                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom04">女主QQ</label>
                        <input type="text" class="form-control" placeholder="请输入女主QQ（用于显示头像）"
                               name="girlimg" value="<?php echo $text['girlimg'] ?>" required>

                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom05">起始时间</label>
                        <input type="datetime-local" class="form-control" placeholder="请输入起始时间"
                               name="startTime" value="<?php echo $text['startTime'] ?>" required>
                    </div>
                    <div class="form-group mb-3 text_right">
                        <button class="btn btn-primary" type="submit" id="editCoupleSettingPost">提交修改</button>
                    </div>
                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">卡片配置&版权配置</h4>
                <form class="needs-validation" action="posts/editCardSettingPost.php" method="post"
                      enctype="multipart/form-data" onsubmit="return submitCardForm()" novalidate>

                    <div class="form-group mb-3">
                        <label for="bgimg_file">背景图片上传</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="bgimg_file"
                                       name="bgimg_file" accept="image/*" onchange="previewImage(this)">
                                <label class="custom-file-label" for="bgimg_file">选择图片</label>
                            </div>
                        </div>
                        <small class="form-text text-muted">支持 JPG、PNG、GIF 格式，最大 5MB</small>

                        <!-- 图片预览区域 -->
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <img id="preview" src="" alt="预览图片" style="max-width: 300px; max-height: 200px;">
                        </div>

                        <!-- 当前背景图片 -->
                        <?php if (!empty($text['bgimg'])): ?>
                            <div class="mt-2">
                                <small class="text-muted">当前背景图片：</small><br>
                                <img src="/admin/static/wallpaper/<?php echo $text['bgimg']; ?>"
                                     alt="当前背景" style="max-width: 300px; max-height: 200px;">
                            </div>
                        <?php endif; ?>

                        <!-- 隐藏字段保存原有图片名称 -->
                        <input type="hidden" name="old_bgimg" value="<?php echo $text['bgimg']; ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label for="validationCustom01">卡片1Name</label>
                        <input type="text" class="form-control" placeholder="请输入卡片Name"
                               name="card1" value="<?php echo $text['card1'] ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom02">卡片1描述</label>
                        <input type="text" class="form-control" placeholder="请输入卡片描述"
                               name="deci1" value="<?php echo $text['deci1'] ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom03">卡片2Name</label>
                        <input type="text" class="form-control" placeholder="请输入卡片Name"
                               name="card2" value="<?php echo $text['card2'] ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom04">卡片2描述</label>
                        <input type="text" class="form-control" placeholder="请输入卡片描述"
                               name="deci2" value="<?php echo $text['deci2'] ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom05">卡片3Name</label>
                        <input type="text" class="form-control" placeholder="请输入卡片Name"
                               name="card3" value="<?php echo $text['card3'] ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom05">卡片3描述</label>
                        <input type="text" class="form-control" placeholder="请输入卡片描述"
                               name="deci3" value="<?php echo $text['deci3'] ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom05">域名备案号</label>
                        <input type="text" class="form-control" placeholder="没有请留空" name="icp"
                               value="<?php echo $text['icp'] ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom05">站点版权信息</label>
                        <input type="text" class="form-control" placeholder="请输入站点版权信息"
                               name="Copyright" value="<?php echo $text['Copyright'] ?>" required>
                    </div>
                    <div class="form-group mb-3 text_right">
                        <button class="btn btn-primary" type="submit" id="editCardSettingPost">提交修改</button>
                    </div>
                </form>
            </div>
        </div>
    </div> <!-- end col-->
</div>

<?php
include_once 'Footer.php';
?>

</body>
</html>

<script>
    function submitBasicSetting() {
        // 表单验证
        var form = document.getElementById('basicSettingForm');
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return false;
        }

        // 禁用提交按钮
        var btn = document.getElementById('editbasicSettingPost');
        btn.disabled = true;
        btn.innerHTML = '提交中...';

        // AJAX提交
        $.ajax({
            url: 'posts/editbasicSettingPost.php',
            type: 'POST',
            data: $('#basicSettingForm').serialize(),
            success: function (response) {
                response = response.trim();
                if (response === 'success') {
                    alert('修改成功！');
                    // 可选：刷新页面或更新显示
                    location.reload();
                } else {
                    alert('修改失败！');
                }
                btn.disabled = false;
                btn.innerHTML = '提交修改';
            },
            error: function () {
                alert('网络错误！');
                btn.disabled = false;
                btn.innerHTML = '提交修改';
            }
        });
    }
    // 情侣配置表单提交
    function submitCoupleForm() {
        var form = document.querySelector('form[action="posts/editCoupleSettingPost.php"]');

        // 表单验证
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return false;
        }

        // 获取按钮并禁用
        var btn = document.getElementById('editCoupleSettingPost');
        var originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> 提交中...';

        // AJAX提交
        $.ajax({
            url: 'posts/editCoupleSettingPost.php',
            type: 'POST',
            data: $(form).serialize(),
            dataType: 'text',
            success: function(response) {
                response = response.trim();

                if (response === '1') {
                    alert('情侣配置修改成功！');
                    // 可选：刷新页面
                    location.reload();
                } else if (response === '0') {
                    alert('修改失败，请重试！');
                } else if (response === '3') {
                    alert('QQ号码格式不正确！');
                } else if (response.includes('非法操作')) {
                    alert('非法操作，请重新登录！');
                    location.href = 'login.php';
                } else {
                    alert('未知错误！');
                }

                // 恢复按钮
                btn.disabled = false;
                btn.innerHTML = originalText;
            },
            error: function(xhr, status, error) {
                alert('网络错误，请检查网络连接！');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });

        return false;
    }

    // 图片预览功能
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);

            // 更新文件名显示
            var fileName = input.files[0].name;
            var label = input.nextElementSibling;
            label.innerText = fileName;
        }
    }

    // 卡片配置表单提交
    function submitCardForm() {
        var form = document.querySelector('form[action="posts/editCardSettingPost.php"]');

        // 表单验证
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return false;
        }

        // 获取按钮并禁用
        var btn = document.getElementById('editCardSettingPost');
        var originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> 提交中...';

        // 使用 FormData 处理文件上传
        var formData = new FormData(form);

        // AJAX提交
        $.ajax({
            url: 'posts/editCardSettingPost.php',
            type: 'POST',
            data: formData,
            processData: false,  // 不处理数据
            contentType: false,  // 不设置内容类型
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message || '修改失败，请重试！');
                }

                // 恢复按钮
                btn.disabled = false;
                btn.innerHTML = originalText;
            },
            error: function(xhr, status, error) {
                alert('网络错误，请检查网络连接！');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });

        return false;
    }
</script>