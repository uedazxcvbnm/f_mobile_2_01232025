<?php
$name = $_POST['name'];
$detail = $_POST['detail'];

require_once __DIR__ . '/team_class.php';
$team = new Team();
$team->addTeam($name, $detail);
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>禁酒アプリ</title>
        <link rel="stylesheet" href="../css/team_search.css">
    </head>
    <body>
        <h1>グループが作成されました。</h1>
        <a href="../src/team_search.php"><input type="button" name="back" value="戻る"></a>
    </body>
</html>