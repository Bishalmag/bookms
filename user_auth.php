<?php
session_start();
 
// Check if user is logged in and has the correct user type
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'user') {
    header("Location: login.php");
    exit();
}
?>
