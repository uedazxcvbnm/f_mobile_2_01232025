<?php
session_start();

// データベース接続情報
$host = 'localhost';
$dbname = 'pbl2';
$username = 'kobe';
$password = 'denshi';

try {
    // データベースに接続
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ログインしているユーザーのIDをセッションから取得
    if (!isset($_SESSION['user_id'])) {
        echo "ログインが必要です。";
        exit;
    }

    $user_id = $_SESSION['user_id'];

    // ユーザー情報とプロフィールを取得するクエリ
    $sql = "
        SELECT u.username, COALESCE(p.profile_image, '../images/default.png') AS profile_image, 
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
        $profile_image = htmlspecialchars($user['profile_image']);
        $goal = htmlspecialchars($user['goal']);
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
<html>

<head>
    <?php require_once __DIR__ . '../../header/header.php'; ?>
    <link rel="stylesheet" href="../profile/profile.css">
</head>

<body>
    <div class="profile-content">
        <h1>プロフィール</h1>
        <img src="<?php echo $profile_image; ?>" alt="プロフィール画像">
        <h2><?php echo $username; ?></h2>
        <p>目標</p>
        <textarea readonly><?php echo $goal; ?></textarea>

        <!-- 編集ボタンを追加 -->
        <div class="br">
            <a href="profile_edit.php" class="profile_edit_button">プロフィールを編集</a>
        </div>
        <div class="pb">
            <a href="/team_F_alcohol/profile/change_password.php">
                <input type="button" name="password" value="パスワード変更" class="pass_change">
            </a>
        </div>
</body>

</html>