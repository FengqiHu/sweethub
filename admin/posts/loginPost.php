<?php
// 设置 session 配置
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '.sweethub.cn'); // 注意前面的点
//ini_set('session.cookie_secure', true); // 如果使用 HTTPS
ini_set('session.cookie_httponly', true);
ini_set('session.cookie_samesite', 'Lax');
session_start();
header('Content-Type: text/plain; charset=utf-8');

$user = isset($_POST['adminName']) ? $_POST['adminName'] : '';
$pw = isset($_POST['pw']) ? $_POST['pw'] : '';

include_once "../dbConfig/Database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "select * from user where user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $Login_user = $row['user'];
        $Login_pw = $row['password']; // 假设密码字段名是 password

        if ($pw == $Login_pw) {
            $_SESSION['loginadmin'] = $user;
            echo "success:登录成功";
        } else {
            echo "error:密码错误";
        }
    } else {
        echo "error:用户名错误";
    }

    $stmt->close();
} else {
    echo "error:非法请求";
}

$conn->close();
?>