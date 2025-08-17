<?php
session_start();
include_once '../dbConfig/connect.php';

function checkQQ($qq)
{
    if (preg_match("/^[1-9][0-9]{4,}$/", $qq)) {
        return true;
    } else {
        return false;
    }
}

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] <> '') {
    $boy = htmlspecialchars(trim($_POST['boy']));
    $girl = htmlspecialchars(trim($_POST['girl']));
    $boyimg = htmlspecialchars(trim($_POST['boyimg']), ENT_QUOTES);
    $girlimg = htmlspecialchars(trim($_POST['girlimg']), ENT_QUOTES);
    $startTime = trim($_POST['startTime']);

    // 假设有checkQQ函数
    if (checkQQ($boyimg) && checkQQ($girlimg)) {
        $sql = "UPDATE text SET startTime = '$startTime', girlimg = '$girlimg', boyimg = '$boyimg', girl = '$girl', boy = '$boy' WHERE id = 1";
        $result = mysqli_query($connect, $sql);
        if ($result) {
            echo "1";
        } else {
            echo "0";
        }
    } else {
        echo "3";
    }
} else {
    echo "非法操作";
}
exit();
?>