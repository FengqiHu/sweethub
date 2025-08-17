<?php

include_once 'admin/dbConfig/connect.php';

$ipchaxun = "select * from black_ip";
$ipres = mysqli_query($connect, $ipchaxun);

// 收集所有被封禁的IP地址
$banned_ips = array();
while ($IPinfo = mysqli_fetch_array($ipres)) {
    $banned_ips[] = $IPinfo['ip'];
}

// 获取访问者IP并检查是否被封禁
$ip = $_SERVER["REMOTE_ADDR"];

if (in_array($ip, $banned_ips)) {
    die ("<script>alert('你的IP($ip)已被封禁，禁止访问本页面');location.href = 'error.php';</script>");
}

?>