<?php
session_start();
include 'config.php';

$startDate = $_GET['start'];
$endDate = $_GET['end'];

$user_id = $_SESSION['user_id'];

if (!$startDate || !$endDate) {
    http_response_code(400);
    echo json_encode(["error" => "Missing start or end date"]);
    exit();
}

$query = $conn->prepare("SELECT * FROM daily_record WHERE user_id=? AND date BETWEEN ? AND ?");
$query->bind_param("iss", $user_id, $startDate, $endDate);
$query->execute();
$result = $query->get_result();

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

error_log("Fetched Data for $startDate to $endDate: " . json_encode($data));

echo json_encode($data);