<nav>
    <div class="nav-container">
        <div class="nav-visible">
            <a class="nav-link" href="../input_daily_record/input_alchol_count.php">登録</a>
            <a class="nav-link" href="../joingrouplist/joingrouplist.php">参加グループ</a>
            <a class="nav-link" href="../globalchat/globalchatscreen.php">チャット</a>
            <a class="nav-link" href="../notification/notification.php">通知</a>
        </div>
        <span class="menu-toggle" onclick="toggleMenu()">☰</span>
    </div>
</nav>

<!-- 将 .nav-hidden 移到 <nav> 外部 -->
<div class="nav-hidden">
    <span class="close-menu" onclick="toggleMenu()">×</span>
    <ul class="nav-list">
        <li><a class="nav-link" href="./../input_daily_record/graph/graph_count.php">グラフ</a></li>
        <li><a class="nav-link" href="./../input_daily_record/calendar/calendar.php">カレンダー</a></li>
        <li><a class="nav-link" href="../team/team_search.php">グループ検索</a></li>
        <li><a class="nav-link" href="../post/post_list.php">投稿一覧</a></li>
        <li><a class="nav-link" href="../post/liked_posts.php">いいねリスト</a></li>
        <li><a class="nav-link" href="../profile/profile.php">プロフィール</a></li>
        <li><a onclick="openLogoutpop()">ログアウト</a></li>
    </ul>
</div>

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
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 60px;
        /* 设置固定高度 */
        background-color: #123C69;
        padding: 0 15px;
        box-sizing: border-box;
        z-index: 10;
    }

    .nav-container {
        height: 100%;
        display: flex;
        align-items: center;
    }

    .nav-visible {
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
    }

    .nav-visible a {
        text-decoration: none;
        color: white;
        font-size: 14px;
        margin-right: 5px;
        padding: 0 8px;
        border-radius: 4px;
        white-space: nowrap;
        line-height: 60px;
        /* 使文字垂直居中 */
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
        margin-left: auto;
        line-height: 60px;
        /* 使图标垂直居中 */
    }

    /* 隐藏菜单样式 */
    .nav-hidden {
        display: none;
        position: fixed;
        /* 固定定位 */
        top: 60px;
        /* 与导航栏高度匹配 */
        left: 0;
        width: 100%;
        background-color: #123C69;
        z-index: 15;
        overflow-y: auto;
    }

    .close-menu {
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 24px;
        color: white;
        cursor: pointer;
    }

    .nav-list {
        list-style: none;
        margin: 0;
        padding: 40px 0 10px 0;
        /* 顶部留出空间给关闭按钮 */
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
        /* 初始状态隐藏 */
        position: fixed;
        /* 固定定位，覆盖整个视口 */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        /* 半透明背景 */
        justify-content: center;
        /* 水平居中 */
        align-items: center;
        /* ログアウトのポップアップ画面が最前面に出るようにした */
        z-index:2;
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
        font-size: 24px;
        cursor: pointer;
    }

    .lobt input[type="button"] {
        width: 180px;
        height: 50px;
        border-radius: 30px;
        margin: 10px;
        font-size: 16px;
    }

    /* 重置 body 的样式 */
    body {
        margin: 0;
        padding: 0;
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