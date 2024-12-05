<?php
$servername = "mysql311.phy.lolipop.lan";  // 新しいサーバー名
$username = "LAA1632250";  // 新しいユーザー名
$password = "9vWqKeipemkaEzZ";  // 新しいパスワード
$dbname = "LAA1632250-pbl2";  // 新しいデータベース名

$conn = new mysqli($servername, $username, $password, $dbname);

// 接続エラーチェック
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
