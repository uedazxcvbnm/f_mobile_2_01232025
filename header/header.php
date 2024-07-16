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
            <input type="button" name="yes" onclick="location.href='./../login/logout_display.php'" value="はい">&nbsp;<input type="button" name="no"  value="いいえ" onclick="closeLogoutpop()">
        </div>
    </div>
</div>

<style>
    nav {
        width: 100%;
        height: 70px;
        background-color: dimgray;
        padding-top: 5px;
        box-sizing: border-box;
        position: fixed;
        top: 0;
        left: 0;
    }
    ul {
        display: flex;
    }
    li {
        list-style: none;
    }
    a {
        display: block;
        text-decoration: none;
        color: white;
        margin-right: 50px;
    }
    .logoutpop-container {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        justify-content: center;
        align-items: center;
    }

    .logoutpop-box {
        background-color: #fff;
        width: 400px;
        height: 400px;
        padding: 20px;
        border-radius: 5px;
        text-align: center;
        position: relative;
    }

    .close-logout {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
    }
    input{
        width: 180px;
        height: 50px;
        border-radius: 30px;
    }
    .lobt{
        margin-top: 200px;
    }
</style>

<script>
    function openLogoutpop() {
        document.getElementById('logoutpop').style.display = 'flex';
    }

    function closeLogoutpop() {
        document.getElementById('logoutpop').style.display = 'none';
    }
</script>