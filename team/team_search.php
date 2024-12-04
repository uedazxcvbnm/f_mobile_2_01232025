<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ./../login/login_display.php');
    exit();
}

require_once __DIR__ . '/team_class.php';
$user_id = $_SESSION['user_id'];
$team = new Team();
$teams = $team->getTeams($user_id, $user_id);


$ident = 0;
$group = null;


if (isset($_GET['group'])) {
    $ident = $_GET['group'];
    $group = $team->getTeam($ident);
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <title>禁酒アプリ</title>
    <link rel="stylesheet" href="../team/team_search.css">
    <?php
    require_once __DIR__ . '/../header/header.php';
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <h1>グループ検索一覧</h1>

    <div class="button-container">
        <button class="new_group_create" onclick="location.href='./team_create.php'">グループ作成</button>

        <form method="GET" action="">
            <?php
            foreach ($teams as $team_item) {
                echo '<button type="submit" name="group" value="' . htmlspecialchars($team_item['team_id'], ENT_QUOTES, 'UTF-8') . '" class="group_button">' . htmlspecialchars($team_item['name'], ENT_QUOTES, 'UTF-8') . '<br><div class="fsize">参加人数' . htmlspecialchars($team_item['size'], ENT_QUOTES, 'UTF-8') . '人</div></button>';
            }
            ?>
        </form>
    </div>

    <?php if (isset($_GET['group']) && $ident > 0 && !empty($group)) { ?>
        <div id="popup" class="popup-container">
            <div class="popup-box">
                <button class="close-button" onclick="closePopup()">×</button>
                <h2>グループ詳細</h2>
                <p><?php echo htmlspecialchars($group['detail'], ENT_QUOTES, 'UTF-8'); ?></p>
                <form method="POST" action="../team/team_user.php">
                    <input type="hidden" name="team_id" value="<?php echo htmlspecialchars($group['team_id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="submit" value="参加">
                </form>
            </div>
        </div>
    <?php } ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_GET['group']) && $ident > 0 && !empty($group)) { ?>

                document.getElementById('popup').style.display = 'flex';
            <?php } ?>
        });

        function closePopup() {
            document.getElementById('popup').style.display = 'none';

            const url = new URL(window.location.href);
            url.searchParams.delete('group');
            window.history.replaceState({}, document.title, url.toString());
        }
    </script>
</body>

</html>