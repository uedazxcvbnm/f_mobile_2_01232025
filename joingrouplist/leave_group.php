<?php
session_start();
// ログインしていないときの処理
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'ログインされていません。']);
    exit();
}

require_once __DIR__ . '/../team/team_class.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $team_id = $_POST['team_id'];

    // デバッグ: 受け取ったデータを確認
    if (empty($team_id) || empty($user_id)) {
        error_log("退会処理: 必要なデータが不足しています - team_id: $team_id, user_id: $user_id");
        echo json_encode(['status' => 'error', 'message' => '必要なデータが不足しています。']);
        exit();
    }

    try {
        $team = new Team();
        $team->leaveTeam($team_id, $user_id);
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        // エラーメッセージの詳細をログに記録し、出力
        error_log("退会処理中にエラーが発生しました: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => '退会処理中にエラーが発生しました。']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => '無効なリクエストです。']);
}
