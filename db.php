<?php
$servername = "localhost";
$username = "database_name"; #change
$password = "database_username"; #change
$dbname = "database_password"; #change

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
