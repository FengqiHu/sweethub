<?php
// simple_test.php - 简单的压缩测试
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_FILES && isset($_FILES['testImage'])) {
    $uploadedFile = $_FILES['testImage']['tmp_name'];
    $targetDir = __DIR__ . '/test_uploads/';

    // 创建测试目录
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // 获取原始图片信息
    $imageInfo = getimagesize($uploadedFile);
    $originalSize = filesize($uploadedFile);

    echo "<h3>原始图片信息</h3>";
    echo "类型: " . $imageInfo['mime'] . "<br>";
    echo "尺寸: " . $imageInfo[0] . " x " . $imageInfo[1] . "<br>";
    echo "大小: " . number_format($originalSize / 1024, 2) . " KB<br><br>";

    // 创建图像资源
    $image = imagecreatefromstring(file_get_contents($uploadedFile));

    if ($image) {
        // 测试WebP
        if (function_exists('imagewebp')) {
            $webpPath = $targetDir . 'test_' . time() . '.webp';
            $webpResult = imagewebp($image, $webpPath, 85);

            echo "<h3>WebP压缩结果</h3>";
            if ($webpResult && file_exists($webpPath)) {
                $webpSize = filesize($webpPath);
                echo "保存成功！<br>";
                echo "文件路径: " . $webpPath . "<br>";
                echo "文件大小: " . number_format($webpSize / 1024, 2) . " KB<br>";
                echo "压缩率: " . round((1 - $webpSize / $originalSize) * 100, 2) . "%<br>";
                echo '<img src="test_uploads/' . basename($webpPath) . '" style="max-width: 300px;"><br><br>';
            } else {
                echo "保存失败！<br>";
                echo "错误: " . error_get_last()['message'] . "<br><br>";
            }
        }

        // 测试JPEG
        $jpegPath = $targetDir . 'test_' . time() . '.jpg';
        $jpegResult = imagejpeg($image, $jpegPath, 85);

        echo "<h3>JPEG压缩结果</h3>";
        if ($jpegResult && file_exists($jpegPath)) {
            $jpegSize = filesize($jpegPath);
            echo "保存成功！<br>";
            echo "文件路径: " . $jpegPath . "<br>";
            echo "文件大小: " . number_format($jpegSize / 1024, 2) . " KB<br>";
            echo "压缩率: " . round((1 - $jpegSize / $originalSize) * 100, 2) . "%<br>";
            echo '<img src="test_uploads/' . basename($jpegPath) . '" style="max-width: 300px;"><br>';
        } else {
            echo "保存失败！<br>";
            echo "错误: " . error_get_last()['message'] . "<br>";
        }

        imagedestroy($image);
    } else {
        echo "无法创建图像资源！";
    }

    // 显示目录权限
    echo "<h3>目录信息</h3>";
    echo "目录路径: " . $targetDir . "<br>";
    echo "目录存在: " . (is_dir($targetDir) ? '是' : '否') . "<br>";
    echo "目录可写: " . (is_writable($targetDir) ? '是' : '否') . "<br>";
    echo "目录权限: " . substr(sprintf('%o', fileperms($targetDir)), -4) . "<br>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>图片压缩测试</title>
    <meta charset="utf-8">
</head>
<body>
<h2>上传图片测试压缩</h2>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="testImage" accept="image/*" required>
    <button type="submit">测试压缩</button>
</form>
</body>
</html>