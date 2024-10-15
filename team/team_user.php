<?php
session_start();

$user_id = $_SESSION['user_id'];
$team_id = $_POST['team_id'];


require_once __DIR__ . '/team_class.php';
$team = new Team();
$team->joinedTeam($team_id, $user_id);
$team->addTeamSize($team_id)
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta http-equiv="Refresh" content="0;URL=../chat/chatscreen.php">
    </head>
</html>