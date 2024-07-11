<?php
$servername = 'localhost';
$dbname = 'database_name'; #change
$username = 'database_username'; #change
$password = 'database_password'; #change
$charset = 'utf8mb4';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

$loadAll = isset($_GET['load_all']) ? $_GET['load_all'] : 'false';

if ($loadAll === 'true') {
    $sql = "SELECT * FROM messages ORDER BY timestamp ASC";
} else {
    $sql = "SELECT * FROM messages ORDER BY timestamp DESC LIMIT 5";
}

$result = $conn->query($sql);

$messages = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

if ($loadAll !== 'true') {
    $messages = array_reverse($messages); // Reverse to get the correct order
}

echo json_encode($messages);

$conn->close();
?>