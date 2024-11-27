<?php
// 受け取る
$userMail = $_POST['user_mail_info'];
$userName = $_POST['user_name_info'];
$userPass = $_POST['password_info'];

require_once __DIR__ . '/user.php';
$user_object = new User();
$user_object->signUp($userMail, $userName, $userPass);

// 将来的
// redirect();
// exit();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <!-- 11/27書き換えあるいは追加 -->
    <title>アカウント登録完了</title>
    <link rel="stylesheet" href="register.css">
</head>

<body>
    <!-- 現在 -->
    <div class="container">
        <h2>アカウントを登録しました</h2>
        <p><a href="login_display.php">ログイン画面に移動</a></p>
    <div>
</body>