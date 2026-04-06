<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/functions.php';
require_once 'includes/language.php';
require_once 'includes/header.php';

$filters = [
    'search' => isset($_GET['search']) ? trim($_GET['search']) : '',
    'category' => isset($_GET['category']) ? $_GET['category'] : 'all',
    'brand' => isset($_GET['brand']) ? trim($_GET['brand']) : '',
    'year_min' => isset($_GET['year_min']) ? (int)$_GET['year_min'] : '',
    'year_max' => isset($_GET['year_max']) ? (int)$_GET['year_max'] : '',
    'price_min' => isset($_GET['price_min']) ? (float)$_GET['price_min'] : '',
    'price_max' => isset($_GET['price_max']) ? (float)$_GET['price_max'] : '',
    'fuel_type' => isset($_GET['fuel_type']) ? $_GET['fuel_type'] : '',
    'transmission' => isset($_GET['transmission']) ? $_GET['transmission'] : ''
];

function getFilteredCars($filters) {
    $db = new Database();
    $pdo = $db->getConnection();
    $sql = "SELECT * FROM cars WHERE 1=1";
    $params = [];
    if (!empty($filters['search'])) {
        $sql .= " AND (title_en LIKE ? OR title_fr LIKE ? OR brand LIKE ? OR model LIKE ?)";
        $s = "%{$filters['search']}%";
        $params = array_merge($params, [$s,$s,$s,$s]);
    }
    if (!empty($filters['category']) && $filters['category'] != 'all') {
        $sql .= " AND category = ?";
        $params[] = $filters['category'];
    }
    if (!empty($filters['brand'])) {
        $sql .= " AND brand LIKE ?";
        $params[] = "%{$filters['brand']}%";
    }
    if (!empty($filters['year_min'])) { $sql .= " AND year >= ?"; $params[] = $filters['year_min']; }
    if (!empty($filters['year_max'])) { $sql .= " AND year <= ?"; $params[] = $filters['year_max']; }
    if (!empty($filters['price_min'])) { $sql .= " AND price >= ?"; $params[] = $filters['price_min'] / 600; }
    if (!empty($filters['price_max'])) { $sql .= " AND price <= ?"; $params[] = $filters['price_max'] / 600; }
    if (!empty($filters['fuel_type'])) { $sql .= " AND fuel_type = ?"; $params[] = $filters['fuel_type']; }
    if (!empty($filters['transmission'])) { $sql .= " AND transmission = ?"; $params[] = $filters['transmission']; }
    $sql .= " ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$cars = getFilteredCars($filters);
$brands = getAllBrands();
?>

<main style="padding-top: 100px;">
  <div class="section-inner">
    <div class="section-label"><?php echo t('our_inventory'); ?></div>
    <h2 class="section-title"><?php echo t('find_your_perfect_car'); ?></h2>
    <form action="products.php" method="GET" class="filter-form" style="margin: 2rem 0;">
      <div class="filter-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem;">
        <input type="text" name="search" placeholder="<?php echo t('search_placeholder'); ?>" value="<?php echo htmlspecialchars($filters['search']); ?>" style="flex:2; padding:10px; background:var(--bg-secondary); border:1px solid var(--border); color:var(--text-primary); border-radius:8px;">
        <select name="category" style="flex:1; padding:10px; background:var(--bg-secondary); border:1px solid var(--border); color:var(--text-primary); border-radius:8px;">
          <option value="all"><?php echo t('all_cars'); ?></option>
          <option value="SUV" <?php echo $filters['category']=='SUV'?'selected':''; ?>><?php echo t('suv'); ?></option>
          <option value="Sedan" <?php echo $filters['category']=='Sedan'?'selected':''; ?>><?php echo t('sedan'); ?></option>
          <option value="Sports" <?php echo $filters['category']=='Sports'?'selected':''; ?>><?php echo t('sports'); ?></option>
          <option value="Luxury" <?php echo $filters['category']=='Luxury'?'selected':''; ?>><?php echo t('luxury'); ?></option>
          <option value="Trucks" <?php echo $filters['category']=='Trucks'?'selected':''; ?>><?php echo t('trucks'); ?></option>
        </select>
        <select name="brand" style="flex:1; padding:10px; background:var(--bg-secondary); border:1px solid var(--border); color:var(--text-primary); border-radius:8px;">
          <option value=""><?php echo t('all_brands'); ?></option>
          <?php foreach ($brands as $b): ?>
            <option value="<?php echo htmlspecialchars($b); ?>" <?php echo $filters['brand']==$b?'selected':''; ?>><?php echo htmlspecialchars($b); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="filter-row" style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem;">
        <input type="number" name="year_min" placeholder="<?php echo t('year_min'); ?>" value="<?php echo $filters['year_min']; ?>" style="flex:1; padding:10px; background:var(--bg-secondary); border:1px solid var(--border); color:var(--text-primary); border-radius:8px;">
        <input type="number" name="year_max" placeholder="<?php echo t('year_max'); ?>" value="<?php echo $filters['year_max']; ?>" style="flex:1; padding:10px;">
        <input type="number" name="price_min" placeholder="<?php echo t('price_min'); ?> (F CFA)" value="<?php echo $filters['price_min']; ?>" style="flex:1; padding:10px;">
        <input type="number" name="price_max" placeholder="<?php echo t('price_max'); ?> (F CFA)" value="<?php echo $filters['price_max']; ?>" style="flex:1; padding:10px;">
      </div>
      <div class="filter-row" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
        <select name="fuel_type" style="flex:1; padding:10px; background:var(--bg-secondary); border:1px solid var(--border); color:var(--text-primary); border-radius:8px;">
          <option value=""><?php echo t('fuel_type'); ?></option>
          <option value="Petrol" <?php echo $filters['fuel_type']=='Petrol'?'selected':''; ?>>Petrol</option>
          <option value="Diesel" <?php echo $filters['fuel_type']=='Diesel'?'selected':''; ?>>Diesel</option>
          <option value="Electric" <?php echo $filters['fuel_type']=='Electric'?'selected':''; ?>>Electric</option>
          <option value="Hybrid" <?php echo $filters['fuel_type']=='Hybrid'?'selected':''; ?>>Hybrid</option>
        </select>
        <select name="transmission" style="flex:1; padding:10px; background:var(--bg-secondary); border:1px solid var(--border); color:var(--text-primary); border-radius:8px;">
          <option value=""><?php echo t('transmission'); ?></option>
          <option value="Manual" <?php echo $filters['transmission']=='Manual'?'selected':''; ?>>Manual</option>
          <option value="Automatic" <?php echo $filters['transmission']=='Automatic'?'selected':''; ?>>Automatic</option>
        </select>
        <button type="submit" class="btn-primary-hero" style="padding:10px 20px;"><?php echo t('filter'); ?></button>
        <a href="products.php" class="btn-ghost-hero" style="padding:10px 20px;"><?php echo t('reset'); ?></a>
      </div>
    </form>
    <p style="margin-bottom:1rem;"><?php echo count($cars); ?> <?php echo t('cars_found'); ?></p>
    <div class="cars-grid">
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
  </div>
</main>

<div class="modal-overlay" id="detailModal">
  <div class="modal-box" id="modalContent"><div class="modal-close"><button onclick="closeDetail()"><i class="fas fa-times"></i> <?php echo t('close'); ?></button></div></div>
</div>

<script>
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
    document.getElementById('modalContent').innerHTML = `...`; // same as index.php modal
}
function openCarDetail(id) { const car = carsData.find(c => c.id == id); if (car) showCarModal(car); }
function closeDetail() { document.getElementById('detailModal').classList.remove('open'); document.body.style.overflow = ''; }
document.getElementById('detailModal').addEventListener('click', e => { if (e.target === this) closeDetail(); });
// Add language, theme, etc. from index.php if needed
</script>

<?php include 'includes/footer.php'; ?>