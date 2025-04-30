<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page or any other page after logout
header("Location: login.php"); // Change "login.php" to the appropriate URL
exit();
?>
