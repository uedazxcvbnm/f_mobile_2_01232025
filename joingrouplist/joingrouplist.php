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
    require_once __DIR__ . '/../header/header.php';
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
                <div class="group-item" data-group-id="<?= htmlspecialchars($group['team_id'], ENT_QUOTES, 'UTF-8') ?>" data-group-name="<?= htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8') ?>" data-group-size="<?= htmlspecialchars($group['size'], ENT_QUOTES, 'UTF-8') ?>">
                    <div class="group-info">
                        <div class="group-name"><?= htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="group-members">メンバー数: <?= htmlspecialchars($group['size'], ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                    <div class="group-buttons">
                        <button class="chat-button" onclick="goToChat(<?= htmlspecialchars($group['team_id'], ENT_QUOTES, 'UTF-8') ?>)">
                            <img src="../chat/mesicon.png" alt="チャット" />
                        </button>
                        <button class="timeline-button" onclick="goToTimeline()">
                            <img src="../chat/timeline.png" alt="タイムライン" />
                        </button>
                        <button class="leave-button" onclick="leaveGroup(this, <?= htmlspecialchars($group['team_id'], ENT_QUOTES, 'UTF-8') ?>)">
                            <img src="../chat/taikaiicon.png" alt="退会" />
                        </button>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <script>
        function goToChat(groupId) {
            window.location.href = "../chat/chatscreen.php?group_id=" + encodeURIComponent(groupId);
        }

        function goToTimeline() {
            window.location.href = "../timeline/timeline.php";
        }

        function leaveGroup(button, groupId) {
            const confirmLeave = confirm("本当に退会しますか？");
            if (confirmLeave) {
                // TODO: サーバー側で退会処理を実行する (Ajaxを使うのが推奨されます)
                const groupItem = button.closest(".group-item");
                groupItem.remove();

                // グループがなくなった場合のメッセージ表示
                const groupListContainer = document.querySelector(".group-list-container");
                if (groupListContainer.children.length === 0) {
                    document.getElementById("no-groups-message").style.display = "block";
                }
            }
        }
    </script>
</body>

</html>