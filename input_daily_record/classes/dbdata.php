<?php
class dbdata
{
    protected $pdo;

    // コンストラクタ
    public  function  __construct()
    {
        // PDOオブジェクトを生成する                                                             
        $dsn = 'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1632250-pbl2;charset=utf8';
        $user = 'LAA1632250';
        $password = '9vWqKeipemkaEzZ';
        try {
            $this->pdo = new PDO($dsn, $user, $password);
        } catch (Exception  $e) {
            echo 'Error:' . $e->getMessage();
            die();
        }
    }

    // SELECT文実行用のメソッド
    protected function query($sql, $array_params)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($array_params);
        return $stmt;  // PDOステートメントオブジェクトを返すのでfetch( )、fetchAll( )で結果セットを取得                                   
    }

    // INSERT、UPDATE、DELETE文実行用のメソッド    
    protected function exec($sql, $array_params)
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($array_params);  // 成功：true、失敗：false
        return $stmt;
    }
}
