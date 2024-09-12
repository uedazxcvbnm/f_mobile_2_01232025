<?php
session_start();

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$rname = $name;

require_once __DIR__ . '/team_class.php';
$team = new Team();
$team->addUserTeam($name,$rname, $user_id);
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta http-equiv="Refresh" content="0;URL=../chat/chatscreen.php">
    </head>
</html>