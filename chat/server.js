const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors');
const mysql = require('mysql');
const multer = require('multer');
const path = require('path');
const fs = require('fs');
const mime = require("mime-types");

const app = express();
app.use(cors());
app.use(express.json({ limit: '100mb' }));
app.use('/images', express.static(path.join(__dirname, 'images')));

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

    // 1/9追加
    socket.on('sendMessage_image', (data) => {
        const { message, image, user_id, team_id } = data;
        const date = new Date();
    
        // 画像データがない場合はエラーを送信
        if (!image) {
            return socket.emit("upload_error", "画像データが不足しています。");
        }
    
        try {
            // Base64データからプレフィックスを除去
            const base64Image = image.replace(/^data:image\/[a-z]+;base64,/, '');
            const imageBuffer = Buffer.from(base64Image, 'base64'); // バイナリデータに変換
            const imageName = `image_${Date.now()}.png`; // ファイル名の生成（拡張子は適宜変更）
            const imagePath = path.join(__dirname, "../images", imageName); // 保存先パス
    
            // 画像をファイルとして保存
            fs.writeFile(imagePath, imageBuffer, (err) => {
                if (err) {
                    console.error('Error writing image to file:', err);
                    return socket.emit("upload_error", "画像の保存に失敗しました。");
                }
    
                console.log(`Image saved at ${imagePath}`);
    
                // DBでは相対パスを保存
                const relativeImagePath = `./../images/${imageName}`;
    
                // データベースにメッセージと画像パスを保存
                const query = "INSERT INTO group_chat (message, image, date, user_id, team_id) VALUES (?, ?, ?, ?, ?)";
                db.query(query, [message, relativeImagePath, date, user_id, team_id], (err, result) => {
                    if (err) {
                        console.error("Error inserting message:", err);
                        return;
                    }
    
                    const selectQuery = "SELECT username FROM user WHERE user_id = ?";
                    db.query(selectQuery, [user_id], (err, rows) => {
                        if (err) {
                            console.error("Error fetching username:", err);
                            return;
                        }
                        const username = rows[0].username;
    
                        // クライアントに新しいメッセージを送信
                        io.emit("receiveMessage_image", {
                            id: result.insertId,
                            message,
                            image: relativeImagePath, // クライアント用に相対パスを送信
                            date,
                            user_id,
                            team_id,
                            username,
                            edited: false,
                        });
                    });
                });
            });
        } catch (error) {
            console.error("Error processing image data:", error);
            socket.emit("upload_error", "画像データの処理中にエラーが発生しました。");
        }
    });

    socket.on('sendMessage', (data) => {
        const { message, user_id, team_id } = data;
        const date = new Date();

        const query = "INSERT INTO group_chat (message, date, user_id,team_id) VALUES (?, ?, ?, ?)";
        db.query(query, [message, date, user_id, team_id], (err, result) => {
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
                // クライアントに新しいメッセージを送信
                io.emit('receiveMessage', {
                    id: result.insertId,
                    message,
                    date,
                    user_id,
                    team_id,
                    username,
                    edited: false
                });
            });
        });
    });


    socket.on('editMessage', (data) => {
        const { id, message } = data;
        console.log('Editing message with id:', id, 'to:', message);

        // group_chatテーブルでメッセージを更新する
        const query = "UPDATE group_chat SET message = ?, edited = TRUE WHERE id = ?";
        db.query(query, [message, parseInt(id, 10)], (err, result) => {
            if (err) {
                console.error('Error updating message:', err);
                return;
            }

            // 影響を受けた行が0の場合、メッセージが見つからなかったことを警告
            if (result.affectedRows === 0) {
                console.warn('No message found with id:', id);
                return;
            }

            // クエリが成功した場合、クライアントに更新を通知
            io.emit('updateMessage', { id, message, edited: true });
        });
    });
    socket.on('removeMessage', function (data) {
        const messageContainer = document.querySelector(`.message-container[data-message-id='${data.id}']`);
        if (messageContainer) {
            messageContainer.remove();
        }
    });

    socket.on('deleteFailed', function (data) {
        alert('メッセージの削除に失敗しました。もう一度お試しください。');
    });



    socket.on('deleteMessage', (data) => {
        const { id, user_id } = data;
        console.log('Deleting message with id:', id);

        const selectQuery = "SELECT username FROM user WHERE user_id = ?";
        db.query(selectQuery, [user_id], (err, rows) => {
            if (err) {
                console.error('Error fetching username:', err);
                return;
            }

            if (rows.length === 0) {
                console.warn('No user found with user_id:', user_id);
                return;
            }

            const username = rows[0].username;

            const deleteQuery = "DELETE FROM group_chat WHERE id = ?";
            db.query(deleteQuery, [parseInt(id, 10)], (err, result) => {
                if (err) {
                    console.error('Error deleting message:', err);
                    return;
                }

                if (result.affectedRows === 0) {
                    console.warn('No message found with id:', id);
                    return;
                }

                // 削除が成功したら全クライアントに通知
                io.emit('removeMessage', { id });

                // 「〇〇さんがメッセージを削除しました」というログメッセージを送信
                const logMessage = `${username}がメッセージを削除しました。`;
                io.emit('logMessage', { message: logMessage, date: new Date() });
            });
        });
    });




    socket.on('disconnect', () => {
        console.log('Client disconnected');
    });
});

server.listen(3000, () => {
    console.log('Server is running on port 3000');
});
