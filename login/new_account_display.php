<!DOCTYPE html>
<html lang="ja">

<head>
    <title>毎日の記録の入力画面</title>
    <link rel="stylesheet" href="login.css">
    <meta charset="UTF-8">
    <script>
        function validateForm() {
            const password = document.forms["registrationForm"]["password_info"].value;
            const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
            const errorMessage = document.getElementById("error_message");

            if (!passwordRegex.test(password)) {
                errorMessage.textContent = "パスワードは8文字以上で、英字と数字を含める必要があります。";
                return false;
            }
            errorMessage.textContent = "";
            return true;
        }
    </script>
</head>

<body>
    <div class="input_daily_screen">
        <div class="daily_registration_form">
            <form name="registrationForm" method="POST" action="register.php" onsubmit="return validateForm()">
                <div id="error_message" style="color: red; margin-bottom: 15px;"></div>
                <div>メールアドレス：<input type="text" name="user_mail_info"></div>
                <div>ユーザー名：<input type="text" name="user_name_info"></div>
                <div>パスワード：<input type="password" name="password_info"></div>
                <!-- <div>パスワード確認：<input type="password" name="password_info"></div> -->
                <p><input type="submit" value="登録" class="login_button"></p>
                <p><a href="../login/login_display.php">登録済みの方はこちら</a></p>
            </form>
        </div>
    </div>

</body>

</html>