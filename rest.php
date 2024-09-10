<?php
// データベース接続情報
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// MySQLデータベースに接続
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続チェック
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 時刻を取得　23:59:59

// データを取得して　取得した時刻時点で今日のデータがない場合は変数にTrueを入れる
require_once __DIR__.'input_daily_record/classes/';
$daily_data= new dailyData();

$today_date = 

$daily_data->get_oneday_alchol($today_date);

if (){
    $today_data_none = 0;
}

if (){
// データを挿入するSQL文
    $sql = "INSERT INTO your_table (column1, column2) VALUES ('value1', 'value2')";
}

// クエリの実行
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// 接続を閉じる
$conn->close();
?>