<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /team_F_alcohol/login/login_display.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];


$sql = "SELECT posts.*, user.username FROM posts JOIN user ON posts.user_id = user.user_id ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿一覧画面</title>
    <?php
    require_once __DIR__ . '../../header/header.php';
    ?>
    <style>
        /* 全体のスタイル */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
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

        /* 投稿一覧のスタイル */
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


        body {
            padding-top: 80px;
        }

        /* いいねしたリストボタンのスタイル */
        .liked-posts-button {
            position: absolute;
            top: 200px;
            left: 130px;
            background-color: #0056df;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .liked-posts-button:hover {
            background-color: #45a049;
        }

        /* 投稿を作成するボタンのスタイル */
        .create-post-button {
            position: absolute;
            top: 200px;
            right: 130px;
            background-color: #0056df;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .create-post-button:hover {
            background-color: #45a049;
        }
    </style>

</head>

<body>
    <div class="container">
        <!-- いいねしたリストボタン -->
        <a href="liked_posts.php" class="liked-posts-button">いいねしたリスト</a>

        <!-- 投稿を作成するボタン -->
        <a href="post_article.php" class="create-post-button">投稿を作成する</a>
        <h1>投稿一覧</h1>

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
</body>

</html>