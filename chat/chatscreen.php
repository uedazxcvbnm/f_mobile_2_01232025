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
            const socket = io('http://localhost:3000');

            document.getElementById("back-button").addEventListener("click", function() {
                window.location.href = "../joingrouplist/joingrouplist.html";
            });

            const groupName = "社会人禁酒グループ";
            const groupMembers = 5;

            document.getElementById("group-name").textContent = groupName;
            document.getElementById("group-members").textContent = `(${groupMembers})`;

            const sendButton = document.getElementById("send");
            const textInput = document.getElementById("text");
            const chatArea = document.querySelector(".chat-area");

            let lastMessageId = 0;

            function sendMessage() {
                const message = textInput.value.trim();

                if (message === "") {
                    displayEmptyMessage();
                    return; // メッセージが空の場合は何もせずに終了
                }

                console.log('Sending message:', message); // デバッグ用のログ

                socket.emit('sendMessage', {
                    message: message,
                    user_id: 1 // ユーザーIDは適切な値に置き換えてください
                });

                textInput.value = "";
                scrollToBottom();
            }

            sendButton.addEventListener("click", function() {
                console.log('Send button clicked'); // デバッグ用のログ
                sendMessage();
            });

            textInput.addEventListener("keydown", function(event) {
                if (event.key === "Enter" && !event.shiftKey) {
                    event.preventDefault();
                    sendMessage();
                }
            });

            socket.on('receiveMessage', function(data) {
                console.log('Received message:', data); // デバッグ用のログ
                displayMessage(data.message, "received", new Date(data.date), data.id);
            });

            socket.on('updateMessage', function(data) {
                const messageElement = document.querySelector(`.message-container[data-message-id='${data.id}'] .message-content`);
                if (messageElement) {
                    messageElement.innerHTML = data.message.replace(/\n/g, "<br>");
                }
            });

            socket.on('removeMessage', function(data) {
                const messageContainer = document.querySelector(`.message-container[data-message-id='${data.id}']`);
                if (messageContainer) {
                    messageContainer.remove();
                }
            });

            function displayMessage(message, type, date, id) {
                const messageContainer = document.createElement("div");
                messageContainer.classList.add("message-container", type === "sent" ? "sent" : "received");
                messageContainer.dataset.messageId = id;

                const messageElement = document.createElement("div");
                messageElement.classList.add("message");
                messageElement.innerHTML = `
                    <div class="message-name">ユーザー名</div>
                    <div class="message-content">${message.replace(/\n/g, "<br>")}</div>
                    <span class="message-time">${formatDate(date)}</span>
                `;

                const iconElement = document.createElement("div");
                iconElement.classList.add("icon"); // 仮のアイコンのスタイルを適用するためのクラス

                messageContainer.appendChild(iconElement);
                messageContainer.appendChild(messageElement);
                chatArea.appendChild(messageContainer);

                messageContainer.addEventListener("contextmenu", function(event) {
                    event.preventDefault();
                    currentMessageContainer = messageContainer;
                    contextMenu.style.top = `${event.clientY}px`;
                    contextMenu.style.left = `${event.clientX}px`;
                    contextMenu.style.display = "block";
                });

                scrollToBottom();
            }

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

            async function fetchMessages() {
                try {
                    const response = await fetch('fetch_messages.php');
                    const messages = await response.json();

                    messages.forEach(message => {
                        displayMessage(message.content, message.type === 'sent' ? "sent" : "received", new Date(message.date), message.id);
                        lastMessageId = message.id;
                    });

                    scrollToBottom();
                } catch (error) {
                    console.error(error);
                }
            }

            async function loadInitialMessages() {
                try {
                    const response = await fetch('fetch_messages.php');
                    const messages = await response.json();

                    messages.forEach(message => {
                        displayMessage(message.content, message.type === 'sent' ? "sent" : "received", new Date(message.date), message.id);
                        lastMessageId = Math.max(lastMessageId, message.id);
                    });

                    scrollToBottom();
                } catch (error) {
                    console.error(error);
                }
            }

            loadInitialMessages();
            // setInterval(fetchMessages, 2000); // 定期的にメッセージを取得する

            const contextMenu = document.getElementById("context-menu");
            let currentMessageContainer = null;

            document.addEventListener("click", function(event) {
                if (!contextMenu.contains(event.target)) {
                    contextMenu.style.display = "none";
                }
            });

            document.getElementById("edit-button").addEventListener("click", function() {
                if (currentMessageContainer) {
                    editMessage(currentMessageContainer);
                    contextMenu.style.display = "none";
                }
            });

            document.getElementById("delete-button").addEventListener("click", function() {
                if (currentMessageContainer) {
                    const confirmDelete = confirm("本当に削除しますか？");
                    if (confirmDelete) {
                        deleteMessage(currentMessageContainer);
                    }
                    contextMenu.style.display = "none";
                }
            });

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