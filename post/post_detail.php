<?php
include 'config.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_text'])) {
    $comment_text = $_POST['comment_text'];
    $post_id = $_POST['post_id'];
    $stmt = $conn->prepare("INSERT INTO comments (post_id, comment_text) VALUES (?, ?)");
    $stmt->bind_param("is", $post_id, $comment_text);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
    $stmt->close();
    exit;
}


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

$post_id = $_GET['id'];
$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();


$sql_comments = "SELECT * FROM comments WHERE post_id = ?";
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
    <link rel="stylesheet" href="post_detail.css">
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

        p {
            text-align: center;
        }

        .post-image {
            display: block;
            margin: 20px auto;
            max-width: 100%;
            height: auto;
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
            padding-bottom: 100px;
            /* Ensure enough space for the fixed input */
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
    </style>
</head>

<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($post['post_title']); ?></h1>
        <p>投稿日: <?php echo $post['created_at']; ?></p>
        <p><?php echo nl2br(htmlspecialchars($post['post_content'])); ?></p>
        <!-- <img src="uploads/<?php /*echo htmlspecialchars(basename($post['post_image'])); */?>" alt="投稿画像" class="post-image"> -->
        <p><a href="<?php echo htmlspecialchars($post['post_url']); ?>">参考URL</a></p>
        <p>タグ: <?php echo htmlspecialchars($post['post_tags']); ?></p>
        <p>いいねの数: <?php echo $post['likes']; ?></p>
        <button class="like-btn" onclick="likePost(<?php echo $post['id']; ?>)">いいね</button>
        <button class="edit-btn" onclick="window.location.href='edit_post.php?id=<?php echo $post['id']; ?>'">編集</button>
        <button class="delete-btn" onclick="deletePost(<?php echo $post['id']; ?>)">削除</button>
    </div>



    <h2>コメント一覧</h2>
    <div id="comment-list" class="comment-list">
        <?php while ($comment = $result_comments->fetch_assoc()) { ?>
            <div class="comment-item" id="comment-<?php echo $comment['id']; ?>">
                <p><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                <p>いいねの数: <?php echo $comment['likes']; ?></p>
                <button onclick="likeComment(<?php echo $comment['id']; ?>)">いいね</button>
                <button onclick="editComment(<?php echo $comment['id']; ?>)">編集</button>
                <button onclick="deleteComment(<?php echo $comment['id']; ?>)">削除</button>
            </div>
        <?php } ?>
    </div>
    </div>

    <div class="comment-input">
        <form id="comment-form-bottom">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <textarea name="comment_text" placeholder="コメントを入力してください"></textarea>
            <button type="button" onclick="addComment()">コメントを投稿する</button>
        </form>
    </div>

    <script>
        function likePost(postId) {
            fetch('like_post.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + postId
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        location.reload();
                    } else {
                        alert('いいねに失敗しました。');
                    }
                });
        }

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
                            window.location.href = 'post_list.php';
                        } else {
                            alert('削除に失敗しました。');
                        }
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
                        location.reload();
                    } else {
                        alert('コメントの投稿に失敗しました。');
                    }
                });
        }

        function likeComment(commentId) {
            fetch('like_comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + commentId
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        location.reload();
                    } else {
                        alert('いいねに失敗しました。');
                    }
                });
        }

        function editComment(commentId) {
            const commentItem = document.getElementById('comment-' + commentId);
            const commentText = commentItem.querySelector('p').innerText;
            const newCommentText = prompt('コメントを編集してください:', commentText);
            if (newCommentText !== null) {
                fetch('edit_comment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'id=' + commentId + '&comment_text=' + encodeURIComponent(newCommentText)
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'success') {
                            location.reload();
                        } else {
                            alert('コメントの編集に失敗しました。');
                        }
                    });
            }
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
                        if (data === 'success') {
                            location.reload();
                        } else {
                            alert('コメントの削除に失敗しました。');
                        }
                    });
            }
        }
    </script>
</body>

</html>