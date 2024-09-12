<?php
session_start();

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$rname = $name;
$detail = $_POST['detail'];

require_once __DIR__ . '/team_class.php';
$team = new Team();
$team->addTeam($name, $detail);
$team->addUserTeam($name, $rname, $user_id)
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>禁酒アプリ</title>
        <link rel="stylesheet" href="../css/team_create.css">
    </head>
    <body>
        <h1>グループが作成されました。</h1>
        <a href="../team/team_search.php"><div class="bc"><button>戻る</button></div></a>
    </body>
</html>