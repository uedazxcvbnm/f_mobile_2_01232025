<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="../chat/chatscreen.css">
    <script src="http://localhost:3000/socket.io/socket.io.js"></script>
</head>

<body>
    <div class="container">
        <div class="header">
            <button id="back-button">&lt; 戻る</button>
            <div class="group-info">
                <span id="group-name">社会人禁酒グループ</span>
                <span id="group-members">(5)</span>
            </div>
        </div>
        <div class="chat-area"></div>
        <div class="message-area">
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
            // Socket.ioでサーバーに接続
            const socket = io('http://localhost:3000');

            // 「戻る」ボタンのクリックイベントを設定
            document.getElementById("back-button").addEventListener("click", function() {
                window.location.href = "../joingrouplist/joingrouplist.html";
            });

            // グループ名とメンバー数を設定
            const groupName = "社会人禁酒グループ";
            const groupMembers = 5;

            document.getElementById("group-name").textContent = groupName;
            document.getElementById("group-members").textContent = `(${groupMembers})`;

            const sendButton = document.getElementById("send");
            const textInput = document.getElementById("text");
            const chatArea = document.querySelector(".chat-area");

            let lastMessageId = 0;

            // URLパラメータからクエリパラメータを取得する関数
            function getQueryParam(param) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(param);
            }

            // URLからuser_idを取得
            const user_id = getQueryParam('user_id');

            // メッセージを送信する関数
            function sendMessage() {
                const message = textInput.value.trim();

                if (message === "") {
                    displayEmptyMessage();
                    return; // メッセージが空の場合は何もせずに終了
                }

                console.log('Sending message:', message); // デバッグ用のログ

                // サーバーにメッセージを送信
                socket.emit('sendMessage', {
                    message: message,
                    user_id: user_id
                });

                textInput.value = "";
                scrollToBottom();
            }

            // 「送信」ボタンのクリックイベントを設定
            sendButton.addEventListener("click", function() {
                console.log('Send button clicked'); // デバッグ用のログ
                sendMessage();
            });

            // Enterキーでメッセージを送信するイベントを設定
            textInput.addEventListener("keydown", function(event) {
                if (event.key === "Enter" && !event.shiftKey) {
                    event.preventDefault();
                    sendMessage();
                }
            });

            // 新しいメッセージを受信したときの処理
            socket.on('receiveMessage', function(data) {
                console.log('Received message:', data); // デバッグ用のログ
                const messageType = data.user_id == user_id ? 'sent' : 'received';
                displayMessage(data.message, messageType, new Date(data.date), data.id, data.username, data.user_id);
            });

            // メッセージが更新されたときの処理
            socket.on('updateMessage', function(data) {
                const messageElement = document.querySelector(`.message-container[data-message-id='${data.id}'] .message-content`);
                if (messageElement) {
                    messageElement.innerHTML = data.message.replace(/\n/g, "<br>");
                }
            });

            // メッセージが削除されたときの処理
            socket.on('removeMessage', function(data) {
                const messageContainer = document.querySelector(`.message-container[data-message-id='${data.id}']`);
                if (messageContainer) {
                    messageContainer.remove();
                }
            });

            // メッセージを表示する関数
            function displayMessage(message, type, date, id, username, messageUserId) {
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

                const iconElement = document.createElement("div");
                iconElement.classList.add("icon"); // 仮のアイコンのスタイルを適用するためのクラス

                // 自分のメッセージにはアイコンを表示しない
                if (type !== "sent") {
                    messageContainer.appendChild(iconElement);
                }

                messageContainer.appendChild(messageElement);
                chatArea.appendChild(messageContainer);

                // 右クリックでコンテキストメニューを表示
                messageContainer.addEventListener("contextmenu", function(event) {
                    event.preventDefault();
                    if (messageUserId == user_id) {
                        currentMessageContainer = messageContainer;
                        contextMenu.style.top = `${event.clientY}px`;
                        contextMenu.style.left = `${event.clientX}px`;
                        contextMenu.style.display = "block";
                    }
                });

                scrollToBottom();
            }

            // 空のメッセージを表示する関数
            function displayEmptyMessage() {
                const messageContainer = document.createElement("div");
                messageContainer.classList.add("message-container");

                const messageElement = document.createElement("div");
                messageElement.classList.add("message", "empty");
                messageElement.textContent = "\u00A0".repeat(20);

                messageContainer.appendChild(messageElement);
                chatArea.appendChild(messageContainer);

                scrollToBottom();
            }

            // チャットエリアをスクロールして一番下まで表示する関数
            function scrollToBottom() {
                chatArea.scrollTop = chatArea.scrollHeight;
            }

            // 日付をフォーマットする関数
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

            // メッセージをサーバーから取得する関数
            async function fetchMessages() {
                try {
                    const response = await fetch('fetch_messages.php');
                    const messages = await response.json();

                    messages.forEach(message => {
                        const messageType = message.user_id == user_id ? 'sent' : 'received';
                        displayMessage(message.content, messageType, new Date(message.date), message.id, message.username, message.user_id);
                        lastMessageId = message.id;
                    });

                    scrollToBottom();
                } catch (error) {
                    console.error(error);
                }
            }

            // 初期メッセージをロードする関数
            async function loadInitialMessages() {
                try {
                    const response = await fetch('fetch_messages.php');
                    const messages = await response.json();

                    messages.forEach(message => {
                        const messageType = message.user_id == user_id ? 'sent' : 'received';
                        displayMessage(message.content, messageType, new Date(message.date), message.id, message.username, message.user_id);
                        lastMessageId = Math.max(lastMessageId, message.id);
                    });

                    scrollToBottom();
                } catch (error) {
                    console.error(error);
                }
            }

            // 初期メッセージをロード
            loadInitialMessages();

            const contextMenu = document.getElementById("context-menu");
            let currentMessageContainer = null;

            // コンテキストメニューのクリックイベントを設定
            document.addEventListener("click", function(event) {
                if (!contextMenu.contains(event.target)) {
                    contextMenu.style.display = "none";
                }
            });

            // 「編集」ボタンのクリックイベントを設定
            document.getElementById("edit-button").addEventListener("click", function() {
                if (currentMessageContainer) {
                    editMessage(currentMessageContainer);
                    contextMenu.style.display = "none";
                }
            });

            // 「削除」ボタンのクリックイベントを設定
            document.getElementById("delete-button").addEventListener("click", function() {
                if (currentMessageContainer) {
                    const confirmDelete = confirm("本当に削除しますか？");
                    if (confirmDelete) {
                        deleteMessage(currentMessageContainer);
                    }
                    contextMenu.style.display = "none";
                }
            });

            // メッセージを編集する関数
            function editMessage(messageContainer) {
                const messageElement = messageContainer.querySelector(".message-content");
                const originalMessage = messageElement.innerHTML.replace(/<br>/g, '\n'); // メッセージ内容を取得して改行を置換

                const messageId = messageContainer.dataset.messageId;
                const newMessage = prompt("メッセージを編集:", originalMessage);

                if (newMessage !== null) {
                    socket.emit('editMessage', {
                        id: messageId,
                        message: newMessage
                    });
                }
            }

            // メッセージを削除する関数
            function deleteMessage(messageContainer) {
                const messageId = messageContainer.dataset.messageId;
                socket.emit('deleteMessage', {
                    id: messageId
                });
            }
        });
    </script>
</body>

</html>