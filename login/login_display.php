<?php
session_start();
// session_destroy();
require_once __DIR__ . '/user.php';

$loginMessage = ''; // 初期化されたメッセージ変数
$penaltyRemainingTime = 0; // 残りペナルティ時間の初期化

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = $_POST['user_name_info'];
    $password = $_POST['password_info'];

    $user = new User();
    $loginResult = $user->auth($userName, $password);

    // ログイン結果の判定
    if (is_string($loginResult)) {
        // エラーメッセージが返ってきた場合
        if (preg_match('/アカウントがロックされています。あと (\d+)分 (\d+)秒後に再試行してください。/', $loginResult, $matches)) {
            $loginMessage = "アカウントがロックされています。";
            $penaltyRemainingTime = ($matches[1] * 60) + $matches[2]; // ペナルティの残り時間を秒に変換
        } else {
            $loginMessage = $loginResult;
        }
    } else {
        // ログイン成功、セッション設定とリダイレクト
        $_SESSION['user_id'] = $loginResult['user_id'];
        $_SESSION['username'] = $loginResult['username'];
        header('Location: welcome.php');
        exit(); // リダイレクト後のコード実行を防止
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <title>毎日の記録の入力画面</title>
    <link rel="stylesheet" href="login.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <script>
        // ペナルティ残り時間をカウントダウンするJavaScript
        let penaltyTime = <?php echo $penaltyRemainingTime; ?>;

        function updatePenaltyCountdown() {
            if (penaltyTime > 0) {
                const minutes = Math.floor(penaltyTime / 60);
                const seconds = penaltyTime % 60;
                document.getElementById("penalty_timer").innerText = `${minutes}分 ${seconds}秒後に再試行できます`;
                penaltyTime--;

                // 1秒ごとに更新
                setTimeout(updatePenaltyCountdown, 1000);
            } else {
                // ペナルティが解除されたらタイマーを消してフォームを有効化
                // document.getElementById("penalty_timer").innerText = "";
                //document.getElementById("login_form").style.display = "block";
                // document.getElementById("other_account_button").style.display = "none";

                // <!-- 11/27書き換えあるいは追加 -->
                // ペナルティが解除されたらメッセージをクリアし、ページの再読み込みを促す
                document.getElementById("penalty_timer").innerText = "ペナルティ時間が終了しました。ページを再読み込みしてください。";
                document.querySelector(".message").innerText = ""; // 「アカウントがロックされています」を消去
            }
        }

        window.onload = function() {
            if (penaltyTime > 0) {
                document.getElementById("login_form").style.display = "none";
                document.getElementById("other_account_button").style.display = "block";
                updatePenaltyCountdown();
            }
        };

        // 11/27書き換えあるいは追加
        function showLoginForm() {
            // document.getElementById("login_form").style.display = "block";
            // document.getElementById("penalty_timer").innerText = "";
            // document.getElementById("other_account_button").style.display = "none";
            location.reload();
        }
    </script>
</head>

<body>
    <div class="input_daily_screen">
        <div class="daily_registration_form">
            <h2>ログイン</h2>

            <!-- メッセージ表示部分 -->
            <?php if ($loginMessage): ?>
                <!-- 11/27書き換えあるいは追加 -->
                <p class="error_message"><?php echo htmlspecialchars($loginMessage, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>

            <!-- ペナルティ残り時間表示部分 -->
            <p id="penalty_timer"></p>

            <!-- 別のアカウントでログインボタン -->
            <!-- 11/27書き換えあるいは追加 -->
            <button id="other_account_button" style="display:none;" onclick="showLoginForm()">ページを再読み込み</button>

            <!-- ログインフォーム -->
            <form id="login_form" method="POST" action="login_display.php" style="display: <?php echo $penaltyRemainingTime > 0 ? 'none' : 'block'; ?>;">
                <div>ユーザー名：<input type="text" name="user_name_info" required></div>
                <div>パスワード：<input type="password" name="password_info" required></div>
                <p><input type="submit" value="ログイン" class="login_button"></p>
            </form>
            
            <!-- 11/27書き換えあるいは追加 -->
            <!-- <a href="new_account_display.php">アカウントがない場合新規登録</a> -->
            <a href="new_account_display.php" style="display: <?php echo $penaltyRemainingTime > 0 ? 'none' : 'block'; ?>;">アカウントがない場合新規登録</a>
        </div>
    </div>
</body>

</html>