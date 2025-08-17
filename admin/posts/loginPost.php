<?php
session_start();
@($user = $_POST['adminName']);
@($pw = $_POST['pw']);
include_once "../dbConfig/Database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "select * from login where user =?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $USER);
    $USER = mysqli_real_escape_string($conn, $user);
    $PW = $pw;
    $stmt->bind_result($id, $Login_user, $Login_pw);
    $result = $stmt->execute();
    if (!$result)
        echo "错误信息：" . $stmt->error;
    $stmt->fetch();
}

if ($USER == $Login_user) {
    if ($PW == $Login_pw) {
        $_SESSION['loginadmin'] = $USER;
        echo "<script>location.href = '../index.php';</script>";
    } else {
        //密码错误
        die("<script>alert('登录失败，用户名或密码错误！！！');history.back();</script>");
    }
} else {
    //用户名错误
    die("<script>alert('登录失败，用户名或密码错误！！！');history.back();</script>");
}

