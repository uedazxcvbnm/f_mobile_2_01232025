<?php
session_start();
// 11/27書き換えあるいは追加
if (!isset($_SESSION['user_id'])) {
    header('Location: ./../login/login_display.php');
    exit();
}

// チャット画面でユーザーIDと名前を取得する
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'ゲスト';
// 11/27書き換えあるいは追加
// $group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;
$group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : 0;

$servername = "localhost";
$db_username = "kobe";
$db_password = "denshi";
$dbname = "pbl2";

try {
    // データベース接続
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 11/27書き換えあるいは追加
    // URLパラメータから `group_id` が取得できない場合のみ `joined_teams` テーブルから取得
    // if ($group_id === 0 && $user_id > 0) {
        // joined_teams テーブルから user_id が参加しているチームを取得
    //     $stmt = $conn->prepare("SELECT team_id FROM joined_teams WHERE user_id = :user_id LIMIT 1");
    //     $stmt->bindParam(':user_id', $user_id);
    //     $stmt->execute();
    //     $result = $stmt->fetch(PDO::FETCH_ASSOC);

    //     if ($result) {
    //         $group_id = $result['team_id'];
    //     } else {
    //         die("有効なグループIDを取得できませんでした");
    //     }
    // }

    // group_id が取得できなかった場合にエラーを出す
    if ($group_id === 0) {
        // die("有効なグループIDを指定してください");
        die("無効なアクセスです");        
    }

    // グループ名と参加人数を取得する
    $stmt = $conn->prepare("SELECT t.name AS group_name, COUNT(jt.user_id) AS member_count 
                            FROM teams t
                            LEFT JOIN joined_teams jt ON t.team_id = jt.team_id
                            WHERE t.team_id = :group_id");
    $stmt->bindParam(':group_id', $group_id);
    $stmt->execute();
    $group_info = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$group_info) {
        die("グループ情報を取得できませんでした");
    }
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
    exit();
}

$group_name = $group_info['group_name'];
$member_count = $group_info['member_count'];
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="../chat/chatscreen.css">
    <script src="http://localhost:3000/socket.io/socket.io.js"></script>
    <?php require_once __DIR__ . '../../header/header.php'; ?>
</head>

<body>
    <div class="container">
        <div class="header">
            <button id="back-button">&lt; 戻る</button>
            <div class="group-info">
                <span id="group-name"><?php echo htmlspecialchars($group_name, ENT_QUOTES, 'UTF-8'); ?></span>
                <span id="group-members">(<?php echo $member_count; ?>)</span>
            </div>
        </div>
        <div class="chat-area"></div>
        <div class="message-area">
            <!-- 画像をアップロード -->
            <input type="file" id="chatimage_upload" name="chatimage_upload">
            <!--  -->
            <div class="message-area-text">
                <textarea id="text"></textarea>
            </div>
            <div class="message-area-button">
                <button id="send">▻</button>
            </div>
        </div>
    </div>
    <div id="context-menu" class="context-menu">
        <div class="context-menu-item" id="edit-button">編集</div>
        <div class="context-menu-item" id="delete-button">削除</div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const socket = io('http://localhost:3000');


            // ユーザーIDをクエリパラメータとしてURLに含める
            const user_id = "<?php echo $user_id; ?>";
            const group_id = "<?php echo htmlspecialchars($group_id, ENT_QUOTES, 'UTF-8'); ?>";

            document.getElementById("back-button").addEventListener("click", function() {
                window.location.href = "../joingrouplist/joingrouplist.php";
            });

            const sendButton = document.getElementById("send");
            const textInput = document.getElementById("text");
            const chatArea = document.querySelector(".chat-area");

            // 画像
            const imageArea = document.getElementById('chatimage_upload');

            let lastMessageId = 0;

            function sendMessage() {
                const message = textInput.value.trim();
                if (message === "") {
                    displayEmptyMessage();
                    return;
                }
                socket.emit('sendMessage', {
                    message: message,
                    user_id: user_id,
                    team_id: group_id
                });

                textInput.value = "";
                scrollToBottom();
            }

            // 1/9追加
            // ファイルをサーバーにアップロード
            function uploadImageToDB(file, callback) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const base64Image = event.target.result.split(',')[1]; // "data:image/png;base64," を取り除く
                    fetch('/upload_image_to_db', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ image: base64Image }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Image saved to DB:', data);
                        // callback(data.imageUrl); // サーバーから返された画像URLを利用
                        console.log('Uploaded image URL:', data.imageUrl);
                        // 画像を表示
                        const imgElement = document.createElement('img');
                        imgElement.src = data.imageUrl;
                        document.body.appendChild(imgElement);
                    })
                    .catch(error => {
                        console.error('Error saving image to DB:', error);
                    });
                };
                reader.readAsDataURL(file);
            }


            // 1/9追加
            // 画像と文章を同時に送信する場合
            function sendMessage_image() {
                const message = textInput.value.trim();
                console.log('1');
                
                // if (message === "" || chat_image_file === "") {
                //     displayEmptyMessage();
                //     return;
                // }
                // imageArea.addEventListener('change', function(event){
                    console.log('2');
                    // console.log(typeof imageArea.value);
                    // console.log(typeof imageArea.value.files[0]);
                    // console.log(chat_image);
                    if(imageArea.value){
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            const chat_image = event.target.result;
                            console.log(chat_image);
                            socket.emit('sendMessage_image', {
                                message: message,
                                image: chat_image,
                                user_id: user_id,
                                team_id: group_id
                            });
                        };
                        reader.readAsDataURL(imageArea.files[0]); 
                    } else {
                        console.error('No file selected');
                    }

                    textInput.value = "";
                    imageArea.value = "";
                    scrollToBottom();
                // });
                
            }

            // 関数呼び出し
            sendButton.addEventListener("click", function() {
                // 1/9追加
                console.log(imageArea.value);
                if(imageArea.value){
                    console.log('imageArea.value');
                    sendMessage_image();
                }else{
                    sendMessage(); // メッセージを送信する関数を呼び出す
                    console.log('sendMessage');
                }
            });

            textInput.addEventListener("keydown", function(event) {
                if (event.key === "Enter" && !event.shiftKey) {
                    event.preventDefault();
                    sendMessage();
                }
            });

            socket.on('receiveMessage', function(data) {
                console.log('receiveM');
                if (data.team_id == group_id) {
                    const messageType = data.user_id == user_id ? 'sent' : 'received';
                    displayMessage(data.message, messageType, new Date(data.date), data.id, data.username, data.user_id, data.edited, data.is_deleted);
                }
            });

            // 1/9追加
            // 画像を受けとる
            socket.on('receiveMessage_image', function(data) {
                console.log('data');
                if (data.team_id == group_id) {
                    const messageType = data.user_id == user_id ? 'sent' : 'received';
                    displayMessage_Image(data.message, messageType, data.image, new Date(data.date), data.id, data.username, data.user_id, data.edited, data.is_deleted);
                }
            });


            socket.on('updateMessage', function(data) {
                const messageElement = document.querySelector(`.message-container[data-message-id='${data.id}'] .message-content`);
                if (messageElement) {
                    messageElement.innerHTML = data.message.replace(/\n/g, "<br>");
                    let editedElement = document.querySelector(`.message-container[data-message-id='${data.id}'] .message-edited`);
                    if (data.edited == 1) { // データベースの edited が 1 の場合にのみ「編集済み」を表示
                        if (editedElement) {
                            editedElement.style.display = 'block';
                        } else {
                            editedElement = document.createElement('span');
                            editedElement.classList.add('message-edited');
                            editedElement.textContent = '編集済み';
                            messageElement.parentNode.appendChild(editedElement);
                        }
                    } else if (editedElement) {
                        editedElement.style.display = 'none';
                    }
                }
            });




            socket.on('removeMessage', function(data) {
                const messageContainer = document.querySelector(`.message-container[data-message-id='${data.id}']`);
                if (messageContainer) {
                    messageContainer.remove();
                }
            });

            socket.on('logMessage', function(data) {
                displayLogMessage(data.message, new Date(data.date));
            });




            function displayMessage(message, type, date, id, username, messageUserId, edited, is_deleted) {
                const messageContainer = document.createElement("div");
                messageContainer.classList.add("message-container", type === "sent" ? "sent" : "received");
                messageContainer.dataset.messageId = id;
                messageContainer.dataset.userId = messageUserId;

                const messageElement = document.createElement("div");
                messageElement.classList.add("message");
                messageElement.innerHTML = `
        <div class="message-name">${username}</div>
        <div class="message-content">${message.replace(/\n/g, "<br>")}</div>
        <span class="message-time">${formatDate(date)}</span>
    `;

                if (edited == 1) { // edited が 1 の場合のみ編集済みを表示
                    const editedElement = document.createElement('span');
                    editedElement.classList.add('message-edited');
                    editedElement.textContent = '編集済み';
                    messageElement.appendChild(editedElement);
                }

                const iconElement = document.createElement("div");
                iconElement.classList.add("icon");

                if (type !== "sent") {
                    messageContainer.appendChild(iconElement);
                }

                messageContainer.appendChild(messageElement);

                if (messageUserId == user_id) {
                    messageContainer.addEventListener("contextmenu", function(event) {
                        event.preventDefault();
                        showContextMenu(event, messageContainer);
                    });
                }

                chatArea.appendChild(messageContainer);
                scrollToBottom();
            }

            // 画像をチャット画面に表示
            // 1/9追加
            function displayMessage_Image(message, type, image, date, id, username, messageUserId, edited, is_deleted) {
                console.log(image);

                console.log('0');
                const messageContainer = document.createElement("div");
                messageContainer.classList.add("message-container", type === "sent" ? "sent" : "received");
                messageContainer.dataset.messageId = id;
                messageContainer.dataset.userId = messageUserId;

                const imageContainer = document.createElement("div");
                imageContainer.classList.add("message-container", type === "sent" ? "sent" : "received");
                imageContainer.dataset.messageId = id;
                imageContainer.dataset.userId = messageUserId;

                console.log('1');

                const messageElement = document.createElement("div");
                messageElement.classList.add("message");
                messageElement.innerHTML = `
                <div class="message-name">${username}</div>
                <div class="message-content">${message.replace(/\n/g, "<br>")}</div>
                <span class="message-time">${formatDate(date)}</span>`;
                
                const imageElement = document.createElement("div");
                imageElement.classList.add("display_image");
                imageElement.innerHTML = `
                <img width="100px" height="100px" src=${image}>
                <span class="message-time">${formatDate(date)}</span>
                `;

                console.log('2');

                const iconElement = document.createElement("div");
                iconElement.classList.add("icon");

                if (type !== "sent") {
                    messageContainer.appendChild(iconElement);
                }

                messageContainer.appendChild(messageElement);
                imageContainer.appendChild(imageElement);

                if (messageUserId == user_id) {
                    messageContainer.addEventListener("contextmenu", function(event) {
                        event.preventDefault();
                        showContextMenu_image(event, imageContainer);
                    });
                }

                // チャットに文章と画像を表示
                chatArea.appendChild(messageContainer);
                chatArea.appendChild(imageContainer);
                scrollToBottom();
            }



            function deleteMessage(messageContainer) {
                const messageId = messageContainer.dataset.messageId;
                const userId = messageContainer.dataset.userId;

                // クライアント側で即座にメッセージを画面から削除
                messageContainer.remove();

                // サーバーに削除リクエストを送信
                socket.emit('deleteMessage', {
                    id: messageId,
                    user_id: userId
                });
            }



            function displayLogMessage(message, date) {
                const logContainer = document.createElement("div");
                logContainer.classList.add("log-container");
                logContainer.innerHTML = `
            <span class="log-message">${message}</span>
            <span class="log-time">${formatDate(date)}</span>
        `;
                chatArea.appendChild(logContainer);
                scrollToBottom();
            }

            function showContextMenu(event, messageContainer) {
                const contextMenu = document.getElementById("context-menu");
                const editButton = document.getElementById("edit-button");
                const deleteButton = document.getElementById("delete-button");

                editButton.onclick = function() {
                    editMessage(messageContainer);
                    contextMenu.style.display = 'none';
                };
                deleteButton.onclick = function() {
                    const confirmDelete = confirm("本当に削除しますか？");
                    if (confirmDelete) {
                        deleteMessage(messageContainer);
                    }
                    contextMenu.style.display = 'none';
                };

                contextMenu.style.top = `${event.clientY}px`;
                contextMenu.style.left = `${event.clientX}px`;
                contextMenu.style.display = 'block';

                document.addEventListener("click", function() {
                    contextMenu.style.display = 'none';
                }, {
                    once: true
                });
            }



            function scrollToBottom() {
                chatArea.scrollTop = chatArea.scrollHeight;
            }

            function formatDate(date) {
                const options = {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                return date.toLocaleDateString('ja-JP', options);
            }

            async function loadInitialMessages() {
                try {
                    const response = await fetch('fetch_messages.php?group_id=' + group_id);
                    const messages = await response.json();

                    messages.forEach(message => {
                        const messageType = message.user_id == user_id ? 'sent' : 'received';
                        if (message.image != null){
                            displayMessage_Image(message.content, messageType, message.image, new Date(message.date), message.id, message.username, message.user_id, message.edited, message.is_deleted);
                        } else {
                            displayMessage(message.content, messageType, new Date(message.date), message.id, message.username, message.user_id, message.edited, message.is_deleted);
                        }
                    });

                    // 削除ログメッセージも表示
                    const logResponse = await fetch('fetch_log_messages.php?group_id=' + group_id);
                    const logMessages = await logResponse.json();

                    logMessages.forEach(log => {
                        displayLogMessage(log.message, new Date(log.date));
                    });

                    scrollToBottom();
                } catch (error) {
                    console.error(error);
                }
            }


            loadInitialMessages();

            function editMessage(messageContainer) {
                const messageElement = messageContainer.querySelector(".message-content");
                const originalMessage = messageElement.innerHTML.replace(/<br>/g, '\n');

                const messageId = messageContainer.dataset.messageId;
                const newMessage = prompt("メッセージを編集:", originalMessage);

                if (newMessage !== null) {
                    console.log('Editing message:', newMessage);
                    socket.emit('editMessage', {
                        id: messageId,
                        message: newMessage,
                        user_id: user_id
                    });
                }
            }

            function deleteMessage(messageContainer) {
                const messageId = messageContainer.dataset.messageId;
                const userId = messageContainer.dataset.userId;

                // クライアント側で即座にメッセージを画面から削除
                messageContainer.remove();

                // サーバーに削除リクエストを送信
                socket.emit('deleteMessage', {
                    id: messageId,
                    user_id: userId
                });
            }

            const deleteButton = document.getElementById("delete-button");
            deleteButton.onclick = function() {
                const confirmDelete = confirm("本当に削除しますか？");
                if (confirmDelete) {
                    deleteMessage(selectedMessageContainer);
                }
                contextMenu.style.display = 'none';
            };

        });
    </script>
</body>

</html>