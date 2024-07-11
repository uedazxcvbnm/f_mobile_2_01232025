<?php
    session_start();

    $user_name_info = $_POST['user_name_info'];
    $password_info = $_POST['password_info'];

    require_once __DIR__.'/user.php';
    $user_table = new User();
    $login_result = $user_table->auth($user_name_info, $password_info);

    if(empty($login_result['user_id'])){
        $login_error = 'ユーザーID、パスワードを確認してください';
    } else{
        // セッションに　を格納
        $_SESSION['user_id'] = $login_result['user_id'];
        $_SESSION['username'] = $login_result['username'];
        $_SESSION['password'] = $login_result['password'];
    }
?>

<html lang="ja">

<head>
	<meta charset="UTF-8">
</head>

<html>
<body>
<?php
    // ログインしていないときはログイン画面に移動
    if (!isset($_SESSION['user_id'])){
        header('Location: ./login_display.php');
        exit();
    }
    if (empty($login_error)){
        echo 'こんにちは';
        echo '<p><a href="logout_display.php">ログアウト</a></p>';
    } else{
        echo 'ユーザーID、パスワードが違います';
        echo '<p><a href="login_display.php">ログインページへ</a></p>';
    }
?>
</body>
</html>