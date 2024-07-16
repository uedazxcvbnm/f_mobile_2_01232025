<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿一覧画面</title>
    <link rel="stylesheet" href="post_list.css">
    <?php
            require_once __DIR__ . '../../header/header.php';
    ?>
</head>

<body>
    <div class="container">
        <h1>投稿一覧画面</h1>
        <?php
        include 'config.php';

        $sql = "SELECT * FROM posts ORDER BY created_at DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='post-item'>";
                echo "<h2>" . htmlspecialchars($row['post_title']) . "</h2>";
                echo "<p>" . nl2br(htmlspecialchars($row['post_content'])) . "</p>";
                echo "<p><a href='" . htmlspecialchars($row['post_url']) . "'>参考URL</a></p>";
                echo "<p>タグ: " . htmlspecialchars($row['post_tags']) . "</p>";
                echo "<p>投稿日: " . $row['created_at'] . "</p>";
                echo "<p>いいねの数: " . $row['likes'] . "</p>";
                echo "<p><a href='post_detail.php?id=" . $row['id'] . "'>詳細を見る</a></p>";
                echo "</div>";
            }
        } else {
            echo "<p>投稿がありません。</p>";
        }

        $conn->close();
        ?>
    </div>
</body>

</html>