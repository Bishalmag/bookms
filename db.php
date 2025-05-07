<?php
// Database credentials
$host = 'localhost';
$dbname = 'book_management_system';
$username = 'root';  // Default XAMPP username
$password = '';      // Default XAMPP password (empty)

try {
    // Create a PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, display an error message and stop script execution
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
