<?php
require_once __DIR__.'/dbdata.php';
class dailyData extends dbdata{
    public function insert_dailyData($drink, $glass, $account_drunk, $alcohol_money, $alchol_volume){
        $sql = "insert into daily_record(drink_id, glass_id, date, account_drunk, money, alchol_volume) values(?, ?, NOW(), ?, ?, ?)";
        $result = $this->exec($sql, [$drink, $glass, $account_drunk, $alcohol_money, $alchol_volume]);
    }

    public function get_date(){
        $sql = "select date from daily_record";
        $stmt = $this->query($sql, []);
        $items = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $items;
    }

    public function get_alc($drink_id){
        $sql = "select alc from drink where drink_id=?";
        $stmt = $this->query($sql, [$drink_id]);
        $item = $stmt->fetch(PDO::FETCH_COLUMN);
        return $item;
    }

    public function get_glass_v($glass_id){
        $sql = "select glass_v from glass where glass_id=?";
        $stmt = $this->query($sql, [$glass_id]);
        $item = $stmt->fetch(PDO::FETCH_COLUMN);
        return $item;
    }
}
?>