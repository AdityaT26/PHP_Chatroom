<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hideout</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap">
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // Enable Pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('<Your_Pusher_Key>', {  //change
            cluster: '<Your_Pusher_Cluster>' //change
        });

        var channel = pusher.subscribe('chat-channel');
        channel.bind('new-message', function(data) {
            var chatBox = document.getElementById('chat-box');
            var messageElement = document.createElement('div');
            messageElement.className = 'message';
            messageElement.innerHTML = `<strong style="color:${getUsernameColor(data.username)};">${data.username}:</strong> ${data.message}`;
            chatBox.appendChild(messageElement);
            chatBox.scrollTop = chatBox.scrollHeight;
        });

        function toggleDarkMode() {
            document.body.classList.toggle('light-mode');
        }

        function getUsernameColor(username) {
            const colors = ['#FF5733', '#33FF57', '#3357FF', '#F333FF', '#33FFF5', '#FF33EC'];
            let hash = 0;
            for (let i = 0; i < username.length; i++) {
                hash = username.charCodeAt(i) + ((hash << 5) - hash);
            }
            return colors[Math.abs(hash) % colors.length];
        }

        function loadChats(loadAll = false) {
            fetch(`load_chats.php?load_all=${loadAll}`)
                .then(response => response.json())
                .then(messages => {
                    var chatBox = document.getElementById('chat-box');
                    if (loadAll) {
                        chatBox.innerHTML = '';
                    }
                    messages.forEach(data => {
                        var messageElement = document.createElement('div');
                        messageElement.className = 'message';
                        messageElement.innerHTML = `<strong style="color:${getUsernameColor(data.username)};">${data.username}:</strong> ${data.message}`;
                        chatBox.appendChild(messageElement);
                    });
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
        }

        function toggleEmojiKeyboard() {
            var emojiKeyboard = document.getElementById('emoji-keyboard');
            if (emojiKeyboard.style.display === 'none' || !emojiKeyboard.style.display) {
                emojiKeyboard.style.display = 'block';
            } else {
                emojiKeyboard.style.display = 'none';
            }
        }

        function insertEmoji(emoji) {
            var messageInput = document.getElementById('message');
            messageInput.value += emoji;
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadChats(); // Load the last 5 messages on page load
        });
    </script>
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: 'Montserrat', sans-serif, Arial, Helvetica, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 90vh;
            margin: 0;
            padding: 0;
            touch-action: manipulation;
        }
        .chat-container {
            width: 90%;
            max-width: 600px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .chat-box {
            border: 1px solid #ccc;
            height: 450px;
            width: 100%;
            overflow-y: scroll;
            background-color: #1e1e1e;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
        }
        .message {
            margin-bottom: 10px;
            border-radius: 5px;
            padding: 5px 10px;
        }
        .chat-form {
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-top: auto;
        }
        .chat-form input {
            width: 75%;
            padding: 10px;
            border: none;
            border-radius: 10px 0 0 10px;
            font-size: 16px;
        }
        .chat-form button[type="submit"] {
            width: 25%;
            padding: 10px;
            border: none;
            border-radius: 0 10px 10px 0;
            font-size: 16px;
            background-color: #6200ea;
            color: #ffffff;
            cursor: pointer;
        }
        .chat-form button.emoji-button {
            width: 12%;
            padding: 10px;
            border: none;
            border-radius: 50%;
            font-size: 16px;
            background-color: #6200ea;
            color: #121212;
            cursor: pointer;
            margin-left: 5px;
        }
        .dark-mode-toggle {
            position: absolute;
            top: 15px;
            right: 20px;
            background-color: #6200ea;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 10px;
            cursor: pointer;
        }
        h2 {
            position: absolute;
            top: 0px;
            left: 30px;
        }
        .load-chats-button {
            background-color: #6200ea;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 10px;
            margin: 10px;
            cursor: pointer;
        }
        .emoji-keyboard {
            display: none;
            background-color: #1e1e1e;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 10px;
            position: absolute;
            bottom: 100px;
            z-index: 10;
            
        }
        .emoji-keyboard button {
            font-size: 20px;
            background: none;
            border: none;
            cursor: pointer;
            margin: 2px;
        }
        .close-emoji-keyboard {
            display: block;
            margin-top: 10px;
            background-color: #6200ea;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 5px;
            cursor: pointer;
        }
        body.light-mode {
            background-color: #ffffff;
            color: #000000;
        }
        body.light-mode .chat-box {
            background-color: #f1f1f1;
        }
        body.light-mode .dark-mode-toggle {
            background-color: #000000;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <button class="dark-mode-toggle" onclick="toggleDarkMode()">🌻/🪻</button>
    <h2>Welcome back to the chatroom!</h2>
    <div class="chat-container">
        
        <button class="load-chats-button" onclick="loadChats(true)">Load Previous Chats</button>
        <div id="chat-box" class="chat-box"></div>
        <form class="chat-form" id="chat-form">
            <input type="text" id="message" placeholder="Type a message..." required>
            <button type="submit">Send</button>
            <button type="button" class="emoji-button" onclick="toggleEmojiKeyboard()">😊</button>
        </form>
    </div>
    <div id="emoji-keyboard" class="emoji-keyboard">
        <button onclick="insertEmoji('😂')">😂</button>
        <button onclick="insertEmoji('😭')">😭</button>
        <button onclick="insertEmoji('🥺')">🥺</button>
        <button onclick="insertEmoji('😘')">😘</button>
        <button onclick="insertEmoji('🥰')">🥰</button>
        <br>
        <button onclick="insertEmoji('💀')">💀</button>
        <button onclick="insertEmoji('❤️')">❤️</button>
        <button onclick="insertEmoji('🩵')">🩵</button>
        <button onclick="insertEmoji('🩷')">🩷</button>
        <button onclick="insertEmoji('💛')">💛</button>
        <br>
        <button onclick="insertEmoji('🧡')">🧡</button>
        <button onclick="insertEmoji('💞')">💞</button>
        <button onclick="insertEmoji('🍯')">🍯</button>
        <button onclick="insertEmoji('🧸')">🧸</button>
        <button onclick="insertEmoji('🧁')">🧁</button>
        <br>
        <button onclick="insertEmoji('🤤')">🤤</button>
        <button onclick="insertEmoji('🤭')">🤭</button>
        <button onclick="insertEmoji('💅🏻')">💅🏻</button>
        <button onclick="insertEmoji('😤')">😤</button>
        <button onclick="insertEmoji('😠')">😠</button>
        <br>
        <button onclick="insertEmoji('😳')">😳</button>
        <button onclick="insertEmoji('😋')">😋</button>
        <button onclick="insertEmoji('🙏🏻')">🙏🏻</button>
        <button onclick="insertEmoji('👀')">👀</button>
        <button onclick="insertEmoji('✨')">✨</button>
        <button class="close-emoji-keyboard" onclick="toggleEmojiKeyboard()">Close</button>
    </div>
    <script>
        document.getElementById('chat-form').addEventListener('submit', function(e) {
            e.preventDefault();

            var message = document.getElementById('message').value;
            //var username = sessionStorage.getItem('username');
            var username = "<?php echo $_GET['username']; ?>";
            if (!username) {
                username = prompt('Enter your name:');
                sessionStorage.setItem('username', username);
            }

            fetch('chat_server.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `username=${username}&message=${encodeURIComponent(message)}`
            }).then(() => {
                document.getElementById('message').value = '';
            });
        });
    </script>
</body>
</html>
