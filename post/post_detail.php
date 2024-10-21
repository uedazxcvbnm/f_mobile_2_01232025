<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /team_F_alcohol/login/login_display.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_text'])) {
    $comment_text = $_POST['comment_text'];
    $post_id = $_POST['post_id'];
    $stmt = $conn->prepare("INSERT INTO comments (post_id, comment_text, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $post_id, $comment_text, $current_user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: post_detail.php?id=" . $post_id);
    exit;
}


$post_id = $_GET['id'];
$sql = "SELECT posts.*, user.username FROM posts JOIN user ON posts.user_id = user.user_id WHERE posts.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();


$sql_comments = "SELECT comments.*, user.username FROM comments JOIN user ON comments.user_id = user.user_id WHERE post_id = ?";
$stmt_comments = $conn->prepare($sql_comments);
$stmt_comments->bind_param("i", $post_id);
$stmt_comments->execute();
$result_comments = $stmt_comments->get_result();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿詳細画面</title>
    <?php
        require_once __DIR__ . '../../header/header.php';
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }


        .container {
            margin-top: 50px;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .post-content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .post-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        .post-author {
            font-size: 16px;
            color: #888;
            margin-bottom: 20px;
        }

        .post-body {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .post-tags {
            margin-top: 15px;
            font-size: 14px;
            color: #777;
        }


        .comment-section {
            max-width: 800px;
            margin: 0 auto;
            padding-bottom: 100px;
        }

        .comment-item {
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            width: 60%;
            display: flex;
            background-color: #eef6fa;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        }

        .comment-left {
            justify-content: flex-start;
            background-color: #eef6fa;
            text-align: left;
        }

        .comment-right {
            justify-content: flex-end;
            background-color: #e1f7d5;
            text-align: right;
            margin-left: auto;
        }

        .comment-item strong {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }


        .comment-input {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #fff;
            padding: 15px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .comment-input textarea {
            width: 80%;
            height: 50px;
            resize: none;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
        }

        .comment-input button {
            background-color: #1578c9;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }

        .comment-input button:hover {
            background-color: #136aa7;
        }

        /* 提及用户的样式 */
        .mention-user {
            cursor: pointer;
            color: #1a73e8;
        }

        /* 按钮样式 */
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">

        <h1 class="post-title"><?php echo htmlspecialchars($post['post_title']); ?></h1>


        <p class="post-author">投稿者: <?php echo htmlspecialchars($post['username']); ?> | 投稿日: <?php echo $post['created_at']; ?></p>


        <div class="post-content">
            <p class="post-body"><?php echo nl2br(htmlspecialchars($post['post_content'])); ?></p>
            <p class="post-tags">タグ: <?php echo htmlspecialchars($post['post_tags']); ?></p>
        </div>
    </div>


    <div class="comment-section">
        <h2>コメント一覧</h2>

        <?php while ($comment = $result_comments->fetch_assoc()) {
            $comment_class = ($current_user_id == $comment['user_id']) ? 'comment-right' : 'comment-left';
        ?>
            <div class="comment-item <?php echo $comment_class; ?>">
                <strong><span class="mention-user" onclick="mentionUser('<?php echo htmlspecialchars($comment['username']); ?>')"><?php echo htmlspecialchars($comment['username']); ?></span></strong>
                <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
            </div>
        <?php } ?>
    </div>


    <div class="comment-input">
        <form id="comment-form-bottom" method="POST">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <textarea name="comment_text" placeholder="コメントを入力してください"></textarea>
            <button type="submit">コメントを投稿する</button>
        </form>
    </div>

    <script>
        function mentionUser(username) {
            const textarea = document.querySelector('textarea[name="comment_text"]');
            textarea.value += `@${username} `;
            textarea.focus();
        }
    </script>
</body>

</html>