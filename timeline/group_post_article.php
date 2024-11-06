<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /team_F_alcohol/login/login_display.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];

// 获取 URL 中的 team_id 参数
$team_id = isset($_GET['team_id']) ? (int)$_GET['team_id'] : null;
if (!$team_id) {
    echo "無効なグループIDです。";
    exit;
}

// 获取组名
$sql_team_name = "SELECT name FROM teams WHERE team_id = ?";
$stmt_team_name = $conn->prepare($sql_team_name);
$stmt_team_name->bind_param("i", $team_id);
$stmt_team_name->execute();
$result_team_name = $stmt_team_name->get_result();
$team_data = $result_team_name->fetch_assoc();
$team_name = $team_data['name'] ?? '未知のグループ'; // 如果没有找到组名，使用默认名称
$stmt_team_name->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 获取表单数据
    $post_title = $_POST['post_title'];
    $post_content = $_POST['post_content'];
    $post_url = $_POST['post_url'];
    $post_tags = $_POST['post_tags'];

    // 插入投稿
    $stmt = $conn->prepare("INSERT INTO posts (post_title, post_content, post_url, post_tags, user_id, team_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssii", $post_title, $post_content, $post_url, $post_tags, $current_user_id, $team_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();

        // 投稿成功后重定向到团队的 timeline 页面
        header("Location: timeline.php?team_id=" . $team_id);
        exit;
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
    <title><?php echo htmlspecialchars($team_name); ?> - 限定公開 - 投稿フォーム</title>
    <?php require_once __DIR__ . '../../header/header.php'; ?>
    <link rel="stylesheet" href="group_post_article.css">
</head>

<body>
    <div class="container">
        <h2 style="text-align: center;"><?php echo htmlspecialchars($team_name); ?> - 限定公開 - 投稿フォーム</h2>
        <form action="group_post_article.php?team_id=<?php echo $team_id; ?>" method="post">
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
                <input type="text" id="post_tags" name="post_tags" placeholder="タグが複数の場合：スペースで区切る">
                <small>※ 複数のタグを追加可能です</small>
            </div>
            <div class="form-group" style="text-align: center;">
                <input type="submit" name="submit" value="投稿する">
            </div>
        </form>
    </div>
</body>

</html>