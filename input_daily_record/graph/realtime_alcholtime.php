<?php
// session
session_start();
if (!isset($_SESSION['user_id'])){
    header('Location: ./../../login/login_display.php');
    exit();
}

if(isset($_GET['date'])){
    // 指定した日付を取得
    $selected_date = $_GET['date'];

    $user_id = $_SESSION['user_id'];

    // メソッドを呼び出し
    require_once __DIR__.'../../classes/daily_record_method.php';
    // ../classes/daily_record_method.php';
    $dailyData = new dailyData();
    $items = $dailyData->get_yesno_time($selected_date, $user_id);

    // json_encode
    header('Content-Type: application/json');
    echo json_encode($items);

} else{
    //
    echo 'a';
    json_encode(["error" => "日付が指定されていません"]);
}
?>