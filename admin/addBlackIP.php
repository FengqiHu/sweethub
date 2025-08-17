<?php
session_start();
include_once 'Nav.php';

$ipchaxun = "select * from black_ip";
$ipres = mysqli_query($connect, $ipchaxun);
$IPinfo = mysqli_fetch_array($ipres);
?>

<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">IP封禁拉黑添加</h4>

                <form class="needs-validation" id="addBlackIpForm" action="posts/addBlackIpPost.php" method="post"
                      onsubmit="return false;" novalidate>
                    <div class="form-group mb-3">
                        <label for="validationCustom05">IP地址</label>
                        <input type="text" class="form-control" id="validationCustom05" placeholder="请输入需封禁的IP"
                               name="ip" value="" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom05">信息备注</label>
                        <input type="text" class="form-control" id="validationCustom05"
                               placeholder="备注IP封禁情况(被封禁的IP会显示此备注内容)" name="note" value="" required>
                    </div>
                    <div class="form-group mb-3 text_right">
                        <button class="btn btn-primary" type="button" id="ipAddPost">提交添加</button>
                    </div>
                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<?php
include_once 'Footer.php';
?>

</body>
</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // 配置 Toastr（可选）
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        $("#ipAddPost").click(function () {
            // 获取表单
            var form = document.getElementById('addBlackIpForm');

            // 表单验证
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return false;
            }

            // 获取输入值
            var ip = $("input[name='ip']").val().trim();
            var note = $("input[name='note']").val().trim();

            // IP格式验证（可选）
            var ipPattern = /^(\d{1,3}\.){3}\d{1,3}$/;
            if (!ipPattern.test(ip)) {
                toastr.error('请输入正确的IP地址格式！', 'SweetHub');
                return false;
            }

            // 禁用按钮，防止重复提交
            var btn = $(this);
            var originalText = btn.html();
            btn.prop('disabled', true);
            btn.html('<i class="mdi mdi-loading mdi-spin"></i> 提交中...');

            // AJAX提交
            $.ajax({
                url: "posts/addBlackIpPost.php",
                data: {
                    ip: ip,
                    note: note
                },
                type: "POST",
                dataType: "text",
                success: function (res) {
                    console.log("Response: ", res); // 调试用
                    res = res.trim();

                    if (res == "1") {
                        toastr.success("IP封禁成功！", "SweetHub");
                        // 清空表单
                        form.reset();
                        form.classList.remove('was-validated');
                        setTimeout(function () {
                            window.location.href = 'blackIpList.php';
                        }, 1000); // 添加延迟时间
                    } else if (res == "0") {
                        toastr.error("IP封禁失败！", "SweetHub");
                        // 恢复按钮
                        btn.prop('disabled', false);
                        btn.html(originalText);
                    } else if(res == "2"){
                        toastr.error("IP已存在！", "SweetHub");
                        // 恢复按钮
                        btn.prop('disabled', false);
                        btn.html(originalText);
                    } else if (res.includes('非法操作')) {
                        toastr.error('非法操作，请重新登录！', 'SweetHub');
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 1000);
                    } else {
                        toastr.error("未知错误！", "SweetHub");
                        // 恢复按钮
                        btn.prop('disabled', false);
                        btn.html(originalText);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Ajax error: ", error); // 调试用
                    toastr.error("网络错误，请稍后重试！", "SweetHub");
                    // 恢复按钮
                    btn.prop('disabled', false);
                    btn.html(originalText);
                }
            });
        });
    });
</script>