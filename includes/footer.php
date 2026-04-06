<footer>
  <div class="footer-inner">
    <div class="footer-top">
      <div class="footer-brand">
        <span class="logo-text"><?php echo getSetting('site_title'); ?></span>
        <p><?php echo $lang == 'en' ? getSetting('footer_text_en') : getSetting('footer_text_fr'); ?></p>
        <div class="social-links">
          <?php
          $socials = ['facebook'=>'fab fa-facebook-f','instagram'=>'fab fa-instagram','tiktok'=>'fab fa-tiktok','youtube'=>'fab fa-youtube','twitter'=>'fab fa-twitter','linkedin'=>'fab fa-linkedin-in'];
          foreach ($socials as $key => $icon):
              $url = getSetting('social_' . $key);
              if ($url): ?>
                <a href="<?php echo $url; ?>" class="social-link" target="_blank"><i class="<?php echo $icon; ?>"></i></a>
          <?php endif; endforeach; ?>
        </div>
      </div>
      <div><div class="footer-heading"><?php echo t('quick_links'); ?></div><ul class="footer-links"><li><a href="index.php"><?php echo t('home'); ?></a></li><li><a href="products.php"><?php echo t('products'); ?></a></li><li><a href="index.php#inventory"><?php echo t('inventory'); ?></a></li><li><a href="index.php#brands"><?php echo t('brands'); ?></a></li><li><a href="index.php#why-us"><?php echo $lang == 'en' ? 'About Us' : 'À Propos'; ?></a></li></ul></div>
      <div><div class="footer-heading"><?php echo t('categories'); ?></div><ul class="footer-links"><li><a href="products.php?category=SUV"><?php echo t('suv'); ?></a></li><li><a href="products.php?category=Sedan"><?php echo t('sedan'); ?></a></li><li><a href="products.php?category=Sports"><?php echo t('sports'); ?></a></li><li><a href="products.php?category=Trucks"><?php echo t('trucks'); ?></a></li></ul></div>
      <div><div class="footer-heading"><?php echo t('contact_us'); ?></div><ul class="footer-links">
        <?php $phones = getSettingJson('phones', []); foreach ($phones as $phone): ?>
          <li><a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $phone); ?>"><i class="fas fa-phone"></i> <?php echo htmlspecialchars($phone); ?></a></li>
        <?php endforeach; ?>
        <li><a href="https://wa.me/<?php echo getSetting('whatsapp_number'); ?>" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a></li>
        <?php $addresses = getSettingJson('addresses', []); foreach ($addresses as $addr): ?>
          <li><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($addr); ?></li>
        <?php endforeach; ?>
      </ul><div style="margin-top:1.5rem;"><a href="https://wa.me/<?php echo getSetting('whatsapp_number'); ?>" target="_blank" class="wa-btn-nav"><i class="fab fa-whatsapp"></i> <?php echo t('talk_to_agent'); ?></a></div></div>
    </div>
    <div class="footer-bottom">
      <span>© <span id="year"></span> <?php echo getSetting('site_title'); ?>. <?php echo t('all_rights'); ?></span>
      <span>Powered by <a href="mailto:adtechprofessional@gmail.com" style="color: var(--gold); text-decoration: none;">AdtechPro</a></span>
      <span><a href="privacy.php" style="color: var(--text-muted);"><?php echo t('privacy_policy'); ?></a> | <a href="terms.php" style="color: var(--text-muted);"><?php echo t('terms_of_service'); ?></a></span>
    </div>
  </div>
</footer>
<div class="floating-wa"><a href="https://wa.me/<?php echo getSetting('whatsapp_number'); ?>" target="_blank"><div class="wa-dot"><i class="fab fa-whatsapp"></i></div><span><?php echo t('talk_to_agent'); ?></span></a></div>
<button id="backToTop" style="position:fixed; bottom:80px; right:20px; display:none; background:var(--gold); border:none; border-radius:50%; width:45px; height:45px; cursor:pointer; z-index:1000; color:#000;"><i class="fas fa-arrow-up"></i></button>
<script>
document.getElementById('year').textContent = new Date().getFullYear();
const backBtn = document.getElementById('backToTop');
window.addEventListener('scroll', () => { if (window.scrollY > 300) backBtn.style.display = 'block'; else backBtn.style.display = 'none'; });
backBtn.addEventListener('click', () => window.scrollTo({top:0, behavior:'smooth'}));
</script>
</body>
</html>