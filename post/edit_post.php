<?php
include 'config.php';

$post_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_title = $_POST['post_title'];
    $post_content = $_POST['post_content'];
    $post_url = $_POST['post_url'];
    $post_tags = $_POST['post_tags'];
    $target_file = "";

    if (!empty($_FILES["post_image"]["name"])) {
        $target_dir = "/Applications/XAMPP/xamppfiles/htdocs/team_F_alcohol/uploads/";
        $target_file = $target_dir . basename($_FILES["post_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (move_uploaded_file($_FILES["post_image"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("UPDATE posts SET post_image=? WHERE id=?");
            $stmt->bind_param("si", $target_file, $post_id);
            $stmt->execute();
        }
    }

    $stmt = $conn->prepare("UPDATE posts SET post_title=?, post_content=?, post_url=?, post_tags=? WHERE id=?");
    $stmt->bind_param("ssssi", $post_title, $post_content, $post_url, $post_tags, $post_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "<script>
    alert('投稿が編集されました。');
    window.location.href = 'post_detail.php?id=" . $post_id . "';
    </script>";
}

$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="post_article.css">
    <title>投稿編集 - 禁酒サイト</title>
</head>

<body>
    <div class="container">
        <h2 style="text-align: center;">投稿編集フォーム</h2>
        <form action="edit_post.php?id=<?php echo $post_id; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="post_title">タイトル:</label>
                <input type="text" id="post_title" name="post_title" value="<?php echo htmlspecialchars($post['post_title']); ?>" placeholder="タイトルを入力（任意）">
            </div>
            <div class="form-group">
                <label for="post_content">本文:</label>
                <textarea id="post_content" name="post_content" placeholder="投稿内容を入力してください" rows="8"><?php echo htmlspecialchars($post['post_content']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="post_url">参考にしたサイトのURL:</label>
                <input type="url" id="post_url" name="post_url" value="<?php echo htmlspecialchars($post['post_url']); ?>" placeholder="参考にしたサイトのURLを入力">
            </div>
            <div class="form-group">
                <label for="post_image">画像:</label>
                <input type="file" id="post_image" name="post_image">
                <small>※ 画像は任意です</small>
            </div>
            <div class="form-group">
                <label for="post_tags">タグ:</label>
                <input type="text" id="post_tags" name="post_tags" value="<?php echo htmlspecialchars($post['post_tags']); ?>" placeholder="タグをカンマ区切りで入力">
                <small>※ 複数のタグを追加可能です</small>
            </div>
            <div class="form-group" style="text-align: center;">
                <input type="submit" name="submit" value="編集する">
            </div>
        </form>
    </div>
</body>

</html>