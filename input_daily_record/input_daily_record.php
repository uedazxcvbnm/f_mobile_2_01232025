<!DOCTYPE html>
<html lang="ja">

<head>
    <title>毎日の記録の入力画面</title>
    <link rel="stylesheet" href="input_daily_record.css">
    <meta charset="UTF-8">
</head>
<body>
    <div class="input_daily_screen">
    <form method="POST" action="./add_record.php">
        <div class="daily_registration_form">
            <p>飲酒量：<input type="text" name="account_drunk">ml</p>
            <p>お酒に使った金額：<input type="text" name="alcohol_money">円</p>
            <p>最高血圧：<input type="text" name="sBP"> ／ 最低血圧：<input type="text" name="dBP"></p>
            <p><input type="submit" value="送信"></p>
        </div>
    </form>
    </div>
</body>
</html>