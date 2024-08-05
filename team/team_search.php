<?php
require_once __DIR__ . '/team_class.php';
$team = new Team();
$teams = $team->getTeams();
$ident = 0;
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
        <link rel="stylesheet" href="../team/team_search.css">
        <?php
            require_once __DIR__ . '../../header/header.php';
        ?>
    </head>
    <body>
        <div class="team_search_side">
            <div class="team_search_content">
                <h1>グループ検索一覧</h1>
                <a href="../team/team_create.php"><div class="br"><input class="new_group_create" type="button" name="new" value="グループ作成"></div></a>
                <div class="bt">
                    <form method="POST" action="">
                        <?php
                            foreach($teams as $team){
                                echo '<button type="submit" name="group" value="'.$team['ident'].'" class="group_button">'.$team['name'].'<br><div class="fsize">参加人数'.$team['size'].'人</div></button>';
                            }
                        ?>
                    </form>
                </div>
            </div>
        </div>

        <?php
            if($ident == 0){
                $flag = 0;
            }else{
                $flag = 1;
            }
        ?>

        <div id="popup" class="popup-container">
            <div class="popup-box">
                <span class="close-button" onclick="closePopup()">×</span>
                <h2>グループ詳細</h2>
                <?php
                echo $group['detail'];
                ?>
                <br><a href="../chat/chatscreen.php"><div class="mt"><input type="button" name="join" value="参加"></div></a>
            </div>
        </div>

        <script>
            <?php
                if($flag == 1){
                    echo "document.getElementById('popup').style.display = 'flex';";
                }
            ?>
            function openPopup() {
                document.getElementById('popup').style.display = 'flex';
            }

            function closePopup() {
                document.getElementById('popup').style.display = 'none';
            }
        </script>
    </body>
</html>