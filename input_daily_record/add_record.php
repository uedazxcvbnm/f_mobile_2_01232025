<?php
// 飲み物の選択ボタン
$action_drink = $_POST['drink_button_info'];
// 容器の選択ボタン
$action_glass = $_POST['glass_button_info'];

// 何杯飲んだか
$account_drunk = $_POST['account_drunk'];
// 金額
$alcohol_money = $_POST['alcohol_money'];
// 血圧
$sBP = $_POST['sBP'];
$dBP = $_POST['dBP'];

// インスタンスを作成
require_once __DIR__.'/classes/daily_record_method.php';
$dailyData = new dailyData();

// アルコール度数を取得　id指定
$action_drink_volume = $dailyData->get_alc($action_drink);

// グラスを取得　id指定
$action_glass_volume = $dailyData->get_glass_v($action_glass);

// echo $action_drink_volume;



// 計算
$alchol_volume = $action_glass_volume * $action_drink_volume/100 * 0.8;
echo $alchol_volume;

// データを登録
$dailyData->insert_dailyData($action_drink, $action_glass, $account_drunk, $alcohol_money, $sBP, $dBP, $alchol_volume);

// comment_home画面に移動
header("Location: ./graph/graph.php");
exit;



?>