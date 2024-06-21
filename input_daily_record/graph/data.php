<?php
include 'config.php';

// 获取请求的开始和结束日期
$startDate = $_GET['start'];
$endDate = $_GET['end'];

// 检查是否提供了开始和结束日期
if (!$startDate || !$endDate) {
    http_response_code(400);
    echo json_encode(["error" => "Missing start or end date"]);
    exit();
}

// 准备 SQL 查询
$query = $conn->prepare("SELECT * FROM daily_record WHERE date BETWEEN ? AND ?");
$query->bind_param("ss", $startDate, $endDate);
$query->execute();
$result = $query->get_result();

// 处理结果
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// 调试输出数据
error_log("Fetched Data for $startDate to $endDate: " . json_encode($data));

// 返回 JSON 响应
echo json_encode($data);
