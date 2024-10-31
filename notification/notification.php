<?php
    session_start();
    $user_id = $_SESSION['user_id'];

    require_once __DIR__ . '/notification_class.php';
    $notification = new Notification();
    $globalChats = $notification->getGlobalChats($user_id);
    $chats = $notification->getChats($user_id);
    $comments = $notification->getComments($user_id, $user_id);
    $all = $notification->getAll($user_id, $user_id);
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <title>禁酒アプリ</title>
        <link rel="stylesheet" href="../notification/notification.css">
        <?php
            require_once __DIR__ . '../../header/header.php';
        ?>
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
                        foreach($all as $one){
                            $date = date('m/d', strtotime($one['date']));
                            if($date == date('m/d')){
                                $count++;
                            }
                        }
                        echo '<div class="center">本日の通知数：' . $count . '</div>';
                        $date = 0;
                        foreach($all as $one){
                            if($date != date('m/d', strtotime($one['date']))){
                                $date = date('m/d', strtotime($one['date']));
                                echo '<p>' . $date . '</p>';
                            };
                            echo '<div class="notification-item">';
                            echo $one['username'] . 'さん&nbsp;';
                            echo date('H:i', strtotime($one['date']));
                            echo '<h2>&emsp;' . $one['message'] . '</h2><br>';
                            if(is_null($one['post_title'])){
                                if(is_null($one['name'])){
                                    echo '<div class="tag">&emsp;全体チャット</div>';
                                }else{
                                    echo '<div class="tag">&emsp;' . $one['name'] .'</div>';
                                }
                            }else{
                                echo '<div class="tag">&emsp;' . $one['post_title'] .'</div>';
                            }
                            echo '</div>';
                        }
                    ?>
                </div>

                <div class="tab-content">
                    <?php
                        $count = 0;
                        foreach($globalChats as $globalChat){
                            $date = date('m/d', strtotime($globalChat['date']));
                            if($date == date('m/d')){
                                $count++;
                            }
                        }
                        echo '<div class="center">本日の通知数：' . $count . '</div>';
                        $date = 0;
                        foreach($globalChats as $globalChat){
                            if($date != date('m/d', strtotime($globalChat['date']))){
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
                        foreach($chats as $chat){
                            $date = date('m/d', strtotime($chat['date']));
                            if($date == date('m/d')){
                                $count++;
                            }
                        }
                        echo '<div class="center">本日の通知数：' . $count . '</div>';
                        $date = 0;
                        foreach($chats as $chat){
                            if($date != date('m/d', strtotime($chat['date']))){
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
                        foreach($comments as $comment){
                            $date = date('m/d', strtotime($comment['created_at']));
                            if($date == date('m/d')){
                                $count++;
                            }
                        }
                        echo '<div class="center">本日の通知数：' . $count . '</div>';
                        $date = 0;
                        foreach($comments as $comment){
                            if($date != date('m/d', strtotime($comment['created_at']))){
                                $date = date('m/d', strtotime($comment['created_at']));
                                echo '<p>' . $date . '</p>';
                            };
                            echo '<div class="notification-item">';
                            echo $comment['username'] . 'さん&nbsp;';
                            echo date('H:i', strtotime($comment['created_at']));
                            echo '<h2>&emsp;' . $comment['comment_text'] . '</h2><br>';
                            echo '<div class="tag">&emsp;' . $comment['post_title'] . '</div>';
                            echo '</div>';
                            if($date == date('m/d')){
                                $count++;
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                tabs = document.querySelectorAll('#tab li');
                for(i = 0; i < tabs.length; i++) {
                    tabs[i].addEventListener('click', tabSwitch, false);
                }
                function tabSwitch(){
                    tabs = document.querySelectorAll('#tab li');
                    var node = Array.prototype.slice.call(tabs, 0);
                    node.forEach(function (element) {
                        element.classList.remove('active');
                    });
                    this.classList.add('active');
                    content = document.querySelectorAll('.tab-content');
                    var node = Array.prototype.slice.call(content, 0);
                    node.forEach(function (element) {
                        element.classList.remove('active');
                    });
                    const arrayTabs = Array.prototype.slice.call(tabs);
                    const index = arrayTabs.indexOf(this);
                    document.querySelectorAll('.tab-content')[index].classList.add('active');
                };
            });
        </script>
    </body>
</html>