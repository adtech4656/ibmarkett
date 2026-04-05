<?php http_response_code(404); require_once 'includes/header.php'; ?>
<main style="padding-top:120px; text-align:center;"><h1>404</h1><p><?php echo t('page_not_found_text'); ?></p><a href="index.php" class="btn-primary-hero"><?php echo t('back_home'); ?></a></main>
<?php include 'includes/footer.php'; ?>