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
                <button class="drink_button" data-action="1">ビール</button>
                <button class="drink_button" data-action="2">日本酒</button>
                <button class="drink_button" data-action="3">ワイン</button>
                <!-- </select> -->
            </div>
            <!-- 容器を選択するボタン-->
            <div class="glass_kinds">飲酒量：
                <!-- <select id="glass_kinds_id"> -->
                    <!-- <option value='1'> -->
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
    document.addEventListener('DOMContentLoaded', () => {
        var buttons_drink = document.querySelectorAll('.drink_button');
        buttons_drink.forEach((button) => {
            button.addEventListener('click', function(){
                // 中身
                
                // 選択状態を解除
                // btn.classList.remove('selected');
                buttons_drink.forEach(
                    btn => btn.classList.remove('selected')
                );
                // 選択状態を追加
                this.classList.add('selected');

                // ボタンごとに別々の処理を実行
                const action = button.getAttribute('data-action');

                var buttons_glass = document.querySelectorAll('.glass_button');

                handleButtonClick_drink(action, buttons_glass);                
            });
        });

        

        
    });

    document.addEventListener('DOMContentLoaded', () => {
        var buttons_glass = document.querySelectorAll('.glass_button');
        buttons_glass.forEach((button) => {
            button.addEventListener('click', function(){
                // 中身
                // 選択状態を解除
                // btn.classList.remove('selected');
                buttons_glass.forEach(btn => btn.classList.remove('selected'));
                // 選択状態を追加
                this.classList.add('selected');
                // ボタンごとに別々の処理を実行
                const action = button.getAttribute('data-action');
                console.log(buttons_glass)
                handleButtonClick_drink(action, buttons_glass);
            });

        });
    });

    function handleButtonClick_drink(action, buttons_glass) {
        console.log(buttons_glass);
        
        switch(action) {
            case '1':
                buttons_glass.forEach((btn_glass) => {
                    var btn_glass_item = btn_glass.getAttribute('data-action');
                    // console.log(btn_glass_item);
                    if (btn_glass_item =='4'|| btn_glass_item=='5' || btn_glass_item=='6'|| btn_glass_item=='7'|| btn_glass_item=='8'){
                        btn_glass.style.visibility = 'hidden';
                    }
                });
                // ここにButton 1の処理を記述
                break;
            case '2':
                buttons_glass.forEach((btn_glass) => {
                        var btn_glass_item = btn_glass.getAttribute('data-action');
                        // console.log(btn_glass_item);
                        if (btn_glass_item=='1'|| btn_glass_item=='2' || btn_glass_item=='3'|| btn_glass_item=='4'|| btn_glass_item=='5'){
                            btn_glass.style.visibility = 'hidden';
                        }
                    });
                // ここにButton 2の処理を記述
                break;
            case '3':
                buttons_glass.forEach((btn_glass) => {
                        var btn_glass_item = btn_glass.getAttribute('data-action');
                        if (btn_glass_item=='1'|| btn_glass_item=='2' || btn_glass_item=='5'|| btn_glass_item=='6'|| btn_glass_item=='7' || btn_glass_item=='8'){
                            btn_glass.style.visibility = 'hidden';
                        }
                    });
                // ここにButton 3の処理を記述
                break;
            default:
                console.log('Unknown action');
                break;
        }
    }

    function handleButtonClick_glass(action) {
        switch(action) {
            case '1':
                console.log('Button 1 clicked');
                return 1;
                // ここにButton 1の処理を記述
                break;
            case '2':
                console.log('Button 2 clicked');
                return 2;
                // ここにButton 2の処理を記述
                break;
            case '3':
                console.log('Button 3 clicked');
                return 3;
                // ここにButton 3の処理を記述
                break;
            case '4':
                console.log('Button 4 clicked');
                return 4;
                // ここにButton 3の処理を記述
                break;
            case '5':
                console.log('Button 5 clicked');
                return 5;
                // ここにButton 3の処理を記述
                break;
            case '6':
                console.log('Button 6 clicked');
                return 6;
                // ここにButton 3の処理を記述
                break;
            case '7':
                console.log('Button 7 clicked');
                return 7;
                // ここにButton 3の処理を記述
                break;
            case '8':
                console.log('Button 8 clicked');
                return 8;
                // ここにButton 3の処理を記述
                break;
            default:
                console.log('Unknown action');
                break;
        }
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

    // // 
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