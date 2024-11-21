<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ./login_display.php');
    exit();
}

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ウェルカムページ</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #e6f0ff;
            margin: 0;
            display: flex;
            justify-content: center;

            align-items: center;

            min-height: 100vh;
            font-family: Arial, sans-serif;
        }

        .welcome_container {
            text-align: center;
            background-color: #e6f0ff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 90%;

            margin: 0 auto;
            box-sizing: border-box;

        }

        .welcome_container h1 {
            font-size: 22px;

            color: #1578c9;
            margin-bottom: 15px;
        }

        .welcome_container p {
            font-size: 14px;

            color: #333333;
            margin: 10px 0;
        }

        .welcome_actions {
            margin-top: 20px;
        }

        .welcome_actions a {
            display: block;

            text-decoration: none;
            color: #ffffff;
            background-color: #1578c9;
            padding: 12px 0;
            border-radius: 5px;
            margin: 10px 0;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        .welcome_actions a:hover {
            background-color: #105ea6;
        }
    </style>
</head>

<body>
    <div class="welcome_container">
        <h1>ようこそ、<?php echo $username; ?>さん！</h1>
        <div class="welcome_actions">
            <a href="../input_daily_record/input_alchol_count.php">記録を入力する</a>
            <a href="../notification/notification.php">通知画面へ</a>
            <a href="../login/logout_display.php">ログアウト</a>
        </div>
    </div>
</body>

</html>