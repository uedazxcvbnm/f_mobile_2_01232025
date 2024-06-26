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
                <button class="drink_button" data-action="1">ビール</button>
                <button class="drink_button" data-action="2">日本酒</button>
                <button class="drink_button" data-action="3">ワイン</button>
            </div>
            <!-- 容器を選択するボタン-->
            <div class="glass_kinds">グラスの選択：
                <button class="glass_button" data-action="1">缶300ml</button>
                <button class="glass_button" data-action="2">缶500ml</button>
                <button class="glass_button" data-action="3">コップ200ml</button>
                <button class="glass_button" data-action="4">グラス100ml</button>
                <button class="glass_button" data-action="5">シングル30ml</button>
                <button class="glass_button" data-action="6">ダブル60ml</button>
                <button class="glass_button" data-action="7">２勺36ml</button>
                <button class="glass_button" data-action="8">２勺半45ml</button>
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
    // var flag_buttonclick = false;
    let lastClickedButton = null;
    document.addEventListener('DOMContentLoaded', () => {
        var buttons_drink = document.querySelectorAll('.drink_button');

        const buttonStates = new Map();

        // ボタンクリック処理
        buttons_drink.forEach((button) => {
            buttonStates.set(button, false);
            button.addEventListener('click', function() {
                // 中身

                // 選択状態を解除
                buttons_drink.forEach(btn => btn.classList.remove('selected'));
                // 设置当前按钮为选中状态
                this.classList.add('selected');

                // 選択状態を追加
                const action = button.getAttribute('data-action');
                var buttons_glass = document.querySelectorAll('.glass_button');

                // 対応するコンテナボタンを表示または非表示にします

                handleButtonClick_drink(action, buttons_glass);

                lastClickedButton = button;
            });
        });

        var buttons_glass = document.querySelectorAll('.glass_button');
        buttons_glass.forEach((button) => {
            button.addEventListener('click', function() {

                if (this.classList.contains('selected')) {
                    this.classList.remove('selected');
                } else {
                    buttons_glass.forEach(btn => btn.classList.remove('selected'));
                    this.classList.add('selected');
                }
                handleButtonClick_glass(this.getAttribute('data-action'));
            });
        });
    });

    function handleButtonClick_drink(action, buttons_glass) {

        buttons_glass.forEach((btn_glass) => {
            btn_glass.style.visibility = 'visible';
            var btn_glass_item = btn_glass.getAttribute('data-action');
            if (action == '1' && ['4', '5', '6', '7', '8'].includes(btn_glass_item)) {
                btn_glass.style.visibility = 'hidden';
            } else if (action == '2' && ['1', '2', '3', '4', '5'].includes(btn_glass_item)) {
                btn_glass.style.visibility = 'hidden';
            } else if (action == '3' && ['1', '2', '5', '6', '7', '8'].includes(btn_glass_item)) {
                btn_glass.style.visibility = 'hidden';
            }
        });
        sendDataToPHP_btndrink(action);
    }

    function handleButtonClick_glass(action) {

        sendDataToPHP_btnglass(action);
    }

    function sendDataToPHP_btndrink(action) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'add_record_2.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log('Response from PHP:', xhr.responseText);
                } else {
                    console.error('Error:', xhr.status);
                }
            }
        };
        xhr.send(`action_drink=${action}`);
    }

    function sendDataToPHP_btnglass(action) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'add_record_2.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log('Response from PHP:', xhr.responseText);
                } else {
                    console.error('Error:', xhr.status);
                }
            }
        };
        xhr.send(`action_glass=${action}`);
    }


    // var buttons_drink = document.querySelectorAll('.drink_button');
    // buttons_drink.forEach((btn) => {
    //     btn.addEventListener('click', function(){
    //         // 中身
    //         // 選択状態を解除
    //         btn.classList.remove('selected');
    //         // 選択状態を追加
    //         this.classList.add('selected');
    //     });
    // });

    // var buttons_glass = document.querySelectorAll('.glass_button');
    // buttons_glass.forEach((btn) => {
    //     button.addEventListener('click', function(){
    //         // 中身
    //         // 選択状態を解除
    //         btn.classList.remove('selected');
    //         // 選択状態を追加
    //         this.classList.add('selected');
    //     });
    // })
</script>

</html>