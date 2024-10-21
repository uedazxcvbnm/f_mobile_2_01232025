<?php
session_start();
// ログインしていないときの処理
if (!isset($_SESSION['user_id'])) {
    header('Location: ./../login/login_display.php');
    exit();
}

require_once __DIR__ . '/team_class.php';
$user_id = $_SESSION['user_id'];
$team = new Team();
$teams = $team->getTeams($user_id, $user_id);
$ident = 0;

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $ident = $_POST['group'];
    $product = new Team();
    $group = $product->getTeam($ident);
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <title>禁酒アプリ</title>
    <link rel="stylesheet" href="../team/team_search.css">
    <?php
    require_once __DIR__ . '../../header/header.php';
    ?>

</head>

<body>
    <h1>グループ検索一覧</h1>
    <div class="br">
        <input type="button" onclick="location.href='./team_create.php'" name="new" value="グループ作成">
    </div>
    <div class="bt">
        <form method="POST" action="">
            <?php
            foreach ($teams as $team) {
                echo '<button type="submit" name="group" value="' . $team['team_id'] . '">' . $team['name'] . '<br><div class="fsize">参加人数' . $team['size'] . '人</div></button>';
            }
            ?>
        </form>
    </div>

    <?php if ($ident > 0 && !empty($group)) { ?>
        <div id="popup" class="popup-container" style="display: flex;">
            <div class="popup-box">
                <span class="close-button" onclick="closePopup()">×</span>
                <h2>グループ詳細</h2>
                <p><?= htmlspecialchars($group['detail'], ENT_QUOTES, 'UTF-8') ?></p>
                <form method="POST" action="../team/team_user.php">
                    <input type="hidden" name="team_id" value="<?= htmlspecialchars($group['team_id'], ENT_QUOTES, 'UTF-8') ?>">
                    <input type="submit" value="参加">
                </form>
            </div>
        </div>
    <?php } ?>

    <script>
        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }
    </script>
</body>

</html>