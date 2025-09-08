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

// 图片压缩函数
function compressImage($sourcePath, $targetDir, $quality = 85) {
    // 获取图片信息
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        return false;
    }

    // 创建图像资源
    $image = imagecreatefromstring(file_get_contents($sourcePath));
    if (!$image) {
        return false;
    }

    // 生成唯一文件名（不带扩展名）
    $baseFilename = uniqid();
    $result = false;
    $finalFilename = '';

    // 优先尝试WebP格式
    if (function_exists('imagewebp')) {
        $webpPath = $targetDir . $baseFilename . '.webp';
        if (@imagewebp($image, $webpPath, $quality)) {
            if (file_exists($webpPath) && filesize($webpPath) > 0) {
                $finalFilename = $baseFilename . '.webp';
                $result = true;
            } else {
                // 如果WebP保存失败，删除可能创建的空文件
                @unlink($webpPath);
            }
        }
    }

    // 如果WebP失败，尝试JPEG
    if (!$result) {
        $jpegPath = $targetDir . $baseFilename . '.jpg';
        if (@imagejpeg($image, $jpegPath, $quality)) {
            if (file_exists($jpegPath) && filesize($jpegPath) > 0) {
                $finalFilename = $baseFilename . '.jpg';
                $result = true;
            } else {
                @unlink($jpegPath);
            }
        }
    }

    // 释放内存
    imagedestroy($image);

    return $result ? $finalFilename : false;
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
    // 使用压缩函数处理图片
    $filename = compressImage($file['tmp_name'], $targetDir, 85);

    // 移动上传的文件
    if ($filename) {
        $imgUrl = '/admin/static/albumImg/' . $filename;
    } else {
        // 如果压缩失败，尝试直接移动原文件
        $filename = uniqid() . '.' . $ext;
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            $imgUrl = '/admin/static/albumImg/' . $filename;
        } else {
            echo 'upload_failed';
            exit;
        }
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