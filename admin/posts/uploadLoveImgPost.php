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
$imgText = isset($_POST['imgText']) ? mysqli_real_escape_string($connect, $_POST['imgText']) : '';
$oldImgUrl = isset($_POST['oldImgUrl']) ? $_POST['oldImgUrl'] : '';
$imgUrl = $oldImgUrl; // 默认使用原图片URL

// 验证必填字段
if (empty($id) || empty($imgText)) {
    echo 'empty_fields';
    exit;
}

// 处理文件上传
if (isset($_FILES['imgFile']) && $_FILES['imgFile']['error'] == 0) {
    // 目标存储路径
    $targetDir = __DIR__ . "/../static/albumImg/";

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
        // 更新图片URL为新上传的图片
        $imgUrl = '/admin/static/albumImg/' . $filename;

        // 删除旧图片（可选）
        if ($oldImgUrl && strpos($oldImgUrl, '/admin/static/albumImg/') !== false) {
            $oldFilePath = __DIR__ . '/..' . str_replace('/admin', '', $oldImgUrl);
            if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                @unlink($oldFilePath);
            }
        }
    } else {
        echo 'upload_failed';
        exit;
    }
    // 更新数据库
    $sql = "UPDATE loveImg SET imgDatd='$imgDatd', imgText='$imgText', imgUrl='$filename' WHERE id=$id";
}else{
    $sql = "UPDATE loveImg SET imgDatd='$imgDatd', imgText='$imgText' WHERE id=$id";
}

$result = mysqli_query($connect, $sql);

if ($result) {
    echo 'success';
} else {
    echo 'error';
}

mysqli_close($connect);
?>