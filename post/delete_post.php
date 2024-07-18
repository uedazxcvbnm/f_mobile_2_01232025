<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['id'];


    $sql_comments = "DELETE FROM comments WHERE post_id = ?";
    $stmt_comments = $conn->prepare($sql_comments);
    $stmt_comments->bind_param("i", $post_id);
    $stmt_comments->execute();
    $stmt_comments->close();


    $sql = "DELETE FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
    $stmt->close();
}
