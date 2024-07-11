<link rel="stylesheet" href="../header/header.css">
<nav>
    <ul>
        <li><a href="../input_daily_record/input_daily_record.php">登録</a></li>
        <li><a href="../team/team_search.php">グループ検索</a></li>
        <li><a href="../joingrouplist/joingrouplist.php">参加グループ</a></li>
        <li><a href="../post/post_list.php">投稿一覧</a></li>
        <li><a onclick="openLogoutpop()">ログアウト</a></li>
    </ul>
</nav>

<div id="logoutpop" class="logoutpop-container">
    <div class="logoutpop-box">
        <span class="close-logout" onclick="closeLogoutpop()">×</span>
        <h2>ログアウトしますか？</h2>
        <div class="lobt">
            <input type="button" name="yes" value="はい">&nbsp;<input type="button" name="no"  value="いいえ">
        </div>
    </div>
</div>

<script>
    function openLogoutpop() {
        document.getElementById('logoutpop').style.display = 'flex';
    }

    function closeLogoutpop() {
        document.getElementById('logoutpop').style.display = 'none';
    }
</script>