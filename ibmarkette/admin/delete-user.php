<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';
$id = (int)$_GET['id'];
if ($id != $_SESSION['admin_id']) {
    $db = new Database(); $pdo = $db->getConnection();
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
}
header('Location: users.php'); exit;