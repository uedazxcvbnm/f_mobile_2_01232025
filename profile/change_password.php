<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
session_start();
include 'config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: /team_F_alcohol/login/login_display.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];
$error_message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];


    $stmt = $conn->prepare("SELECT password FROM user WHERE user_id = ?");
    $stmt->bind_param("i", $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();


    if ($user && $user['password'] === $current_password) {

        if ($new_password !== $confirm_password) {
            $error_message = '新しいパスワードと確認パスワードが一致しません。';
        } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $new_password)) {
            $error_message = 'パスワードは8文字以上で、英文字と数字の両方を含む必要があります。';
        } else {

            $stmt = $conn->prepare("UPDATE user SET password = ? WHERE user_id = ?");
            $stmt->bind_param("si", $new_password, $current_user_id);
            if ($stmt->execute()) {
                $success_message = 'パスワードが更新されました。';
            } else {
                $error_message = 'パスワードの更新中にエラーが発生しました。';
            }
            $stmt->close();
        }
    } else {
        $error_message = '現在のパスワードが正しくありません。';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    require_once __DIR__ . '../../header/header.php';
    ?>
    <title>パスワード変更</title>
    <link rel="stylesheet" href="change_password.css">
</head>

<body>
    <div class="input_daily_screen">
        <div class="daily_registration_form">
            <h1>パスワード変更</h1>

            <?php if ($error_message) { ?>
                <p style="color: red;"><?php echo $error_message; ?></p>
            <?php } ?>

            <?php if (isset($success_message)) { ?>
                <p style="color: green;"><?php echo $success_message; ?></p>
            <?php } ?>

            <form method="POST" action="">
                <div class="form-group">
                    <input type="password" name="current_password" placeholder="現在のパスワード" required><br><br>
                    <input type="password" name="new_password" placeholder="新しいパスワード" required><br><br>
                    <input type="password" name="confirm_password" placeholder="新しいパスワードの確認" required><br><br>
                </div>
                <div class="form-group">
                    <input type="submit" class="button record_button" value="パスワードを変更する">
                </div>
            </form>
        </div>
    </div>
</body>

</html>