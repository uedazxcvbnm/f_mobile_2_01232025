<?php
session_start();  // 启动会话

// 检查用户是否已经登录
if (!isset($_SESSION['user_id'])) {
    // 如果用户没有登录，重定向到登录页面
    header("Location: /team_F_alcohol/login/login_display.php");
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_title = $_POST['post_title'];
    $post_content = $_POST['post_content'];
    $post_url = $_POST['post_url'];
    $post_tags = $_POST['post_tags'];
    $user_id = $_SESSION['user_id'];  // 获取当前登录用户的ID

    $stmt = $conn->prepare("INSERT INTO posts (post_title, post_content, post_url, post_tags, user_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $post_title, $post_content, $post_url, $post_tags, $user_id);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();

        // 投稿成功后重定向
        echo "<script>
        alert('投稿成功、3秒後に投稿一覧画面に遷移します。');
        setTimeout(function(){
            window.location.href = 'post_list.php';
        }, 3000);
        </script>";
    } else {
        echo "<script>alert('投稿に失敗しました。');</script>";
    }
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
                <label for="post_tags">タグ:</label>
                <input type="text" id="post_tags" name="post_tags" placeholder="タグが複数の場合：スペースで区切る
">
                <small>※ 複数のタグを追加可能です</small>
            </div>
            <div class="form-group" style="text-align: center;">
                <input type="submit" name="submit" value="投稿する">
            </div>
        </form>
    </div>
</body>

</html>