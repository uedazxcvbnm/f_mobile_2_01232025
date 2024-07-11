<?php
    session_start();
    $_SESSION = array();
    session_destroy();
?>
<html>
<body>
<?php
    echo 'ログアウトしました';
    echo '<p><a href="login_display.php">ログインページへ</a></p>';
?>
</body>
</html>