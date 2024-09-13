<?php
session_start();
include 'config.php'; // データベース接続ファイル

// POSTリクエストかどうか確認
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $post_id = $_POST['id'];
    $user_id = $_SESSION['user_id']; // ログインしているユーザーのID

    // 投稿のuser_idを確認して、ログインユーザーが投稿者かどうかチェック
    $stmt = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $stmt->bind_result($post_user_id);
    $stmt->fetch();
    $stmt->close();

    // 投稿者本人のみ削除可能
    if ($post_user_id == $user_id) {
        // 投稿を削除
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->bind_param("i", $post_id);
        if ($stmt->execute()) {
            echo 'success'; // 成功レスポンス
        } else {
            echo 'error'; // エラーレスポンス
        }
        $stmt->close();
    } else {
        echo 'error'; // 削除権限がない場合
    }
} else {
    echo 'error'; // 無効なリクエストの場合
}
