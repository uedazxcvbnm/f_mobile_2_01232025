<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /team_F_alcohol/login/login_display.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];

// 获取URL参数中的team_id
$team_id = isset($_GET['team_id']) ? (int)$_GET['team_id'] : null;
if (!$team_id) {
    echo "無効なグループIDです。";
    exit;
}

// 验证用户是否属于该团队
$sql_check_team = "SELECT * FROM joined_teams WHERE user_id = ? AND team_id = ?";
$stmt_check_team = $conn->prepare($sql_check_team);
$stmt_check_team->bind_param("ii", $current_user_id, $team_id);
$stmt_check_team->execute();
$result_check_team = $stmt_check_team->get_result();

if ($result_check_team->num_rows === 0) {
    echo "このグループにアクセス権がありません。";
    exit;
}
$stmt_check_team->close();

// 获取该团队的名称
$sql_team_name = "SELECT name FROM teams WHERE team_id = ?";
$stmt_team_name = $conn->prepare($sql_team_name);
$stmt_team_name->bind_param("i", $team_id);
$stmt_team_name->execute();
$result_team_name = $stmt_team_name->get_result();
$team_name = $result_team_name->fetch_assoc()['name'];
$stmt_team_name->close();

// 获取该团队的帖子
$sql = "SELECT posts.*, user.username 
        FROM posts 
        JOIN user ON posts.user_id = user.user_id 
        WHERE posts.team_id = ? 
        ORDER BY posts.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $team_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($team_name); ?> - 限定公開 - タイムライン</title>
    <?php require_once __DIR__ . '../../header/header.php'; ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
            padding-top: 80px;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            margin-top: 100px;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .create-post-button {
            display: block;
            margin: 0 auto;
            background-color: #0056df;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            width: fit-content;
        }

        .create-post-button:hover {
            background-color: #45a049;
        }

        .post-item {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .post-item h2 {
            margin-top: 0;
        }

        .post-item p {
            margin: 5px 0;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .post-item a {
            color: #1a73e8;
            text-decoration: none;
        }

        .post-item a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($team_name); ?> - 限定公開 - タイムライン</h1>
        <!-- 投稿を作成する按钮 -->
        <a href="group_post_article.php?team_id=<?php echo $team_id; ?>" class="create-post-button">投稿を作成する</a>

        <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="post-item">
                    <h2><?php echo htmlspecialchars($row['post_title']); ?></h2>
                    <p>投稿者: <?php echo htmlspecialchars($row['username']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($row['post_content'])); ?></p>
                    <p>投稿日: <?php echo $row['created_at']; ?></p>
                    <p><a href="group_post_detail.php?id=<?php echo $row['id']; ?>&team_id=<?php echo $row['team_id']; ?>">詳細を見る</a></p>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>投稿がありません。</p>
        <?php } ?>
    </div>
</body>

</html>