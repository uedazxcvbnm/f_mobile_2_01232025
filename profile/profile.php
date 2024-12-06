<?php
session_start();

// データベース接続情報
$servername = "mysql311.phy.lolipop.lan";
$username = "LAA1632250";
$password = "9vWqKeipemkaEzZ";
$dbname = "LAA1632250-pbl2";

try {
    // MySQLに接続する
    $pdo = new PDO("mysql:host=$servername;port=3306;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // エラーメッセージを表示する代わりにログに記録し、ユーザーには一般的なメッセージを表示
    error_log("データベース接続エラー: " . $e->getMessage());
    die("データベースへの接続に失敗しました。後ほどもう一度お試しください。");
}

// ログインしているユーザーのIDをセッションから取得
if (!isset($_SESSION['user_id'])) {
    header('Location: ./../login/login_display.php');
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // ユーザー情報とプロフィールを取得するクエリ
    $sql = "
        SELECT u.username, 
               p.profile_image, 
               COALESCE(p.goal, '目標が設定されていません') AS goal 
        FROM user u
        LEFT JOIN profiles p ON u.user_id = p.user_id 
        WHERE u.user_id = :user_id
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    // クエリを実行
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $username = htmlspecialchars($user['username']);
        $profile_image = $user['profile_image'];
        $goal = htmlspecialchars($user['goal']);

        // フルURLを作成
        $base_url = "https://alc-community.catfood.jp/mobile_teamF_alcohol/"; // 正しい公開URLに変更
        if (!empty($profile_image) && strpos($profile_image, 'uploads/images/') === 0) {
            $profile_image_url = $base_url . $profile_image; // パス全体を使用してURLを生成
        } else {
            $profile_image_url = $base_url . "uploads/images/default.jpg";
        }
    } else {
        echo "ユーザー情報が見つかりません。";
        exit;
    }
} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        color: #333;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;

        align-items: flex-start;

        min-height: 100vh;
        padding-top: 150px;
    }


    .profile-container {
        width: 90%;
        max-width: 500px;
        padding: 15px;
        background-color: #fff;
        border-radius: 10px;
        box-sizing: border-box;
        text-align: center;
        margin: 20px auto;

    }


    h1 {
        font-size: 22px;

        font-weight: bold;
        color: #1583de;
        margin-bottom: 15px;

    }


    img {
        display: block;
        margin: 0 auto 15px auto;

        width: 120px;

        height: 120px;
        border-radius: 50%;
        border: 3px solid #1578c9;
    }


    h2 {
        font-size: 18px;

        font-weight: normal;
        color: #444;
        margin-bottom: 10px;
    }


    p {
        font-size: 14px;

        color: #666;
        margin-bottom: 5px;
    }


    textarea {
        width: 100%;

        height: 80px;
        padding: 10px;
        font-size: 14px;

        color: #333;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 5px;
        resize: none;
        margin-bottom: 15px;

        outline: none;
        box-sizing: border-box;
    }


    .profile_edit_button {
        display: block;
        color: #1578c9;
        background-color: #e0f3ff;
        padding: 12px 0;
        border: 1px solid #1578c9;
        border-radius: 5px;
        text-decoration: none;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 15px;
        text-align: center;

    }

    .profile_edit_button:hover {
        background-color: #b3e0ff;
    }


    .pb {
        text-align: center;
        margin-top: 15px;

    }


    .pb input[type="button"] {
        display: block;

        width: 100%;

        color: #1578c9;
        background-color: #e0f3ff;
        padding: 12px 0;

        border: 1px solid #1578c9;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        box-sizing: border-box;
        margin: 0 auto;

    }

    .pb input[type="button"]:hover {
        background-color: #b3e0ff;
    }


    .pass_change {
        color: white;
        background-color: #1578c9;
    }


    .profile-content {
        margin-top: 75px;

        padding: 20px;
        width: 90%;

        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
        background-color: #fff;
        border-radius: 10px;
        box-sizing: border-box;
    }
</style>
<html>

<head>
    <?php require_once __DIR__ . '../../header/header.php'; ?>
    <link rel="stylesheet" href="../profile/profile.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="profile-content">
        <h1>プロフィール</h1>
        <img src="<?php echo htmlspecialchars($profile_image_url . '?t=' . time()); ?>" width="120" height="120"><br>

        <h2><?php echo $username; ?></h2>
        <p>目標</p>
        <textarea readonly><?php echo $goal; ?></textarea>

        <!-- 編集ボタンを追加 -->
        <div class="br">
            <a href="profile_edit.php" class="profile_edit_button"> プロフィールを編集</a>
        </div>
        <div class="pb">
            <a href="/mobile_teamF_alcohol/profile/change_password.php">
                <input type="button" name="password" value="パスワード変更" class="pass_change">
            </a>
        </div>
    </div>
</body>

</html>