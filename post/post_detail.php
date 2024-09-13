<?php
session_start();
include 'config.php'; // 确保包含数据库连接文件

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_text'])) {
    // 如果用户没有登录，跳转到登录页面
    if (!isset($_SESSION['user_id'])) {
        header("Location: /team_F_alcohol/login/login_display.php");
        exit;
    }

    // 如果已经登录，继续处理评论
    $comment_text = $_POST['comment_text'];
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];  // 从会话中获取登录的用户ID

    $stmt = $conn->prepare("INSERT INTO comments (post_id, comment_text, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $post_id, $comment_text, $user_id);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
    $stmt->close();
    exit;
}

// 处理点赞评论的请求
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['like_comment'])) {
    $comment_id = $_POST['like_comment'];
    $stmt = $conn->prepare("UPDATE comments SET likes = likes + 1 WHERE id = ?");
    $stmt->bind_param("i", $comment_id);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
    $stmt->close();
    exit;
}

// 处理删除评论的请求
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_comment'])) {
    $comment_id = $_POST['delete_comment'];
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->bind_param("i", $comment_id);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
    $stmt->close();
    exit;
}

// 获取文章详情
$post_id = $_GET['id'];
$sql = "SELECT posts.*, user.username 
        FROM posts 
        JOIN user ON posts.user_id = user.user_id 
        WHERE posts.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

// 获取评论列表
$sql_comments = "
    SELECT comments.*, user.username 
    FROM comments 
    JOIN user ON comments.user_id = user.user_id 
    WHERE post_id = ?";
$stmt_comments = $conn->prepare($sql_comments);
$stmt_comments->bind_param("i", $post_id);
$stmt_comments->execute();
$result_comments = $stmt_comments->get_result();

// 現在のユーザーのID
$current_user_id = $_SESSION['user_id'];

// 投稿の取得
$post_id = $_GET['id'];
$sql = "SELECT posts.*, user.username 
        FROM posts 
        JOIN user ON posts.user_id = user.user_id 
        WHERE posts.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <?php
    require_once __DIR__ . '../../header/header.php';
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>投稿詳細画面</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .post-content {
            font-size: 18px;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            color: #333;
        }

        .post-content p {
            margin-bottom: 15px;
        }

        button {
            display: block;
            margin: 10px auto;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        .like-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .edit-btn {
            background-color: #FFC107;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .delete-btn {
            background-color: #F44336;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .comment-input {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #fff;
            padding: 10px;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            z-index: 100;
            height: 80px;
            box-sizing: border-box;
        }

        .comment-input form {
            display: flex;
            justify-content: center;
            align-items: center;
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
        }

        .comment-section {
            padding-bottom: 200px;
        }

        .comment-item {
            margin: 10px 0;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }

        .comment-item button {
            margin: 5px;
        }

        .container {
            margin-top: 80px;
        }

        .comment-list {
            padding-bottom: 120px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($post['post_title']); ?></h1>
        <p>投稿者: <?php echo htmlspecialchars($post['username']); ?></p>
        <p>投稿日: <?php echo $post['created_at']; ?></p>
        <div class="post-content">
            <p><?php echo nl2br(htmlspecialchars($post['post_content'])); ?></p>
        </div>
        <p><a href="<?php echo htmlspecialchars($post['post_url']); ?>">参考URL</a></p>
        <p>タグ: <?php echo htmlspecialchars($post['post_tags']); ?></p>
        <p>いいねの数: <?php echo $post['likes']; ?></p>

        <?php
        // 投稿者本人のみ編集・削除ボタンを表示
        if ($current_user_id == $post['user_id']) {
            echo '<button class="edit-btn" onclick="window.location.href=\'edit_post.php?id=' . $post['id'] . '\'">編集</button>';
            echo '<button class="delete-btn" onclick="deletePost(' . $post['id'] . ')">削除</button>';
        }
        ?>
        <button class="like-btn" onclick="likePost(<?php echo $post['id']; ?>)">
            <i class="fas fa-thumbs-up"></i> いいね
        </button>
    </div>

    <h2>コメント一覧</h2>
    <div id="comment-list" class="comment-list">
        <?php
        // コメント一覧を取得
        $sql_comments = "
            SELECT comments.*, user.username 
            FROM comments 
            JOIN user ON comments.user_id = user.user_id 
            WHERE post_id = ?";
        $stmt_comments = $conn->prepare($sql_comments);
        $stmt_comments->bind_param("i", $post_id);
        $stmt_comments->execute();
        $result_comments = $stmt_comments->get_result();

        while ($comment = $result_comments->fetch_assoc()) { ?>
            <div class="comment-item" id="comment-<?php echo $comment['id']; ?>">
                <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong></p>
                <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                <p>いいねの数: <?php echo $comment['likes']; ?></p>

                <?php
                // コメント投稿者本人のみ編集・削除ボタンを表示
                if ($current_user_id == $comment['user_id']) {
                    echo '<button onclick="editComment(' . $comment['id'] . ')">編集</button>';
                    echo '<button onclick="deleteComment(' . $comment['id'] . ')">削除</button>';
                }
                ?>
                <button onclick="likeComment(<?php echo $comment['id']; ?>)">いいね</button>
            </div>
        <?php } ?>
    </div>

    <div class="comment-input">
        <form id="comment-form-bottom">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <textarea name="comment_text" placeholder="コメントを入力してください"></textarea>
            <button type="button" onclick="addComment()">コメントを投稿する</button>
        </form>
    </div>

    <script>
        // 投稿のいいね処理
        function likePost(postId) {
            fetch('like_post.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'post_id=' + postId
                })
                .then(response => response.text())
                .then(data => {
                    location.reload(); // 成功後にページをリロード
                });
        }

        // 投稿の削除処理
        function deletePost(postId) {
            if (confirm('本当に削除しますか？')) {
                fetch('delete_post.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id=' + postId
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'success') {
                            window.location.href = 'post_list.php'; // 成功後にリダイレクト
                        } else {
                            alert('削除に失敗しました。');
                        }
                    })
                    .catch(error => {
                        console.error('エラー:', error);
                        alert('削除リクエスト中にエラーが発生しました。');
                    });
            }
        }

        // コメントのいいね処理
        function likeComment(commentId) {
            fetch('like_comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'comment_id=' + commentId
                })
                .then(response => response.text())
                .then(data => {
                    location.reload(); // 成功後にページをリロード
                });
        }

        function deleteComment(commentId) {
            if (confirm('本当に削除しますか？')) {
                fetch('delete_comment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id=' + commentId
                    })
                    .then(response => response.text())
                    .then(data => {
                        location.reload(); // 成功後にページをリロード
                    });
            }
        }

        function addComment() {
            const form = document.getElementById('comment-form-bottom');
            const formData = new FormData(form);
            fetch('post_detail.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        location.reload(); // 成功後にページをリロード
                    } else {
                        alert('コメントの投稿に失敗しました。');
                    }
                });
        }
    </script>
</body>

</html>