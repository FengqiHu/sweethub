<?php
header("Content-Type:text/html; charset=utf8");

// 数据库环境变量。
// Railway 中建议在应用服务里配置 DB_HOST、DB_PORT、DB_USER、DB_PASSWORD、DB_NAME，
// 并分别引用 MySQL 服务对应的 MYSQLHOST、MYSQLPORT、MYSQLUSER、MYSQLPASSWORD、MYSQLDATABASE。
// 同时兼容直接使用 Railway 原生 MYSQL* 变量的配置方式。
$db_address = getenv("DB_HOST") ?: getenv("MYSQLHOST") ?: "127.0.0.1";
$db_port = (int) (getenv("DB_PORT") ?: getenv("MYSQLPORT") ?: 3306);
$db_username = getenv("DB_USER") ?: getenv("MYSQLUSER") ?: "root";
$db_password = getenv("DB_PASSWORD");
if ($db_password === false) {
    $db_password = getenv("MYSQLPASSWORD");
}
if ($db_password === false) {
    $db_password = "";
}
$db_name = getenv("DB_NAME") ?: getenv("MYSQLDATABASE") ?: "sweethub";

// 安全码
$Like_Code = "LovePHP";

//版本号
$version = 20241108;
