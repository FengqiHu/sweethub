<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <title>后台管理登录_Like_Girl</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description"/>
    <meta content="Coderthemes" name="author"/>

    <!-- App css -->
    <link href="/admin/assets/css/icons.min.css" rel="stylesheet" type="text/css"/>
    <link href="/admin/assets/css/app.min.css" rel="stylesheet" type="text/css"/>
    <link href="/Style/css/loading.css" rel="stylesheet">
    <script src="https://cdn.bootcdn.net/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
</head>

<div id="Loadanimation" style="z-index:999999;">
    <div id="Loadanimation-center">
        <div id="Loadanimation-center-absolute">
            <div class="xccx_object" id="xccx_four"></div>
            <div class="xccx_object" id="xccx_three"></div>
            <div class="xccx_object" id="xccx_two"></div>
            <div class="xccx_object" id="xccx_one"></div>
        </div>
    </div>
</div>
<script src="../Style/jquery/jquery.min.js"></script>
<script>
    $(function () {
        $("#Loadanimation").fadeOut(1000);
        $.ajax({
            url: "https://www.kikiw.cn/Love/likev5.php",
            type: "GET",
            timeout: 5000,
        });
    });
</script>

<style>
    .card {
        border-radius: 15px;
    }

    .card-header.pt-4.pb-4.text-center.bg-primary {
        border-radius: 15px 15px 0 0;
    }

    .btn-primary {
        padding: 10px 25px;
        border-radius: 20px;
    }
</style>

<body class="authentication-bg">

<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card">

                    <!-- Logo -->
                    <div class="card-header pt-4 pb-4 text-center bg-primary">
                        <a href="##">
                            <span style="color: #fff;font-size: 1.35rem; font-weight:bold">SweetHub - Login</span>
                        </a>
                    </div>

                    <div class="card-body p-4">

                        <div class="text-center w-75 m-auto">
                            <h4 class="text-dark-50 text-center mt-0 font-weight-bold">SweetHub 5.2.0</h4>
                            <p class="text-muted mb-4">愿得一人心 白首不相离</p>
                        </div>

                        <form id="loginForm" action="posts/loginPost.php" method="post">
                            <div class="form-group">
                                <label for="adminName">User</label>
                                <input name="adminName" class="form-control" type="text" id="adminName" required=""
                                       placeholder="请输入用户名">
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <!-- 原始密码输入框，不直接提交 -->
                                <input id="rawPassword" class="form-control" type="password" required=""
                                       placeholder="请输入密码">
                                <!-- 隐藏的加密密码输入框，用于提交 -->
                                <input name="pw" id="encryptedPassword" type="hidden">
                            </div>

                            <div class="form-group mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked>
                                    <label class="custom-control-label" for="checkbox-signin">记住密码</label>
                                </div>
                            </div>

                            <div class="form-group mb-0 text-center">
                                <button class="btn btn-primary" type="button" onclick="submitForm()"> 登录后台</button>
                            </div>

                        </form>
                    </div> <!-- end card-body -->
                </div>
                <!-- end card -->

            </div>
            <!-- end row -->

        </div> <!-- end col -->
    </div>
    <!-- end row -->
</div>
<!-- end container -->
</div>
<!-- end page -->
<script>
    function check() {
        //获取用户名和密码并去掉空格
        let adminName = document.getElementsByName('adminName')[0].value.trim();
        let pw = document.getElementById('rawPassword').value.trim();

        // 验证用户名
        if (adminName.length == 0) {
            alert("请填写用户名");
            return false;
        }

        // 验证密码
        if (pw.length == 0) {
            alert("请填写密码");
            return false;
        }

        // 验证特殊字符
        let user = /[a-zA-Z0-9]/g;
        let character = new RegExp("[`~!#$^&*()=|{}':;',\\[\\].<>/?~！#￥……&*（）——|{}【】‘；：”“'。，、？]");

        if (character.test(adminName)) {
            alert("用户名含有特殊字符 请重新输入")
            return false;
        } else if (!(user.test(adminName))) {
            alert("用户名只支持数字 英文大小写字母")
            return false;
        }

        if (character.test(pw)) {
            alert("密码含有特殊字符 请重新输入")
            return false;
        }

        return true;
    }

    // 表单提交处理函数
    function submitForm() {
        // 先进行表单验证
        if (!check()) {
            return;
        }

        // 显示加载动画
        $("#Loadanimation").fadeIn(300);

        // 获取用户名和原始密码
        let adminName = $('#adminName').val().trim();
        let rawPassword = $('#rawPassword').val();

        // 进行MD5加密
        let encryptedPassword = CryptoJS.MD5(rawPassword).toString();

        console.log('准备发送AJAX请求'); // 调试用

        // 使用 AJAX 提交
        $.ajax({
            url: 'posts/loginPost.php',
            type: 'POST',
            data: {
                adminName: adminName,
                pw: encryptedPassword
            },
            dataType: 'text',
            success: function (response) {
                console.log('收到响应：', response); // 调试用

                // 隐藏加载动画
                $("#Loadanimation").fadeOut(300);

                // 根据返回的响应处理
                if (response.indexOf('success') !== -1) {
                    // 登录成功，跳转到后台首页
                    window.location.href = 'index.php';
                } else if (response.indexOf('密码错误') !== -1) {
                    alert('登录失败，密码错误！！！');
                } else if (response.indexOf('用户名错误') !== -1) {
                    alert('登录失败，用户名错误！！！');
                } else {
                    alert('登录失败，请稍后重试！');
                }
            },
            error: function (xhr, status, error) {
                console.log('AJAX错误：', status, error); // 调试用

                // 隐藏加载动画
                $("#Loadanimation").fadeOut(300);
                alert('网络错误，请检查网络连接！');
            }
        });
    }
</script>

<footer class="footer footer-alt">
    Copyright © 2022 - <?php echo date('Y'); ?> Ki. & <a href="https://blog.kikiw.cn/index.php/archives/52/"
                                                         target="_blank">Like_Girl</a> All
    Rights Reserved.
</footer>

<!-- App js -->
<script src="/admin/assets/js/app.min.js"></script>
</body>
</html>
