<?php
session_start();
include_once '../dbConfig/connect.php';

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] != '') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        $stmt = $connect->prepare("DELETE FROM article WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
        $stmt->close();
    } else {
        echo "error";
    }
} else {
    echo "unauthorized";
}
?>