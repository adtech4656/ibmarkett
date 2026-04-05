<?php
define('DB_HOST', 'sql123.infinityfree.com');   // Get the exact host from your control panel
define('DB_NAME', 'if0_41581228_ibmarkette');   // Your database name
define('DB_USER', 'if0_41581228');     // Your database user
define('DB_PASS', '3fym4ler0go');        // The password you set

define('SITE_URL', 'https://ash.infinityfree.com/');   // Your domain with trailing slash
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', SITE_URL . 'uploads/');

// Turn off error display on live server
ini_set('display_errors', 0);
error_reporting(0);
ini_set('log_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>