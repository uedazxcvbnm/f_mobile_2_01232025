<?php
$servername = "localhost";
$username = "kobe";
$password = "denshi";
$dbname = "pbl2";

$data = json_decode(file_get_contents("php://input"), true);
$messageId = $data['id'];
$newMessage = $data['message'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("UPDATE chat SET message = :message WHERE id = :id");
    $stmt->bindParam(':message', $newMessage);
    $stmt->bindParam(':id', $messageId);
    $stmt->execute();

    echo json_encode(["status" => "success"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
