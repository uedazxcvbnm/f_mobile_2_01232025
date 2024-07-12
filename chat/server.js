const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors');
const mysql = require('mysql');

const app = express();
app.use(cors());
app.use(express.json());

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
        const { message, user_id } = data;
        const date = new Date();

        const query = "INSERT INTO chat (message, date, user_id) VALUES (?, ?, ?)";
        db.query(query, [message, date, user_id], (err, result) => {
            if (err) {
                console.error('Error inserting message:', err);
                return;
            }
            const selectQuery = "SELECT username FROM user WHERE user_id = ?";
            db.query(selectQuery, [user_id], (err, rows) => {
                if (err) {
                    console.error('Error fetching username:', err);
                    return;
                }
                const username = rows[0].username;
                io.emit('receiveMessage', { id: result.insertId, message, date, user_id, username, edited: false });
            });
        });
    });

    socket.on('editMessage', (data) => {
        const { id, message } = data;
        console.log('Editing message with id:', id, 'to:', message); // デバッグ用ログ

        const query = "UPDATE chat SET message = ?, edited = 1 WHERE id = ?";
        db.query(query, [message, id], (err, result) => {
            if (err) {
                console.error('Error updating message:', err);
                return;
            }
            io.emit('updateMessage', { id, message, edited: true });
        });
    });

    socket.on('deleteMessage', (data) => {
        const { id } = data;
        console.log('Deleting message with id:', id); // デバッグ用ログ

        const query = "DELETE FROM chat WHERE id = ?";
        db.query(query, [id], (err, result) => {
            if (err) {
                console.error('Error deleting message:', err);
                return;
            }
            io.emit('removeMessage', { id });
        });
    });

    socket.on('disconnect', () => {
        console.log('Client disconnected');
    });
});

server.listen(3000, () => {
    console.log('Server is running on port 3000');
});
