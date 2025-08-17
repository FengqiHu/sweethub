<?php
session_start();
include_once '../dbConfig/connect.php';

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] <> '') {
    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if ($id && is_numeric($id)) {
        // 使用预处理语句防止SQL注入
        $sql = "DELETE FROM leaving WHERE id = ?";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            echo 'success';
        } else {
            echo 'error';
        }
        mysqli_stmt_close($stmt);
    } else {
        echo 'invalid';
    }
} else {
    echo 'unauthorized';
}
exit();
?>