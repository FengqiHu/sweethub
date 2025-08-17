<?php
session_start();
include_once '../Component/Nav.php';
$lovelist = "select * from lovelist order by id desc";
$reslist = mysqli_query($connect, $lovelist);
?>

<link href="../assets/css/vendor/dataTables.bootstrap4.css" rel="stylesheet" type="text/css"/>
<link href="../assets/css/vendor/responsive.bootstrap4.css" rel="stylesheet" type="text/css"/>
<link href="../assets/css/vendor/buttons.bootstrap4.css" rel="stylesheet" type="text/css"/>
<link href="../assets/css/vendor/select.bootstrap4.css" rel="stylesheet" type="text/css"/>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">恋爱清单
                    <a href="/admin/LoveList/addLoveList.php">
                        <button type="button" class="btn btn-success btn-sm right_10">
                            <i class="mdi mdi-circle-edit-outline"></i>新增
                        </button>
                    </a>
                </h4>
                <table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
                    <thead>
                    <tr>
                        <th>序号</th>
                        <th>事件标题</th>
                        <th>完成状态</th>
                        <th>图片预览</th>
                        <th style="width:150px;">操作</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    $SerialNumber = 0;
                    while ($list = mysqli_fetch_array($reslist)) {
                        $SerialNumber++;
                        ?>
                        <tr>
                            <td>
                                <div class="SerialNumber">
                                    <?php echo $SerialNumber ?>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($list['eventName']) ?></td>
                            <td>
                                <?php if ($list['icon']) { ?>
                                    <span class="badge badge-success-lighten">
                                        <i class="mdi mdi-check-circle mr-1"></i>已完成
                                    </span>
                                <?php } else { ?>
                                    <span class="badge badge-danger-lighten">
                                        <i class="mdi mdi-clock-outline mr-1"></i>未完成
                                    </span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if ($list['imgurl'] && $list['imgurl'] != '0') { ?>
                                    <?php
                                    // 拼接完整的图片路径
                                    $fullImagePath = '/admin/static/listImg/' . $list['imgurl'];
                                    ?>
                                    <img src="<?php echo htmlspecialchars($fullImagePath); ?>"
                                         alt="<?php echo htmlspecialchars($list['eventName']); ?>"
                                         class="img-preview"
                                         onclick="showImageModal('<?php echo htmlspecialchars($fullImagePath); ?>')"
                                         onerror="this.onerror=null; this.src='/admin/assets/images/no-image.png';">
                                <?php } else { ?>
                                    <span class="no-image">暂无图片</span>
                                <?php } ?>
                            </td>
                            <td>
                                <a href="editLoveList.php?id=<?php echo $list['id'] ?>&icon=<?php echo $list['icon'] ?>&name=<?php echo urlencode($list['eventName']) ?>&imgurl=<?php echo urlencode($list['imgurl']); ?>">
                                    <button type="button" class="btn btn-secondary btn-rounded btn-sm">
                                        <i class="mdi mdi-clipboard-text-play-outline mr-1"></i>修改
                                    </button>
                                </a>
                                <a href="javascript:void(0);" onclick="del(<?php echo $list['id']; ?>,'<?php echo addslashes(htmlspecialchars($list['eventName'])); ?>');">
                                    <button type="button" class="btn btn-danger btn-rounded btn-sm">
                                        <i class="mdi mdi-delete-empty mr-1"></i>删除
                                    </button>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- 图片预览模态框 -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="close-modal">&times;</span>
    <img class="image-modal-content" id="modalImage">
</div>

<script>
    // 显示图片模态框
    function showImageModal(imgUrl) {
        var modal = document.getElementById('imageModal');
        var modalImg = document.getElementById('modalImage');
        modal.style.display = "block";
        modalImg.src = imgUrl;
    }

    // 关闭图片模态框
    function closeImageModal() {
        document.getElementById('imageModal').style.display = "none";
    }

    function del(id, text) {
        if (confirm('您确认要删除 "' + text + '" 内容吗？')) {
            $.ajax({
                url: '../posts/deleteLoveListPost.php',
                type: 'GET',
                data: {id: id},
                dataType: 'text',
                success: function (response) {
                    response = response.trim();
                    if (response === 'success') {
                        toastr["success"]("删除内容成功！", "SweetHub");

                        // 删除行
                        var row = $('a[onclick*="del(' + id + '"]').closest('tr');
                        $(row).remove();

                        // 更新序号
                        updateSerialNumbers();
                        updateTotalCount();

                        // 重新初始化 DataTable
                        $('#basic-datatable').DataTable();
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
</script>
<!-- 添加图片预览的样式 -->
<style>
    .img-preview {
        max-width: 80px;
        max-height: 30px;
        object-fit: cover;
        border-radius: 4px;
        cursor: pointer;
        transition: transform 0.2s;
    }
    .img-preview:hover {
        transform: scale(1.1);
    }

    /* 图片弹窗样式 */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.8);
        cursor: pointer;
    }

    .image-modal-content {
        display: block;
        max-width: 90%;
        max-height: 90%;
        margin: auto;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .close-modal {
        position: absolute;
        top: 20px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
    }

    .close-modal:hover,
    .close-modal:focus {
        color: #bbb;
    }

    .no-image {
        color: #999;
        font-style: italic;
    }
</style>
<?php include_once '../Component/Footer.php'; ?>

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
<script src="../assets/js/pages/demo.datatable-init.js"></script>

</body>
</html>