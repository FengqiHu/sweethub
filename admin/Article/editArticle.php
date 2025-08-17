<?php
session_start();
include_once '../Component/Nav.php';
$id = $_GET['id'];
$article = "SELECT * FROM article WHERE id=$id limit 1";
$resarticle = mysqli_query($connect, $article);
$mod = mysqli_fetch_array($resarticle);
?>

<link href="/admin/editormd/css/editormd.css" rel="stylesheet">
<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">修改文章—— <?php echo $mod['title'] ?></h4>

                <form class="needs-validation" action="../posts/articleUpdatePost.php" method="post" onsubmit="return check()"
                      novalidate>
                    <div class="form-group mb-3">
                        <label for="validationCustom01">标题</label>
                        <input name="title" type="text" class="form-control" id="validationCustom01"
                               placeholder="请输入标题" value="<?php echo $mod['title'] ?>" required>
                    </div>
                    <div id="test-editor">
                        <textarea name="content"><?php echo $mod['content'] ?></textarea>
                    </div>
                    <div class="form-group mb-3 text_right">
                        <input name="id" value="<?php echo $id ?>" type="hidden">
                        <button class="btn btn-primary" type="button" id="updateArticle">修改发布</button>
                    </div>
                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<!--<script src="https://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>-->
<script src="../js/jquery.min.js"></script>
<script src="/admin/editormd/editormd.js"></script>
<script type="text/javascript">
    var editor;
    $(function () {
        editor = editormd("test-editor", {
            htmlDecode: true,
            path: "/admin/editormd/lib/",
            imageUpload: true, // 开启图片上传
            imageFormats: ["jpg", "jpeg", "gif", "png", "bmp", "webp"], // 允许的格式
            imageUploadURL: "../posts/uploadArticleImgPost.php" // 上传处理接口
        });

        // 阻止表单默认提交
        $('form').on('submit', function(e) {
            e.preventDefault();
        });

        // AJAX 提交
        $('#updateArticle').click(function() {
            if (!check()) {
                return false;
            }

            $.ajax({
                url: '../posts/articleUpdatePost.php',
                type: 'POST',
                data: {
                    id: $('input[name="id"]').val(),
                    title: $('input[name="title"]').val(),
                    content: editor.getMarkdown()
                },
                success: function(response) {
                    alert("提交修改成功！");
                    window.location.href = 'articleList.php';
                },
                error: function() {
                    alert("网络错误，请稍后重试！");
                }
            });
        });

    });

    // 定义 check 函数
    function check() {
        let title = $('input[name="title"]').val().trim();
        let content = editor.getMarkdown().trim();

        if (title.length == 0) {
            alert("文章标题不能为空");
            return false;
        } else if (content.length == 0) {
            alert("文章内容不能为空");
            return false;
        }
        return true;
    }
</script>

<?php
include_once '../Component/Footer.php';
?>

</body>
</html>