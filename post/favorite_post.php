<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /team_F_alcohol/login/login_display.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];
$post_id = $_POST['post_id'];


$sql = "SELECT is_favorite FROM likes WHERE user_id = ? AND post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $current_user_id, $post_id);
$stmt->execute();
$result = $stmt->get_result();
$like = $result->fetch_assoc();
$stmt->close();


if ($like['is_favorite']) {
    $sql = "UPDATE likes SET is_favorite = FALSE WHERE user_id = ? AND post_id = ?";
} else {
    $sql = "UPDATE likes SET is_favorite = TRUE WHERE user_id = ? AND post_id = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $current_user_id, $post_id);
$stmt->execute();
$stmt->close();


header("Location: liked_posts.php");
exit;
