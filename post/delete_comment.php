<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_id = $_POST['id'];
    $sql = "DELETE FROM comments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $comment_id);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
    $stmt->close();
}
