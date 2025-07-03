<?php
$plainPassword = 'admin';
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
echo $hashedPassword;
?>
