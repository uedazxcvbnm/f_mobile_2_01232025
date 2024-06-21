<!DOCTYPE html>
<html lang="ja">

<head>
    <title>毎日の記録の入力画面</title>
    <link rel="stylesheet" href="input_daily_record.css">
    <meta charset="UTF-8">
</head>
<body>
    <div class="input_daily_screen">
        <div class="daily_registration_form">
            <!-- 飲み物を選択するボタン -->
            <div class="drink_kinds">飲み物を選択：
                <!-- <select id="drink_kinds_id"> -->
                <!-- <option value='1'> -->
                <button class="drink_button">ビール</button>
                <button class="drink_button">発泡酒</button>
                <button class="drink_button">ワイン</button>
                <!-- </select> -->
            </div>
            <!-- 容器を選択するボタン-->
            <div class="glass_kinds">飲酒量：
                <!-- <select id="glass_kinds_id"> -->
                    <!-- <option value='1'> -->
                    <button class="glass_button">缶300ml</button>
                    <button class="glass_button">缶500ml</button>
                    <button class="glass_button">コップ200ml</button>
                    <button class="glass_button">グラス100ml</button>
                    <button class="glass_button">シングル30ml</button>
                    <button class="glass_button">ダブル60ml</button>
                    <button class="glass_button">２勺36ml</button>
                    <button class="glass_button">２勺半45ml</button>
            </div>
            <form method="POST" action="./add_record.php">
                    <p><input type="text" name="account_drunk">杯</p>
                    <p>お酒に使った金額：<input type="text" name="alcohol_money">円</p>
                    <p>最高血圧：<input type="text" name="sBP"> ／ 最低血圧：<input type="text" name="dBP"></p>
                    <p><input type="submit" value="送信"></p>
            </form>
        </div>
    </div>
</body>
<script>
    var buttons_drink = document.getElementById('.drink_button');
    buttons_drink.forEach(btn => {
        button.addEventListener('click', function(){
            // 中身
            // 選択状態を解除
            btn.classList.remove('selected');
            // 選択状態を追加
            this.classList.add('selected');
        });

    })

    // 
    var buttons_glass = document.querySelectorAll('.glass_button');
    buttons_glass.forEach(btn => {
        button.addEventListener('click', function(){
            // 中身
            // 選択状態を解除
            btn.classList.remove('selected');
            // 選択状態を追加
            this.classList.add('selected');

        });

    })
</script>
</html>