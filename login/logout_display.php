<!DOCTYPE html>
<html lang="ja">
<?php
    session_start();
    $_SESSION = array();
    session_destroy();
?>


<head>
    <meta charset="UTF-8">
    <title>ログアウト完了</title>
    <link rel="stylesheet" href="register.css">
</head>

<body>
    <div class="container">
        <h3>ログアウトしました</h3>
        <p><a href="login_display.php" class="button">ログインページへ</a></p>
    </div>
</body>

</html>