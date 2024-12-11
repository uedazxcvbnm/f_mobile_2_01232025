<!DOCTYPE html>
<html lang="ja">

<head>
    <title>禁酒アプリ</title>
    <link rel="stylesheet" href="../team/team_create.css">
    <?php
    require_once __DIR__ . '../../header/header.php';
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <h1>新規グループの作成</h1>
    <form method="post" action="../team/team_add.php">
        <p>グループ名:<input type="text" name="name" required="required"></p>
        <p>グループの詳細:</p>
        <textarea name="detail" required="required" placeholder="グループの詳細を入力してください（200字まで）" maxlength="200" oninput="updateCount(this)"></textarea>
        <div class="limit-text"><span id="char-count">0</span>/200文字</div>
        <div class="bc"><button type="submit">決定</button></div>
    </form>

    <script>
        function updateCount(textarea) {
            const count = textarea.value.length;
            document.getElementById('char-count').textContent = count;
        }
    </script>
</body>

</html>