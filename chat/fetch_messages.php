<?php
$servername = "localhost";
$username = "kobe";
$password = "denshi";
$dbname = "pbl2";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("
    SELECT group_chat.id, group_chat.message AS content, group_chat.date, group_chat.user_id, group_chat.edited, group_chat.is_deleted, user.username 
    FROM group_chat 
    JOIN user ON group_chat.user_id = user.user_id 
    WHERE group_chat.team_id = :group_id 
    ORDER BY group_chat.id ASC
");
    $stmt->bindParam(':group_id', $group_id);

    $stmt->execute();


    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($messages);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
