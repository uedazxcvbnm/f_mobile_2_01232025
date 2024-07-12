
<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <title>毎日の記録の入力画面</title>
    <link rel="stylesheet" href="login.css">
    <meta charset="UTF-8">
</head>

<body>
    <div class="input_daily_screen">
        <div class="daily_registration_form">
            <form method="POST" action="login_exec.php">
                <div>ユーザー名：<input type="text" name="user_name_info"></div>
                <div>パスワード：<input type="password" name="password_info"></div>
                <p><input type="submit" value="送信"></p>
            </form>
        </div>
    </div>
    

</body>
</html>