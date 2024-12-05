<?php
session_start();
// ログインしていないときの処理
if (!isset($_SESSION['user_id'])) {
    header('Location: ./../login/login_display.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <title>毎日の記録の入力画面</title>
    <link rel="stylesheet" href="input_alchol_count.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <?php
    require_once __DIR__ . '/./../header/header.php';
    ?>
</head>
<style>
    body {
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .input_daily_screen {
        background-color: #e1e9f3;
        padding: 20px;
        border-radius: 10px;
        box-sizing: border-box;
        width: 90%;
        max-width: 500px;
        margin: 0 auto;
    }

    .daily_registration_form {
        margin: 0;
        padding: 20px;
        border: 0;
        background-color: #ffffff;
        border-radius: 10px;
        box-sizing: border-box;
        width: 100%;
    }

    h1 {
        text-align: center;
        font-size: 24px;
        margin-bottom: 20px;
    }

    .button,
    .alchol_data_button {
        background-color: #fef9ed;
        border: none;
        color: #1578c9;
        padding: 15px 0;
        text-align: center;
        text-decoration: none;
        display: block;
        font-size: 16px;
        margin: 10px 0;
        cursor: pointer;
        border-radius: 8px;
        width: 100%;
        box-sizing: border-box;
    }

    .alchol_data_button.selected {
        border: 2.5px solid #FFD700;
        background-color: #5797cb;
        color: #fff;
    }

    .record_button {
        background-color: #5797cb;
        color: white;
        width: 100%;
        padding: 15px 0;
        font-size: 18px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        box-sizing: border-box;
        margin-top: 20px;
    }

    .record_button:hover {
        background-color: #FFD700;
        color: black;
    }
</style>

<body>
    <div class="input_daily_screen">
        <div class="daily_registration_form">
            <h1>飲酒した時間帯を記録</h1>
            <div>飲酒をしたらはいと答え、飲酒を我慢できたらいいえと答える</div>
            <button id="alchol_data_yes" class="alchol_data_button">飲酒した</button>
            <button id="alchol_data_no" class="alchol_data_button">飲酒を我慢した</button>
            <form id="alcholdata_form" method="POST" action="./add_record_count.php">
                <input type="hidden" id="alchol_data_button_info" name="alchol_data_button_info">
                <p><input type="submit" value="送信" class="record_button"></p>
            </form>
        </div>
    </div>
</body>
<script>
    var flag_buttonclick = false;

    // var buttons_alchol_data = document.querySelectorAll('.alchol_data_button');
    var yesbutton = document.getElementById('alchol_data_yes');
    var nobutton = document.getElementById('alchol_data_no');

    var alchol_data_button_info = document.getElementById('alchol_data_button_info');


    // 飲み物の種類を選択するボタン
    document.addEventListener('DOMContentLoaded', () => {
        // はい　がクリックされたときの処理
        yesbutton.addEventListener("click", function() {
            document.getElementById("alchol_data_button_info").value = 1; // 値を1に設定
            toggleSelected_inputAlchol(alchol_data_yes, alchol_data_no);
            // document.getElementById("alcholdata_form").submit();  // フォームを送信
        });

        // いいえ　がクリックされたときの処理
        nobutton.addEventListener("click", function() {
            document.getElementById("alchol_data_button_info").value = 2; // 値を2に設定
            toggleSelected_inputAlchol(alchol_data_no, alchol_data_yes);
            // document.getElementById("alcholdata_form").submit();  // フォームを送信
        });
    });

    // selected クラスの追加・解除を行う関数
    function toggleSelected_inputAlchol(selectedButton, otherButton) {
        selectedButton.classList.add('selected');
        otherButton.classList.remove('selected');
    }

    // document.addEventListener('DOMContentLoaded', () => {
    //     buttons_alchol_data.forEach((button) => {
    //         button.addEventListener('click', function(){
    //             // 選択状態を解除
    //             buttons_alchol_data.forEach(
    //                 btn => btn.classList.remove('selected')
    //             );
    //             // 選択状態を追加
    //             this.classList.add('selected');

    //             // ボタンごとに別々の処理を実行
    //             const action_alchol_data = button.getAttribute('data-action');

    //             flag_buttonclick = !flag_buttonclick;
    //             if (flag_buttonclick){
    //                 handleButtonClick_alcholdata(action_alchol_data);
    //             }
    //         })
    //     })
    // });
</script>

</html>