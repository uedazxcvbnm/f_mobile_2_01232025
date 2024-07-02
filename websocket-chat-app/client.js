const WebSocket = require('ws');

const ws = new WebSocket('ws://localhost:8080');

ws.onopen = function () {
    console.log('WebSocket client connected');
};

ws.onmessage = function (event) {
    console.log('Received message:', event.data);
    // 受信したメッセージを表示するなどの処理を記述
};

function sendMessage(message) {
    ws.send(message);
}

// 例として、テキストボックスからの入力でメッセージを送信する場合
const input = document.getElementById('message-input');
const sendButton = document.getElementById('send-button');

sendButton.addEventListener('click', function () {
    const message = input.value.trim();
    if (message !== '') {
        sendMessage(message);
        input.value = '';
    }
});
