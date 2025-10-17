<?php
session_start();
include_once '../dbConfig/connect.php';
include '../Component/Compresser.php';

// 检查是否登录
if (!isset($_SESSION['loginadmin'])) {
    echo 'unauthorized';
    exit;
}

// 获取表单数据
$eventdate = isset($_POST['eventdate']) ? mysqli_real_escape_string($connect, $_POST['eventdate']) : '';
$eventname = isset($_POST['eventname']) ? mysqli_real_escape_string($connect, trim($_POST['eventname'])) : '';
$uploadType = isset($_POST['uploadType']) ? $_POST['uploadType'] : 'file';
$icon = isset($_POST['icon']) && $_POST['icon'] == 1 ? 1 : 0;

// 验证必填字段
if (empty($eventname)) {
    echo 'empty_fields';
    exit;
}

// 处理文件上传时，检查具体的错误类型
if (!isset($_FILES['imgFile']) || $_FILES['imgFile']['error'] != 0) {
    $errorCode = isset($_FILES['imgFile']['error']) ? $_FILES['imgFile']['error'] : 'no_file';

    // 根据错误代码返回具体信息
    switch($errorCode) {
        case UPLOAD_ERR_INI_SIZE:
            echo 'file_exceeds_ini_size';
            break;
        case UPLOAD_ERR_FORM_SIZE:
            echo 'file_exceeds_form_size';
            break;
        case UPLOAD_ERR_PARTIAL:
            echo 'file_partial_upload';
            break;
        case UPLOAD_ERR_NO_FILE:
            echo 'no_file_uploaded';
            break;
        default:
            echo 'empty_fields';
    }
    exit;
}

// 根据上传类型处理
if ($uploadType === 'file') {
    // 处理文件上传
    if (!isset($_FILES['imgFile']) || $_FILES['imgFile']['error'] != 0) {
        echo 'empty_fields';
        exit;
    }

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

    // 使用压缩函数处理图片
    $filename = compressImage($file['tmp_name'], $targetDir);

    if ($filename) {
        $imgUrl = '/admin/static/listImg/' . $filename;
    } else {
        // 如果压缩失败，尝试直接移动原文件
        $filename = uniqid() . '.' . $ext;
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $imgUrl = '/admin/static/listImg/' . $filename;
        } else {
            echo 'upload_failed';
            exit;
        }
    }
}

// 插入数据库 - 修正：使用 $imgUrl 而不是 $filename
$sql = "INSERT INTO loveList (eventName, icon, imgurl, imgDatd) VALUES ('$eventname', '$icon', '$filename', '$eventdate')";
$result = mysqli_query($connect, $sql);

if ($result) {
    echo 'success';
} else {
    echo 'error';
}

mysqli_close($connect);
?>