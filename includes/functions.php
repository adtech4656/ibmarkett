<?php
require_once 'db.php';

function getSetting($key) {
    $db = new Database();
    $pdo = $db->getConnection();
    $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['setting_value'] : '';
}

function getSettingJson($key, $default = []) {
    $value = getSetting($key);
    if (empty($value)) return $default;
    $decoded = json_decode($value, true);
    return is_array($decoded) ? $decoded : $default;
}

function updateSetting($key, $value) {
    $db = new Database();
    $pdo = $db->getConnection();
    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    return $stmt->execute([$key, $value]);
}

function uploadFile($file, $targetDir, $allowedTypes = ['jpg','jpeg','png','gif','webp'], $maxSize = 5000000) {
    if ($file['error'] !== UPLOAD_ERR_OK) return false;
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedTypes)) return false;
    if ($file['size'] > $maxSize) return false;
    $filename = uniqid() . '.' . $ext;
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    if (move_uploaded_file($file['tmp_name'], $targetDir . $filename)) {
        return $filename;
    }
    return false;
}

function getCars($search = '', $category = '') {
    $db = new Database();
    $pdo = $db->getConnection();
    $sql = "SELECT * FROM cars WHERE 1=1";
    $params = [];
    if (!empty($search)) {
        $sql .= " AND (title_en LIKE ? OR title_fr LIKE ? OR brand LIKE ? OR model LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm; $params[] = $searchTerm; $params[] = $searchTerm; $params[] = $searchTerm;
    }
    if (!empty($category) && $category != 'all') {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    $sql .= " ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllBrands() {
    $db = new Database();
    $pdo = $db->getConnection();
    $stmt = $pdo->query("SELECT DISTINCT brand FROM cars ORDER BY brand");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
function uploadHeroImage($file, $old = null) {
    $targetDir = UPLOAD_DIR . 'hero/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
    if ($file['error'] !== UPLOAD_ERR_OK) return $old;
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png','gif','webp'])) return $old;
    $filename = uniqid() . '.' . $ext;
    if (move_uploaded_file($file['tmp_name'], $targetDir . $filename)) {
        if ($old && file_exists(UPLOAD_DIR . $old)) unlink(UPLOAD_DIR . $old);
        return '/uploads/hero/' . $filename;
    }
    return $old;
}
?>