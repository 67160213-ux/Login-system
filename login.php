<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config_mysqli.php';

function e($str) { return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }

$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

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

    $_SESSION['user_id']   = (int)$user['id'];
    $_SESSION['user_name'] = $user['display_name'];
    $mysqli->query("UPDATE users SET last_login = NOW() WHERE id = {$_SESSION['user_id']}");
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>เข้าสู่ระบบ</title>
<style>
body { font-family: sans-serif; background: #f2f2f2; display: flex; justify-content: center; align-items: center; height: 100vh; }
form { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 300px; }
input { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 5px; }
button { width: 100%; padding: 10px; background: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer; }
button:hover { background: #0056b3; }
.flash { background: #ffecec; color: #a40000; padding: 10px; margin-bottom: 10px; border-radius: 5px; font-size: 14px; }
</style>
</head>
<body>
<form method="POST" action="login.php">
<h2>เข้าสู่ระบบ</h2>
<?php if ($flash): ?><div class="flash"><?= e($flash) ?></div><?php endif; ?>
<label for="email">อีเมล:</label>
<input type="email" id="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required>
<label for="password">รหัสผ่าน:</label>
<input type="password" id="password" name="password" required>
<button type="submit">เข้าสู่ระบบ</button>
<p style="margin-top:10px; font-size:12px; text-align:center;">ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
</form>
</body>
</html>






