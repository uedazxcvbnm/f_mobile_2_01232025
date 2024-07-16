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

            function getQueryParam(param) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(param);
            }

            const user_id = getQueryParam('user_id');

            function sendMessage() {
                const message = textInput.value.trim();

                if (message === "") {
                    displayEmptyMessage();
                    return;
                }

                socket.emit('sendMessage', {
                    message: message,
                    user_id: user_id
                });

                textInput.value = "";
                scrollToBottom();
            }

            sendButton.addEventListener("click", function() {
                sendMessage();
            });

            textInput.addEventListener("keydown", function(event) {
                if (event.key === "Enter" && !event.shiftKey) {
                    event.preventDefault();
                    sendMessage();
                }
            });

            socket.on('receiveMessage', function(data) {
                const messageType = data.user_id == user_id ? 'sent' : 'received';
                displayMessage(data.message, messageType, new Date(data.date), data.id, data.username, data.user_id, data.edited, data.is_deleted);
            });

            socket.on('updateMessage', function(data) {
                const messageElement = document.querySelector(`.message-container[data-message-id='${data.id}'] .message-content`);
                if (messageElement) {
                    messageElement.innerHTML = data.message.replace(/\n/g, "<br>");
                    const editedElement = document.querySelector(`.message-container[data-message-id='${data.id}'] .message-edited`);
                    if (editedElement) {
                        editedElement.style.display = 'block';
                    } else {
                        const newEditedElement = document.createElement('span');
                        newEditedElement.classList.add('message-edited');
                        newEditedElement.textContent = '編集済み';
                        messageElement.parentNode.appendChild(newEditedElement);
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
                if (is_deleted) {
                    displayLogMessage(message, date);
                    return;
                }

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

                if (edited) {
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
                        const messageType = message.user_id == user_id ? 'sent' : 'received';
                        displayMessage(message.content, messageType, new Date(message.date), message.id, message.username, message.user_id, message.edited, message.is_deleted);
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
                        const messageType = message.user_id == user_id ? 'sent' : 'received';
                        displayMessage(message.content, messageType, new Date(message.date), message.id, message.username, message.user_id, message.edited, message.is_deleted);
                        lastMessageId = Math.max(lastMessageId, message.id);
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
                console.log('Deleting message:', messageId);
                socket.emit('deleteMessage', {
                    id: messageId,
                    user_id: user_id
                });
            }
        });
    </script>
</body>

</html>