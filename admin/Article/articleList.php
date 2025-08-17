<?php
session_start();

include_once '../Component/Nav.php';
$article = "select * from article order by id desc";
$resarticle = mysqli_query($connect, $article);
?>

<link href="/admin/assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css"/>
<link href="/admin/assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css"/>
<link href="/admin/assets/css/vendor/buttons.bootstrap4.css" rel="stylesheet" type="text/css"/>
<link href="/admin/assets/css/vendor/select.bootstrap4.css" rel="stylesheet" type="text/css"/>
<!-- third party css end -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="header-title mb-3 size_18">点点滴滴
                    <a class="fabu" href="addArticle.php">
                        <button type="button" class="btn btn-success btn-sm right_10">
                            <i class="mdi mdi-circle-edit-outline"></i>新增
                        </button>
                    </a></h4>
                <table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>标题</th>
                        <th>发布时间</th>
                        <th>发布者</th>
                        <th style="width:150px;">操作</th>
                    </tr>
                    </thead>

                    <form class="needs-validation" action="../posts/articleUpdatePost.php" method="post">
                        <tbody>
                        <?php
                        $SerialNumber = 0;
                        while ($info = mysqli_fetch_array($resarticle)) {
                            $SerialNumber++;
                            ?>
                            <tr>
                                <td>
                                    <div class="SerialNumber">
                                        <?php echo $SerialNumber ?>
                                    </div>
                                </td>
                                <td><?php echo $info['title'] ?></td>
                                <td><?php echo $info['updated_time'] ?></td>
                                <td><?php echo $info['author'] ?></td>
                                <td>
                                    <a href="editArticle.php?id=<?php echo $info['id'] ?>">
                                        <button type="button" class="btn btn-secondary btn-rounded">
                                            <i class=" mdi mdi-clipboard-text-play-outline mr-1"></i>修改
                                        </button>
                                    </a>
                                    <a href="javascript:del(<?php echo $info['id']; ?>,'<?php echo $info['title']; ?>');">
                                        <button type="button" class="btn btn-danger btn-rounded delete-btn"
                                                data-id="<?php echo $info['id']; ?>"
                                                data-title="<?php echo htmlspecialchars($info['title'], ENT_QUOTES); ?>">
                                            <i class="mdi mdi-delete-empty mr-1"></i>删除
                                        </button>
                                    </a>
                                    <input name="id" value="<?php echo $info['id']; ?>" type="hidden">

                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                </table>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        // 使用事件委托
        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var title = $(this).data('title');

            if (confirm('您确认要删除标题为 "' + title + '" 的文章吗？')) {
                $.ajax({
                    url: '../posts/deleteArticlePost.php',
                    type: 'POST',
                    data: { id: id },
                    dataType: 'text',
                    success: function(response) {
                        if (response.trim() === 'success') {
                            //从表格中移走
                            // 从表格中移除该行
                            var row = $('a[href*="del(' + id + '"]').closest('tr');
                            $(row).fadeOut(400, function () {
                                $(this).remove();
                                // 更新序号
                                updateSerialNumbers();
                                // 更新总数
                                updateTotalCount();
                            });
                            toastr["success"]("文章删除成功！", "SweetHub");
                            // setTimeout(function() {
                            //     location.href = 'articleList.php';
                            // }, 1500);
                        } else if (response.trim() === 'unauthorized') {
                            alert('请先登录！');
                            window.location.href = '../login.php';
                        } else {
                            showAlert('文章删除失败！', 'danger');
                        }
                    },
                    error: function() {
                        showAlert('网络错误，请稍后重试！', 'danger');
                    }
                });
            }
        });
    });

    // 更新序号
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

    // 显示提示信息
    function showAlert(message, type) {
        var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
            message +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span>' +
            '</button>' +
            '</div>';

        if ($('.alert-container').length === 0) {
            $('.card').before('<div class="alert-container"></div>');
        }
        $('.alert-container').html(alertHtml);

        setTimeout(function() {
            $('.alert').alert('close');
        }, 3000);
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