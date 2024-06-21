<?php
$servername = "localhost";
$username = "root"; // 请根据你的实际配置进行调整
$password = ""; // 请根据你的实际配置进行调整
$dbname = "pbl2";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
