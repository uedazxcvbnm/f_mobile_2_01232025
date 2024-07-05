<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .like-btn,
        .edit-btn,
        .delete-btn {
            background-color: #ccc;
            color: white;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <?php
    include 'config.php';


    $post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($post_id <= 0) {
        die('无效的ID。');
    }


    $sql = "SELECT * FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows === 0) {
        echo "<p>投稿がありません。</p>";
    } else {
        $post = $result->fetch_assoc();
        echo "<h1>" . htmlspecialchars($post['post_title']) . "</h1>";
        echo "<p>投稿日: " . $post['created_at'] . "</p>";
        echo "<p>" . nl2br(htmlspecialchars($post['post_content'])) . "</p>";
        if (!empty($post['post_image'])) {
            echo "<img src='" . htmlspecialchars($post['post_image']) . "' alt='Post Image' class='post-image'>";
        }
        echo "<p>タグ: " . htmlspecialchars($post['post_tags']) . "</p>";
        echo "<p>いいねの数: " . $post['likes'] . "</p>";
        echo "<button class='like-btn' onclick='likePost(" . $post['id'] . ")'>いいね</button>";
        echo "<button class='edit-btn' onclick='window.location.href=\"edit_post.php?id=" . $post['id'] . "\"'>編集</button>";
        echo "<button class='delete-btn' onclick='deletePost(" . $post['id'] . ")'>削除</button>";
    }

    $stmt->close();
    $conn->close();
    ?>
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
    </script>
</body>

</html>