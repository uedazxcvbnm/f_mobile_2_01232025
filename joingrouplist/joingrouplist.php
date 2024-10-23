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
                    <div class="group-name"><?= htmlspecialchars($group['name'], ENT_QUOTES, 'UTF-8') ?></div>
                    <div class="group-members">メンバー数: <?= htmlspecialchars($group['size'], ENT_QUOTES, 'UTF-8') ?></div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <!-- ポップアップ -->
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <h2 id="popup-group-name"></h2>
            <div class="popup-buttons">
                <button id="popup-chat-button" class="chat-button">チャット画面へ</button>
                <button id="popup-leave-button" class="leave-button">退会</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const groupListContainer = document.querySelector(".group-list-container");
            const noGroupsMessage = document.getElementById("no-groups-message");
            const popup = document.getElementById("popup");
            const closePopup = document.querySelector(".close");
            const popupGroupName = document.getElementById("popup-group-name");
            const popupChatButton = document.getElementById("popup-chat-button");
            const popupLeaveButton = document.getElementById("popup-leave-button");

            // グループアイテムをクリックするとポップアップを表示
            groupListContainer.addEventListener("click", function(event) {
                const groupItem = event.target.closest(".group-item");
                if (groupItem) {
                    const groupId = groupItem.getAttribute("data-group-id");
                    const groupName = groupItem.getAttribute("data-group-name");

                    // ポップアップにグループ情報を表示
                    popupGroupName.textContent = groupName;

                    // チャットボタンのクリックイベント
                    popupChatButton.onclick = function() {
                        window.location.href = "../chat/chatscreen.php?group_id=" + encodeURIComponent(groupId);
                    };

                    // 退会ボタンのクリックイベント
                    popupLeaveButton.onclick = function() {
                        const confirmLeave = confirm("本当に退会しますか？");
                        if (confirmLeave) {
                            // サーバー側で退会処理を行う（ここでは簡略化してアイテムを削除）
                            groupItem.remove();
                            popup.style.display = "none";

                            // グループがなくなった場合のメッセージ表示
                            if (groupListContainer.children.length === 0) {
                                noGroupsMessage.style.display = "block";
                            }
                        }
                    };

                    // ポップアップを表示
                    popup.style.display = "block";
                }
            });

            // ポップアップを閉じるイベント
            closePopup.addEventListener("click", function() {
                popup.style.display = "none";
            });

            // ポップアップ外をクリックして閉じるイベント
            window.addEventListener("click", function(event) {
                if (event.target == popup) {
                    popup.style.display = "none";
                }
            });
        });
    </script>
</body>

</html>