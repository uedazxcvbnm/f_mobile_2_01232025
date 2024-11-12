<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /team_F_alcohol/login/login_display.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];


// 查询语句，确保只显示全体公开的帖子
$sql = "SELECT posts.*, user.username 
        FROM posts 
        JOIN user ON posts.user_id = user.user_id 
        WHERE posts.visibility = 'public' AND (posts.team_id IS NULL OR posts.team_id = 0)
        ORDER BY posts.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿一覧画面</title>
    <?php require_once __DIR__ . '../../header/header.php'; ?>
    <style>
        .container {
            width: 100%;
            /* 调整容器宽度 */
            margin: 0 auto;
            /* 确保居中 */
            margin-top: 100px;
            padding: 0;
            /* 移除额外的内边距 */
            text-align: center;
        }

        h1 {
            color: #333;
        }

        /* 按钮容器的样式 */
        .button-container {
            display: flex;
            justify-content: center;
            /* 居中对齐按钮 */
            gap: 20px;
            /* 按钮之间的间距 */
            margin: 20px 0 30px 0;
        }

        /* 按钮样式 */
        .liked-posts-button,
        .create-post-button {
            display: inline-block;
            background-color: #0056df;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .liked-posts-button:hover,
        .create-post-button:hover {
            background-color: #45a049;
        }

        /* 投稿内容样式 */
        .post-item {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            /* 添加内部边距 */
            margin: 10px auto;
            /* 上下间距，确保左右居中 */
            border-radius: 5px;
            text-align: left;
            width: 100%;
            /* 保持占满容器宽度 */
            box-sizing: border-box;
            /* 确保边框和内边距计算在宽度内 */
        }

        .post-item h2 {
            margin-top: 0;
        }

        .post-item p {
            margin: 5px 0;
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
        <!-- 投稿一览标题 -->
        <h1>投稿一覧</h1>

        <!-- いいねしたリスト按钮和投稿を作成する按钮 -->
        <div class="button-container">
            <a href="liked_posts.php" class="liked-posts-button">いいねしたリスト</a>
            <a href="post_article.php" class="create-post-button">投稿を作成する</a>
        </div>

        <!-- 投稿内容 -->
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="post-item">
                    <h2><?php echo htmlspecialchars($row['post_title']); ?></h2>
                    <p>投稿者: <?php echo htmlspecialchars($row['username']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($row['post_content'])); ?></p>
                    <p>投稿日: <?php echo $row['created_at']; ?></p>

                    <!-- 显示当前帖子的点赞数量 -->
                    <p>いいねの数:
                        <?php
                        $post_id = $row['id'];
                        $like_count_sql = "SELECT COUNT(*) as like_count FROM likes WHERE post_id = ?";
                        $stmt_like_count = $conn->prepare($like_count_sql);
                        $stmt_like_count->bind_param("i", $post_id);
                        $stmt_like_count->execute();
                        $result_like_count = $stmt_like_count->get_result();
                        $like_count = $result_like_count->fetch_assoc()['like_count'];
                        echo $like_count;
                        $stmt_like_count->close();
                        ?>
                    </p>

                    <p><a href="post_detail.php?id=<?php echo $row['id']; ?>">詳細を見る</a></p>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>投稿がありません。</p>
        <?php } ?>
    </div>