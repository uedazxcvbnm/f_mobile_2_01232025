<?php
include 'config.php';

$user_id = $_SESSION['user_id'];
$startDate = $_GET['start'];
$endDate = $_GET['end'];

if (!$startDate || !$endDate) {
    http_response_code(400);
    echo json_encode(["error" => "Missing start or end date"]);
    exit();
}

$query = $conn->prepare("SELECT * FROM daily_record WHERE date BETWEEN ? AND ?");
$query->bind_param("ss", $startDate, $endDate);
$query->execute();
$result = $query->get_result();

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

error_log("Fetched Data for $startDate to $endDate: " . json_encode($data));

echo json_encode($data);
