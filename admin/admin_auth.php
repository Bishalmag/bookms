<?php
session_start();
// Check if admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}



?>  