<?php
session_start();
// ログインしていないときの処理
if (!isset($_SESSION['user_id'])) {
    header('Location: ./../login/login_display.php');
    exit();
}
$user_id = $_SESSION['user_id'];

require_once __DIR__ . '/notification_class.php';
$notification = new Notification();
$globalChats = $notification->getGlobalChats($user_id);

// 11/27書き換えあるいは追加
// $chats = $notification->getChats($user_id);
$teams = $notification->getTeamId($user_id);
$array = array_fill(0, 10, 0);
$i = 0;
foreach($teams as $team){
    $array[$i]  = $team['team_id'];
    $i++;
}
$chats = $notification->getChats($user_id, $array[0], $array[1], $array[2], $array[3], $array[4], $array[5], $array[6], $array[7], $array[8], $array[9]);


$comments = $notification->getComments($user_id, $user_id);
$all = $notification->getAll($user_id, $user_id, $array[0], $array[1], $array[2], $array[3], $array[4], $array[5], $array[6], $array[7], $array[8], $array[9]);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <title>禁酒アプリ</title>
    <link rel="stylesheet" href="../notification/notification.css">
    <?php
    require_once __DIR__ . '../../header/header.php';
    ?>
    <style>
        body {
            background-color: #f9f9f9;
            margin: 0;
        }

        h1 {
            margin-top: 50px;
            text-align: center;
            font-size: 1.5em;
        }

        p {
            margin: 0 10px;
            font-size: 0.9em;
        }

        .center {
            text-align: center;
            font-size: 1em;
        }

        .wrap-tab {
            overflow: hidden;
            padding: 0;
        }

        .list-tab {
            display: flex;
            justify-content: space-between;
            margin: 0 10px;
            border-bottom: 3px solid #1578c9;
        }

        .list-tab>li {
            display: block;
            padding: 1em 0.5em;
            margin: 0 2px;
            width: 24%;
            color: #fff;
            font-size: 2em;
            text-align: center;
            background: #ccc;
            box-sizing: border-box;
            cursor: pointer;
            border-radius: 5px;
        }

        .list-tab .active {
            background: #1578c9;
        }

        .tab-content {
            display: none;
            padding: 1em;
            margin: 0 10px;
        }

        .tab-content.active {
            display: block;
        }

        .notification-item {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            margin: 0 10px 10px 10px;
            border-radius: 5px;
            font-size: 0.9em;
        }

        .notification-item h2 {
            font-size: 1em;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .tag {
            padding: 5px 10px;
            border-radius: 15px;
            background-color: #f5f5f5;
            font-size: 0.8em;
            display: inline-block;
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>通知一覧</h1>
    <div class="wrap-tab">
        <ul id="tab" class="list-tab" style="padding: 0;">
            <li class="active">全て</li>
            <li>全体チャット</li>
            <li>チャット</li>
            <li>コメント</li>
        </ul>

        <div class="wrap-tab-content">
            <div class="tab-content active">
                <?php
                $count = 0;
                foreach ($all as $one) {
                    $date = date('m/d', strtotime($one['date']));
                    if ($date == date('m/d')) {
                        $count++;
                    }
                }
                echo '<div class="center">本日の通知：' . $count . '件</div>';
                $date = '';
                foreach ($all as $one) {
                    if ($date != date('m/d', strtotime($one['date']))) {
                        $date = date('m/d', strtotime($one['date']));
                        echo '<p>' . $date . '</p>';
                    }
                    echo '<div class="notification-item">';
                    echo $one['username'] . 'さん&nbsp;';
                    echo date('H:i', strtotime($one['date']));
                    echo '<h2>&emsp;' . htmlspecialchars($one['message'], ENT_QUOTES, 'UTF-8') . '</h2><br>';
                    if (is_null($one['post_title'])) {
                        if (is_null($one['name'])) {
                            echo '<div class="tag">&emsp;全体チャット</div>';
                        } else {
                            echo '<div class="tag">&emsp;' . htmlspecialchars($one['name'], ENT_QUOTES, 'UTF-8') . '</div>';
                        }
                    } else {
                        echo '<div class="tag">&emsp;' . htmlspecialchars($one['post_title'], ENT_QUOTES, 'UTF-8') . '</div>';
                    }
                    echo '</div>';
                }
                ?>
            </div>

            <div class="tab-content">
                <?php
                $count = 0;
                foreach ($globalChats as $globalChat) {
                    $date = date('m/d', strtotime($globalChat['date']));
                    if ($date == date('m/d')) {
                        $count++;
                    }
                }
                echo '<div class="center">本日の全体チャット：' . $count . '件</div>';
                $date = 0;
                foreach ($globalChats as $globalChat) {
                    if ($date != date('m/d', strtotime($globalChat['date']))) {
                        $date = date('m/d', strtotime($globalChat['date']));
                        echo '<p>' . $date . '</p>';
                    };
                    echo '<div class="notification-item">';
                    echo $globalChat['username'] . 'さん&nbsp;';
                    echo date('H:i', strtotime($globalChat['date']));
                    echo '<h2>&emsp;' . $globalChat['message'] . '</h2><br>';
                    echo '<div class="tag">&emsp;全体チャット</div>';
                    echo '</div>';
                }
                ?>
            </div>

            <div class="tab-content">
                <?php
                $count = 0;
                foreach ($chats as $chat) {
                    $date = date('m/d', strtotime($chat['date']));
                    if ($date == date('m/d')) {
                        $count++;
                    }
                }
                echo '<div class="center">本日のグループチャット：' . $count . '件</div>';
                $date = 0;
                foreach ($chats as $chat) {
                    if ($date != date('m/d', strtotime($chat['date']))) {
                        $date = date('m/d', strtotime($chat['date']));
                        echo '<p>' . $date . '</p>';
                    }
                    echo '<div class="notification-item">';
                    echo $chat['username'] . 'さん&nbsp;';
                    echo date('H:i', strtotime($chat['date']));
                    echo '<h2>&emsp;' . $chat['message'] . '</h2><br>';
                    echo '<div class="tag">&emsp;' . $chat['name'] . '</div>';
                    echo '</div>';
                }
                ?>
            </div>

            <div class="tab-content">
                <?php
                $count = 0;
                foreach ($comments as $comment) {
                    $date = date('m/d', strtotime($comment['created_at']));
                    if ($date == date('m/d')) {
                        $count++;
                    }
                }
                echo '<div class="center">本日のコメント：' . $count . '件</div>';
                $date = 0;
                foreach ($comments as $comment) {
                    if ($date != date('m/d', strtotime($comment['created_at']))) {
                        $date = date('m/d', strtotime($comment['created_at']));
                        echo '<p>' . $date . '</p>';
                    };
                    echo '<div class="notification-item">';
                    echo $comment['username'] . 'さん&nbsp;';
                    echo date('H:i', strtotime($comment['created_at']));
                    echo '<h2>&emsp;' . $comment['comment_text'] . '</h2><br>';
                    echo '<div class="tag">&emsp;' . $comment['post_title'] . '</div>';
                    echo '</div>';
                    if ($date == date('m/d')) {
                        $count++;
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('#tab li');
            tabs.forEach(function(tab) {
                tab.addEventListener('click', tabSwitch, false);
            });

            function tabSwitch() {
                tabs.forEach(function(tab) {
                    tab.classList.remove('active');
                });
                this.classList.add('active');
                const contents = document.querySelectorAll('.tab-content');
                contents.forEach(function(content) {
                    content.classList.remove('active');
                });
                const index = Array.from(tabs).indexOf(this);
                contents[index].classList.add('active');
            }
        });
    </script>
</body>

</html>