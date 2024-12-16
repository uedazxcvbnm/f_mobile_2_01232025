<?php
$servername = "mysql311.phy.lolipop.lan";
$username = "LAA1632250";
$password = "9vWqKeipemkaEzZ";
$dbname = "LAA1632250-pbl2";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
