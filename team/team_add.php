<?php
session_start();

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$detail = $_POST['detail'];

require_once __DIR__ . '/team_class.php';
$team = new Team();
$team->addTeam($name, $detail);
$new_team = $team->getNewTeam();

foreach($new_team as $team){
}
$new_team_id = $team;
$product = new Team();
$product->joinedTeam($new_team_id, $user_id);
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