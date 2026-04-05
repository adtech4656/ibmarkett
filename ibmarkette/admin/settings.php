<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Define updateSettingJson function
function updateSettingJson($key, $value) {
    updateSetting($key, json_encode($value));
}

// Set admin default language to French
if (!isset($_SESSION['admin_lang'])) $_SESSION['admin_lang'] = 'fr';
$lang = $_SESSION['admin_lang'];

$db = new Database();
$pdo = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'setting_') === 0) {
            updateSetting(substr($key, 8), trim($value));
        }
    }
    
    // Hero slides with image upload
    $slides = [];
    $count = count($_POST['hero_slides_title_en']);
    for ($i = 0; $i < $count; $i++) {
        $image_url = $_POST['hero_slides_image_hidden'][$i] ?? '';
        if (isset($_FILES['hero_slides_image']['tmp_name'][$i]) && $_FILES['hero_slides_image']['error'][$i] === UPLOAD_ERR_OK) {
            $file = [
                'name' => $_FILES['hero_slides_image']['name'][$i],
                'type' => $_FILES['hero_slides_image']['type'][$i],
                'tmp_name' => $_FILES['hero_slides_image']['tmp_name'][$i],
                'error' => $_FILES['hero_slides_image']['error'][$i],
                'size' => $_FILES['hero_slides_image']['size'][$i]
            ];
            $uploaded = uploadHeroImage($file, $image_url);
            if ($uploaded) $image_url = $uploaded;
        }
        $slides[] = [
            'image' => $image_url,
            'title_en' => $_POST['hero_slides_title_en'][$i],
            'title_fr' => $_POST['hero_slides_title_fr'][$i],
            'subtitle_en' => $_POST['hero_slides_subtitle_en'][$i],
            'subtitle_fr' => $_POST['hero_slides_subtitle_fr'][$i],
            'btn_text_en' => $_POST['hero_slides_btn_en'][$i],
            'btn_text_fr' => $_POST['hero_slides_btn_fr'][$i],
            'btn_link' => $_POST['hero_slides_btn_link'][$i],
        ];
    }
    updateSettingJson('hero_slides', $slides);
    
    // Social
    foreach (['facebook','instagram','tiktok','youtube','twitter','linkedin'] as $soc) {
        updateSetting('social_'.$soc, $_POST['social_'.$soc] ?? '');
    }
    // Addresses & Phones
    $addresses = array_filter(array_map('trim', $_POST['addresses'] ?? []));
    updateSettingJson('addresses', array_values($addresses));
    $phones = array_filter(array_map('trim', $_POST['phones'] ?? []));
    updateSettingJson('phones', array_values($phones));
    updateSetting('whatsapp_number', trim($_POST['whatsapp_number'] ?? ''));
    
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logo = uploadFile($_FILES['logo'], UPLOAD_DIR);
        if ($logo) updateSetting('logo_path', $logo);
    }
    $success = "Paramètres enregistrés.";
}
if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
    $logo = uploadFile($_FILES['logo'], UPLOAD_DIR);
    if ($logo) updateSetting('logo_path', $logo);
}

$settings = [];
$stmt = $pdo->query("SELECT * FROM settings");
while ($row = $stmt->fetch()) { $settings[$row['setting_key']] = $row['setting_value']; }
$hero_slides = getSettingJson('hero_slides', []);
$addresses = getSettingJson('addresses', ['']);
$phones = getSettingJson('phones', ['']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paramètres du site - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <style>
        .settings-section { background: #1e1e1e; border-radius: 16px; padding: 1.5rem; margin-bottom: 2rem; }
        .settings-section h2 { margin-top: 0; color: #C9A84C; font-size: 1.3rem; margin-bottom: 1rem; border-bottom: 1px solid #333; padding-bottom: 0.5rem; }
        .slide-item { background: #2a2a2a; padding: 1rem; margin-bottom: 1rem; border-radius: 8px; border-left: 3px solid #C9A84C; }
        .slide-item button.remove-slide { float: right; background: #e74c3c; color: white; border: none; padding: 2px 8px; border-radius: 4px; cursor: pointer; }
        .form-row { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
        .form-group { flex: 1; min-width: 200px; }
        .add-btn { background: #C9A84C; color: #000; border: none; padding: 5px 12px; border-radius: 4px; cursor: pointer; margin-top: 0.5rem; }
        .repeatable-item { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; }
        .repeatable-item input { flex: 1; }
        .repeatable-item button { background: #e74c3c; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer; }
        .current-image { margin-top: 5px; }
        .current-image img { max-width: 100px; border-radius: 4px; }
        .lang-switch { text-align: right; margin-bottom: 1rem; }
        .lang-switch a { color: #C9A84C; margin-left: 1rem; text-decoration: none; }
        .lang-switch a.active { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>IBMARKETTE</h2>
    <div class="nav">
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a>
        <a href="cars.php"><i class="fas fa-car"></i> Voitures</a>
        <a href="featured.php"><i class="fas fa-star"></i> Voitures vedettes</a>
        <a href="users.php"><i class="fas fa-users"></i> Utilisateurs</a>
        <a href="settings.php" class="active"><i class="fas fa-cog"></i> Paramètres</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </div>
</div>
<div class="main">
        
    <div class="lang-switch">
        <a href="?lang=fr" class="<?php echo $lang == 'fr' ? 'active' : ''; ?>">Français</a>
        <a href="?lang=en" class="<?php echo $lang == 'en' ? 'active' : ''; ?>">English</a>
    </div>
   
    <h1><i class="fas fa-cog"></i> Paramètres du site</h1>
     <div class="form-group"><label>Logo (upload)</label><input type="file" name="logo" accept="image/*"><?php if(!empty($settings['logo_path'])) echo '<br><img src="../uploads/'.$settings['logo_path'].'" width="80" style="margin-top:5px;">'; ?></div>
    <?php if (isset($success)): ?><div class="message"><?php echo $success; ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">

        <!-- Hero Slider Section -->
        <div class="settings-section">
            <h2><i class="fas fa-images"></i> Slider Hero</h2>
            <div id="heroSlidesContainer">
                <?php foreach ($hero_slides as $idx => $slide): ?>
                <div class="slide-item">
                    <button type="button" class="remove-slide" onclick="this.parentElement.remove()">✖</button>
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="hero_slides_image[]" accept="image/*">
                        <input type="hidden" name="hero_slides_image_hidden[]" value="<?php echo htmlspecialchars($slide['image']); ?>">
                        <?php if ($slide['image']): ?>
                        <div class="current-image"><img src="<?php echo $slide['image']; ?>"><br><small>Image actuelle</small></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-row"><div class="form-group"><label>Titre (EN)</label><input type="text" name="hero_slides_title_en[]" value="<?php echo htmlspecialchars($slide['title_en']); ?>" required></div>
                    <div class="form-group"><label>Titre (FR)</label><input type="text" name="hero_slides_title_fr[]" value="<?php echo htmlspecialchars($slide['title_fr']); ?>" required></div></div>
                    <div class="form-row"><div class="form-group"><label>Sous-titre (EN)</label><textarea name="hero_slides_subtitle_en[]" rows="2"><?php echo htmlspecialchars($slide['subtitle_en']); ?></textarea></div>
                    <div class="form-group"><label>Sous-titre (FR)</label><textarea name="hero_slides_subtitle_fr[]" rows="2"><?php echo htmlspecialchars($slide['subtitle_fr']); ?></textarea></div></div>
                    <div class="form-row"><div class="form-group"><label>Texte bouton (EN)</label><input type="text" name="hero_slides_btn_en[]" value="<?php echo htmlspecialchars($slide['btn_text_en']); ?>"></div>
                    <div class="form-group"><label>Texte bouton (FR)</label><input type="text" name="hero_slides_btn_fr[]" value="<?php echo htmlspecialchars($slide['btn_text_fr']); ?>"></div>
                    <div class="form-group"><label>Lien bouton</label><input type="text" name="hero_slides_btn_link[]" value="<?php echo htmlspecialchars($slide['btn_link']); ?>"></div></div>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="addSlide" class="add-btn"><i class="fas fa-plus"></i> Ajouter un slide</button>
        </div>

        <!-- Social Media -->
        <div class="settings-section">
            <h2><i class="fas fa-share-alt"></i> Réseaux sociaux</h2>
            <?php $socials = ['facebook'=>'Facebook','instagram'=>'Instagram','tiktok'=>'TikTok','youtube'=>'YouTube','twitter'=>'Twitter','linkedin'=>'LinkedIn']; ?>
            <?php foreach ($socials as $key => $label): ?>
            <div class="form-group"><label><?php echo $label; ?></label><input type="url" name="social_<?php echo $key; ?>" value="<?php echo htmlspecialchars($settings['social_'.$key] ?? ''); ?>" placeholder="https://..."></div>
            <?php endforeach; ?>
        </div>

        <!-- Addresses -->
        <div class="settings-section">
            <h2><i class="fas fa-map-marker-alt"></i> Adresses (max 2)</h2>
            <div id="addressesContainer">
                <?php foreach ($addresses as $addr): ?>
                <div class="repeatable-item"><input type="text" name="addresses[]" value="<?php echo htmlspecialchars($addr); ?>" placeholder="Adresse"><button type="button" class="remove-addr">✖</button></div>
                <?php endforeach; ?>
                <?php if (count($addresses) < 2): ?><div class="repeatable-item"><input type="text" name="addresses[]" value="" placeholder="Adresse"><button type="button" class="remove-addr">✖</button></div><?php endif; ?>
            </div>
            <button type="button" id="addAddress" class="add-btn" <?php echo count($addresses) >= 2 ? 'disabled' : ''; ?>><i class="fas fa-plus"></i> Ajouter une adresse</button>
        </div>

        <!-- Phone Numbers -->
        <div class="settings-section">
            <h2><i class="fas fa-phone"></i> Numéros de téléphone (max 3)</h2>
            <div id="phonesContainer">
                <?php foreach ($phones as $phone): ?>
                <div class="repeatable-item"><input type="tel" name="phones[]" value="<?php echo htmlspecialchars($phone); ?>" placeholder="Numéro"><button type="button" class="remove-phone">✖</button></div>
                <?php endforeach; ?>
                <?php for ($i = count($phones); $i < 3; $i++): ?>
                <div class="repeatable-item"><input type="tel" name="phones[]" value="" placeholder="Numéro"><button type="button" class="remove-phone">✖</button></div>
                <?php endfor; ?>
            </div>
            <button type="button" id="addPhone" class="add-btn" <?php echo count($phones) >= 3 ? 'disabled' : ''; ?>><i class="fas fa-plus"></i> Ajouter un numéro</button>
        </div>

        <!-- WhatsApp -->
        <div class="settings-section">
            <h2><i class="fab fa-whatsapp"></i> Numéro WhatsApp</h2>
            <div class="form-group"><input type="tel" name="whatsapp_number" value="<?php echo htmlspecialchars($settings['whatsapp_number'] ?? ''); ?>" placeholder="ex: 2250789433290"></div>
        </div>

        <!-- General Settings -->
        <div class="settings-section">
            <h2><i class="fas fa-cog"></i> Paramètres généraux</h2>
            <div class="form-group"><label>Titre du site</label><input type="text" name="setting_site_title" value="<?php echo htmlspecialchars($settings['site_title'] ?? ''); ?>"></div>
            <div class="form-group"><label>Logo (upload)</label><input type="file" name="logo" accept="image/*"><?php if(!empty($settings['logo_path'])) echo '<br><img src="../uploads/'.$settings['logo_path'].'" width="80" style="margin-top:5px;">'; ?></div>
            <div class="form-group"><label>Email de contact</label><input type="email" name="setting_contact_email" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>"></div>
            <div class="form-group"><label>Texte du pied de page (EN)</label><textarea name="setting_footer_text_en" rows="3"><?php echo htmlspecialchars($settings['footer_text_en'] ?? ''); ?></textarea></div>
            <div class="form-group"><label>Texte du pied de page (FR)</label><textarea name="setting_footer_text_fr" rows="3"><?php echo htmlspecialchars($settings['footer_text_fr'] ?? ''); ?></textarea></div>
        </div>

        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Enregistrer tous les paramètres</button>
    </form>
</div>

<script>
// Add slide
document.getElementById('addSlide')?.addEventListener('click', function() {
    const container = document.getElementById('heroSlidesContainer');
    const div = document.createElement('div'); div.className = 'slide-item';
    div.innerHTML = `<button type="button" class="remove-slide" onclick="this.parentElement.remove()">✖</button>
        <div class="form-group"><label>Image</label><input type="file" name="hero_slides_image[]" accept="image/*"><input type="hidden" name="hero_slides_image_hidden[]" value=""></div>
        <div class="form-row"><div class="form-group"><label>Titre (EN)</label><input type="text" name="hero_slides_title_en[]" required></div>
        <div class="form-group"><label>Titre (FR)</label><input type="text" name="hero_slides_title_fr[]" required></div></div>
        <div class="form-row"><div class="form-group"><label>Sous-titre (EN)</label><textarea name="hero_slides_subtitle_en[]" rows="2"></textarea></div>
        <div class="form-group"><label>Sous-titre (FR)</label><textarea name="hero_slides_subtitle_fr[]" rows="2"></textarea></div></div>
        <div class="form-row"><div class="form-group"><label>Texte bouton (EN)</label><input type="text" name="hero_slides_btn_en[]"></div>
        <div class="form-group"><label>Texte bouton (FR)</label><input type="text" name="hero_slides_btn_fr[]"></div>
        <div class="form-group"><label>Lien bouton</label><input type="text" name="hero_slides_btn_link[]"></div></div>`;
    container.appendChild(div);
});

// Add address
const addrContainer = document.getElementById('addressesContainer');
document.getElementById('addAddress')?.addEventListener('click', function() {
    if (addrContainer.children.length >= 2) { this.disabled = true; return; }
    const div = document.createElement('div'); div.className = 'repeatable-item';
    div.innerHTML = '<input type="text" name="addresses[]" placeholder="Adresse"><button type="button" class="remove-addr" onclick="this.parentElement.remove()">✖</button>';
    addrContainer.appendChild(div);
});

// Add phone
const phoneContainer = document.getElementById('phonesContainer');
document.getElementById('addPhone')?.addEventListener('click', function() {
    if (phoneContainer.children.length >= 3) { this.disabled = true; return; }
    const div = document.createElement('div'); div.className = 'repeatable-item';
    div.innerHTML = '<input type="tel" name="phones[]" placeholder="Numéro"><button type="button" class="remove-phone" onclick="this.parentElement.remove()">✖</button>';
    phoneContainer.appendChild(div);
});
</script>
</body>
</html>