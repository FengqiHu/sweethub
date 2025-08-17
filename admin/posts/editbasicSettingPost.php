<?php
session_start();
include_once '../dbConfig/connect.php';

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] <> '') {
    $title = htmlspecialchars(trim($_POST['title']), ENT_QUOTES);
    $logo = htmlspecialchars(trim($_POST['logo']), ENT_QUOTES);
    $writing = htmlspecialchars(trim($_POST['writing']), ENT_QUOTES);
    $WebPjax = trim($_POST['WebPjax']);
    $WebBlur = trim($_POST['WebBlur']);

    mysqli_begin_transaction($connect);

    $success = true;

    $sql = "UPDATE text SET title = '$title', logo = '$logo', writing = '$writing' WHERE id = '1'";
    if (!mysqli_query($connect, $sql)) {
        $success = false;
    }

    $diy = "UPDATE diySet SET Pjaxkg = '$WebPjax', Blurkg = '$WebBlur' WHERE id = '1'";
    if (!mysqli_query($connect, $diy)) {
        $success = false;
    }

    if ($success) {
        mysqli_commit($connect);
        echo 'success';
    } else {
        mysqli_rollback($connect);
        echo 'error';
    }
} else {
    echo 'unauthorized';
}
?>