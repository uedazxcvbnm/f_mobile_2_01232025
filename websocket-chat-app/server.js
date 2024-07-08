const WebSocket = require('ws');

const wss = new WebSocket.Server({ port: 8080 });

let nextClientId = 1;
const clients = {};

wss.on('connection', function connection(ws) {
    const clientId = nextClientId++;
    clients[clientId] = ws;

    ws.on('message', function incoming(message) {
        // メッセージを受信したときの処理
        console.log(`Received message: ${message}`);

        // すべてのクライアントにメッセージを送信
        Object.values(clients).forEach(client => {
            if (client.readyState === WebSocket.OPEN) {
                client.send(message);
            }
        });
    });

    ws.on('close', function () {
        // クライアントが切断されたときの処理
        delete clients[clientId];
    });
});

console.log('WebSocket server running on ws://localhost:8080');
