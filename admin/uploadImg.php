<?php
// 保存路径
$uploadDir = __DIR__ . '/../static/articleImg/';

// 检查目录
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (!empty($_FILES['editormd-image-file']['name'])) {
    $fileName = time() . '_' . basename($_FILES['editormd-image-file']['name']);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['editormd-image-file']['tmp_name'], $targetPath)) {
        $url = '/static/articleImg/' . $fileName; // 图片访问路径
        echo json_encode([
            'success' => 1,
            'message' => '上传成功',
            'url' => $url
        ]);
    } else {
        echo json_encode([
            'success' => 0,
            'message' => '上传失败'
        ]);
    }
} else {
    echo json_encode([
        'success' => 0,
        'message' => '没有上传文件'
    ]);
}
