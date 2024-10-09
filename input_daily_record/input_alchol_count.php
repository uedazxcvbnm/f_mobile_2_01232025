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
            <button id="alchol_data_yes" class="alchol_data_boolean">はい</button>
            <button id="alchol_data_no" class="alchol_data_boolean">いいえ</button>
            <form id="alcholdata_form" method="POST" action="./add_record_count.php">
                <input type="hidden" id="alchol_data_boolean_info" name="alchol_data_boolean_info">
                <p><input type="submit" value="送信" class="record_button"></p>
            </form>
        </div>
    </div>
</body>
<script>
    var flag_buttonclick = false;

    var buttons_dalchol_data = document.querySelectorAll('.alchol_data_boolean');
    var alchol_data_boolean_info = document.getElementById('alchol_data_boolean_info');
    // 飲み物の種類を選択するボタン
    document.addEventListener('DOMContentLoaded', () => {
        // はい　がクリックされたときの処理
        document.getElementById("alchol_data_yes").addEventListener("click", function () {
            document.getElementById("alchol_data_boolean_info").value = 1;  // 値を1に設定
            // document.getElementById("alcholdata_form").submit();  // フォームを送信
        });

        // いいえ　がクリックされたときの処理
        document.getElementById("alchol_data_no").addEventListener("click", function () {
            document.getElementById("alchol_data_boolean_info").value = 2;  // 値を2に設定
            // document.getElementById("alcholdata_form").submit();  // フォームを送信
        });
    });

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