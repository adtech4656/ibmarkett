<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';
require_once '../includes/functions.php';
$db = new Database(); $pdo = $db->getConnection();
$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$id]);
$car = $stmt->fetch();
if (!$car) { header('Location: cars.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand']; $model = $_POST['model']; $year = (int)$_POST['year']; $price = (float)$_POST['price'];
    $mileage = (int)$_POST['mileage']; $fuel_type = $_POST['fuel_type']; $transmission = $_POST['transmission'];
    $category = $_POST['category']; $description_en = $_POST['description_en']; $description_fr = $_POST['description_fr'];
    $title_en = $_POST['title_en']; $title_fr = $_POST['title_fr'];
    $image = $car['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploaded = uploadFile($_FILES['image'], UPLOAD_DIR);
        if ($uploaded) {
            if ($image && file_exists(UPLOAD_DIR . $image)) unlink(UPLOAD_DIR . $image);
            $image = $uploaded;
        }
    }
    $stmt = $pdo->prepare("UPDATE cars SET brand=?, model=?, year=?, price=?, mileage=?, fuel_type=?, transmission=?, category=?, image=?, description_en=?, description_fr=?, title_en=?, title_fr=? WHERE id=?");
    $stmt->execute([$brand, $model, $year, $price, $mileage, $fuel_type, $transmission, $category, $image, $description_en, $description_fr, $title_en, $title_fr, $id]);
    header('Location: cars.php'); exit;
}
?>
<!DOCTYPE html>
<html><head><title>Edit Car</title><link rel="stylesheet" href="assets/css/admin.css"></head>
<body><div class="sidebar"><h2>IBMARKETTE</h2><div class="nav"><a href="dashboard.php">Dashboard</a><a href="cars.php">Cars</a><a href="featured.php">Featured Cars</a><a href="users.php">Users</a><a href="settings.php">Settings</a><a href="logout.php">Logout</a></div></div>
<div class="main"><h1>Edit Car</h1><form method="POST" enctype="multipart/form-data">
<div class="form-group"><label>Brand</label><input type="text" name="brand" value="<?php echo htmlspecialchars($car['brand']); ?>" required></div>
<div class="form-group"><label>Model</label><input type="text" name="model" value="<?php echo htmlspecialchars($car['model']); ?>" required></div>
<div class="form-group"><label>Year</label><input type="number" name="year" value="<?php echo $car['year']; ?>" required></div>
<div class="form-group"><label>Price (USD)</label><input type="number" step="0.01" name="price" value="<?php echo $car['price']; ?>" required></div>
<div class="form-group"><label>Mileage (km)</label><input type="number" name="mileage" value="<?php echo $car['mileage']; ?>" required></div>
<div class="form-group"><label>Fuel Type</label><select name="fuel_type"><option <?php echo $car['fuel_type']=='Petrol'?'selected':''; ?>>Petrol</option><option <?php echo $car['fuel_type']=='Diesel'?'selected':''; ?>>Diesel</option><option <?php echo $car['fuel_type']=='Electric'?'selected':''; ?>>Electric</option><option <?php echo $car['fuel_type']=='Hybrid'?'selected':''; ?>>Hybrid</option></select></div>
<div class="form-group"><label>Transmission</label><select name="transmission"><option <?php echo $car['transmission']=='Manual'?'selected':''; ?>>Manual</option><option <?php echo $car['transmission']=='Automatic'?'selected':''; ?>>Automatic</option></select></div>
<div class="form-group"><label>Category</label><select name="category"><option <?php echo $car['category']=='SUV'?'selected':''; ?>>SUV</option><option <?php echo $car['category']=='Sedan'?'selected':''; ?>>Sedan</option><option <?php echo $car['category']=='Sports'?'selected':''; ?>>Sports</option><option <?php echo $car['category']=='Luxury'?'selected':''; ?>>Luxury</option><option <?php echo $car['category']=='Trucks'?'selected':''; ?>>Trucks</option></select></div>
<div class="form-group"><label>Image</label><input type="file" name="image" accept="image/*"><?php if($car['image']): ?><br><img src="../uploads/<?php echo $car['image']; ?>" width="100"><?php endif; ?></div>
<div class="form-group"><label>Description (EN)</label><textarea name="description_en" rows="4"><?php echo htmlspecialchars($car['description_en']); ?></textarea></div>
<div class="form-group"><label>Description (FR)</label><textarea name="description_fr" rows="4"><?php echo htmlspecialchars($car['description_fr']); ?></textarea></div>
<div class="form-group"><label>Title (EN)</label><input type="text" name="title_en" value="<?php echo htmlspecialchars($car['title_en']); ?>"></div>
<div class="form-group"><label>Title (FR)</label><input type="text" name="title_fr" value="<?php echo htmlspecialchars($car['title_fr']); ?>"></div>
<button type="submit" class="btn-primary">Save</button><a href="cars.php" class="btn-secondary">Cancel</a>
</form></div></body></html>