<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';
require_once '../includes/functions.php';

$db = new Database();
$pdo = $db->getConnection();

// Upload helper
function uploadFeaturedImage($file, $old = null) {
    $targetDir = UPLOAD_DIR . 'featured/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    if ($file['error'] !== UPLOAD_ERR_OK) return $old;
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png','gif','webp'])) return $old;
    $filename = uniqid() . '.' . $ext;
    if (move_uploaded_file($file['tmp_name'], $targetDir . $filename)) {
        if ($old && file_exists(UPLOAD_DIR . $old)) unlink(UPLOAD_DIR . $old);
        return '/uploads/featured/' . $filename;
    }
    return $old;
}

// Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("SELECT image_url FROM featured_cars WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetchColumn();
    if ($img && file_exists(UPLOAD_DIR . $img)) unlink(UPLOAD_DIR . $img);
    $pdo->prepare("DELETE FROM featured_cars WHERE id = ?")->execute([$id]);
    header('Location: featured.php?msg=deleted');
    exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM featured_cars WHERE id = ?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title_en = $_POST['title_en'];
    $title_fr = $_POST['title_fr'];
    $description_en = $_POST['description_en'];
    $description_fr = $_POST['description_fr'];
    $price_cfa = (int)$_POST['price_cfa'];
    $year = (int)$_POST['year'];
    $mileage = (int)$_POST['mileage'];
    $hp = (int)$_POST['hp'];
    $fuel_type = $_POST['fuel_type'];
    $transmission = $_POST['transmission'];
    $category = $_POST['category'];
    $sort_order = (int)$_POST['sort_order'];
    $is_large_card = isset($_POST['is_large_card']) ? 1 : 0;
    $active = isset($_POST['active']) ? 1 : 0;

    $image_url = $edit ? $edit['image_url'] : '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploaded = uploadFeaturedImage($_FILES['image'], $image_url);
        if ($uploaded) $image_url = $uploaded;
    }
    if (!empty($_POST['image_url_external']) && empty($_FILES['image']['name'])) {
        $image_url = $_POST['image_url_external'];
    }

    if (!empty($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE featured_cars SET title_en=?, title_fr=?, description_en=?, description_fr=?, image_url=?, price_cfa=?, year=?, mileage=?, hp=?, fuel_type=?, transmission=?, category=?, sort_order=?, is_large_card=?, active=? WHERE id=?");
        $stmt->execute([$title_en, $title_fr, $description_en, $description_fr, $image_url, $price_cfa, $year, $mileage, $hp, $fuel_type, $transmission, $category, $sort_order, $is_large_card, $active, $_POST['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO featured_cars (title_en, title_fr, description_en, description_fr, image_url, price_cfa, year, mileage, hp, fuel_type, transmission, category, sort_order, is_large_card, active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$title_en, $title_fr, $description_en, $description_fr, $image_url, $price_cfa, $year, $mileage, $hp, $fuel_type, $transmission, $category, $sort_order, $is_large_card, $active]);
    }
    header('Location: featured.php?msg=saved');
    exit;
}

$featured = $pdo->query("SELECT * FROM featured_cars ORDER BY sort_order ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Featured Cars - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        .featured-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px,1fr)); gap: 1.5rem; margin-top: 1.5rem; }
        .featured-card { background: #1e1e1e; border: 1px solid #333; border-radius: 12px; padding: 1rem; transition: transform 0.2s; }
        .featured-card:hover { transform: translateY(-4px); background: #2a2a2a; }
        .featured-card img { width: 100%; height: 160px; object-fit: cover; border-radius: 8px; margin-bottom: 1rem; }
        .featured-card h3 { color: #C9A84C; margin-bottom: 0.5rem; }
        .featured-card .price { font-size: 1.2rem; font-weight: bold; margin: 0.5rem 0; }
        .featured-card .badge { display: inline-block; background: #C9A84C; color: #000; padding: 2px 8px; border-radius: 20px; font-size: 0.7rem; margin: 0.5rem 0; }
        .form-row { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
        .form-group { flex: 1; min-width: 200px; }
        .checkbox-group { display: flex; align-items: center; gap: 0.5rem; }
        .checkbox-group label { margin: 0; }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>IBMARKETTE</h2>
    <div class="nav">
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="cars.php"><i class="fas fa-car"></i> Cars</a>
        <a href="featured.php" class="active"><i class="fas fa-star"></i> Featured Cars</a>
        <a href="users.php"><i class="fas fa-users"></i> Users</a>
        <a href="settings.php"><i class="fas fa-cog"></i> Settings</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>
<div class="main">
    <h1><i class="fas fa-star"></i> Featured Cars</h1>
    <?php if (isset($_GET['msg'])): ?>
        <div class="message"><?php echo $_GET['msg'] == 'saved' ? 'Featured car saved successfully.' : 'Featured car deleted.'; ?></div>
    <?php endif; ?>

    <!-- Add/Edit Form -->
    <div style="background: #1e1e1e; border-radius: 16px; padding: 1.5rem; margin-bottom: 2rem;">
        <h2><?php echo $edit ? 'Edit Featured Car' : 'Add New Featured Car'; ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <?php if ($edit): ?><input type="hidden" name="id" value="<?php echo $edit['id']; ?>"><?php endif; ?>
            <div class="form-row">
                <div class="form-group"><label>Title (EN) *</label><input type="text" name="title_en" value="<?php echo $edit ? htmlspecialchars($edit['title_en']) : ''; ?>" required></div>
                <div class="form-group"><label>Title (FR) *</label><input type="text" name="title_fr" value="<?php echo $edit ? htmlspecialchars($edit['title_fr']) : ''; ?>" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Description (EN)</label><textarea name="description_en" rows="3"><?php echo $edit ? htmlspecialchars($edit['description_en']) : ''; ?></textarea></div>
                <div class="form-group"><label>Description (FR)</label><textarea name="description_fr" rows="3"><?php echo $edit ? htmlspecialchars($edit['description_fr']) : ''; ?></textarea></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Upload Image</label><input type="file" name="image" accept="image/*"><?php if($edit && $edit['image_url']): ?><br><img src="<?php echo $edit['image_url']; ?>" width="80" style="margin-top:5px;"><?php endif; ?></div>
                <div class="form-group"><label>OR External Image URL</label><input type="url" name="image_url_external" value="<?php echo $edit && strpos($edit['image_url'],'/uploads/')!==0 ? htmlspecialchars($edit['image_url']) : ''; ?>" placeholder="https://..."></div>
                <div class="form-group"><label>Price (F CFA) *</label><input type="number" name="price_cfa" value="<?php echo $edit ? $edit['price_cfa'] : ''; ?>" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Year *</label><input type="number" name="year" value="<?php echo $edit ? $edit['year'] : ''; ?>" required></div>
                <div class="form-group"><label>Mileage (km) *</label><input type="number" name="mileage" value="<?php echo $edit ? $edit['mileage'] : ''; ?>" required></div>
                <div class="form-group"><label>Horsepower (HP) *</label><input type="number" name="hp" value="<?php echo $edit ? $edit['hp'] : ''; ?>" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Fuel Type</label><select name="fuel_type">
                    <option value="Petrol" <?php echo $edit && $edit['fuel_type']=='Petrol'?'selected':''; ?>>Petrol</option>
                    <option value="Diesel" <?php echo $edit && $edit['fuel_type']=='Diesel'?'selected':''; ?>>Diesel</option>
                    <option value="Electric" <?php echo $edit && $edit['fuel_type']=='Electric'?'selected':''; ?>>Electric</option>
                    <option value="Hybrid" <?php echo $edit && $edit['fuel_type']=='Hybrid'?'selected':''; ?>>Hybrid</option>
                </select></div>
                <div class="form-group"><label>Transmission</label><select name="transmission">
                    <option value="Manual" <?php echo $edit && $edit['transmission']=='Manual'?'selected':''; ?>>Manual</option>
                    <option value="Automatic" <?php echo $edit && $edit['transmission']=='Automatic'?'selected':''; ?>>Automatic</option>
                </select></div>
                <div class="form-group"><label>Category</label><select name="category">
                    <option value="SUV" <?php echo $edit && $edit['category']=='SUV'?'selected':''; ?>>SUV</option>
                    <option value="Sedan" <?php echo $edit && $edit['category']=='Sedan'?'selected':''; ?>>Sedan</option>
                    <option value="Sports" <?php echo $edit && $edit['category']=='Sports'?'selected':''; ?>>Sports</option>
                    <option value="Luxury" <?php echo $edit && $edit['category']=='Luxury'?'selected':''; ?>>Luxury</option>
                    <option value="Trucks" <?php echo $edit && $edit['category']=='Trucks'?'selected':''; ?>>Trucks</option>
                </select></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Sort Order (lower = higher)</label><input type="number" name="sort_order" value="<?php echo $edit ? $edit['sort_order'] : '0'; ?>"></div>
                <div class="form-group checkbox-group"><input type="checkbox" name="is_large_card" value="1" id="largeCard" <?php echo $edit && $edit['is_large_card'] ? 'checked' : ''; ?>><label for="largeCard">Display as Large Card</label><small style="margin-left:0.5rem;">(Only one should be large)</small></div>
                <div class="form-group checkbox-group"><input type="checkbox" name="active" value="1" id="active" <?php echo $edit && $edit['active'] ? 'checked' : ''; ?> <?php echo !$edit ? 'checked' : ''; ?>><label for="active">Active</label></div>
            </div>
            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save</button>
                <a href="featured.php" class="btn-secondary"><i class="fas fa-times"></i> Cancel</a>
            </div>
        </form>
    </div>

    <!-- Existing Featured Cars -->
    <h2>Existing Featured Cars</h2>
    <div class="featured-grid">
        <?php foreach ($featured as $f): ?>
        <div class="featured-card">
            <img src="<?php echo htmlspecialchars($f['image_url']); ?>" alt="<?php echo htmlspecialchars($f['title_en']); ?>">
            <h3><?php echo htmlspecialchars($f['title_en']); ?></h3>
            <div class="price">F CFA <?php echo number_format($f['price_cfa'], 0, ',', ' '); ?></div>
            <div class="badge"><?php echo $f['is_large_card'] ? 'Large Card' : 'Stack Card'; ?></div>
            <div style="margin-top: 1rem;">
                <a href="?edit=<?php echo $f['id']; ?>"><i class="fas fa-edit"></i> Edit</a> |
                <a href="?delete=<?php echo $f['id']; ?>" onclick="return confirm('Delete this featured car?')"><i class="fas fa-trash"></i> Delete</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>