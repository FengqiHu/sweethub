<?php
session_start();
include_once '../Component/Nav.php';
?>

<link href="/admin/editormd/css/editormd.css" rel="stylesheet">
<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">新增文章</h4>

                <form class="needs-validation" m action="../posts/addArticlePost.php" method="post" onsubmit="return check()"
                      novalidate>
                    <div class="form-group col-sm-4">
                        <label for="validationCustom01">发布者Name</label>
                        <select class="form-control" id="example-select" name="author">
                            <option value="<?php echo $text['boy'] ?>"><?php echo $text['boy'] ?></option>
                            <option value="<?php echo $text['girl'] ?>"><?php echo $text['girl'] ?></option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="validationCustom01">标题</label>
                        <input type="text" class="form-control" id="validationCustom01" placeholder="请输入标题"
                               name="title" required>
                    </div>
                    <div id="test-editor">
                        <textarea name="content"></textarea>
                    </div>
                    <div class="form-group mb-3 text_right">
                        <button class="btn btn-primary" type="submit" id="addArticle">发布文章</button>
                    </div>
                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>


<script src="https://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="/admin/editormd/editormd.js"></script>
<script type="text/javascript">
    var editor; // 全局变量

    $(function () {
        // 初始化编辑器
        editor = editormd("test-editor", {
            htmlDecode: true,
            path: "/admin/editormd/lib/",
            imageUpload: true,
            imageFormats: ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
            imageUploadURL: "../posts/uploadArticleImgPost.php"
        });

        // 阻止表单默认提交
        $('form').on('submit', function(e) {
            e.preventDefault();
        });

        // AJAX 提交
        $('#addArticle').click(function() {
            // 验证
            if (!check()) {
                return false;
            }

            // 禁用按钮，防止重复提交
            $(this).prop('disabled', true).text('发布中...');

            $.ajax({
                url: '../posts/addArticlePost.php',
                type: 'POST',
                data: {
                    title: $('input[name="title"]').val(),
                    author: $('select[name="author"]').val(),
                    content: editor.getMarkdown()
                },
                dataType: 'text',
                success: function(response) {
                    if(response.trim() === 'success') {
                        alert("文章发布成功！");
                        window.location.href = 'articleList.php';
                    } else if(response.trim() === 'unauthorized') {
                        alert("请先登录！");
                        window.location.href = '../login.php';
                    } else {
                        alert("文章发布失败！");
                        $('#addArticle').prop('disabled', false).text('发布文章');
                    }
                },
                error: function(xhr, status, error) {
                    alert("网络错误，请稍后重试！");
                    console.error("Error: " + error);
                    $('#addArticle').prop('disabled', false).text('发布文章');
                }
            });
        });
    });

    // 验证函数
    function check() {
        var title = $('input[name="title"]').val().trim();
        var content = editor.getMarkdown().trim();

        if (title.length === 0) {
            alert("文章标题不能为空");
            return false;
        }

        if (content.length === 0) {
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