<?php
session_start();
// ログインしていないときの処理
if (!isset($_SESSION['user_id'])) {
    header('Location: ./../login/login_display.php');
    exit();
}

require_once __DIR__ . '/../team/team_class.php';
$user_id = $_SESSION['user_id'];
$team = new Team();
$joined_teams = $team->getJoinedTeams($user_id);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>参加グループ一覧</title>
    <link rel="stylesheet" href="../joingrouplist/joingrouplist.css">
    <?php
    require_once __DIR__ . '../../header/header.php';
    ?>
</head>

<body>
    <header>
        <h1>参加しているグループ</h1>
    </header>

    <div class="group-list-container">
        <?php if (empty($joined_teams)) { ?>
            <p id="no-groups-message">参加しているグループはありません</p>
        <?php } else { ?>
            <?php foreach ($joined_teams as $group) { ?>
                <div class="group-item">
                    <div class="group-name"><?= htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="group-members">メンバー数: <?= htmlspecialchars($group['size'], ENT_QUOTES, 'UTF-8') ?></div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>




</body>

</html>