<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';
require_once '../includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand']; $model = $_POST['model']; $year = (int)$_POST['year']; $price = (float)$_POST['price'];
    $mileage = (int)$_POST['mileage']; $fuel_type = $_POST['fuel_type']; $transmission = $_POST['transmission'];
    $category = $_POST['category']; $description_en = $_POST['description_en']; $description_fr = $_POST['description_fr'];
    $title_en = $_POST['title_en']; $title_fr = $_POST['title_fr'];
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploaded = uploadFile($_FILES['image'], UPLOAD_DIR);
        if ($uploaded) $image = $uploaded;
    }
    $db = new Database(); $pdo = $db->getConnection();
    $stmt = $pdo->prepare("INSERT INTO cars (brand, model, year, price, mileage, fuel_type, transmission, category, image, description_en, description_fr, title_en, title_fr) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([$brand, $model, $year, $price, $mileage, $fuel_type, $transmission, $category, $image, $description_en, $description_fr, $title_en, $title_fr]);
    header('Location: cars.php'); exit;
}
?>
<!DOCTYPE html>
<html><head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"><title>Add Car</title><link rel="stylesheet" href="assets/css/admin.css"></head>
<body><div class="sidebar"><h2>IBMARKETTE</h2><div class="nav"><a href="dashboard.php">Dashboard</a><a href="cars.php">Cars</a><a href="featured.php">Featured Cars</a><a href="users.php">Users</a><a href="settings.php">Settings</a><a href="logout.php">Logout</a></div></div>
<div class="main"><h1>Add Car</h1><form method="POST" enctype="multipart/form-data">
<div class="form-group"><label>Brand</label><input type="text" name="brand" required></div>
<div class="form-group"><label>Model</label><input type="text" name="model" required></div>
<div class="form-group"><label>Year</label><input type="number" name="year" required></div>
<div class="form-group"><label>Price (USD)</label><input type="number" step="0.01" name="price" required></div>
<div class="form-group"><label>Mileage (km)</label><input type="number" name="mileage" required></div>
<div class="form-group"><label>Fuel Type</label><select name="fuel_type"><option>Petrol</option><option>Diesel</option><option>Electric</option><option>Hybrid</option></select></div>
<div class="form-group"><label>Transmission</label><select name="transmission"><option>Manual</option><option>Automatic</option></select></div>
<div class="form-group"><label>Category</label><select name="category"><option>SUV</option><option>Sedan</option><option>Sports</option><option>Luxury</option><option>Trucks</option></select></div>
<div class="form-group"><label>Image</label><input type="file" name="image" accept="image/*"></div>
<div class="form-group"><label>Description (EN)</label><textarea name="description_en" rows="4"></textarea></div>
<div class="form-group"><label>Description (FR)</label><textarea name="description_fr" rows="4"></textarea></div>
<div class="form-group"><label>Title (EN)</label><input type="text" name="title_en"></div>
<div class="form-group"><label>Title (FR)</label><input type="text" name="title_fr"></div>
<button type="submit" class="btn-primary">Save</button><a href="cars.php" class="btn-secondary">Cancel</a>
</form>
</div>
</body>
</html>