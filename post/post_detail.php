<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /team_F_alcohol/login/login_display.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['like_post'])) {
    $post_id = $_POST['post_id'];

    $check_like_sql = "SELECT * FROM likes WHERE post_id = ? AND user_id = ?";
    $stmt_check_like = $conn->prepare($check_like_sql);
    $stmt_check_like->bind_param("ii", $post_id, $current_user_id);
    $stmt_check_like->execute();
    $result_check_like = $stmt_check_like->get_result();

    if ($result_check_like->num_rows > 0) {

        $unlike_sql = "DELETE FROM likes WHERE post_id = ? AND user_id = ?";
        $stmt_unlike = $conn->prepare($unlike_sql);
        $stmt_unlike->bind_param("ii", $post_id, $current_user_id);
        $stmt_unlike->execute();
        $stmt_unlike->close();
    } else {

        $like_sql = "INSERT INTO likes (post_id, user_id) VALUES (?, ?)";
        $stmt_like = $conn->prepare($like_sql);
        $stmt_like->bind_param("ii", $post_id, $current_user_id);
        $stmt_like->execute();
        $stmt_like->close();
    }

    $stmt_check_like->close();
    header("Location: post_detail.php?id=" . $post_id);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_text'])) {
    $comment_text = $_POST['comment_text'];
    $post_id = $_POST['post_id'];

    if (!empty($comment_text)) {
        $stmt = $conn->prepare("INSERT INTO comments (post_id, comment_text, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $post_id, $comment_text, $current_user_id);
        $stmt->execute();
        $stmt->close();
    }
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

// URLのパラメータから取得
if(isset($_GET['page']) && is_numeric($_GET['page'])) {
    $comment_page = $_GET['page'];
} else {
    $comment_page = 1;
}
// $comment_limitを10より大きくするとなぜか画面がバグる
// chatgptによると変数の遅延評価、キャッシュの影響、PHPの出力バッファリングなどが原因の可能性があるらしい。専門用語は調べてないけど
$comment_limit=10;
$comment_offset = ($comment_page - 1) * $comment_limit;
$comment_count_sql = 'SELECT COUNT(*) as cnt FROM comments';
$stmt = $conn->prepare($comment_count_sql);
$stmt->execute();
$result = $stmt->get_result();
$comment_count = $result->fetch_assoc();
$stmt->close();
// var_dump($comment_count);
$max_page = ceil($comment_count['cnt'] / $comment_limit);


$check_like_sql = "SELECT * FROM likes WHERE post_id = ? AND user_id = ?";
$stmt_check_like = $conn->prepare($check_like_sql);
$stmt_check_like->bind_param("ii", $post_id, $current_user_id);
$stmt_check_like->execute();
$result_check_like = $stmt_check_like->get_result();
$is_liked = ($result_check_like->num_rows > 0); // 判断是否已点赞
$stmt_check_like->close();


// $sql_comments = "SELECT comments.*, user.username FROM comments JOIN user ON comments.user_id = user.user_id WHERE post_id = ?";
$sql_comments = "SELECT comments.*, user.username FROM comments JOIN user ON comments.user_id = user.user_id WHERE post_id = ? LIMIT $comment_limit OFFSET $comment_offset";
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
    <link rel="stylesheet" href="./post_detail.css">
</head>

<body>
    <div class="container">

        <h1 class="post-title"><?php echo htmlspecialchars($post['post_title']); ?></h1>

        <p class="post-author">投稿者: <?php echo htmlspecialchars($post['username']); ?> | 投稿日: <?php echo $post['created_at']; ?></p>

        <div class="post-content">
            <p class="post-body"><?php echo nl2br(htmlspecialchars($post['post_content'])); ?></p>
            <p class="post-tags">タグ: <?php echo htmlspecialchars($post['post_tags']); ?></p>
        </div>

        <!-- 良いね -->
        <form method="POST">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <button type="submit" name="like_post" class="like-btn">
                <?php echo $is_liked ? '<i class="fas fa-thumbs-up"></i>' : '<i class="far fa-thumbs-up"></i>'; ?>
            </button>
        </form>

        <p>いいねの数:
            <?php

            $count_likes_sql = "SELECT COUNT(*) as like_count FROM likes WHERE post_id = ?";
            $stmt_count_likes = $conn->prepare($count_likes_sql);
            $stmt_count_likes->bind_param("i", $post_id);
            $stmt_count_likes->execute();
            $result_count_likes = $stmt_count_likes->get_result();
            $like_count = $result_count_likes->fetch_assoc()['like_count'];
            echo $like_count;
            ?>
        </p>
    </div>

    <div class="comment-section">
        <h2>コメント一覧</h2>

        <?php while ($comment = $result_comments->fetch_assoc()) {
            $comment_class = ($current_user_id == $comment['user_id']) ? 'comment-right' : 'comment-left';
            echo '<div class="comment-item '.$comment_class.'">';
            echo '<strong><span class="mention-user" onclick="mentionUser('.htmlspecialchars($comment['username']).')">'.htmlspecialchars($comment['username']).'</span></strong>';
            echo '<p>'.nl2br(htmlspecialchars($comment['comment_text'])).'</p>';
            echo '</div>';
        } 
        
        $display_number_first = 10*($comment_page-1)+1;
        if ($comment_page == $max_page && $comment_count['cnt'] % 10 !== 0){
            $display_number_last = 10*($comment_page-1)+$comment_count['cnt'] % 10;
        } else{
            $display_number_last = 10*$comment_page;
        }
        // if($page == $max_page && $count['cnt'] % 5 !== 0) {
        //     $to_record = ($page - 1) * 5 + $count['cnt'] % 5;
        // } else {
        //     $to_record = $page * 5;
        // }
        // echo '<div class="pagination">';の上にecho $comment_page;を置かないと、if ($comment_page > 1) {以下のコードがうまく動かない
        // echo '<div class="current_page">現在のページは'.$max_page.'ページ中'.$comment_page.'ページです</div>';
        if($display_number_last % 5 == 1){
            echo '<div class="current_page">現在の表示件数は'.$comment_count['cnt'].'個中'.$display_number_first.'個です</div>';
        } else{
            echo '<div class="current_page">現在の表示件数は'.$comment_count['cnt'].'個中'.$display_number_first.'～'.$display_number_last.'個です</div>';
        }
        echo '<div class="pagination">';
            if ($comment_page > 1) {
                echo '<a class="prevornext" href="?id='.$post_id.'&page='. ($comment_page - 1) . '">&laquo;</a>';
            }else{
                // ボタンが無効の場合のCSS
                echo '<span class="first_last_page">&laquo;</span>';
            }
            // ページの表示範囲
            if ($comment_page==1 || $comment_page==$max_page){
                $page_range = 4;
            } elseif ($comment_page==2 || $comment_page==$max_page-1){
                $page_range = 3;
            } else {
                $page_range = 2;
            }
                
            // <!-- 数字が書かれたボタンで移動 -->
            for ($i=1;$i<=$max_page;$i++){
                if($i >= ($comment_page - $page_range) && $i<=($comment_page + $page_range)){
                    // 現在のページ番号のボタンをクリックしても
                    if($comment_page==$i){
                        // ボタンが無効の場合のCSS
                        echo '<span class="now_page_number">'.$i.'</span>';
                    } else {
                        echo '<a class="page_number" href="?id='.$post_id.'&page='.$i.'">'.$i.'</a>';
                    }
                }
            }
            if($comment_page<$max_page){
                echo '<a class="prevornext" href="?id='.$post_id.'&page='. ($comment_page + 1) . '">&raquo;</a>';
            }else{
                // ボタンが無効の場合のCSS
                echo '<span class="first_last_page">&raquo;</span>';
            }
        echo "</div>";
        ?>        
            
        
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