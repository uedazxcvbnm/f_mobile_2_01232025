<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_id = $_POST['id'];
    $comment_text = $_POST['comment_text'];
    $sql = "UPDATE comments SET comment_text = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $comment_text, $comment_id);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
    $stmt->close();
}
