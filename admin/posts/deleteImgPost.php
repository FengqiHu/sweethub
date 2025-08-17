<?php
session_start();
include_once '../dbConfig/connect.php';

if (isset($_SESSION['loginadmin']) && $_SESSION['loginadmin'] <> '') {
    $id = $_GET['id'];
    if (is_numeric($id)) {
        $sql = "DELETE FROM loveImg WHERE id = $id";
        $result = mysqli_query($connect, $sql);
        if ($result) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'invalid';
    }
} else {
    echo 'unauthorized';
}
?>