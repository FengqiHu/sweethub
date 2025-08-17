<?php
session_start();
include_once '../Component/Nav.php';
$loveImg = "select * from loveImg order by id desc";
$resImg = mysqli_query($connect, $loveImg);
?>


<link href="../assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css"/>
<link href="../assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css"/>
<link href="../assets/css/vendor/buttons.bootstrap4.css" rel="stylesheet" type="text/css"/>
<link href="../assets/css/vendor/select.bootstrap4.css" rel="stylesheet" type="text/css"/>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">恋爱相册<a href="addLoveImg.php">
                        <button type="button" class="btn btn-success btn-sm right_10">
                            <i class="mdi mdi-circle-edit-outline"></i>新增
                        </button>
                    </a></h4>
                <table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>图片描述</th>
                        <th>日期</th>
                        <th style="width:150px;">操作</th>
                    </tr>
                    </thead>


                    <tbody>
                    <?php
                    $SerialNumber = 0;
                    while ($list = mysqli_fetch_array($resImg)) {
                        $SerialNumber++;
                        ?>
                        <tr>
                            <td>
                                <div class="SerialNumber">
                                    <?php echo $SerialNumber ?>
                                </div>
                            </td>
                            <td><?php echo $list['imgText'] ?></td>
                            <td><?php echo $list['imgDatd'] ?></td>
                            <td>
                                <a href="editLoveImg.php?id=<?php echo $list['id'] ?>">
                                    <button type="button" class="btn btn-secondary btn-rounded">
                                        <i class=" mdi mdi-clipboard-text-play-outline mr-1"></i>修改
                                    </button>
                                </a>
                                <a href="javascript:del(<?php echo $list['id']; ?>,'<?php echo $list['imgText']; ?>');">
                                    <button type="button" class="btn btn-danger btn-rounded">
                                        <i class=" mdi mdi-delete-empty mr-1"></i>删除
                                    </button>
                                </a></td>
                        </tr>
                    <?php
                    }
                    ?>
                    </tbody>
                </table>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>


<script>
    function del(id, imgText) {
        if (confirm('您确认要删除 "' + imgText + '" 的相册图片吗？')) {
            // 发送AJAX请求
            $.ajax({
                url: '../posts/deleteImgPost.php',
                type: 'GET',
                data: {id: id},
                dataType: 'text',
                success: function (response) {
                    response = response.trim(); // 去除可能的空格
                    if (response === 'success') {
                        toastr["success"]("删除相册成功！", "SweetHub");
                        // 从表格中移除该行
                        var row = $('a[href*="del(' + id + '"]').closest('tr');
                        $(row).fadeOut(400, function () {
                            $(this).remove();
                            // 更新序号
                            updateSerialNumbers();
                            updateTotalCount();

                        });
                    } else if (response === 'error') {
                        toastr["error"]("删除相册失败！", "SweetHub");
                    } else if (response === 'invalid') {
                        toastr["error"]("参数错误！", "SweetHub");
                    } else if (response === 'unauthorized') {
                        toastr["error"]("非法操作，请先登录！", "SweetHub");
                        setTimeout(function() {
                            location.href = '../login.php';
                        }, 1500);
                    } else {
                        toastr["error"]("未知错误！", "SweetHub");
                    }
                },
                error: function (xhr, status, error) {
                    toastr["error"]("删除失败：网络错误或服务器异常", "SweetHub");
                }
            });
        }
    }

    // 更新序号
    function updateSerialNumbers() {
        $('.SerialNumber').each(function(index) {
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
<script src="../assets/js/vendor/jquery.dataTables.min.js"></script>
<script src="../assets/js/vendor/dataTables.bootstrap4.js"></script>
<script src="../assets/js/vendor/dataTables.responsive.min.js"></script>
<script src="../assets/js/vendor/responsive.bootstrap4.min.js"></script>
<script src="../assets/js/vendor/dataTables.buttons.min.js"></script>
<script src="../assets/js/vendor/buttons.bootstrap4.min.js"></script>
<script src="../assets/js/vendor/buttons.html5.min.js"></script>
<script src="../assets/js/vendor/buttons.flash.min.js"></script>
<script src="../assets/js/vendor/buttons.print.min.js"></script>
<script src="../assets/js/vendor/dataTables.keyTable.min.js"></script>
<script src="../assets/js/vendor/dataTables.select.min.js"></script>
<!-- third party js ends -->
<!-- demo app -->
<script src="../assets/js/pages/demo.datatable-init.js"></script>
<!-- end demo js-->
</body>
</html>