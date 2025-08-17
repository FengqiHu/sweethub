<?php
session_start();
include_once '../Component/Nav.php';
include_once '../dbConfig/Database.php';
include_once '../Function.php';

$nub = "select count(id) as shu from leaving";
$res = mysqli_query($connect, $nub);
$leav = mysqli_fetch_array($res);
$shu = $leav['shu'];

$stmt = $conn->prepare("SELECT id, name, text, ip, city, UNIX_TIMESTAMP(updated_time) FROM leaving ORDER BY id DESC");
$result = $stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $name, $text, $ip, $city, $updated_time);
if (!$result)
    echo "错误信息：" . $stmt->error;

?>


<link href="/admin/assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css"/>
<link href="/admin/assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css"/>
<link href="/admin/assets/css/vendor/buttons.bootstrap4.css" rel="stylesheet" type="text/css"/>
<link href="/admin/assets/css/vendor/select.bootstrap4.css" rel="stylesheet" type="text/css"/>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="text-lg-right">
                            <a class="fabu" href="leavingSetting.php">
                                <button type="button" class="btn btn-success mb-2 mr-2"><i
                                            class=" mdi mdi-brightness-5"></i> 留言相关设置
                                </button>
                            </a>
                        </div>
                    </div><!-- end col-->
                </div>
                <h4 class="header-title mb-3 size_18">留言管理
                    <button type="button" class="btn btn-secondary btn-sm btn-rounded margin_left">
                        共 <b><?php echo $leav['shu'] ?></b> 条
                    </button>
                </h4>
                <table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th style="width: 40%;">留言内容</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>IP</th>
                        <th style="width: 125px;">Action</th>
                    </tr>
                    </thead>

                    <form class="needs-validation" action="../posts/articleUpdatePost.php" method="post">
                        <tbody>
                        <?php
                        $SerialNumber = 0;
                        while ($stmt->fetch()) {
                            $SerialNumber++;
                            ?>
                            <tr>
                                <td>
                                    <div class="SerialNumber">
                                        <?php echo $SerialNumber ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-content" data-id="<?php echo $id ?>">
                                        <div class="text-preview" id="preview-<?php echo $id ?>">
                                            <?php
                                            $displayText = htmlspecialchars($text);
                                            if (mb_strlen($text) > 40) {
                                                echo mb_substr($displayText, 0, 40) . '...';
                                            } else {
                                                echo $displayText;
                                            }
                                            ?>
                                        </div>
                                        <div class="text-full" id="full-<?php echo $id ?>" style="display: none;">
                                            <?php echo nl2br(htmlspecialchars($text)) ?>
                                        </div>
                                        <?php if (mb_strlen($text) > 40): ?>
                                            <button type="button" class="btn btn-link btn-sm p-0 mt-1 toggle-text"
                                                    data-id="<?php echo $id ?>"
                                                    onclick="toggleText(<?php echo $id ?>)">
                                                <span id="btn-text-<?php echo $id ?>">展开全文</span>
                                                <i class="mdi mdi-chevron-down" id="btn-icon-<?php echo $id ?>"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted"><?php echo date('Y-m-d H:i:s', $updated_time) ?>
                                        <div class="color"><?php echo time_tran($updated_time) ?></div>
                                    </small>
                                </td>
                                <td>
                                    <h5><span class="badge badge-success-lighten"><i
                                                    class="mdi mdi-account-circle mr-1 rihjt-0"></i>
                                                <?php echo $name ?></span>
                                    </h5>
                                </td>
                                <td>
                                    <h5>
                                            <span
                                                    class="badge badge-danger-lighten"><?php echo $ip ? $ip : '127.0.0.1'; ?></span>
                                    </h5>
                                    <i><?php echo $city ? $city : '未知'; ?></i>
                                </td>
                                <td>
                                    <a href="javascript:del(<?php echo $id; ?>,'<?php echo addslashes(htmlspecialchars($text)); ?>');">
                                        <button style="white-space: nowrap;" type="button"
                                                class="btn btn-danger btn-rounded">
                                            <i class=" mdi mdi-delete-empty mr-1"></i>删除
                                        </button>
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                </table>
                </form>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>


<style>
    .table td,
    .table th {
        white-space: nowrap;
    }

    /* 留言内容列特殊处理 - 提高优先级 */
    #basic-datatable td:nth-child(2),
    .table td:nth-child(2) {
        white-space: normal !important;
        word-break: break-word;
        word-wrap: break-word;
        max-width: 400px;
        min-width: 200px;
    }

    .text-content {
        position: relative;
        white-space: normal !important;
    }

    .text-preview {
        line-height: 1.5;
        white-space: normal !important;
    }

    .text-full {
        line-height: 1.5;
        white-space: normal !important;
        word-break: break-word;
        word-wrap: break-word;
        animation: fadeIn 0.3s ease-in;
    }

    /* 确保 nl2br 生成的 <br> 标签生效 */
    .text-full br {
        display: block;
        content: "";
        margin-top: 0;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .toggle-text {
        color: #6c757d;
        text-decoration: none;
        font-size: 0.875rem;
        transition: color 0.2s;
    }

    .toggle-text:hover {
        color: #007bff;
        text-decoration: none;
    }

    .toggle-text i {
        transition: transform 0.3s;
        display: inline-block;
    }

    .toggle-text.expanded i {
        transform: rotate(180deg);
    }

    /* DataTable 响应式模式下的处理 */
    .dtr-details .text-full {
        white-space: normal !important;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // 展开/收起文本
    function toggleText(id) {
        var preview = $('#preview-' + id);
        var full = $('#full-' + id);
        var btnText = $('#btn-text-' + id);
        var btn = $('button[data-id="' + id + '"]');

        if (preview.is(':visible')) {
            preview.fadeOut(200, function() {
                full.fadeIn(200);
                btnText.text('收起');
                btn.addClass('expanded');
            });
        } else {
            full.fadeOut(200, function() {
                preview.fadeIn(200);
                btnText.text('展开全文');
                btn.removeClass('expanded');
            });
        }
    }

    function del(id, text) {
        // 截取文本用于显示，避免确认框过长
        var displayText = text.length > 40 ? text.substring(0, 40) + '...' : text;

        if (confirm('您确认要删除 "' + displayText + '" 内容吗？')) {
            // 发送AJAX请求
            $.ajax({
                url: '../posts/deleteLeavingPost.php',
                type: 'GET',
                data: {id: id},
                dataType: 'text',
                success: function (response) {
                    response = response.trim(); // 去除可能的空格
                    if (response === 'success') {
                        toastr["success"]("删除内容成功！", "SweetHub");
                        // 从表格中移除该行
                        var row = $('a[href*="del(' + id + '"]').closest('tr');
                        $(row).fadeOut(400, function () {
                            $(this).remove();
                            // 更新序号
                            updateSerialNumbers();
                            // 更新总数
                            updateTotalCount();
                        });
                    } else if (response === 'error') {
                        toastr["error"]("删除内容失败！", "SweetHub");
                    } else if (response === 'invalid') {
                        toastr["error"]("参数错误！", "SweetHub");
                    } else if (response === 'unauthorized') {
                        toastr["error"]("非法操作，请先登录！", "SweetHub");
                        location.href = '../login.php';
                    } else {
                        toastr["error"]("未知错误！", "SweetHub");
                    }
                },
                error: function (xhr, status, error) {
                    alert('删除失败：网络错误或服务器异常');
                }
            });
        }
    }

    // 更新序号的函数
    function updateSerialNumbers() {
        $('.SerialNumber').each(function (index) {
            $(this).text(index + 1);
        });
    }

    // 更新总数的函数
    function updateTotalCount() {
        var currentCount = $('#basic-datatable tbody tr').length;
        $('.btn-secondary b').text(currentCount);
    }
</script>

<?php
include_once '../Component/Footer.php';
?>
<!-- third party js -->
<script src="/admin/assets/js/vendor/jquery.dataTables.min.js"></script>
<script src="/admin/assets/js/vendor/dataTables.bootstrap4.js"></script>
<script src="/admin/assets/js/vendor/dataTables.responsive.min.js"></script>
<script src="/admin/assets/js/vendor/responsive.bootstrap4.min.js"></script>
<script src="/admin/assets/js/vendor/dataTables.buttons.min.js"></script>
<script src="/admin/assets/js/vendor/buttons.bootstrap4.min.js"></script>
<script src="/admin/assets/js/vendor/buttons.html5.min.js"></script>
<script src="/admin/assets/js/vendor/buttons.flash.min.js"></script>
<script src="/admin/assets/js/vendor/buttons.print.min.js"></script>
<script src="/admin/assets/js/vendor/dataTables.keyTable.min.js"></script>
<script src="/admin/assets/js/vendor/dataTables.select.min.js"></script>
<!-- third party js ends -->
<!-- demo app -->
<script src="/admin/assets/js/pages/demo.datatable-init.js"></script>
<!-- end demo js-->
</body>

</html>