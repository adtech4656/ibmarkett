<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $db = new Database(); $pdo = $db->getConnection();
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);
    header('Location: users.php'); exit;
}
?>
<!DOCTYPE html>
<html><head><title>Add User</title><link rel="stylesheet" href="assets/css/admin.css"></head>
<body><div class="sidebar"><h2>IBMARKETTE</h2><div class="nav"><a href="dashboard.php">Dashboard</a><a href="cars.php">Cars</a><a href="featured.php">Featured Cars</a><a href="users.php">Users</a><a href="settings.php">Settings</a><a href="logout.php">Logout</a></div></div>
<div class="main"><h1>Add User</h1><form method="POST"><div class="form-group"><label>Username</label><input type="text" name="username" required></div><div class="form-group"><label>Password</label><input type="password" name="password" required></div><button type="submit" class="btn-primary">Add</button><a href="users.php" class="btn-secondary">Cancel</a></form></div></body></html>