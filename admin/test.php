<?php
echo "当前 PHP 上传限制：<br>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";

// 转换为字节数
function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    $val = (int)$val;
    switch($last) {
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }
    return $val;
}

$upload_max = return_bytes(ini_get('upload_max_filesize'));
$post_max = return_bytes(ini_get('post_max_size'));

echo "<br>转换为 MB：<br>";
echo "upload_max_filesize: " . ($upload_max / 1024 / 1024) . " MB<br>";
echo "post_max_size: " . ($post_max / 1024 / 1024) . " MB<br>";
?>