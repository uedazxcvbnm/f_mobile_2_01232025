<?php
    session_start();
    // ログインしていないときの処理
    if (!isset($_SESSION['user_id'])){
        header('Location: ./../login/login_display.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <title>毎日の記録の入力画面</title>
    <link rel="stylesheet" href="input_daily_record.css">
    <meta charset="UTF-8">
    <?php
        require_once __DIR__ . '../../header/header.php';
    ?>
</head>

<body>
    <div class="input_daily_screen">
        <div class="daily_registration_form">
            <h1>飲酒記録を登録</h1>
            <div>酒を飲んだら送信</div>
            <button id="drink_button" class="drink_button" data-action="1">はい</button>
            <button id="drink_button" class="drink_button" data-action="2">いいえ</button>
            <form method="POST" action="./add_record_count.php">
                <input type="hidden" id="drink_button_info" name="drink_button_info">
                <p><input type="submit" value="送信" class="record_button"></p>
            </form>
        </div>
    </div>
</body>
<script>
    var flag_buttonclick = false;

    var buttons_drink = document.querySelectorAll('.drink_button');
    var drink_button_info = document.getElementById('drink_button_info');
    // 飲み物の種類を選択するボタン
    document.addEventListener('DOMContentLoaded', () => {
        buttons_drink.forEach((button) => {
            button.addEventListener('click', function(){
                // 選択状態を解除
                // btn.classList.remove('selected');
                buttons_drink.forEach(
                    btn => btn.classList.remove('selected')
                );
                // 選択状態を追加
                this.classList.add('selected');
            })
        })
    });
</script>
</html>