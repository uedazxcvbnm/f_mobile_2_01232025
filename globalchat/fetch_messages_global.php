<?php
$servername = "localhost";
$username = "kobe";
$password = "denshi";
$dbname = "pbl2";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("
        SELECT global_chat.id, global_chat.message AS content, global_chat.date, global_chat.user_id, global_chat.edited, global_chat.is_deleted, user.username 
        FROM global_chat 
        JOIN user ON global_chat.user_id = user.user_id 
        ORDER BY global_chat.id ASC
    ");
    $stmt->execute();

    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($messages);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
