<?php
// 飲み物の選択
$ = $_POST[''];
// 
$ = $_POST[''];
// 何杯飲んだか
$account_drunk = $_POST['account_drunk'];
// 金額
$alcohol_money = $_POST['alcohol_money'];
// 血圧
$sBP = $_POST['sBP'];
$dBP = $_POST['dBP'];

require_once __DIR__.'/classes/daily_record_method.php';
$dailyData = new dailyData();
$dailyData->insert_dailyData($account_drunk, $alcohol_money, $sBP, $dBP);

// comment_home画面に移動
header("Location: ./graph/graph.php");
exit;

?>