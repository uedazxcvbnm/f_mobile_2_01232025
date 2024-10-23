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
        echo json_encode(['status' => 'error', 'message' => '必要なデータが不足しています。']);
        exit();
    }

    try {
        $team = new Team();
        $team->leaveTeam($team_id, $user_id);   // グループから退会（joined_teams テーブルから削除）
        $team->decreaseTeamSize($team_id);      // グループの人数を減少させる
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        // エラーメッセージの詳細をログに記録し、出力
        echo json_encode(['status' => 'error', 'message' => '退会処理中にエラーが発生しました: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => '無効なリクエストです。']);
}
