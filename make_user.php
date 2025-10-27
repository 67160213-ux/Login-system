<?php
require 'config_mysqli.php';

$email = 'test@gmail.com';
$name  = 'Test User';
$plain = '12345678';

$hash = password_hash($plain, PASSWORD_DEFAULT);

$stmt = $mysqli->prepare('INSERT INTO users (email, display_name, password_hash) VALUES (?, ?, ?)');
$stmt->bind_param('sss', $email, $name, $hash);
$stmt->execute();
echo "Created test user: $email / $plain";
?>
