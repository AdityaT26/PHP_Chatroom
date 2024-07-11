<?php
session_start();
require 'mail_config.php';

define('PASSWORD', '<password>'); // Set your chat password here

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    
    if ($password === PASSWORD) {
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $username;

        // Send email notification
        sendMail($recipients, "Chatroom Alert!", "$username has entered the chatroom.");
        header('Location: chat.php?username=' . urlencode($username));
        exit;
    } else {
        echo "Incorrect password!";
    }
}
?>
