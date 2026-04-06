<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';
$db = new Database(); $pdo = $db->getConnection();
$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
if (!$user) { header('Location: users.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $new_password = $_POST['password'];
    $update = "UPDATE users SET username = ?";
    $params = [$username];
    if (!empty($new_password)) {
        $update .= ", password = ?";
        $params[] = password_hash($new_password, PASSWORD_DEFAULT);
    }
    $update .= " WHERE id = ?";
    $params[] = $id;
    $stmt = $pdo->prepare($update);
    $stmt->execute($params);
    header('Location: users.php'); exit;
}
?>
<!DOCTYPE html>
<html><head><title>Edit User</title><link rel="stylesheet" href="assets/css/admin.css"></head>
<body><div class="sidebar"><h2>IBMARKETTE</h2><div class="nav"><a href="dashboard.php">Dashboard</a><a href="cars.php">Cars</a><a href="featured.php">Featured Cars</a><a href="users.php">Users</a><a href="settings.php">Settings</a><a href="logout.php">Logout</a></div></div>
<div class="main"><h1>Edit User</h1><form method="POST"><div class="form-group"><label>Username</label><input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required></div><div class="form-group"><label>New Password (leave blank to keep current)</label><input type="password" name="password"></div><button type="submit" class="btn-primary">Save</button><a href="users.php" class="btn-secondary">Cancel</a></form></div></body></html>