<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';
$id = (int)$_GET['id'];
$db = new Database(); $pdo = $db->getConnection();
$stmt = $pdo->prepare("SELECT image FROM cars WHERE id = ?");
$stmt->execute([$id]);
$img = $stmt->fetchColumn();
if ($img && file_exists(UPLOAD_DIR . $img)) unlink(UPLOAD_DIR . $img);
$pdo->prepare("DELETE FROM cars WHERE id = ?")->execute([$id]);
header('Location: cars.php'); exit;