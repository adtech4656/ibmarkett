<?php
require_once 'includes/db.php';
$db = new Database();
$pdo = $db->getConnection();
$new_password = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
if ($stmt->execute([$new_password])) {
    echo "Password updated. New hash: " . $new_password;
} else {
    echo "Update failed.";
}
?>