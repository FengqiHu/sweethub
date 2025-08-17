<?php
session_start();
include_once '../dbConfig/connect.php';

// 检查是否登录
if (!isset($_SESSION['loginadmin'])) {
    echo 'unauthorized';
    exit;
}

// 获取表单数据
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$imgDatd = isset($_POST['imgDatd']) ? mysqli_real_escape_string($connect, $_POST['imgDatd']) : '';
$eventname = isset($_POST['eventname']) ? mysqli_real_escape_string($connect, trim($_POST['eventname'])) : '';
$icon = isset($_POST['icon']) && $_POST['icon'] == 1 ? 1 : 0;
$imgUrl = '0'; // 默认值

// 验证必填字段
if (empty($eventname)) {
    echo 'empty_fields';
    exit;
}

// 如果完成状态为1，处理图片上传（可选）
if ($icon == 1 && isset($_FILES['imgFile']) && $_FILES['imgFile']['error'] == 0) {
    // 目标存储路径
    $targetDir = __DIR__ . "/../static/listImg/";

    // 如果目录不存在则创建
    if (!is_dir($targetDir)) {
        if (!mkdir($targetDir, 0777, true)) {
            echo 'error';
            exit;
        }
    }

    // 获取文件信息
    $file = $_FILES['imgFile'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // 允许的文件类型
    $allowedTypes = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'webp'];

    if (!in_array($ext, $allowedTypes)) {
        echo 'invalid_format';
        exit;
    }

    // 检查文件大小（10MB）
    if ($file['size'] > 10 * 1024 * 1024) {
        echo 'file_too_large';
        exit;
    }

    // 生成唯一文件名
    $filename = uniqid() . '.' . $ext;
    $targetFile = $targetDir . $filename;

    // 移动上传的文件
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        // 只存储文件名，不存储完整路径
        $imgUrl = $filename;
    } else {
        echo 'upload_failed';
        exit;
    }
}

// 插入数据库
$sql = "Update loveList set eventName = '$eventname', icon = '$icon', imgurl = '$imgUrl', imgDatd = '$imgDatd' where id = '$id'";
$result = mysqli_query($connect, $sql);

if ($result) {
    echo 'success';
} else {
    echo 'error';
}

mysqli_close($connect);
?>