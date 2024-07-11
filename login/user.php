<?php
require_once __DIR__ . '/../input_daily_record/classes/dbdata.php';

class User extends dbdata{
    public function signUp(){

    }

    public function auth($userName, $password){
        $sql = "select * from user where username=? and password=?";
        $stmt = $this->query($sql, [$userName, $password]);
        return $stmt->fetch();
    }
}