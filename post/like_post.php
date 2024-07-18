<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['id'];

    $stmt = $conn->prepare("UPDATE posts SET likes = likes + 1 WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo 'success';
} else {
    echo 'error';
}
