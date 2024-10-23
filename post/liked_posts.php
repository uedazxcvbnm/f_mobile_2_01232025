<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /team_F_alcohol/login/login_display.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];


$sql = "
    SELECT posts.*, user.username, likes.is_favorite
    FROM likes 
    JOIN posts ON likes.post_id = posts.id
    JOIN user ON posts.user_id = user.user_id
    WHERE likes.user_id = ? 
    ORDER BY likes.is_favorite DESC, posts.created_at DESC";  // 按 is_favorite 排序，优先显示特にお気に入り的帖子
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>いいね一覧画面</title>
    <link rel="stylesheet" href="post_list.css">
    <?php
    require_once __DIR__ . '../../header/header.php';
    ?>
    <style>
        body {
            padding-top: 80px;

        }

        .like-list-container {
            margin-top: 30px;
            max-width: 800px;
            margin: 0 auto;
        }

        .post-item {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .post-title {
            font-size: 20px;
            color: #333;
        }

        .post-author {
            font-size: 14px;
            color: #888;
            margin-bottom: 10px;
        }

        .post-body {
            margin: 10px 0;
            color: #555;
        }

        .post-tags {
            font-size: 12px;
            color: #777;
        }

        .unlike-btn {
            background-color: #FF6347;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }

        .unlike-btn:hover {
            background-color: #FF4500;
        }


        .view-details {
            background-color: #1578c9;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            margin-right: 10px;
            text-decoration: none;
            display: inline-block;

            width: auto;

        }

        .view-details:hover {
            background-color: #125ca8;
        }

        .favorite-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            margin-top: 10px;
        }

        .favorite-btn.active {
            background-color: #FFD700;
        }
    </style>
</head>

<body>
    <div class="like-list-container">
        <h1>いいねした投稿一覧</h1>

        <?php if ($result->num_rows > 0) { ?>
            <?php while ($post = $result->fetch_assoc()) { ?>
                <div class="post-item">
                    <h2 class="post-title"><?php echo htmlspecialchars($post['post_title']); ?></h2>
                    <p class="post-author">投稿者: <?php echo htmlspecialchars($post['username']); ?> | 投稿日: <?php echo $post['created_at']; ?></p>
                    <p class="post-body"><?php echo nl2br(htmlspecialchars($post['post_content'])); ?></p>
                    <p class="post-tags">タグ: <?php echo htmlspecialchars($post['post_tags']); ?></p>

                    <!-- 詳細を見るボタン -->
                    <a href="post_detail.php?id=<?php echo $post['id']; ?>" class="view-details">詳細を見る</a>

                    <!--いいねボタン消す -->
                    <form method="post" action="unlike_post.php" style="display: inline;">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <button type="submit" class="unlike-btn">いいねを取り消す</button>
                    </form>

                    <!-- 特にお気に入りボタン -->
                    <form method="post" action="favorite_post.php" style="display: inline;">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <button type="submit" class="favorite-btn 
                        <?php echo ($post['is_favorite']) ? 'active' : ''; ?>">
                            <?php echo ($post['is_favorite']) ? '特にお気に入りを解除' : '特にお気に入り'; ?>
                        </button>
                    </form>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>まだいいねした投稿はありません。</p>
        <?php } ?>
    </div>
</body>

</html>