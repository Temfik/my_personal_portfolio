<?php
// Database connection
$host = 'localhost';
$dbname = 'temesgen_portfolio';
$username = 'root';
$password = '';

try {
    // Use MySQLi for compatibility with the rest of the admin panel
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");

} catch (Exception $e) {
    // Log the error (do not expose DB info to users)
    error_log("Database connection failed: " . $e->getMessage());
    die("System temporarily unavailable. Please try again later.");
}
?>
