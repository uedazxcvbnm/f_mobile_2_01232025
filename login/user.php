<?php
require_once __DIR__ . '/../input_daily_record/classes/dbdata.php';

class User extends dbdata{
    public function signUp($userMail, $userName, $userPass){
        // 取得
        // userIdはメールアドレス
        // 名前を入力する場所も作る
        $sql = 'select * from user where user_mail = ?';
        $stmt = $this->query($sql, [$userMail]);
        $result = $stmt->fetch();

        // 既に登録されているかどうかのチェック
        // resultに何かある
        if ($result){
            return $result['user_mail'].'は既に登録されています。他のメールアドレスで登録してください';
        }else{
            // result=noneの場合
            // データを追加
            $sql = "insert into user(user_mail, username, password) values(?, ?, ?)";
            $result = $this->exec($sql, [$userMail, $userName, $userPass]);
        }        

        if($result){
            return '';
        }else{
            return '失敗';
        }
        
    }

    public function auth($userName, $password){
        $sql = "select * from user where username=? and password=?";
        $stmt = $this->query($sql, [$userName, $password]);
        return $stmt->fetch();
    }
}