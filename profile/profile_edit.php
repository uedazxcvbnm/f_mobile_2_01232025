<?php
session_start();




try {
    // データベース接続情報
    $dsn = 'mysql:host=localhost;dbname=pbl2;charset=utf8';
    $user = 'kobe';
    $password = 'denshi';

    try {
        $pdo = new PDO($dsn, $user, $password);
    } catch (Exception  $e) {
        echo 'Error:' . $e->getMessage();
        die();
    }

    if (!isset($_SESSION['user_id'])) {
        echo "ログインが必要です。";
        exit;
    }

    $user_id = $_SESSION['user_id'];

    // デフォルト値
    $goal = '';
    $username = '';

    // フォーム送信後の更新処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $goal = $_POST['goal'] ?? '';
        $username = $_POST['username'] ?? '';

        // 画像のアップロード処理
        if (!empty($_FILES['profile_image']['name'])) {
            $image_path = '../images/' . basename($_FILES['profile_image']['name']);
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $image_path)) {
                $sql = "UPDATE profiles p JOIN user u ON u.user_id = p.user_id 
                        SET p.goal = :goal, p.profile_image = :profile_image, u.username = :username 
                        WHERE u.user_id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':profile_image', $image_path, PDO::PARAM_STR);
            } else {
                echo "画像のアップロードに失敗しました。";
                exit;
            }
        } else {
            $sql = "UPDATE profiles p JOIN user u ON u.user_id = p.user_id 
                    SET p.goal = :goal, u.username = :username 
                    WHERE u.user_id = :user_id";
            $stmt = $pdo->prepare($sql);
        }

        // クエリパラメータのバインド
        $stmt->bindParam(':goal', $goal, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        // クエリ実行
        if ($stmt->execute()) {
            header("Location: profile.php"); // 保存後にプロフィールページにリダイレクト
            exit;
        } else {
            echo "データベース更新に失敗しました。";
            exit;
        }
    }

    // 初期表示時のデータ取得
    $sql = "SELECT u.username, COALESCE(p.goal, '') AS goal, COALESCE(p.profile_image, '../images/default.png') AS profile_image 
            FROM profiles p JOIN user u ON u.user_id = p.user_id 
            WHERE u.user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user_profile = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_profile) {
        $username = htmlspecialchars($user_profile['username']);
        $goal = htmlspecialchars($user_profile['goal']);
        $profile_image = htmlspecialchars($user_profile['profile_image']);
    }
} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php require_once __DIR__ . '../../header/header.php'; ?>
    <link rel="stylesheet" href="../profile/profile_edit.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f8fc;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;

            align-items: center;

            min-height: 100vh;
        }


        .edit-container {
            margin-top: 20px;

            max-width: 500px;

            width: 90%;

            padding: 15px 20px;

            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            box-sizing: border-box;

        }


        h1 {
            text-align: center;
            color: #1578c9;
            font-size: 22px;

            margin-bottom: 15px;

        }


        label {
            font-size: 14px;

            color: #333;
            display: block;
            margin-top: 10px;

            margin-bottom: 5px;
            text-align: left;

        }


        input[type="text"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;

            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;

            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="file"]:focus,
        textarea:focus {
            border-color: #1578c9;
            outline: none;
        }


        button.save_button {
            width: 100%;
            padding: 12px;
            font-size: 16px;

            color: white;
            background-color: #1578c9;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        button.save_button:hover {
            background-color: #125e9c;
        }

        a.cancel_button {
            display: inline-block;
            width: 100%;

            text-align: center;

            color: #1578c9;
            background-color: #f0f8ff;
            padding: 12px;
            font-size: 16px;
            text-decoration: none;
            border: 1px solid #1578c9;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            margin-top: 10px;
            box-sizing: border-box;

        }

        a.cancel_button:hover {
            background-color: #d0e7ff;
            color: #125e9c;
        }
    </style>

    <body>
        <!-- 編集画面のコンテナ -->
        <div class="edit-container">
            <h1>プロフィール編集</h1>
            <form action="profile_edit.php" method="post" enctype="multipart/form-data">
                <label for="username">ユーザー名の変更:</label>
                <input type="text" name="username" value="<?php echo $username; ?>" required>

                <label for="profile_image">プロフィール画像の変更:</label>
                <input type="file" name="profile_image" accept="image/*">

                <label for="goal">目標の変更:</label>
                <textarea name="goal" rows="4" cols="50"><?php echo $goal; ?></textarea>

                <button type="submit" class="save_button">保存</button>
                <a href="profile.php" class="cancel_button">キャンセル</a>
            </form>
        </div>
    </body>

</html>