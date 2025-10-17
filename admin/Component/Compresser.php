<?php
//function compressImage($sourcePath, $targetDir, $quality = 20) {
//    // 获取图片信息
//    $imageInfo = getimagesize($sourcePath);
//    if (!$imageInfo) {
//        return false;
//    }
//
//    // 创建图像资源
//    $image = imagecreatefromstring(file_get_contents($sourcePath));
//    if (!$image) {
//        return false;
//    }
//
//    // 生成唯一文件名（不带扩展名）
//    $baseFilename = uniqid();
//    $result = false;
//    $finalFilename = '';
//
//    // 优先尝试WebP格式
//    if (function_exists('imagewebp')) {
//        $webpPath = $targetDir . $baseFilename . '.webp';
//        if (@imagewebp($image, $webpPath, $quality)) {
//            if (file_exists($webpPath) && filesize($webpPath) > 0) {
//                $finalFilename = $baseFilename . '.webp';
//                $result = true;
//            } else {
//                // 如果WebP保存失败，删除可能创建的空文件
//                @unlink($webpPath);
//            }
//        }
//    }
//
//    // 如果WebP失败，尝试JPEG
//    if (!$result) {
//        $jpegPath = $targetDir . $baseFilename . '.jpg';
//        if (@imagejpeg($image, $jpegPath, $quality)) {
//            if (file_exists($jpegPath) && filesize($jpegPath) > 0) {
//                $finalFilename = $baseFilename . '.jpg';
//                $result = true;
//            } else {
//                @unlink($jpegPath);
//            }
//        }
//    }
//
//    // 释放内存
//    imagedestroy($image);
//
//    return $result ? $finalFilename : false;
//}


function compressImage($sourcePath, $targetDir, $maxWidth = 1280, $maxHeight = 1280, $quality = 30)
{
    // 获取图片信息
    $imageInfo = getimagesize($sourcePath);
    if (!$imageInfo) {
        return false;
    }

    list($width, $height) = $imageInfo;

    // 创建源图像
    $image = imagecreatefromstring(file_get_contents($sourcePath));
    if (!$image) {
        return false;
    }

    // ---- 自动缩放 ----
    $ratio = min($maxWidth / $width, $maxHeight / $height, 1);
    $newWidth = intval($width * $ratio);
    $newHeight = intval($height * $ratio);

    $resized = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    imagedestroy($image); // 释放原图内存

    // ---- 保存图像 ----
    $baseFilename = uniqid();
    $result = false;
    $finalFilename = '';

    // 优先WebP（体积小）
    if (function_exists('imagewebp')) {
        $webpPath = $targetDir . $baseFilename . '.webp';
        if (@imagewebp($resized, $webpPath, $quality)) {
            if (file_exists($webpPath) && filesize($webpPath) > 0) {
                $finalFilename = $baseFilename . '.webp';
                $result = true;
            } else {
                @unlink($webpPath);
            }
        }
    }

    // 回退到JPEG
    if (!$result) {
        $jpegPath = $targetDir . $baseFilename . '.jpg';
        if (@imagejpeg($resized, $jpegPath, $quality)) {
            if (file_exists($jpegPath) && filesize($jpegPath) > 0) {
                $finalFilename = $baseFilename . '.jpg';
                $result = true;
            } else {
                @unlink($jpegPath);
            }
        }
    }

    imagedestroy($resized);
    return $result ? $finalFilename : false;
}
