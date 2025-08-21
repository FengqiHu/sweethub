<?php
session_start();

// 检查用户是否登录
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '请先登录']);
    exit;
}

// 设置上传目录
$uploadDir = '/../static/articleVideo/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// 允许的视频格式
$allowedTypes = ['video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/flv', 'video/mkv', 'video/webm'];
$allowedExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm'];

// 检查是否有文件上传
if (!isset($_FILES['video']) || $_FILES['video']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => '文件上传失败']);
    exit;
}

$file = $_FILES['video'];

// 检查文件类型
$fileType = mime_content_type($file['tmp_name']);
$fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($fileType, $allowedTypes) && !in_array($fileExtension, $allowedExtensions)) {
    echo json_encode(['success' => false, 'message' => '不支持的视频格式']);
    exit;
}

// 检查文件大小（100MB）
$maxSize = 100 * 1024 * 1024;
if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => '视频文件大小不能超过100MB']);
    exit;
}

// 生成唯一文件名
$newFileName = uniqid('video_') . '.' . $fileExtension;
$uploadPath = $uploadDir . $newFileName;

// 移动上传的文件
if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
    // 返回视频URL
    $videoUrl = '/admin/static/articleVideo/' . $newFileName;
    echo json_encode([
        'success' => true,
        'url' => $videoUrl,
        'filename' => $newFileName
    ]);
} else {
    echo json_encode(['success' => false, 'message' => '文件保存失败']);
}
?>