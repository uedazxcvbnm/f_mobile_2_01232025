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
            <form method="POST" action="register.php">
                <div>メールアドレス：<input type="text" name="user_mail_info"></div>
                <div>ユーザー名：<input type="text" name="user_name_info"></div>
                <div>パスワード：<input type="password" name="password_info"></div>
                <!-- <div>パスワード確認：<input type="password" name="password_info"></div> -->
                <p><input type="submit" value="登録" class="login_button"></p>
            </form>
        </div>
    </div>
    

</body>
</html>