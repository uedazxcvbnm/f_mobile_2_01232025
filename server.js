const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors'); // 追加
const mysql = require('mysql');

const app = express();
app.use(cors()); // CORSを有効にするために追加

const server = http.createServer(app);
const io = socketIo(server, {
    cors: {
        origin: "http://localhost",
        methods: ["GET", "POST"]
    }
});

const db = mysql.createConnection({
    host: 'localhost',
    user: 'kobe',
    password: 'denshi',
    database: 'pbl2'
});

db.connect((err) => {
    if (err) {
        throw err;
    }
    console.log('MySQL Connected...');
});

io.on('connection', (socket) => {
    console.log('New client connected');

    socket.on('sendMessage', (data) => {
        console.log('Received message:', data); // デバッグ用のログ

        const message = data.message;
        const user_id = data.user_id;
        const date = new Date();

        const query = "INSERT INTO chat (message, date, user_id) VALUES (?, ?, ?)";
        db.query(query, [message, date, user_id], (err, result) => {
            if (err) {
                console.error('Error inserting message:', err); // デバッグ用のエラーログ
                return;
            }
            console.log('Message inserted with ID:', result.insertId); // デバッグ用のログ
            io.emit('receiveMessage', { id: result.insertId, message, date, user_id });
        });
    });

    socket.on('disconnect', () => {
        console.log('Client disconnected');
    });
});

server.listen(3000, () => {
    console.log('Server is running on port 3000');
});
