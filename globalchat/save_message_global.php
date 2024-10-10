<?php
$servername = "localhost";
$username = "kobe";
$password = "denshi";
$dbname = "pbl2";

$data = json_decode(file_get_contents("php://input"), true);
$message = $data['message'];
$date = date('Y-m-d H:i:s');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("INSERT INTO global_chat (message, date) VALUES (:message, :date)");
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':date', $date);
    $stmt->execute();

    echo json_encode(["status" => "success"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
