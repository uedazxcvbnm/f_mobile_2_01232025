<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0);// HTTPS環境でない場合は 0 に設定
ini_set('session.use_strict_mode', 1);
session_start();


require_once __DIR__ . '/user.php';

$loginError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = $_POST['user_name_info'];
    $password = $_POST['password_info'];

    $user = new User();
    $loginResult = $user->auth($userName, $password);

    if (is_string($loginResult)) { // エラーメッセージが返ってきた場合
        $loginError = $loginResult;
    } else {
        // ログイン成功
        session_regenerate_id(true);
        $_SESSION['user_id'] = $loginResult['user_id'];
        $_SESSION['username'] = $loginResult['username'];
        header('Location: welcome.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ログイン画面</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="login_container">
        <h2>ログイン</h2>
        <?php if ($loginError): ?>
            <p class="error_message"><?php echo htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div>ユーザー名：<input type="text" name="user_name_info" required></div>
            <div>パスワード：<input type="password" name="password_info" required></div>
            <p><input type="submit" value="ログイン" class="login_button"></p>
        </form>
    </div>
</body>

</html>