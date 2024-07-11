<?php
$servername = "localhost";
$username = "kobe";
$password = "denshi";
$dbname = "pbl2";

try {
    // データベースに接続
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // メッセージとユーザー名を取得するクエリを実行
    $stmt = $conn->prepare("SELECT chat.id, chat.message AS content, chat.date, chat.user_id, user.username FROM chat JOIN user ON chat.user_id = user.user_id ORDER BY chat.id ASC");
    $stmt->execute();

    // 結果を配列として取得
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 結果をJSON形式で返す
    echo json_encode($messages);
} catch (PDOException $e) {
    // エラーメッセージをJSON形式で返す
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
