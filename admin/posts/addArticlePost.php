<?php
session_start();
include_once '../dbConfig/connect.php';

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] != '') {
    $title = htmlspecialchars(trim($_POST['title']), ENT_QUOTES);
    $content = trim($_POST['content']);
    $author = trim($_POST['author']);

    // 防止SQL注入
    $title = mysqli_real_escape_string($connect, $title);
    $content = mysqli_real_escape_string($connect, $content);
    $author = mysqli_real_escape_string($connect, $author);

    $sql = "INSERT INTO article (title, content, author) VALUES ('$title', '$content', '$author')";
    $result = mysqli_query($connect, $sql);

    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "unauthorized";
}
?>