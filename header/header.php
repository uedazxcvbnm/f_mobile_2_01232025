<nav>
    <div class="nav-container">
        <div class="nav-visible">
            <a class="nav-link" href="../input_daily_record/input_alchol_count.php">登録</a>
            <a class="nav-link" href="../input_daily_record/graph/graph_count.php">グラフ</a>
            <a class="nav-link" href="../team/team_search.php">グループ検索</a>
            <a class="nav-link" href="../joingrouplist/joingrouplist.php">参加グループ</a>
        </div>
        <span class="menu-toggle" onclick="toggleMenu()">☰</span>
        <div class="nav-hidden">
            <ul class="nav-list">
                <li><a class="nav-link" href="../globalchat/globalchatscreen.php">全体チャット</a></li>
                <li><a class="nav-link" href="../post/post_list.php">投稿一覧</a></li>
                <li><a class="nav-link" href="../notification/notification.php">通知</a></li>
                <li><a class="nav-link" href="../profile/profile.php">プロフィール</a></li>
                <li><a class="nav-link" href="../input_daily_record/calendar/calendar.php">カレンダー</a></li>
                <li><a onclick="openLogoutpop()">ログアウト</a></li>
            </ul>
        </div>
    </div>
</nav>

<div id="logoutpop" class="logoutpop-container">
    <div class="logoutpop-box">
        <span class="close-logout" onclick="closeLogoutpop()">×</span>
        <h2>ログアウトしますか？</h2>
        <div class="lobt">
            <input type="button" name="yes" onclick="location.href='./../login/logout_display.php'" value="はい">&nbsp;
            <input type="button" name="no" value="いいえ" onclick="closeLogoutpop()">
        </div>
    </div>
</div>

<style>
    nav {
        width: 100%;
        background-color: #123C69;
        padding: 10px 20px;
        box-sizing: border-box;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .nav-container {
        display: flex;
        align-items: center;
        width: 100%;
        justify-content: space-between;
    }

    .nav-visible a {
        text-decoration: none;
        color: white;
        font-size: 14px;
        margin-right: 10px;
        padding: 5px 10px;
        border-radius: 4px;
    }

    .nav-visible a:hover,
    .nav-visible a.active {
        background-color: cyan;
        color: black;
    }

    .menu-toggle {
        font-size: 24px;
        color: white;
        cursor: pointer;
    }

    .nav-hidden {
        display: none;
        position: absolute;
        top: 60px;
        left: 0;
        background-color: #123C69;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        z-index: 15;
        width: 100%;
    }

    .nav-list {
        list-style: none;
        margin: 0;
        padding: 10px 0;
    }

    .nav-list li {
        margin: 10px 0;
        text-align: center;
    }

    .nav-list a {
        text-decoration: none;
        color: white;
        font-size: 16px;
        display: block;
        padding: 10px 20px;
    }

    .nav-list a:hover,
    .nav-list a.active {
        background-color: cyan;
        color: black;
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
        width: 90%;
        max-width: 400px;
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

    input {
        width: 180px;
        height: 50px;
        border-radius: 30px;
    }

    .lobt {
        margin-top: 200px;
    }
</style>


<script>
    function toggleMenu() {
        const navHidden = document.querySelector('.nav-hidden');
        navHidden.style.display = navHidden.style.display === 'block' ? 'none' : 'block';
    }

    function openLogoutpop() {
        document.getElementById('logoutpop').style.display = 'flex';
    }

    function closeLogoutpop() {
        document.getElementById('logoutpop').style.display = 'none';
    }

    // 高亮当前页面链接
    document.querySelectorAll('.nav-link').forEach(link => {
        if (link.href === window.location.href) {
            link.classList.add('active');
        }
    });
</script>