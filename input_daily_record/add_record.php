<?php
$account_drunk = $_POST['account_drunk'];
$alcohol_money = $_POST['alcohol_money'];
$sBP = $_POST['sBP'];
$dBP = $_POST['dBP'];

require_once __DIR__.'/daily_record_classes/daily_record_method.php';
$dailyData = new dailyData();
$dailyData->insert_dailyData($account_drunk, $alcohol_money, $sBP, $dBP);

// comment_home画面に移動
header("Location: ./dailyRecord_graph.php");
exit;

?>