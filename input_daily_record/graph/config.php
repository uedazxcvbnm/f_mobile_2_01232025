<?php
// PDOオブジェクトを生成する 															
$servername = "localhost"; // サーバー名
$username = 'kobe';
$password = 'denshi';         // パスワード
$dbname = "pbl2";    // データベース名

// 接続の確立
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続エラーチェック
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
