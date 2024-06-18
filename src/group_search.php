<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>禁酒アプリ</title>
        <link rel="stylesheet" href="../css/group_search.css">
    </head>
    <body>
        <h1>グループ検索一覧</h1>

        <div class="bt">
            <?php
                for($i = 1; $i <= 6; $i++){
                    echo "<button onclick="."openPopup()".">グループ$i<br><div class="."fsize".">参加人数".rand(3,15)."人</div></button>";
                }
            ?>
        </div>

        <div id="popup" class="popup-container">
            <div class="popup-box">
                <span class="close-button" onclick="closePopup()">×</span>
                <?php
                    echo "<h2>グループ詳細</h2>";
                    echo "<p>ポップアップの内容をここに入れます。</p>";
                ?>
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