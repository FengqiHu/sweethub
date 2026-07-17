<?php
header("Content-Type:text/html; charset=utf8");

// Railway 环境变量，如果不存在则使用本地默认值
$db_address = getenv("DB_HOST") ?: "127.0.0.1:3306";
$db_username = getenv("DB_USER") ?: "root";
$db_password = getenv("DB_PASSWORD") ?: "12345678";
$db_name = getenv("DB_NAME") ?: "sweethub";

// 安全码
$Like_Code = "LovePHP";

//版本号
$version = 20241108;