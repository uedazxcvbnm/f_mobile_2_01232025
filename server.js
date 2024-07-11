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

            const messageId = result.insertId;
            const userQuery = "SELECT username FROM user WHERE user_id = ?";
            db.query(userQuery, [user_id], (err, userResult) => {
                if (err) {
                    console.error('Error fetching username:', err);
                    return;
                }

                const username = userResult[0].username;
                io.emit('receiveMessage', { id: messageId, message, date, user_id, username });
            });
        });
    });

    socket.on('editMessage', (data) => {
        const { id, message, user_id } = data;

        const getMessageQuery = "SELECT user_id FROM chat WHERE id = ?";
        db.query(getMessageQuery, [id], (err, result) => {
            if (err) {
                console.error('Error fetching message:', err);
                return;
            }

            if (result.length > 0 && result[0].user_id === user_id) {
                const updateQuery = "UPDATE chat SET message = ? WHERE id = ?";
                db.query(updateQuery, [message, id], (err, result) => {
                    if (err) {
                        console.error('Error updating message:', err);
                        return;
                    }
                    io.emit('updateMessage', { id, message });
                });
            } else {
                console.error('User not authorized to edit this message');
            }
        });
    });

    socket.on('deleteMessage', (data) => {
        const { id, user_id } = data;

        const getMessageQuery = "SELECT user_id FROM chat WHERE id = ?";
        db.query(getMessageQuery, [id], (err, result) => {
            if (err) {
                console.error('Error fetching message:', err);
                return;
            }

            if (result.length > 0 && result[0].user_id === user_id) {
                const deleteQuery = "DELETE FROM chat WHERE id = ?";
                db.query(deleteQuery, [id], (err, result) => {
                    if (err) {
                        console.error('Error deleting message:', err);
                        return;
                    }
                    io.emit('removeMessage', { id });
                });
            } else {
                console.error('User not authorized to delete this message');
            }
        });
    });

    socket.on('disconnect', () => {
        console.log('Client disconnected');
    });
});

server.listen(3000, () => {
    console.log('Server is running on port 3000');
});
