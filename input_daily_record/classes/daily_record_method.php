<?php
require_once __DIR__.'/dbdata.php';
class dailyData extends dbdata{
    // onedayテーブルにデータを追加
    public function insert_dailyData($account_data, $user_id){
        $sql = "insert into record_oneday_pbl3(date, alchol_data, user_id) values(NOW(), ?, ?)";
        $result = $this->exec($sql, [$account_data, $user_id]);
    }

    // onedayテーブルのデータを取得
    // public function get_oneday_alchol($today_date, $user_id){
    public function get_oneday_alchol($today_date){
        // $sql = "select date, sum(alchol_volume) as sum_alchol, sum(money) as sum_money from record_oneday where date=? and user_id=? group by date";
        // $sql = "select date, sum(alchol_volume) as sum_alchol, sum(money) as sum_money from record_oneday where date=? group by date";
        $sql = "select date, count(alchol_data=1) as sum_alchol_data from record_oneday_pbl3 where date=? group by date";
        $stmt = $this->query($sql, [$today_date]);
        $items = $stmt->fetchAll();
        return $items;
    }

    // 日付を取得（daily_recordテーブル）
    public function get_date($user_id){
        $sql = "select date from daily_record where user_id=?";
        $stmt = $this->query($sql, [$user_id]);
        $items = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $items;
    }

    // daily_recordテーブル
    public function insert_sumData($alchol_date, $alchol_count, $user_id){
        $sql = "insert into daily_record_pbl3(date, alchol_count, user_id) values(?, ?, ?)";
        $result = $this->exec($sql, [$alchol_date, $alchol_count, $user_id]);
    }

    // daily_recordテーブル
    public function update_sumData($alchol_count, $alchol_date, $user_id){
        $sql = "update daily_record_pbl3 set alchol_volume=? where date=? and user_id=?";
        $result = $this->exec($sql, [$alchol_count, $alchol_date, $user_id]);
    }

    

    // 日付を取得（record_onedayテーブル）
    // public function get_date_oneday(){
    //     $sql = "select date from record_oneday";
    //     $stmt = $this->query($sql, []);
    //     $items = $stmt->fetchAll(PDO::FETCH_COLUMN);
    //     return $items;
    // }

    // 飲み物のアルコール度数を取得
    public function get_alc($drink_id){
        $sql = "select alc from drink where drink_id=?";
        $stmt = $this->query($sql, [$drink_id]);
        $item = $stmt->fetch(PDO::FETCH_COLUMN);
        return $item;
    }

    // コップの体積（１杯で飲んだ量）を取得する
    public function get_glass_v($glass_id){
        $sql = "select glass_v from glass where glass_id=?";
        $stmt = $this->query($sql, [$glass_id]);
        $item = $stmt->fetch(PDO::FETCH_COLUMN);
        return $item;
    }
}
?>