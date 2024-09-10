<?php
    // 受け取る
    $userMail = $_POST['user_mail_info'];
    $userName = $_POST['user_name_info'];
    $userPass = $_POST['password_info'];

    require_once __DIR__.'/user.php';
    $user_object = new User();
    $user_object->signUp($userMail, $userName, $userPass);

    // 将来的
    // redirect();
    // exit();
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <title>毎日の記録の入力画面</title>
    <link rel="stylesheet" href="login.css">
    <meta charset="UTF-8">
</head>

<body>
    <!-- 現在 -->
    <h2>登録成功</h2>
    <p><a href="login_display.php">ログイン画面に移動</a></p>
</body>
