<?php unset($_SESSION['cart']); ?><?php
session_start();
unset($_SESSION['cart']);
header("Location: cart.php");
exit();
?>