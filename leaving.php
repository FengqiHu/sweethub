<?php
include_once 'admin/dbConfig/connect.php';
include_once 'admin/dbConfig/Database.php';

$nub = "select count(id) as shu from leaving";
$res = mysqli_query($connect, $nub);
$leav = mysqli_fetch_array($res);
$shu = $leav['shu'];
$leavSet = "select * from leavSet order by id desc";
$Set = mysqli_query($connect, $leavSet);
$Setinfo = mysqli_fetch_array($Set);
$jiequ = $Setinfo['jiequ'];

$liuyan = "SELECT * FROM leaving order by id desc limit ?";
$stmt = $conn->prepare($liuyan);
$stmt->bind_param("i", $jiequ);
$jiequ = $Setinfo['jiequ'];
$stmt->bind_result($id, $name, $text, $ip, $city, $updated_time);
$result = $stmt->execute();
if (!$result)
    echo "错误信息：" . $stmt->error;

include_once 'head.php';
?>

<head>
    <meta charset="utf-8" />
    <title><?php echo $text['title'] ?> — <?php echo $text['card2'] ?></title>
</head>

<body>

<div id="pjax-container">
    <div class="MessageButtonCard" id="MessageBtn">
        <svg t="1730880204691" class="Message-Icon icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="13875" width="200" height="200"><path d="M512 96C229.2 96 0 282.3 0 512c0 92.1 36.8 177.1 99.1 246 4 4.5 5.3 10.9 3.1 16.5-5.7 14.7-12 29.2-19 43.3-12.9 26.3-28.2 51.7-45.3 75.5-6.2 8.6-7.7 19.7-4.1 29.6 3.6 10 11.9 17.5 22.2 20.1 9.4 2.4 25.2 5.4 44.8 5.4 26 0 58.7-5.4 91.5-25 21.4-12.8 37.5-28.6 49.3-44 4.5-5.9 12.5-7.9 19.3-4.8 74.2 34 159.8 53.4 251 53.4 282.8 0 512-186.3 512-416S794.8 96 512 96z m192 464c-30.9 0-56-25.1-56-56s25.1-56 56-56 56 25.1 56 56-25.1 56-56 56z m-440-56c0-30.9 25.1-56 56-56s56 25.1 56 56-25.1 56-56 56-56-25.1-56-56z m192 0c0-30.9 25.1-56 56-56s56 25.1 56 56-25.1 56-56 56-56-25.1-56-56z" p-id="13876"></path></svg>
    </div>
    <div class="central central-800 bg">
        <div class="title mt-2rem">
            <h1><?php echo $text['deci2'] ?></h1>
        </div>
        <h3>已收到 <b><?php echo $leav['shu'] ?></b> 条祝福留言<i class="jiequ">（显示最新 <?php echo $jiequ ?>条）</i></h3>

        <div class="row">
            <div class="card col-lg-12 col-md-12 col-sm-12 col-sm-x-12">
                <?php
                $messageIndex = 0;
                while ($stmt->fetch()) {
                    $messageIndex++;
                    // 检查文本是否需要展开按钮（简单判断：超过100个字符或包含3个以上换行）
                    $needToggle = mb_strlen($text) > 100 || substr_count($text, "\n") >= 3;
                    ?>
                    <div class="leavform <?php if ($Animation == "1") { ?>animated fadeInUp delay-03s<?php } ?>">
                        <div class="textinfo">
                            <div class="MsgTopInfo">
                                <i class="time">
                                    <?php echo time_tran($updated_time) ?> <b class="yuan"></b>
                                    <?php echo $city ? $city : '未知'; ?>
                                </i>
                            </div>
                            <div class="user_info">
                                <span class="name"><?php echo $name ?></span>
                            </div>
                            <div class="text" id="text-container-<?php echo $messageIndex ?>">
                                <?php if ($needToggle): ?>
                                    <div class="text-wrapper collapsed" id="text-wrapper-<?php echo $messageIndex ?>">
                                        <div class="text-content">
                                            <?php echo nl2br(htmlspecialchars($text)) ?>
                                        </div>
                                    </div>
                                    <button type="button" class="toggle-btn" onclick="toggleMessage(<?php echo $messageIndex ?>)" id="toggle-btn-<?php echo $messageIndex ?>">
                                        <span>展开全文</span>
                                        <i class="mdi mdi-chevron-down"></i>
                                    </button>
                                <?php else: ?>
                                    <div class="text-short">
                                        <?php echo nl2br(htmlspecialchars($text)) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <form action="admin/posts/leavingPost.php" method="post">
                    <div class="inputbox" id="MessageArea">
                        <input id="name" name="name" type="text" placeholder="请输入您的名字" class="let">
                    </div>
                    <textarea  id="wenben" name="text" rows="6" placeholder="请输入您的留言内容..."></textarea>
                    <div class="input-sub">
                        <button type="button" id="leavingPost" class="tijiao">提交留言
                            <svg style="width:1.3em;height: 1.3em;" t="1717899795089" class="icon"
                                 viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                 p-id="28276" width="200" height="200">
                                <path
                                        d="M620.8 179.2c12.8 12.8 6.4 32-6.4 44.8-19.2 6.4-38.4 6.4-44.8-12.8-44.8-70.4-128-115.2-217.6-115.2-140.8 0-256 115.2-256 256 0 89.6 44.8 166.4 115.2 217.6 19.2 6.4 19.2 25.6 12.8 38.4-12.8 19.2-32 19.2-44.8 12.8C89.6 563.2 32 460.8 32 352c0-179.2 140.8-320 320-320 108.8 0 211.2 57.6 268.8 147.2zM326.4 332.8l243.2 601.6 83.2-243.2c6.4-19.2 19.2-32 38.4-38.4L934.4 576 326.4 332.8z m25.6-57.6L960 518.4c32 12.8 51.2 51.2 38.4 83.2-6.4 19.2-19.2 32-38.4 38.4l-243.2 83.2L633.6 960c-12.8 32-44.8 51.2-83.2 38.4-19.2-6.4-32-19.2-38.4-38.4L268.8 358.4c-12.8-32 6.4-70.4 38.4-83.2 12.8-6.4 32-6.4 44.8 0z"
                                        fill="#ffffff" p-id="28277"></path>
                            </svg></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // 展开/收起留言内容
        function toggleMessage(index) {
            const wrapper = document.getElementById('text-wrapper-' + index);
            const btn = document.getElementById('toggle-btn-' + index);
            const btnText = btn.querySelector('span');

            if (wrapper.classList.contains('collapsed')) {
                // 展开
                wrapper.classList.remove('collapsed');
                wrapper.classList.add('expanded');
                btnText.textContent = '收起';
                btn.classList.add('expanded');
            } else {
                // 收起
                wrapper.classList.remove('expanded');
                wrapper.classList.add('collapsed');
                btnText.textContent = '展开全文';
                btn.classList.remove('expanded');
            }
        }

        $("#leavingPost").click(function () {
            let name  = $.trim($('#name').val());      // 姓名输入框
            let text  = $.trim($('#wenben').val());    // 留言 textarea
            if (text.length == 0) {
                toastr["warning"]("请填写您要留言的内容！", "Like_Girl");
                return false;
            } else if (text.length <= 2) {
                toastr["warning"]("请填写两个字符以上的内容！", "Like_Girl");
                return false;
            }else if (name.length >= 20) {
                toastr["warning"]("您的名字太长了！", "Like_Girl");
                return false;
            }
            let nonub = /^[0-9]+$/;
            // let filter = new RegExp("[<?php echo $Setinfo['lanjie'] ?>]");
            let weifan = new RegExp("[<?php echo $Setinfo['lanjiezf'] ?>]");
            if (nonub.test(text)) {
                toastr["warning"]("内容为纯数字 已被拦截！", "Like_Girl");
                return false;
            } else if (weifan.test(text)) {
                toastr["warning"]("您输入的内容是违禁词 <br/>请注意您的发言不文明的留言 <br/>会被管理员拉进小黑屋喔", "Like_Girl");
                return false;
            }

            if(name == null || name == ''){
                name = "匿名";
            }
            if (!text) {
                toastr["warning"]("留言信息不能为空 请先填写完整！", "Like_Girl");
                return false
            }
            $('#leavingPost').text('留言提交中...');
            $("#leavingPost").attr("disabled", "disabled");
            $.ajax({
                    url: "/admin/posts/leavingPost.php",
                    data: {
                        name: name,
                        text: text,
                    },
                    type: "POST",
                    dataType: "text",
                    success: function (res) {
                        setInterval(() => {
                            $('#leavingPost').removeAttr("disabled");
                        }, 5000);
                        if (res == 1) {
                            toastr["success"]("留言提交成功 请刷新本页查看！", "Like_Girl");
                            $('#leavingPost').text('留言成功');
                            // 清空表单
                            $('#name').val('');
                            $('#wenben').val('');
                            // 3秒后刷新页面
                            setTimeout(function() {
                                location.reload();
                            }, 3000);
                        } else if (res == 0) {
                            toastr["error"]("留言提交失败！", "Like_Girl");
                            $('#leavingPost').text('留言失败');
                        } else if (res == 4 || res == 40) {
                            toastr["error"]("留言失败——IP格式错误 ", "Like_Girl");
                            $('#leavingPost').text('留言失败');
                        } else if (res == 5 || res == 50) {
                            toastr["error"]("留言失败——参数错误", "Like_Girl");
                            $('#leavingPost').text('留言失败');
                        } else if (res == 9) {
                            toastr["error"]("留言失败——你今天留言次数太多了~", "Like_Girl");
                            $('#leavingPost').text('留言失败');
                        } else {
                            toastr["error"]("未知错误！", "Like_Girl");
                        }
                    },
                    error: function (err) {
                        toastr["error"]("网络错误 请稍后重试！", "Like_Girl");
                    }
                }
            )
        })
        function loadingname() {
            $('body').loading({
                loadingWidth: 240,
                title: '获取昵称头像中',
                name: 'test',
                discription: '请稍等片刻',
                direction: 'column',
                type: 'origin',
                originDivWidth: 40,
                originDivHeight: 40,
                originWidth: 6,
                originHeight: 6,
                smallLoading: false,
                loadingMaskBg: 'rgba(0,0,0,0.2)'
            });

        }
    </script>
</div>
<style>
    /* 留言文本样式 */
    .text {
        position: relative;
        line-height: 1.6;
        word-break: break-word;
        word-wrap: break-word;
    }

    /* 文本容器 */
    .text-wrapper {
        position: relative;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    /* 预览状态 */
    .text-wrapper.collapsed {
        max-height: 3.2em; /* 约2行文字的高度 */
    }

    /* 展开状态 */
    .text-wrapper.expanded {
        max-height: none;
    }

    /* 文本内容 */
    .text-content {
        line-height: 1.6;
        word-break: break-word;
    }

    /* 渐变遮罩 - 仅在折叠状态显示 */
    .text-wrapper.collapsed::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1.6em;
        background: linear-gradient(to bottom,
        rgba(255,255,255,0) 0%,
        rgba(255,255,255,0.7) 50%,
        rgba(255,255,255,0.9) 100%);
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    /* 鼠标悬停时淡化遮罩 */
    .leavform:hover .text-wrapper.collapsed::after {
        opacity: 0.0;
    }

    /* 展开/收起按钮 */
    .toggle-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-top: 8px;
        padding: 6px 16px;
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 20px;
        color: #666;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .toggle-btn:hover {
        background: #fff;
        border-color: #999;
        color: #333;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        transform: translateY(-1px);
    }

    .toggle-btn:active {
        transform: translateY(0);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .toggle-btn i {
        margin-left: 4px;
        transition: transform 0.3s ease;
        display: inline-block;
        line-height: 1;
        font-size: 16px;
    }

    .toggle-btn.expanded i {
        transform: rotate(180deg);
    }

    /* 留言卡片悬停效果 */
    .leavform {
        margin-bottom: 20px;
        padding: 15px;
        border-bottom: 1px solid #eee;
        transition: background-color 0.2s ease, transform 0.2s ease;
        border-radius: 8px;
    }

    .leavform:hover {
        background-color: rgba(248, 249, 250, 0.5);
    }

    .leavform:last-child {
        border-bottom: none;
    }

    /* 不需要展开按钮的短文本 */
    .text-short {
        line-height: 1.6;
        word-break: break-word;
    }

    /* 优化文本选择效果 */
    .text-content::selection {
        background-color: #b3d4fc;
        color: #000;
    }

    .text-content::-moz-selection {
        background-color: #b3d4fc;
        color: #000;
    }
</style>

<?php
include_once 'footer.php';
?>


</body>

</html>