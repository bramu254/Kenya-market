<?php
include 'db.php';
$email = 'noel@gmail.com';
$conn->query("UPDATE users SET role = 'admin' WHERE email = '$email'");
echo "Updated $email to admin.";
?>
