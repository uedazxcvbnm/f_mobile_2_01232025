<?php
require_once __DIR__.'/daily_record_dbdata.php';
class dailyData extends dbdata{
    public function insert_dailyData($account_drunk, $alcohol_money, $sBP, $dBP){
        $sql = "insert into input_daily_record(date, account_drunk, money, sBP, dBP) values(NOW(), ?, ?, ?, ?)";
        $result = $this->exec($sql, [$account_drunk, $alcohol_money, $sBP, $dBP]);
    }

    public function get_date(){
        $sql = "select date from input_daily_record";
        $stmt = $this->query($sql, []);
        $items = $stmt->fetch();
        return $items;
    }
}
?>