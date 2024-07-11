<?php
require __DIR__ . '/vendor/autoload.php';

$host = 'localhost';
$db = 'database_name'; //change
$user = 'database_username'; //change
$pass = 'database_password'; //change
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$options = array(
    'cluster' => '<Your_Pusher_Cluster>', //change
    'useTLS' => true
);
$pusher = new Pusher\Pusher(
    '<Your_Pusher_Key>', //change
    '<Your_Pusher_Secret>', //change
    '<Your_Pusher_App_ID>', //change
    $options
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $message = htmlspecialchars($_POST['message']);
    
    // Save message to database
    $stmt = $pdo->prepare('INSERT INTO messages (username, message) VALUES (?, ?)');
    $stmt->execute([$username, $message]);
    
    // Trigger Pusher event
    $pusher->trigger('chat-channel', 'new-message', ['username' => $username, 'message' => $message]);
    echo 'Message sent';
}
