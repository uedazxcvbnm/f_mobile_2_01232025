<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>chat</title>
    <link rel="stylesheet" href="../chat/chatscreen.css">
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
                <button id="send" class="disabled-button">▻</button>
            </div>
        </div>
    </div>
    <div id="context-menu" class="context-menu">
        <div class="context-menu-item" id="edit-button">編集</div>
        <div class="context-menu-item" id="delete-button">削除</div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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

            async function saveMessageToServer(message) {
                const response = await fetch('save_message.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        message: message
                    })
                });

                if (!response.ok) {
                    throw new Error('メッセージの保存に失敗しました');
                }
            }

            async function sendMessage() {
                const message = textInput.value.trim();

                if (message === "") {
                    displayEmptyMessage();
                    return; // メッセージが空の場合は何もせずに終了
                }

                try {
                    await saveMessageToServer(message);
                    displayMessage(message, "sent", new Date());
                } catch (error) {
                    console.error(error);
                }

                textInput.value = "";
                scrollToBottom();
            }

            textInput.addEventListener("keydown", function(event) {
                if (event.key === "Enter" && !event.shiftKey) {
                    event.preventDefault();
                    sendMessage();
                }
            });

            sendButton.addEventListener("click", function() {
                sendMessage();
            });

            function displayMessage(message, type, date) {
                const messageContainer = document.createElement("div");
                messageContainer.classList.add("message-container");
                messageContainer.dataset.messageId = ++lastMessageId;

                const messageElement = document.createElement("div");
                messageElement.classList.add("message", type);
                messageElement.innerHTML = message.replace(/\n/g, "<br>");

                const timeElement = document.createElement("span");
                timeElement.classList.add("message-time");
                timeElement.textContent = formatDate(date);

                messageElement.appendChild(timeElement);
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
                        displayMessage(message.content, "received", new Date(message.date));
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
                        displayMessage(message.content, message.type === 'sent' ? "sent" : "received", new Date(message.date));
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
                    deleteMessage(currentMessageContainer);
                    contextMenu.style.display = "none";
                }
            });
            //hennsyuu
            function editMessage(messageContainer) {
                const messageElement = messageContainer.querySelector(".message");
                const originalMessage = messageElement.textContent.trim(); // HTMLタグを除去してテキストのみ取得

                // メッセージの内容とIDを取得
                const messageId = messageContainer.dataset.messageId;
                const newMessage = prompt("メッセージを編集:", originalMessage);

                if (newMessage !== null) {
                    // サーバーに更新リクエストを送信
                    updateMessageOnServer(messageId, newMessage);
                }
            }

            // サーバーにメッセージの更新リクエストを送信する関数
            async function updateMessageOnServer(messageId, newMessage) {
                try {
                    const response = await fetch('update_message.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: messageId,
                            message: newMessage
                        })
                    });

                    const data = await response.json();
                    if (data.status === 'success') {
                        // 更新成功時の処理
                        console.log('メッセージが更新されました。');
                    } else {
                        console.error('更新に失敗しました:', data.message);
                    }
                } catch (error) {
                    console.error('更新リクエスト時にエラーが発生しました:', error.message);
                }
            }


            // サーバーからメッセージを削除する関数
            async function deleteMessageFromServer(messageId) {
                try {
                    const response = await fetch('../chat/delete_message.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: messageId
                        })
                    });

                    if (!response.ok) {
                        throw new Error('メッセージの削除に失敗しました');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        });
    </script>
</body>

</html>