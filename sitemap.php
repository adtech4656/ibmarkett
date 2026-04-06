<?php
header('Content-Type: application/xml');
require_once 'includes/db.php';
$db = new Database(); $pdo = $db->getConnection();
$urls = [];
$urls[] = ['loc' => SITE_URL, 'priority' => '1.0'];
$urls[] = ['loc' => SITE_URL . 'products.php', 'priority' => '0.9'];
$stmt = $pdo->query("SELECT id, updated_at FROM cars");
while ($row = $stmt->fetch()) {
    $urls[] = ['loc' => SITE_URL . 'car-details.php?id=' . $row['id'], 'lastmod' => $row['updated_at'], 'priority' => '0.7'];
}
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
foreach ($urls as $url) {
    echo '<url>';
    echo '<loc>' . htmlspecialchars($url['loc']) . '</loc>';
    if (isset($url['lastmod'])) echo '<lastmod>' . date('Y-m-d', strtotime($url['lastmod'])) . '</lastmod>';
    echo '<priority>' . ($url['priority'] ?? '0.5') . '</priority>';
    echo '</url>';
}
echo '</urlset>';