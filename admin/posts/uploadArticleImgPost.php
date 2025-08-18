<?php
// 目标存储路径
$targetDir = __DIR__ . "/../static/articleImg/";

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
$filename = uniqid() . '.' . $ext; // 防止重名
$targetFile = $targetDir . $filename;

// 移动文件
if (move_uploaded_file($file['tmp_name'], $targetFile)) {
    echo json_encode([
        'success' => 1,
        'message' => '上传成功',
        'url' => '/admin/static/articleImg/' . $filename // 返回可访问的URL
    ]);
} else {
    echo json_encode([
        'success' => 0,
        'message' => '上传失败'
    ]);
}

?>
