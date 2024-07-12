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
</head>

<body>
    <div class="input_daily_screen">
        <div class="daily_registration_form">
            <!-- 飲み物を選択するボタン -->
            <div class="drink_kinds">飲み物を選択：
                <button id="drink_button" class="drink_button" data-action="1">ビール</button>
                <button id="drink_button" class="drink_button" data-action="2">日本酒</button>
                <button id="drink_button" class="drink_button" data-action="3">ワイン</button>
                <!-- </select> -->
            </div>
            <!-- 容器を選択するボタン-->
            <div class="glass_kinds">コップの選択：
                <div id="glass_button" class="glass_button"></div>
            </div>
            <form method="POST" action="./add_record.php">
                    <input type="hidden" id="drink_button_info" name="drink_button_info">
                    <input type="hidden" id="glass_button_info" name="glass_button_info">
                    <p><input type="text" name="account_drunk">杯</p>
                    <p>お酒に使った金額：<input type="text" name="alcohol_money">円</p>
                    <!-- 血圧いったんコメントアウトしよう -->
                    <!-- <p>最高血圧：<input type="text" name="sBP"> ／ 最低血圧：<input type="text" name="dBP"></p> -->
                    <p><input type="submit" value="送信"></p>
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

                // 反転
                flag_buttonclick = !flag_buttonclick;
                if (flag_buttonclick){
                    handleButtonClick_drink(action, buttons_glass);
                }
                else{
                    // すべて表示
                    buttons_glass.forEach((btn_glass) => {
                        btn_glass.style.visibility = 'visible';
                    });
                }                
            });
        });
    });
    
    // 飲み物の種類を選択するボタン
    function handleButtonClick_drink(action, buttons_glass) {
        // console.log(buttons_glass);
        
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
                        if (btn_glass_item=='1'|| btn_glass_item=='2' || btn_glass_item=='3'|| btn_glass_item=='4'|| btn_glass_item=='5' || btn_glass_item=='6'){
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
        drink_button_info.value = action;
    }

    // 数を指定
    // square_button.textContentをリストから抽出

    document.addEventListener('DOMContentLoaded', () => {
        buttons_drink.forEach(button => {
            button.addEventListener('click', () => {
                var action = button.getAttribute('data-action');
                // console.log(action);
                if(action==1){
                    var numberInput = 3;
                }else if(action==2 || action==3){
                    var numberInput = 2;
                }

                // 容器を選択するボタンで使用するリスト 
                var beer_glass =  ['', '缶300ml','缶500ml','コップ200ml'];
                var sake_glass = ['', 'おちょこ36ml','おちょこ45ml'];
                var wine_glass = ['', 'コップ200ml','グラス100ml'];

                var beer_glass_action =  ['', 1, 2, 3];
                var sake_glass_action = ['', 7, 8];
                var wine_glass_action = ['', 3, 4];


                if (action==1){
                    var glass_list = beer_glass;
                    var action_list = beer_glass_action;
                } else if (action==2){
                    var glass_list = sake_glass;
                    var action_list = sake_glass_action;
                } else if (action==3){
                    var glass_list = wine_glass;
                    var action_list = wine_glass_action;
                }
                
                // 
                buttons_drink.forEach(d_button => {
                    // document.getElementById('drink_button').addEventListener('click', () => {
                    d_button.addEventListener('click', () => {  
                        const glassButton_square= document.getElementById('glass_button');
                        // console.log(numberInput);
                        glassButton_square.innerHTML = '';

                        square_button_object = {};

                        for(let i=1; i<numberInput+1; i++){
                            const square_button = document.createElement('button');
                            // ここでクラスを指定
                            square_button.classList.add('glass_button_square');
                            // data-actionを指定　setAttribute
                            square_button.setAttribute('data-action', action_list[i].toString()); 

                            // ボタンのテキストを反映させる
                            square_button.textContent = glass_list[i];

                            square_button_object[`button${i}`] = square_button;

                            glassButton_square.appendChild(square_button);
                        }
                        console.log(glassButton_square);

                        // object型ではforeachが使えない
                        for (let glass_key in square_button_object) {
                            if (square_button_object.hasOwnProperty(glass_key)) {
                                console.log(glass_key)
                                console.log(typeof square_button_object[glass_key]);
                                square_button_object[glass_key].addEventListener('click', () => {
                                    handleButtonClick_glass(glass_key);
                                });
                            }
                        }
                        function handleButtonClick_glass(glass_key) {
                            // ボタンを取得状態
                            // 選択状態
                            // console.log('1');
                            var g_button = square_button_object[glass_key];
                            // g_button.classList.toggle('selected');
                            // console.log(typeof g_button.classList);

                            var g_button_action = g_button.getAttribute('data-action');
                            console.log(g_button_action);

                            // 他のボタンをすべて選択解除
                            for (let key in square_button_object) {
                                if (square_button_object.hasOwnProperty(key) && key !== glass_key) {
                                    square_button_object[key].classList.remove('selected');
                                    // square_button_object[key].textContent = `Button ${square_button_object[key].getAttribute('data-action')}`;
                                }
                            }

                            let selectedButton = null;

                            // console.log(g_button.className);

                            // 現在のボタンの選択状態を切り替え
                            if (selectedButton === g_button) {
                                g_button_action.className -= ' selected';
                                // button.textContent = `Button ${button.getAttribute('data-action')}`;
                                selectedButton = null;
                            } else if(g_button.className.includes(' selected')==false) {
                                // console.log(typeof g_button)
                                // console.log(g_button.className)
                                g_button.className += ' selected';
                                // g_button_action.textContent = `Button ${g_button_action.getAttribute('data-action')} (Selected)`;
                                selectedButton = g_button_action;
                                console.log(g_button.className);
                                
                            }
                            console.log(g_button_action);
                            // actionがうまくいってない
                            switch(g_button_action){
                                case '1':
                                    // button.textContent = '缶300ml';
                                    // ここにButton 1の処理を記述
                                    break;
                                case '2':
                                    // button.textContent = '缶500ml';
                                    // ここにButton 2の処理を記述
                                    break;
                                case '3':
                                    // button.textContent = 'コップ200ml';
                                    // ここにButton 3の処理を記述
                                    break;
                                case '4':
                                    // button.textContent = 'グラス100ml';
                                    // ここにButton 3の処理を記述
                                    break;
                                case '5':
                                    // button.textContent = 'シングル30ml';
                                    // ここにButton 3の処理を記述
                                    break;
                                case '6':
                                    // button.textContent = 'ダブル60ml';
                                    // ここにButton 3の処理を記述
                                    break;
                                case '7':
                                    // button.textContent = '２勺36ml';
                                    // ここにButton 3の処理を記述
                                    break;
                                case '8':
                                    // button.textContent = '２勺半45ml';
                                    // ここにButton 3の処理を記述
                                    break;
                                default:
                                    console.log('Unknown action');
                                    break;
                            }
                            glass_button_info.value = g_button_action;
                            console.log(glass_button_info.value);
                        }
                    });
                });
                
            });
        })
    });

    

    // 選択状態を解除
    // square_button_object.forEach(btn => btn.classList.remove('selected'));
    // 選択状態を追加
    // this.classList.add('selected');
    // ボタンごとに別々の処理を実行
    // const action = square_button_object[glass_key].getAttribute('data-action');


    // 条件
    // 1:缶２　こっぷ
    // 2:コップ　グラス
    // 3:ちょこ２  

    // 
    // document.addEventListener('DOMContentLoaded', () => {
    //     var buttons_glass = document.querySelectorAll('.glass_button_square');
    //     var glass_button_info = document.getElementById('glass_button_info');

    //     buttons_glass.forEach((g_button) => {
    //         console.log('a');
    //         g_button.addEventListener('click', function(){
    //             // 中身
    //             // 選択状態を解除
    //             buttons_glass.forEach(btn => btn.classList.remove('selected'));
    //             // 選択状態を追加
    //             this.classList.add('selected');
    //             // ボタンごとに別々の処理を実行
    //             const action = g_button.getAttribute('data-action');
    //             // console.log(buttons_glass)
    //             handleButtonClick_glass(action, buttons_glass);
    //         });
    //     });
    // });

    


    // １
    // 多分これいらない
    // document.addEventListener('DOMContentLoaded', () => {
    //     const glasses_square = document.querySelectorAll('.glass_button_square');

    //     glasses_square.forEach(g_button => {
    //         g_button.addEventListener('click', () => {
    //             const action = button.getAttribute('data-action');
    //             handleAction(action, g_button);
    //         });
    //     });
    // });

    // ２
    // ボタンのvalue属性を使って、ボタンの中身を書く
    // これは効果的なのか
    
    // <button class="glass_button" data-action="1">缶300ml
    // <button class="glass_button" data-action="2">缶500ml</button>
    // <button class="glass_button" data-action="3">コップ200ml</button>
    // <button class="glass_button" data-action="4">グラス100ml</button>
    // <button class="glass_button" data-action="5">シングル30ml</button>
    // <button class="glass_button" data-action="6">ダブル60ml</button>
    // <button class="glass_button" data-action="7">２勺36ml</button>
    // <button class="glass_button" data-action="8">２勺半45ml</button>
    




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