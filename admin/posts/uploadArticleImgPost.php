<?php
// 目标存储路径
$targetDir = __DIR__ . "/../static/articleImg/";
include '../Component/Compresser.php';

// 如果目录不存在则创建
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// 检查文件是否上传成功
if (!isset($_FILES['editormd-image-file'])) {
    echo json_encode([
        'success' => 0,
        'message' => '没有文件上传'
    ]);
    exit;
}


$file = $_FILES['editormd-image-file'];
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
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

$filename = compressImage($file['tmp_name'], $targetDir);

$imgUrl = '/admin/static/articleImg/' . $filename;

if ($filename) {
    echo json_encode([
        'success' => 1,
        'message' => '上传成功',
        'url' => $imgUrl // 返回可访问的URL
    ]);
} else {
    // 如果压缩失败，尝试直接移动原文件
    $filename = uniqid() . '.' . $ext;
    $targetFile = $targetDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        $imgUrl = '/admin/static/articleImg/' . $filename;
        echo json_encode([
            'success' => 1,
            'message' => '上传成功',
            'url' => $imgUrl // 返回可访问的URL
        ]);
    } else {
        echo json_encode([
            'success' => 0,
            'message' => '上传失败'
        ]);
        exit;
    }
}

?>
