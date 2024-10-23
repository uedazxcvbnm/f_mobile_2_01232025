<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// team_id が POST データに存在するか確認
if (isset($_POST['team_id'])) {
    $team_id = $_POST['team_id'];
} else {
    die("team_id が指定されていません");
}

$user_id = $_SESSION['user_id'];

require_once __DIR__ . '/team_class.php';
$team = new Team();
try {
    // チームへの参加処理を実行
    $team->joinedTeam($team_id, $user_id);
    $team->addTeamSize($team_id);

    // 参加処理が成功した後のリダイレクト
    header("Location: ../chat/chatscreen.php?group_id=" . urlencode($team_id));
    exit();
} catch (Exception $e) {
    die("参加処理中にエラーが発生しました: " . $e->getMessage());
}
