<?php
require_once __DIR__ . '/team_class.php';
$team = new Team();
$teams = $team->getTeams();
$ident = 1;
if($_SERVER["REQUEST_METHOD"] == 'POST'){
    $ident = $_POST['group'];
}
$product = new Team();
$group = $product->getTeam($ident);
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>禁酒アプリ</title>
        <link rel="stylesheet" href="../css/team_search.css">
    </head>
    <body>
        <h1>グループ検索一覧</h1>
        <a href="../src/team_create.php"><input type="button" name="new" value="グループ作成"></a>
        <div class="bt">
            <form method="POST" action="">
                <?php
                    foreach($teams as $team){
                        echo "<button type="."submit"." name="."group"." value=".$team['ident'].">".$team['name']."<br><div class="."fsize".">参加人数".$team['size']."人</div></button>";
                    }
                ?>
            </form>
        </div>

        <div id="popup" class="popup-container">
            <div class="popup-box">
                <span class="close-button" onclick="closePopup()">×</span>
                <h2>グループ詳細</h2>
                <?php
                echo $group['detail'];
                ?>
                <br><div class="mt"><a href="./../chat/chatscreen.html"><input type="button" name="join" value="参加"></a></div>
            </div>
        </div>

        <script>
            function openPopup() {
                document.getElementById('popup').style.display = 'flex';
            }

            function closePopup() {
                document.getElementById('popup').style.display = 'none';
            }
        </script>
    </body>
</html>