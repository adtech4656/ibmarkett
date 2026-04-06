<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';
$db = new Database(); $pdo = $db->getConnection();
$cars = $pdo->query("SELECT * FROM cars ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Cars - IBMARKETTE Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<div class="sidebar">
    <h2>IBMARKETTE</h2>
    <div class="nav">
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="cars.php" class="active"><i class="fas fa-car"></i> Cars</a>
        <a href="featured.php"><i class="fas fa-star"></i> Featured Cars</a>
        <a href="users.php"><i class="fas fa-users"></i> Users</a>
        <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>
<div class="main">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h1><i class="fas fa-car"></i> Manage Cars</h1>
        <a href="add-car.php" class="btn-primary"><i class="fas fa-plus"></i> Add New Car</a>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Year</th>
                <th>Price (USD)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cars as $car): ?>
            <tr>
                <td><?php echo $car['id']; ?></td>
                <td>
                    <?php if ($car['image']): ?>
                        <img src="../uploads/<?php echo htmlspecialchars($car['image']); ?>" width="50" style="border-radius: 4px;">
                    <?php else: ?>—<?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($car['brand']); ?></td>
                <td><?php echo htmlspecialchars($car['model']); ?></td>
                <td><?php echo $car['year']; ?></td>
                <td>$<?php echo number_format($car['price'], 2); ?></td>
                <td>
                    <a href="edit-car.php?id=<?php echo $car['id']; ?>"><i class="fas fa-edit"></i> Edit</a> |
                    <a href="delete-car.php?id=<?php echo $car['id']; ?>" onclick="return confirm('Delete this car?')"><i class="fas fa-trash"></i> Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>