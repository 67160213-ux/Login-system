<?php
require_once 'config_mysqli.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$pass = $_POST['password'] ?? '';

if ($email === '' || $pass === '') {
    $_SESSION['flash'] = 'กรุณากรอก Email และ Password';
    header('Location: login.php');
    exit;
}

$stmt = $mysqli->prepare('SELECT id, email, password_hash, display_name FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($pass, $user['password_hash'])) {
    $_SESSION['flash'] = 'Email หรือรหัสผ่านไม่ถูกต้อง';
    header('Location: login.php');
    exit;
}

$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['user_name'] = $user['display_name'];

$mysqli->query("UPDATE users SET last_login = NOW() WHERE id = {$_SESSION['user_id']}");

header('Location: dashboard.php');
exit;
?>

