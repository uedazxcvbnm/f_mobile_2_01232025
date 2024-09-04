<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../profile/profile.css">
        <?php
            require_once __DIR__ . '../../header/header.php';
        ?>
    </head>
    <body>
        <h1>プロフィール</h1>
        <img src="../images/3471.png">
        <h2>ユーザーネーム</h2>
        <p>目標</p>
        <textarea readonly>自分の目標を書くところ</textarea>
        <div class="br"><button class="profile_edit_goal">編集</button></div>
        <p>パスワード変更</p>
        <div class="pb"><input type="button" name="password" value="パスワード変更ボタン"></div>
    </body>
</html>