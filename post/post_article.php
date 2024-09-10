<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_title = $_POST['post_title'];
    $post_content = $_POST['post_content'];
    $post_url = $_POST['post_url'];
    $post_tags = $_POST['post_tags'];
    $target_file = "";

    if (!empty($_FILES["post_image"]["name"])) {
        $target_dir = "/Applications/XAMPP/xamppfiles/htdocs/team_F_alcohol/uploads/";
        $file_name = basename($_FILES["post_image"]["name"]);
        $target_file = $target_dir . $file_name;
        $web_path = "/team_F_alcohol/uploads/" . $file_name;

        if (move_uploaded_file($_FILES["post_image"]["tmp_name"], $target_file)) {
        } else {
            echo "<script>alert('画像DBに登録できない');</script>";
            exit;
        }
    }


    // $stmt = $conn->prepare("INSERT INTO posts (post_title, post_content, post_url, post_image, post_tags) VALUES (?, ?, ?, ?, ?)");
    $stmt = $conn->prepare("INSERT INTO posts (post_title, post_content, post_url, post_tags) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $post_title, $post_content, $post_url, $post_tags);
    $stmt->execute();
    $new_post_id = $stmt->insert_id;
    $stmt->close();
    $conn->close();


    echo "<script>
    alert('投稿成功、3秒後に投稿一覧画面に遷移します。');
    setTimeout(function(){
        window.location.href = 'post_list.php';
    }, 3000);
    </script>";
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>禁酒サイト - 投稿画面</title>
    <link rel="stylesheet" href="post_article.css">
    <?php
            require_once __DIR__ . '../../header/header.php';
    ?>
</head>

<body>
    <div class="container">
        <h2 style="text-align: center;">禁酒サイト - 投稿フォーム</h2>
        <form action="post_article.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="post_title">タイトル:</label>
                <input type="text" id="post_title" name="post_title" placeholder="タイトルを入力（任意）">
            </div>
            <div class="form-group">
                <label for="post_content">本文:</label>
                <textarea id="post_content" name="post_content" placeholder="投稿内容を入力してください" rows="8"></textarea>
            </div>
            <div class="form-group">
                <label for="post_url">参考にしたサイトのURL:</label>
                <input type="url" id="post_url" name="post_url" placeholder="参考にしたサイトのURLを入力">
            </div>
            <div class="form-group">
                <label for="post_image">画像:</label>
                <input type="file" id="post_image" name="post_image">
                <small>※ 画像は任意です</small>
            </div>
            <div class="form-group">
                <label for="post_tags">タグ:</label>
                <input type="text" id="post_tags" name="post_tags" placeholder="タグをカンマ区切りで入力">
                <small>※ 複数のタグを追加可能です</small>
            </div>
            <div class="form-group" style="text-align: center;">
                <input type="submit" name="submit" value="投稿する">
            </div>
        </form>
    </div>
</body>

</html>