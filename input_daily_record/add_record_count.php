<?php
session_start();

// 飲み物の選択ボタン
$action_drink = $_POST['drink_button_info'];

// インスタンスを作成
require_once __DIR__.'/classes/daily_record_method.php';
$dailyData = new dailyData();

// ユーザーIDを取得
$user_id = $_SESSION['user_id'];

// データを登録
$dailyData->insert_dailyData($action_drink, $action_glass, $account_drunk, $alcohol_money, $alchol_volume, $user_id);

// 今日の日付
$today_date = date('Y-m-d');
echo $today_date;

// 今日のアルコール量を取得
$alchol_volume_today = $dailyData->get_oneday_alchol($today_date);
var_dump($alchol_volume_today);
echo $alchol_volume_today[0]['sum_alchol'];

// グラフに登録済みの日付を取得
$date_array_sumtable = $dailyData->get_date($user_id);
var_dump($date_array_sumtable);

// 今日の日付が含まれているとき
if (in_array($today_date, $date_array_sumtable)){
    $dailyData->update_sumData($alchol_volume_today[0]['sum_money'], $alchol_volume_today[0]['sum_alchol'], $today_date, $user_id);
} else {
    $dailyData->insert_sumData($today_date, $alchol_volume_today[0]['sum_money'], $alchol_volume_today[0]['sum_alchol'], $user_id);
}

// グラフ画面に移動
header("Location: ./graph/graph.php");
exit;
?>