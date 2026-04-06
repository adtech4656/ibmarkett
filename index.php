<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/functions.php';
require_once 'includes/language.php';
require_once 'includes/header.php';

$db = new Database();
$pdo = $db->getConnection();

// Featured cars
$stmt = $pdo->prepare("SELECT * FROM featured_cars WHERE active = 1 ORDER BY sort_order ASC");
$stmt->execute();
$featured_cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
$large_card = null;
$stack_cards = [];
foreach ($featured_cars as $fc) {
    if ($fc['is_large_card']) $large_card = $fc;
    else $stack_cards[] = $fc;
}

// Regular cars
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$cars = getCars($search, $category);
?>

<section class="section" id="inventory">
  <div class="section-inner">
    <div class="reveal">
      <div class="section-label"><?php echo t('featured_collection'); ?></div>
      <h2 class="section-title"><?php echo t('hand_picked'); ?><br><span class="accent"><?php echo t('excellence'); ?></span></h2>
    </div>

    <div class="featured-layout reveal reveal-delay-1" style="margin-top:3rem;">
      <?php if ($large_card): ?>
      <div class="featured-card" onclick="openFeaturedDetail(<?php echo $large_card['id']; ?>)">
        <div class="featured-card-img" style="background-image: url('<?php echo $large_card['image_url']; ?>');"></div>
        <div class="featured-card-overlay"></div>
        <div class="featured-card-content">
          <div class="car-brand"><?php echo htmlspecialchars($large_card['title_en']); ?> · <?php echo $large_card['year']; ?></div>
          <div class="car-name" style="font-size:2.2rem; color: white;"><?php echo htmlspecialchars($large_card['title_en']); ?></div>
          <div class="car-specs">
            <div class="car-spec"><i class="fas fa-tachometer-alt"></i> <?php echo $large_card['hp']; ?> HP</div>
            <div class="car-spec"><i class="fas fa-gas-pump"></i> <?php echo $large_card['fuel_type']; ?></div>
            <div class="car-spec"><i class="fas fa-road"></i> <?php echo number_format($large_card['mileage']); ?> km</div>
          </div>
          <div class="car-footer">
            <div><span class="car-price-label"><?php echo t('starting_at'); ?></span><div class="car-price">F CFA <?php echo number_format($large_card['price_cfa'], 0, ',', ' '); ?></div></div>
            <span class="car-cta-mini"><?php echo t('view_details'); ?> <i class="fas fa-arrow-right"></i></span>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <div class="featured-card-stack">
        <?php foreach ($stack_cards as $stack): ?>
        <div class="stack-card" onclick="openFeaturedDetail(<?php echo $stack['id']; ?>)">
          <div class="stack-card-img"><img src="<?php echo $stack['image_url']; ?>" alt="<?php echo htmlspecialchars($stack['title_en']); ?>"></div>
          <div class="stack-card-body">
            <div><div class="car-brand"><?php echo htmlspecialchars($stack['title_en']); ?> · <?php echo $stack['year']; ?></div><div class="car-name"><?php echo htmlspecialchars($stack['title_en']); ?></div><div class="car-specs"><span class="car-spec"><i class="fas fa-road"></i> <?php echo number_format($stack['mileage']); ?> km</span><span class="car-spec"><i class="fas fa-gas-pump"></i> <?php echo $stack['fuel_type']; ?></span></div></div>
            <div class="car-footer"><div class="car-price">F CFA <?php echo number_format($stack['price_cfa'], 0, ',', ' '); ?></div><span class="car-cta-mini"><?php echo t('view'); ?> <i class="fas fa-arrow-right"></i></span></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="filter-bar reveal" style="margin-top:4rem;">
      <button class="filter-btn <?php echo $category == 'all' ? 'active' : ''; ?>" data-filter="all"><?php echo t('all_cars'); ?></button>
      <button class="filter-btn <?php echo $category == 'SUV' ? 'active' : ''; ?>" data-filter="SUV"><?php echo t('suv'); ?></button>
      <button class="filter-btn <?php echo $category == 'Sedan' ? 'active' : ''; ?>" data-filter="Sedan"><?php echo t('sedan'); ?></button>
      <button class="filter-btn <?php echo $category == 'Sports' ? 'active' : ''; ?>" data-filter="Sports"><?php echo t('sports'); ?></button>
      <button class="filter-btn <?php echo $category == 'Luxury' ? 'active' : ''; ?>" data-filter="Luxury"><?php echo t('luxury'); ?></button>
      <button class="filter-btn <?php echo $category == 'Trucks' ? 'active' : ''; ?>" data-filter="Trucks"><?php echo t('trucks'); ?></button>
    </div>

    <div class="cars-grid reveal" id="carsGrid">
      <?php if (count($cars) > 0): ?>
        <?php foreach ($cars as $car): ?>
          <a href="#" class="car-card" onclick="openCarDetail(<?php echo $car['id']; ?>); return false;">
            <div class="car-img-wrap"><img src="<?php echo $car['image']; ?>" alt="<?php echo htmlspecialchars($car['model']); ?>" loading="lazy"><div class="car-badge-corner"><?php echo t('available'); ?></div><div class="car-img-overlay"></div><div class="car-quick-view"><span><?php echo t('details'); ?></span></div></div>
            <div class="car-body"><div class="car-brand"><?php echo htmlspecialchars($car['brand']); ?> · <?php echo $car['year']; ?></div><div class="car-name"><?php echo htmlspecialchars($car['model']); ?></div><div class="car-specs"><span class="car-spec"><i class="fas fa-road"></i> <?php echo number_format($car['mileage']); ?> km</span><span class="car-spec"><i class="fas fa-gas-pump"></i> <?php echo htmlspecialchars($car['fuel_type']); ?></span></div><div class="car-footer"><div><span class="car-price-label"><?php echo t('price'); ?></span><div class="car-price">F CFA <?php echo number_format($car['price'] * 600, 0, ',', ' '); ?></div></div><span class="car-cta-mini"><?php echo t('details'); ?> <i class="fas fa-arrow-right"></i></span></div></div>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="text-align:center; grid-column:1/-1;"><?php echo t('no_cars_found'); ?></p>
      <?php endif; ?>
    </div>
    <div style="text-align:center; margin-top:3rem;"><a href="products.php" class="btn-ghost-hero" style="display:inline-flex;"><i class="fas fa-plus-circle"></i> <?php echo t('load_more'); ?></a></div>
  </div>
</section>

<section class="section brands-section" id="brands">
  <div class="section-inner"><div class="reveal" style="display:flex; align-items:flex-end; justify-content:space-between;"><div><div class="section-label"><?php echo t('trusted_brands'); ?></div><h2 class="section-title"><?php echo t('we_carry_all_major'); ?><br><span class="accent"><?php echo t('brands'); ?></span></h2></div><p style="max-width:340px; color:var(--text-muted);"><?php echo t('brands_desc'); ?></p></div></div>
  <div class="brands-track-wrap"><div class="brands-track"><div class="brand-item">BMW</div><div class="brand-item">MERCEDES</div><div class="brand-item">PORSCHE</div><div class="brand-item">AUDI</div><div class="brand-item">TOYOTA</div><div class="brand-item">LEXUS</div><div class="brand-item">RANGE ROVER</div><div class="brand-item">FORD</div><div class="brand-item">BENTLEY</div><div class="brand-item">FERRARI</div></div></div>
</section>

<section class="section" id="why-us">
  <div class="section-inner"><div class="reveal" style="text-align:center;"><div class="section-label" style="justify-content:center;"><?php echo t('why_choose_us'); ?></div><h2 class="section-title"><?php echo t('advantage_title'); ?><br><span class="accent"><?php echo t('advantage_accent'); ?></span></h2></div>
  <div class="why-grid">
    <div class="why-card reveal"><div class="why-icon"><i class="fas fa-shield-alt"></i></div><div class="why-title"><?php echo t('verified_stock_title'); ?></div><div class="why-desc"><?php echo t('verified_stock_desc'); ?></div><div class="why-num">01</div></div>
    <div class="why-card reveal reveal-delay-1"><div class="why-icon"><i class="fas fa-handshake"></i></div><div class="why-title"><?php echo t('expert_negotiation_title'); ?></div><div class="why-desc"><?php echo t('expert_negotiation_desc'); ?></div><div class="why-num">02</div></div>
    <div class="why-card reveal reveal-delay-2"><div class="why-icon"><i class="fas fa-clock"></i></div><div class="why-title"><?php echo t('fast_processing_title'); ?></div><div class="why-desc"><?php echo t('fast_processing_desc'); ?></div><div class="why-num">03</div></div>
    <div class="why-card reveal reveal-delay-3"><div class="why-icon"><i class="fab fa-whatsapp"></i></div><div class="why-title"><?php echo t('live_whatsapp_title'); ?></div><div class="why-desc"><?php echo t('live_whatsapp_desc'); ?></div><div class="why-num">04</div></div>
  </div></div>
</section>

<section class="cta-strip" id="contact">
  <div class="cta-strip-inner"><div><h2 class="cta-strip-title"><span><?php echo t('ready_to_find'); ?></span><span><?php echo t('perfect_car'); ?></span></h2><p style="margin-top:1rem;"><?php echo t('cta_subtitle'); ?></p></div><a href="https://wa.me/<?php echo getSetting('whatsapp_number'); ?>" target="_blank" class="wa-big-btn"><div class="wa-icon"><i class="fab fa-whatsapp"></i></div><div><div style="font-size:0.7rem;"><?php echo t('click_to_chat'); ?></div><div><?php echo t('talk_to_agent'); ?></div></div></a></div>
</section>

<?php include 'includes/footer.php'; ?>

<div class="modal-overlay" id="detailModal">
  <div class="modal-box" id="modalContent"><div class="modal-close"><button onclick="closeDetail()"><i class="fas fa-times"></i> <?php echo t('close'); ?></button></div></div>
</div>

<script>
const featuredCarsData = <?php
$featuredJson = [];
foreach ($featured_cars as $fc) {
    $featuredJson[$fc['id']] = [
        'id' => $fc['id'],
        'brand' => explode(' ', $fc['title_en'])[0],
        'model' => $fc['title_en'],
        'year' => $fc['year'],
        'price' => $fc['price_cfa'],
        'mileage' => $fc['mileage'],
        'fuel_type' => $fc['fuel_type'],
        'transmission' => $fc['transmission'],
        'category' => $fc['category'],
        'image' => $fc['image_url'],
        'hp' => $fc['hp'],
        'description_en' => $fc['description_en'],
        'description_fr' => $fc['description_fr'],
    ];
}
echo json_encode($featuredJson);
?>;

const carsData = <?php
$carsJson = [];
foreach ($cars as $car) {
    $carsJson[] = [
        'id' => $car['id'],
        'brand' => $car['brand'],
        'model' => $car['model'],
        'year' => $car['year'],
        'price' => $car['price'] * 600,
        'mileage' => $car['mileage'],
        'fuel_type' => $car['fuel_type'],
        'transmission' => $car['transmission'],
        'category' => $car['category'],
        'image' => $car['image'],
        'description' => $lang == 'en' ? $car['description_en'] : $car['description_fr'],
        'hp' => '—',
    ];
}
echo json_encode($carsJson);
?>;

function showCarModal(car) {
    const priceFormatted = 'F CFA ' + car.price.toLocaleString();
    const mileageFormatted = (car.mileage || 0).toLocaleString() + ' km';
    const hpText = car.hp ? car.hp + ' HP' : '—';
    document.getElementById('modalContent').innerHTML = `
        <div class="modal-close"><button onclick="closeDetail()"><i class="fas fa-times"></i> <?php echo t('close'); ?></button></div>
        <div style="display:grid; grid-template-columns:1fr 1fr; min-height:500px;" id="detailGrid">
            <div style="position:relative; overflow:hidden; min-height:400px; background:var(--bg-secondary);"><img src="${car.image}" alt="${car.model}" style="width:100%; height:100%; object-fit:cover;"><div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(10,10,10,0.6) 0%, transparent 60%);"></div></div>
            <div style="padding:2.5rem; background:var(--bg-card); display:flex; flex-direction:column; justify-content:space-between;">
                <div><div class="car-brand" style="font-size:0.8rem; margin-bottom:0.5rem;">${car.brand} · ${car.year} · ${car.category}</div>
                <h2 style="font-family:'Bebas Neue',sans-serif; font-size:2.8rem; letter-spacing:1px; color:var(--text-primary); line-height:0.9; margin-bottom:1.5rem;">${car.model}</h2>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.5rem; padding:1.5rem; background:var(--bg-secondary); border:1px solid var(--border); border-radius:3px;">
                    <div><div style="font-size:0.65rem; color:var(--text-muted); letter-spacing:2px; text-transform:uppercase;"><?php echo t('mileage'); ?></div><div style="font-family:'Barlow Condensed'; font-size:1.1rem; font-weight:700;">${mileageFormatted}</div></div>
                    <div><div style="font-size:0.65rem; color:var(--text-muted); letter-spacing:2px; text-transform:uppercase;"><?php echo t('fuel_type'); ?></div><div style="font-family:'Barlow Condensed'; font-size:1.1rem; font-weight:700;">${car.fuel_type}</div></div>
                    <div><div style="font-size:0.65rem; color:var(--text-muted); letter-spacing:2px; text-transform:uppercase;"><?php echo t('engine'); ?></div><div style="font-family:'Barlow Condensed'; font-size:1.1rem; font-weight:700;">—</div></div>
                    <div><div style="font-size:0.65rem; color:var(--text-muted); letter-spacing:2px; text-transform:uppercase;"><?php echo t('color'); ?></div><div style="font-family:'Barlow Condensed'; font-size:1.1rem; font-weight:700;">—</div></div>
                    <div><div style="font-size:0.65rem; color:var(--text-muted); letter-spacing:2px; text-transform:uppercase;"><?php echo t('power'); ?></div><div style="font-family:'Barlow Condensed'; font-size:1.1rem; font-weight:700; color:var(--gold);">${hpText}</div></div>
                    <div><div style="font-size:0.65rem; color:var(--text-muted); letter-spacing:2px; text-transform:uppercase;"><?php echo t('transmission'); ?></div><div style="font-family:'Barlow Condensed'; font-size:1.1rem; font-weight:700;">${car.transmission}</div></div>
                </div>
                <p style="font-size:0.9rem; color:var(--text-muted); line-height:1.7; margin-bottom:1.5rem;">${car.description || ''}</p></div>
                <div><div style="display:flex; align-items:center; justify-content:space-between; padding:1rem 0; border-top:1px solid var(--border); border-bottom:1px solid var(--border); margin-bottom:1.5rem;"><div><div style="font-size:0.65rem; color:var(--text-muted); letter-spacing:2px; text-transform:uppercase;"><?php echo t('asking_price'); ?></div><div style="font-family:'Bebas Neue'; font-size:3rem; color:var(--gold); letter-spacing:1px; line-height:1.1;">${priceFormatted}</div></div><div><div style="font-size:0.7rem; color:var(--text-muted);"><?php echo $lang == 'en' ? 'Price may vary.' : 'Le prix peut varier.'; ?></div><div style="font-size:0.7rem; color:var(--gold);"><?php echo t('negotiable'); ?></div></div></div>
                <div style="display:flex; gap:1rem;"><a href="https://wa.me/<?php echo getSetting('whatsapp_number'); ?>?text=Hello! I'm interested in the ${encodeURIComponent(car.brand+' '+car.model+' '+car.year)} listed on IBMARKETTE at ${encodeURIComponent(priceFormatted)}." target="_blank" style="flex:1; display:flex; align-items:center; justify-content:center; gap:10px; background:linear-gradient(135deg,#25D366,#128C7E); color:white; border-radius:3px; padding:14px; font-family:'Barlow Condensed'; font-size:0.9rem; font-weight:700; letter-spacing:2px; text-transform:uppercase; transition:all 0.3s; text-decoration:none; box-shadow:0 6px 20px rgba(37,211,102,0.3);"><i class="fab fa-whatsapp" style="font-size:1.2rem;"></i> <?php echo t('talk_to_agent'); ?></a>
                <button onclick="closeDetail()" style="padding:14px 20px; border:1px solid var(--border); background:none; color:var(--text-muted); border-radius:3px; cursor:pointer; font-family:'Barlow Condensed'; font-size:0.8rem; letter-spacing:2px; text-transform:uppercase;"><i class="fas fa-arrow-left"></i></button></div></div>
            </div>
        </div>
    `;
    if (window.innerWidth < 768) document.getElementById('detailGrid').style.gridTemplateColumns = '1fr';
    document.getElementById('detailModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function openFeaturedDetail(id) { const car = featuredCarsData[id]; if (car) showCarModal(car); }
function openCarDetail(id) { const car = carsData.find(c => c.id == id); if (car) showCarModal(car); }
function closeDetail() { document.getElementById('detailModal').classList.remove('open'); document.body.style.overflow = ''; }
document.getElementById('detailModal').addEventListener('click', e => { if (e.target === this) closeDetail(); });

let currentLang = '<?php echo $lang; ?>';
function setLanguage(lang) {
    currentLang = lang;
    document.querySelectorAll('[data-en]').forEach(el => {
        if (el.hasAttribute('data-' + lang)) el.textContent = el.getAttribute('data-' + lang);
    });
    document.getElementById('langEn').classList.toggle('active', lang === 'en');
    document.getElementById('langFr').classList.toggle('active', lang === 'fr');
    document.cookie = "lang=" + lang + "; path=/";
}
document.getElementById('langEn').addEventListener('click', () => setLanguage('en'));
document.getElementById('langFr').addEventListener('click', () => setLanguage('fr'));

const html = document.documentElement;
document.getElementById('themeToggle').addEventListener('click', () => {
    const isDark = html.getAttribute('data-theme') === 'dark';
    html.setAttribute('data-theme', isDark ? 'light' : 'dark');
});

const searchInput = document.getElementById('navSearch');
if (searchInput) {
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            window.location.href = `?search=${encodeURIComponent(this.value)}&category=<?php echo $category; ?>`;
        }
    });
}
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const filter = this.getAttribute('data-filter');
        const searchTerm = searchInput ? searchInput.value : '';
        window.location.href = `?search=${encodeURIComponent(searchTerm)}&category=${filter}`;
    });
});

function animateCount(el, target) {
    let current = 0;
    const step = Math.ceil(target / 60);
    const timer = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = current + (target > 10 ? '+' : '');
        if (current >= target) clearInterval(timer);
    }, 25);
}
const statNums = document.querySelectorAll('[data-count]');
const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
            entry.target.classList.add('counted');
            animateCount(entry.target, parseInt(entry.target.dataset.count));
        }
    });
}, { threshold: 0.5 });
statNums.forEach(el => counterObserver.observe(el));

const revealEls = document.querySelectorAll('.reveal');
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
}, { threshold: 0.1 });
revealEls.forEach(el => observer.observe(el));

window.addEventListener('scroll', () => {
    const nav = document.getElementById('mainNav');
    if (nav) nav.style.boxShadow = window.scrollY > 50 ? '0 4px 30px rgba(0,0,0,0.4)' : 'none';
});
const cursor = document.getElementById('cursorGlow');
if (cursor) {
    document.addEventListener('mousemove', e => {
        cursor.style.left = e.clientX + 'px';
        cursor.style.top = e.clientY + 'px';
    });
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDetail(); });
</script>
</body>
</html>