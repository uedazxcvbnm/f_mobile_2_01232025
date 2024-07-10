// server.js
const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const mysql = require('mysql');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

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
        const message = data.message;
        const user_id = data.user_id;
        const date = new Date();

        const query = "INSERT INTO chat (message, date, user_id) VALUES (?, ?, ?)";
        db.query(query, [message, date, user_id], (err, result) => {
            if (err) {
                console.error('Error inserting message:', err);
                return;
            }
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
