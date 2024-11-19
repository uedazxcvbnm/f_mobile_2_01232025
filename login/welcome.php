<?php
session_start();

// ログインしていない場合はログインページにリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: ./login_display.php');
    exit();
}

// ユーザー情報を取得
$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ウェルカムページ</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .welcome_container {
            text-align: center;
            background-color: #e6f0ff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            margin: 50px auto;
            font-family: Arial, sans-serif;
        }

        .welcome_container h1 {
            font-size: 24px;
            color: #1578c9;
        }

        .welcome_container p {
            font-size: 16px;
            color: #333333;
            margin: 10px 0;
        }

        .welcome_actions a {
            display: inline-block;
            text-decoration: none;
            color: #ffffff;
            background-color: #1578c9;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 10px;
            transition: background-color 0.3s;
        }

        .welcome_actions a:hover {
            background-color: #105ea6;
        }

        .welcome_actions {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="welcome_container">
        <h1>ようこそ、<?php echo $username; ?>さん！</h1>
        <div class="welcome_actions">
            <p><a href="../input_daily_record/input_alchol_count.php">記録を入力する</a></p>
            <p><a href="../notification/notification.php">通知画面へ</a></p>
            <p><a href="../login/logout_display.php">ログアウト</a></p>
        </div>
    </div>
</body>

</html>