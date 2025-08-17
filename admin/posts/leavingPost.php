<?php

include_once '../dbConfig/Database.php';
include_once '../Function.php';

$name = trim($_POST['name']);
$text = trim($_POST['text']);
$time = time();

$Filter_Name = replaceSpecialChar($name);
$Filter_Text = replaceSpecialChar($text);
$Filter_Time = replaceSpecialChar($time);
$Filter_IP = $_SERVER['REMOTE_ADDR'];
$file = $_SERVER['PHP_SELF'];

// 获取用户城市
$User_City = get_ip_city_New($Filter_IP);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($Filter_Name) && !empty($Filter_Text)) {
        if (filter_var($Filter_IP, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {

            // 计算今天的开始时间
            $todayStart = strtotime("today");

            // 查询今天该 IP + 城市 + 用户名 的留言数
            $check_sql = "SELECT COUNT(*) AS cnt FROM leaving 
                          WHERE ip = ? AND city = ? AND updated_time >= CURDATE()";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("ss", $Filter_IP, $User_City);
            $check_stmt->execute();
            $check_stmt->bind_result($count);
            $check_stmt->fetch();
            $check_stmt->close();

            if ($count > 3) {
                // 今日留言次数过多
                echo "9";
                exit();
            }

            // 插入数据
            $charu = "INSERT INTO leaving (name, text, ip, city) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($charu);
            $stmt->bind_param("ssss", $Filter_Name, $Filter_Text, $Filter_IP, $User_City);
            $result = $stmt->execute();
            if (!$result) {
                echo "错误信息：" . $stmt->error;
                exit();
            }

            if ($result) {
                // 留言成功
                echo "1";
                exit();
            } else {
                // 提交失败
                echo "0";
                exit();
            }

        } else {
            // IP格式错误
            echo "4";
            exit();
        }
    } else {
        // 参数错误
        echo "5";
        exit();
    }
} else {
    echo "<script>alert('非法操作，行为已记录');location.href = 'warning.php?route=$file';</script>";
    exit();
}
