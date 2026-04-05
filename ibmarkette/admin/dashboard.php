<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';
$db = new Database(); $pdo = $db->getConnection();
$total_cars = $pdo->query("SELECT COUNT(*) FROM cars")->fetchColumn();
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_featured = $pdo->query("SELECT COUNT(*) FROM featured_cars")->fetchColumn();
?>
<!DOCTYPE html>
<html><head><title>Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<div class="sidebar">
    <h2>IBMARKETTE</h2>
    <div class="nav">
        <a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="cars.php"><i class="fas fa-car"></i> Cars</a>
        <a href="featured.php"><i class="fas fa-star"></i> Featured Cars</a>
        <a href="users.php"><i class="fas fa-users"></i> Users</a>
        <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>
<div class="main">
    <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
    <div class="dashboard-stats">
        <div class="stat-card"><i class="fas fa-car"></i><div class="stat-number"><?php echo $total_cars; ?></div><div class="stat-label">Total Cars</div></div>
        <div class="stat-card"><i class="fas fa-star"></i><div class="stat-number"><?php echo $total_featured; ?></div><div class="stat-label">Featured Cars</div></div>
        <div class="stat-card"><i class="fas fa-users"></i><div class="stat-number"><?php echo $total_users; ?></div><div class="stat-label">Total Users</div></div>
    </div>
    <p>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
</div>
</body></html>