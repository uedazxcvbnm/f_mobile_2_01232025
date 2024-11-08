<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_id = $_POST['comment_id'];
    $user_id = $_SESSION['user_id'];

    // 检查用户是否已经点赞过
    $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND comment_id = ?");
    $stmt->bind_param("ii", $user_id, $comment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // 如果已经点赞，则取消点赞
        $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND comment_id = ?");
        $stmt->bind_param("ii", $user_id, $comment_id);
        $stmt->execute();

        // 减少评论的点赞数
        $stmt = $conn->prepare("UPDATE comments SET likes = likes - 1 WHERE id = ?");
        $stmt->bind_param("i", $comment_id);
        $stmt->execute();

        echo 'unliked';
    } else {
        // 如果没有点赞，则添加点赞记录
        $stmt = $conn->prepare("INSERT INTO likes (user_id, comment_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $comment_id);
        $stmt->execute();

        // 增加评论的点赞数
        $stmt = $conn->prepare("UPDATE comments SET likes = likes + 1 WHERE id = ?");
        $stmt->bind_param("i", $comment_id);
        $stmt->execute();

        echo 'liked';
    }
}
