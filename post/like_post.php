<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['id'];

    // いいねの数を更新するSQLクエリ
    $sql = "UPDATE posts SET likes = likes + 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
    $stmt->close();
}

$conn->close();
