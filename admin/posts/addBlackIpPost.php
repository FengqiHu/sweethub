<?php
session_start();
include_once '../dbConfig/connect.php';
include_once '../Function.php';

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] <> '') {
    $ip = trim($_POST['ip']);
    $note = trim($_POST['note']);

    // IP格式验证
    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        echo "invalid_ip";
        exit();
    }

    // 检查IP是否已存在
    $checkSql = "SELECT id FROM black_ip WHERE ip = ?";
    $checkStmt = mysqli_prepare($connect, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "s", $ip);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        echo "2";
        exit();
    }

    $city = get_ip_city_New($ip);

    // 使用预处理语句防止SQL注入
    $insertSQL = "INSERT INTO black_ip (ip, city, note) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connect, $insertSQL);
    mysqli_stmt_bind_param($stmt, "sss", $ip, $city, $note);

    if (mysqli_stmt_execute($stmt)) {
        echo "1";
    } else {
        echo "0";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "非法操作";
}
exit();
?>