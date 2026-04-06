<?php
session_start();
if (!isset($_SESSION['admin_lang'])) $_SESSION['admin_lang'] = 'fr';
$lang = $_SESSION['admin_lang'];

require_once '../includes/db.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? ''; // Do NOT trim

    $db = new Database();
    $pdo = $db->getConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body { font-family: Arial; background: #0A0A0A; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-box { background: #1e1e1e; padding: 2rem; border-radius: 8px; width: 300px; }
        .login-box input { width: 100%; padding: 8px; margin: 8px 0; background: #333; border: none; color: #fff; }
        .login-box button { background: #C9A84C; color: #000; padding: 10px; border: none; width: 100%; cursor: pointer; }
        .error { color: red; }
    </style>
</head>
<body>
<div class="login-box">
    <h2>Admin Login</h2>
    <?php if ($error): ?><p class="error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required autofocus>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>