<?php
session_start();
include_once '../dbConfig/connect.php';

// 设置JSON响应头
header('Content-Type: application/json');

if (!isset($_SESSION['loginadmin']) || $_SESSION['loginadmin'] == '') {
    echo json_encode(['status' => 'error', 'message' => '非法操作，请重新登录！']);
    exit();
}

// 获取表单数据
$card1 = htmlspecialchars(trim($_POST['card1']), ENT_QUOTES);
$card2 = htmlspecialchars(trim($_POST['card2']), ENT_QUOTES);
$card3 = htmlspecialchars(trim($_POST['card3']), ENT_QUOTES);
$deci1 = htmlspecialchars(trim($_POST['deci1']), ENT_QUOTES);
$deci2 = htmlspecialchars(trim($_POST['deci2']), ENT_QUOTES);
$deci3 = htmlspecialchars(trim($_POST['deci3']), ENT_QUOTES);
$icp = htmlspecialchars(trim($_POST['icp']), ENT_QUOTES);
$Copyright = htmlspecialchars(trim($_POST['Copyright']), ENT_QUOTES);
$old_bgimg = isset($_POST['old_bgimg']) ? $_POST['old_bgimg'] : '';

// 处理图片上传
$bgimg = $old_bgimg; // 默认使用原有图片
$upload_dir = '../static/wallpaper/'; // 上传目录

// 确保上传目录存在
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (isset($_FILES['bgimg_file']) && $_FILES['bgimg_file']['error'] == 0) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB

    $file_type = $_FILES['bgimg_file']['type'];
    $file_size = $_FILES['bgimg_file']['size'];
    $file_tmp = $_FILES['bgimg_file']['tmp_name'];

    // 验证文件类型
    if (!in_array($file_type, $allowed_types)) {
        echo json_encode(['status' => 'error', 'message' => '只支持 JPG、PNG、GIF 格式的图片！']);
        exit();
    }

    // 验证文件大小
    if ($file_size > $max_size) {
        echo json_encode(['status' => 'error', 'message' => '图片大小不能超过 5MB！']);
        exit();
    }

    // 获取文件扩展名
    $ext = pathinfo($_FILES['bgimg_file']['name'], PATHINFO_EXTENSION);
    $ext = strtolower($ext);

    // 生成唯一文件名
    $filename = uniqid() . '.' . $ext;
    $upload_path = $upload_dir . $filename;

    // 上传文件
    if (move_uploaded_file($file_tmp, $upload_path)) {
        $bgimg = $filename;

        // 删除旧图片（如果存在且不是默认图片）
        if (!empty($old_bgimg) && file_exists($upload_dir . $old_bgimg)) {
            @unlink($upload_dir . $old_bgimg);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => '图片上传失败，请重试！']);
        exit();
    }
}

// 更新数据库
$sql = "UPDATE text SET 
        icp = '$icp', 
        Copyright = '$Copyright', 
        card1 = '$card1', 
        card2 = '$card2', 
        card3 = '$card3', 
        deci1 = '$deci1', 
        deci2 = '$deci2', 
        deci3 = '$deci3', 
        bgimg = '$bgimg' 
        WHERE id = 1";

$result = mysqli_query($connect, $sql);

if ($result) {
    echo json_encode(['status' => 'success', 'message' => '卡片配置修改成功！']);
} else {
    // 如果数据库更新失败，删除刚上传的图片
    if ($bgimg != $old_bgimg && file_exists($upload_dir . $bgimg)) {
        @unlink($upload_dir . $bgimg);
    }
    echo json_encode(['status' => 'error', 'message' => '数据库更新失败：' . mysqli_error($connect)]);
}

mysqli_close($connect);
exit();
?>