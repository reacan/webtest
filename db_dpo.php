<?php
$servername = "localhost";
$username = "username";
$password = "passwd!";
$dbname = "dbname";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


try {
    // Establish the database connection using PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;port=$port;charset=utf8mb4", $username, $password);

    // Set PDO attributes if needed (optional)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection errors if necessary
    echo "Connection failed: " . $e->getMessage();
    die(); // Terminate script execution if the connection fails
}
?>
