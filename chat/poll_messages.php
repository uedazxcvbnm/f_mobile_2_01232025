<?php
$servername = "localhost";
$username = "kobe";
$password = "denshi";
$dbname = "pbl2";

$lastMessageId = $_GET['lastMessageId'];
$user_id = $_GET['user_id'];
$receiver_id = $_GET['receiver_id'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    while (true) {
        $stmt = $conn->prepare("SELECT * FROM chat WHERE id > :lastMessageId AND ((user_id = :user_id AND receiver_id = :receiver_id) OR (user_id = :receiver_id AND receiver_id = :user_id)) ORDER BY id");
        $stmt->bindParam(':lastMessageId', $lastMessageId);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':receiver_id', $receiver_id);
        $stmt->execute();

        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($messages)) {
            echo json_encode($messages);
            break;
        }

        // メッセージがない場合は1秒待機して再度試行
        usleep(100000); // 100ms待機して再試行（マイクロ秒単位）
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
