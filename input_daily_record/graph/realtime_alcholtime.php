<?php
// session



if(isset($_GET['date'])){
    // 指定した日付を取得
    $selected_date = $_GET['date'];

    // メソッドを呼び出し
    require_once __DIR__.'../classes/daily_record_method.php';
    $dailyData = new dailyData();
    $items = $dailyData->get_yesno_time($selected_date, $user_id);

    // json_encode
    header('Content-Type: application/json');
    echo json_encode($items);

} else{
    //
    json_encode(["error" => "日付が指定されていません"]);
}
?>