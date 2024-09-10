<?php
    session_start();
    // ログインしていないときの処理
    if (!isset($_SESSION['user_id'])){
        header('Location: ./../login/login_display.php');
        exit();
    }
?>

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
        <h1>グループ検索一覧</h1>
        <a href="../team/team_create.php"><div class="br"><input type="button" name="new" value="グループ作成"></div></a>
        <div class="bt">
            <form method="POST" action="">
                <?php
                    foreach($teams as $team){
                        echo '<button type="submit" name="group" value="'.$team['ident'].'">'.$team['name'].'<br><div class="fsize">参加人数'.$team['size'].'人</div></button>';
                    }
                ?>
            </form>
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
                $group_name = $group['name'];
                echo $group['detail'];
                ?>
                <form method="post" action="../team/team_user.php">
                    <input type="hidden" name="name" value=<?= $group_name ?>>
                    <input type="submit" value="参加">
                </form>
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