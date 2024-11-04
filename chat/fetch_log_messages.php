<?php
$servername = "localhost";
$username = "kobe";
$password = "denshi";
$dbname = "pbl2";

$group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($group_id > 0) {
        // 削除ログを取得する
        $stmt = $conn->prepare("
            SELECT message, date 
            FROM global_chat 
            WHERE team_id = :group_id AND is_deleted = TRUE
            ORDER BY date ASC
        ");
        $stmt->bindParam(':group_id', $group_id);
        $stmt->execute();

        $logMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($logMessages);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid group ID"]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
