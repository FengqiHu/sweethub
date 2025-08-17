<?php
session_start();
include_once '../dbConfig/connect.php';

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] != '') {
    $id = intval($_POST['id']);
    $title = htmlspecialchars(trim($_POST['title']), ENT_QUOTES);
    $text = trim($_POST['content']);

    // 防止SQL注入
    $title = mysqli_real_escape_string($connect, $title);
    $text = mysqli_real_escape_string($connect, $text);

    $sql = "UPDATE article SET title = '$title', content = '$text' WHERE id = $id";
    $result = mysqli_query($connect, $sql);

    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    header('HTTP/1.1 401 Unauthorized');
    echo "unauthorized";
}
?>