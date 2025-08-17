<?php
session_start();
include_once '../dbConfig/connect.php';

// 设置响应头为 JSON
header('Content-Type: application/json');

$response = array();

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] <> '') {
    // 从 POST 获取 id
    $id = isset($_POST['id']) ? $_POST['id'] : 0;

    if (is_numeric($id) && $id > 0) {
        // 使用预处理语句防止 SQL 注入
        $sql = "DELETE FROM black_ip WHERE id = ?";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            $response['status'] = 'success';
            $response['message'] = 'IP删除成功！';
        } else {
            $response['status'] = 'error';
            $response['message'] = '删除失败，请稍后重试！';
        }

        mysqli_stmt_close($stmt);
    } else {
        $response['status'] = 'error';
        $response['message'] = '参数错误！';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = '非法操作，请重新登录！';
}

echo json_encode($response);
?>