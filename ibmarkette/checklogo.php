<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
$logo = getSetting('logo_path');
echo "Logo path from DB: " . $logo . "<br>";
echo "UPLOAD_DIR: " . UPLOAD_DIR . "<br>";
echo "UPLOAD_URL: " . UPLOAD_URL . "<br>";
echo "Full file path: " . UPLOAD_DIR . $logo . "<br>";
if ($logo && file_exists(UPLOAD_DIR . $logo)) {
    echo "File exists on server.<br>";
    echo '<img src="' . UPLOAD_URL . $logo . '" style="max-width:200px;">';
} else {
    echo "File does NOT exist in " . UPLOAD_DIR;
}
?>