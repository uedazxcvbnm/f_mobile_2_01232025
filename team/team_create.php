<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>禁酒アプリ</title>
        <link rel="stylesheet" href="../team/team_create.css">
        <?php
            require_once __DIR__ . '../../header/header.php';
        ?>
    </head>
    <body>
        <h1>新規グループの作成</h1>
        <form method="post" action="../team/team_add.php">
            <p>&emsp;&emsp;グループ名:<input type="text" name="name" required="required"></p>
            <p>グループの詳細:<input type="text" name="detail" required="required"></p>
            <div class="bc"><button type="submit">決定</button></div>
        </form>
    </body>
</html>