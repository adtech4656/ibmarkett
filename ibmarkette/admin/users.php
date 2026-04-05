<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';
$db = new Database(); $pdo = $db->getConnection();
$users = $pdo->query("SELECT * FROM users ORDER BY id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<div class="sidebar">
    <h2>IBMARKETTE</h2>
    <div class="nav">
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="cars.php"><i class="fas fa-car"></i> Cars</a>
        <a href="featured.php"><i class="fas fa-star"></i> Featured Cars</a>
        <a href="users.php" class="active"><i class="fas fa-users"></i> Users</a>
        <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>
<div class="main">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h1><i class="fas fa-users"></i> Manage Users</h1>
        <a href="add-user.php" class="btn-primary"><i class="fas fa-plus"></i> Add User</a>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo $user['role']; ?></td>
                <td><?php echo $user['created_at']; ?></td>
                <td>
                    <a href="edit-user.php?id=<?php echo $user['id']; ?>"><i class="fas fa-edit"></i> Edit</a>
                    <?php if ($user['id'] != $_SESSION['admin_id']): ?>
                        | <a href="delete-user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Delete this user?')"><i class="fas fa-trash"></i> Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>