<?php
require_once 'config.php';
require_once 'functions.php';
$hero_slides = getSettingJson('hero_slides', []);
if (empty($hero_slides)) {
    $hero_slides = [[
        'image' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=1800&q=80',
        'title_en' => 'DRIVE YOUR DREAM MACHINE',
        'title_fr' => 'CONDUISEZ VOTRE MACHINE DE RÊVE',
        'subtitle_en' => 'IBMARKETTE brings you the finest selection of premium pre-owned vehicles.',
        'subtitle_fr' => 'IBMARKETTE vous présente la meilleure sélection de véhicules d\'occasion premium.',
        'btn_text_en' => 'Browse Inventory',
        'btn_text_fr' => 'Voir l\'Inventaire',
        'btn_link' => '#inventory'
    ]];
}
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
$page_title = getSetting('site_title');
$page_desc = $lang == 'en' ? getSetting('hero_subtitle_en') : getSetting('hero_subtitle_fr');
if (basename($_SERVER['PHP_SELF']) == 'products.php') {
    $page_title .= ' - ' . t('our_inventory');
}
?>
<title><?php echo htmlspecialchars($page_title); ?></title>
<meta name="description" content="<?php echo htmlspecialchars($page_desc); ?>">
<meta name="keywords" content="cars, used cars, premium vehicles, CFA, Abidjan, Côte d'Ivoire">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300&family=Barlow+Condensed:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
/* ===== FULL CSS – COPY FROM THE ORIGINAL DESIGN ===== */
/* Due to length, paste the complete CSS from the original chat or use the condensed version below */
/* The condensed version includes all essential styles: dark/light theme, gold accents, grid, cards, modal, etc. */
:root {
  --gold: #C9A84C;
  --gold-light: #E8C97A;
  --gold-dark: #9A7530;
  --red-accent: #D9241F;
  --white: #F5F5F0;
  --bg-primary: #0A0A0A;
  --bg-secondary: #111111;
  --bg-card: #161616;
  --bg-card-hover: #1E1E1E;
  --text-primary: #F5F5F0;
  --text-muted: #888880;
  --border: rgba(201,168,76,0.2);
  --nav-bg: rgba(10,10,10,0.95);
  --hero-overlay: linear-gradient(135deg, rgba(10,10,10,0.92) 0%, rgba(10,10,10,0.6) 60%, rgba(201,168,76,0.08) 100%);
}
[data-theme="light"] {
  --bg-primary: #F0EDE8;
  --bg-secondary: #E8E4DC;
  --bg-card: #FAFAF7;
  --bg-card-hover: #FFFFFF;
  --text-primary: #0A0A0A;
  --text-muted: #666660;
  --border: rgba(154,117,48,0.25);
  --nav-bg: rgba(240,237,232,0.97);
  --hero-overlay: linear-gradient(135deg, rgba(10,10,10,0.85) 0%, rgba(10,10,10,0.5) 60%, rgba(201,168,76,0.12) 100%);
}
* { margin: 0; padding: 0; box-sizing: border-box; }
html { scroll-behavior: smooth; }
body {
  font-family: 'Barlow', sans-serif;
  background: var(--bg-primary);
  color: var(--text-primary);
  overflow-x: hidden;
  transition: background 0.4s ease, color 0.4s ease;
}
a { text-decoration: none; color: inherit; }
::-webkit-scrollbar { width: 4px; }
::-webkit-scrollbar-track { background: var(--bg-primary); }
::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 2px; }
.cursor-glow {
  position: fixed; width: 20px; height: 20px;
  background: radial-gradient(circle, rgba(201,168,76,0.6), transparent);
  border-radius: 50%; pointer-events: none; z-index: 9999;
  transform: translate(-50%,-50%);
}
.nav-wrapper {
  position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
  background: var(--nav-bg);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid var(--border);
  transition: all 0.3s ease;
}
.nav-inner {
  max-width: 1400px; margin: 0 auto;
  display: flex; align-items: center;
  padding: 0 2rem; height: 72px; gap: 2rem;
}
.nav-logo {
  display: flex; align-items: center; gap: 12px;
  flex-shrink: 0;
}
.nav-logo .logo-mark {
  width: 42px; height: 42px;
  background: var(--gold);
  clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
  display: flex; align-items: center; justify-content: center;
  position: relative;
}
.nav-logo .logo-mark::after {
  content: 'IB';
  font-family: 'Bebas Neue', sans-serif;
  font-size: 14px; color: #0A0A0A;
  position: absolute;
}
.nav-logo .logo-text {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 1.8rem; letter-spacing: 3px;
  background: linear-gradient(135deg, var(--gold-light), var(--gold));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.nav-links {
  display: flex; align-items: center; gap: 0.25rem;
  list-style: none; margin-left: 2rem;
}
.nav-links a {
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 0.95rem; font-weight: 600; letter-spacing: 1.5px;
  text-transform: uppercase; padding: 8px 16px;
  color: var(--text-muted); border-radius: 4px;
  transition: all 0.2s ease; position: relative;
}
.nav-links a::after {
  content: ''; position: absolute; bottom: 2px; left: 16px; right: 16px;
  height: 1px; background: var(--gold);
  transform: scaleX(0); transform-origin: left;
  transition: transform 0.3s ease;
}
.nav-links a:hover, .nav-links a.active {
  color: var(--gold);
}
.nav-links a:hover::after, .nav-links a.active::after { transform: scaleX(1); }
.nav-right {
  margin-left: auto; display: flex; align-items: center; gap: 1rem;
}
.search-bar {
  display: flex; align-items: center;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--border);
  border-radius: 40px; overflow: hidden;
}
.search-bar input {
  background: none; border: none; outline: none;
  color: var(--text-primary); padding: 8px 16px; width: 200px;
}
.search-bar button {
  background: none; border: none; cursor: pointer;
  color: var(--text-muted); padding: 8px 14px;
}
.search-bar button:hover { color: var(--gold); }
.theme-toggle {
  width: 48px; height: 26px;
  background: var(--border);
  border: 1px solid var(--border);
  border-radius: 13px; cursor: pointer;
  position: relative;
}
.theme-toggle::after {
  content: ''; position: absolute;
  top: 2px; left: 2px; width: 20px; height: 20px;
  background: var(--gold); border-radius: 50%;
  transition: transform 0.3s ease;
}
[data-theme="light"] .theme-toggle::after { transform: translateX(22px); }
.lang-btn {
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 0.8rem; font-weight: 700; letter-spacing: 1px;
  text-transform: uppercase; padding: 6px 14px;
  border: 1px solid var(--border); border-radius: 4px;
  background: none; color: var(--text-muted); cursor: pointer;
}
.lang-btn:hover, .lang-btn.active { color: var(--gold); border-color: var(--gold); }
.wa-btn-nav {
  display: flex; align-items: center; gap: 8px;
  background: linear-gradient(135deg, #25D366, #128C7E);
  color: white; border-radius: 40px;
  padding: 8px 18px;
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 0.85rem; font-weight: 700;
  text-transform: uppercase;
  box-shadow: 0 4px 15px rgba(37,211,102,0.3);
}
.wa-btn-nav:hover { transform: translateY(-2px); }
/* Hero Slider */
.hero {
  position: relative; height: 100vh; min-height: 700px;
  display: flex; align-items: center; overflow: hidden;
}
.swiper-slide { height: 100vh; }
.hero-bg {
  position: absolute; inset: 0; z-index: 0;
  background-size: cover; background-position: center;
}
.hero-noise {
  position: absolute; inset: 0; z-index: 1;
  opacity: 0.03;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
}
.hero-content {
  position: relative; z-index: 2;
  max-width: 1400px; margin: 0 auto;
  padding: 0 2rem; padding-top: 72px;
  width: 100%;
}
.hero-badge {
  display: inline-flex; align-items: center; gap: 8px;
  border: 1px solid rgba(201,168,76,0.4);
  background: rgba(201,168,76,0.08);
  border-radius: 2px; padding: 6px 16px;
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 0.75rem; font-weight: 700;
  letter-spacing: 3px; text-transform: uppercase;
  color: var(--gold); margin-bottom: 1.5rem;
  animation: fadeInUp 0.8s ease both;
}
.hero-badge::before {
  content: ''; width: 6px; height: 6px;
  background: var(--gold); border-radius: 50%;
  animation: pulse 2s ease infinite;
}
.hero-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: clamp(4rem, 10vw, 10rem);
  line-height: 0.9; letter-spacing: 2px;
  color: var(--white);
  animation: fadeInUp 0.8s 0.15s ease both;
}
.hero-title .accent {
  background: linear-gradient(135deg, var(--gold-light) 0%, var(--gold) 50%, var(--gold-dark) 100%);
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  display: block;
}
.hero-title .outline {
  -webkit-text-stroke: 1px rgba(255,255,255,0.2);
  -webkit-text-fill-color: transparent;
}
.hero-sub {
  font-size: 1.1rem; font-weight: 300; letter-spacing: 1px;
  color: rgba(245,245,240,0.65); max-width: 500px;
  margin: 1.5rem 0 2.5rem;
  animation: fadeInUp 0.8s 0.3s ease both;
}
.hero-cta {
  display: flex; gap: 1rem; flex-wrap: wrap;
  animation: fadeInUp 0.8s 0.45s ease both;
}
.btn-primary-hero {
  display: flex; align-items: center; gap: 10px;
  background: linear-gradient(135deg, var(--gold-light), var(--gold));
  color: #0A0A0A; border-radius: 3px;
  padding: 16px 32px;
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 1rem; font-weight: 700; letter-spacing: 2px;
  text-transform: uppercase;
  transition: all 0.3s ease;
  overflow: hidden;
  position: relative;
}
.btn-primary-hero::before {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(135deg, transparent, rgba(255,255,255,0.2), transparent);
  transform: translateX(-100%); transition: transform 0.5s ease;
}
.btn-primary-hero:hover::before { transform: translateX(100%); }
.btn-primary-hero:hover { transform: translateY(-3px); box-shadow: 0 15px 40px rgba(201,168,76,0.35); }
.btn-ghost-hero {
  display: flex; align-items: center; gap: 10px;
  background: transparent;
  border: 1px solid rgba(245,245,240,0.25);
  color: var(--white); border-radius: 3px;
  padding: 16px 32px;
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 1rem; font-weight: 700; letter-spacing: 2px;
  text-transform: uppercase;
  cursor: pointer;
}
.btn-ghost-hero:hover {
  border-color: var(--gold); color: var(--gold);
  background: rgba(201,168,76,0.06);
}
.hero-stats {
  position: absolute; bottom: 4rem; right: 2rem;
  display: flex; gap: 3rem; z-index: 2;
}
.hero-stat { text-align: right; }
.hero-stat .num {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 3rem; line-height: 1;
  color: var(--gold); display: block;
}
.hero-stat .label {
  font-size: 0.7rem; font-weight: 600; letter-spacing: 2px;
  text-transform: uppercase; color: var(--text-muted);
}
.hero-scroll {
  position: absolute; bottom: 2rem; left: 50%; transform: translateX(-50%);
  z-index: 2; display: flex; flex-direction: column;
  align-items: center; gap: 8px;
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 0.7rem; letter-spacing: 3px;
  text-transform: uppercase; color: var(--text-muted);
}
.scroll-line {
  width: 1px; height: 50px;
  background: linear-gradient(to bottom, var(--gold), transparent);
  animation: scrollPulse 2s ease infinite;
}
/* Ticker */
.ticker {
  background: var(--gold);
  color: #0A0A0A; overflow: hidden;
  padding: 10px 0;
}
.ticker-inner {
  display: flex; gap: 0;
  animation: ticker 30s linear infinite;
  white-space: nowrap;
}
.ticker-item {
  display: inline-flex; align-items: center; gap: 20px;
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 0.85rem; font-weight: 700;
  letter-spacing: 2px; text-transform: uppercase;
  padding: 0 2rem;
}
.ticker-item::after { content: '◆'; opacity: 0.5; }
/* Sections */
.section { padding: 6rem 0; }
.section-inner { max-width: 1400px; margin: 0 auto; padding: 0 2rem; }
.section-label {
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 0.7rem; font-weight: 700; letter-spacing: 4px;
  text-transform: uppercase; color: var(--gold);
  display: flex; align-items: center; gap: 12px;
  margin-bottom: 1rem;
}
.section-label::before {
  content: ''; width: 30px; height: 1px; background: var(--gold);
}
.section-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: clamp(2.5rem, 5vw, 5rem);
  letter-spacing: 1px; line-height: 0.9;
  color: var(--text-primary);
}
.section-title .accent { color: var(--gold); }
/* Filter bar */
.filter-bar {
  display: flex; gap: 0.5rem; flex-wrap: wrap;
  margin-bottom: 2.5rem; padding-bottom: 1.5rem;
  border-bottom: 1px solid var(--border);
}
.filter-btn {
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 0.8rem; font-weight: 700; letter-spacing: 1.5px;
  text-transform: uppercase; padding: 8px 20px;
  border-radius: 2px; border: 1px solid var(--border);
  background: none; color: var(--text-muted); cursor: pointer;
}
.filter-btn:hover, .filter-btn.active {
  background: var(--gold); color: #0A0A0A; border-color: var(--gold);
}
/* Cars grid */
.cars-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 1.5px;
  background: var(--border);
}
.car-card {
  background: var(--bg-card);
  position: relative; overflow: hidden;
  cursor: pointer; transition: all 0.4s ease;
  display: block;
}
.car-card:hover { background: var(--bg-card-hover); z-index: 2; }
.car-img-wrap {
  position: relative; height: 240px; overflow: hidden;
  background: var(--bg-secondary);
}
.car-img-wrap img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  filter: brightness(0.9);
}
.car-card:hover .car-img-wrap img { transform: scale(1.06); filter: brightness(1); }
.car-badge-corner {
  position: absolute; top: 16px; left: 0;
  background: var(--gold);
  color: #0A0A0A; padding: 4px 14px 4px 12px;
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 0.7rem; font-weight: 800;
  text-transform: uppercase;
  clip-path: polygon(0 0, 100% 0, 90% 100%, 0 100%);
}
.car-quick-view {
  position: absolute; inset: 0;
  display: flex; align-items: center; justify-content: center;
  background: rgba(10,10,10,0.7);
  opacity: 0; transition: opacity 0.3s;
}
.car-card:hover .car-quick-view { opacity: 1; }
.car-quick-view span {
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 0.85rem; font-weight: 700; letter-spacing: 3px;
  text-transform: uppercase; color: var(--gold);
  border: 1px solid var(--gold); padding: 10px 24px;
}
.car-body { padding: 1.5rem; }
.car-brand {
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 0.7rem; font-weight: 700; letter-spacing: 3px;
  text-transform: uppercase; color: var(--gold);
  margin-bottom: 0.4rem;
}
.car-name {
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 1.4rem; font-weight: 700;
  color: var(--text-primary); margin-bottom: 0.5rem;
}
.car-specs {
  display: flex; gap: 1rem; flex-wrap: wrap;
  margin-bottom: 1rem;
}
.car-spec {
  display: flex; align-items: center; gap: 5px;
  font-size: 0.75rem; color: var(--text-muted);
}
.car-spec i { color: var(--gold); font-size: 0.7rem; }
.car-footer {
  display: flex; align-items: center; justify-content: space-between;
  padding-top: 1rem;
  border-top: 1px solid var(--border);
}
.car-price {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 1.8rem; color: var(--gold);
}
.car-price-label {
  font-size: 0.65rem; color: var(--text-muted);
  text-transform: uppercase;
  display: block; margin-top: -4px;
}
.car-cta-mini {
  display: flex; align-items: center; gap: 6px;
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 0.75rem; font-weight: 700;
  text-transform: uppercase; color: var(--gold);
  padding: 8px 16px; border: 1px solid rgba(201,168,76,0.3);
  border-radius: 2px;
}
.car-cta-mini:hover { background: var(--gold); color: #0A0A0A; }
/* Featured layout */
.featured-layout {
  display: grid;
  grid-template-columns: 1.5fr 1fr;
  gap: 1.5px;
  background: var(--border);
}
.featured-card {
  background: var(--bg-card);
  position: relative; overflow: hidden; min-height: 520px;
  display: flex; flex-direction: column; justify-content: flex-end;
  cursor: pointer;
}
.featured-card-img {
  position: absolute; inset: 0;
  background-size: cover; background-position: center;
  transition: transform 0.6s ease; filter: brightness(0.7);
}
.featured-card:hover .featured-card-img { transform: scale(1.04); filter: brightness(0.85); }
.featured-card-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(10,10,10,0.92) 0%, rgba(10,10,10,0.3) 60%, transparent 100%);
}
.featured-card-content {
  position: relative; z-index: 2; padding: 2.5rem;
}
.featured-card-stack {
  display: flex; flex-direction: column; gap: 1.5px;
  background: var(--border);
}
.stack-card {
  background: var(--bg-card);
  display: flex; gap: 0; cursor: pointer;
  transition: background 0.3s; overflow: hidden; min-height: 170px;
}
.stack-card:hover { background: var(--bg-card-hover); }
.stack-card-img {
  width: 200px; flex-shrink: 0;
  background: var(--bg-secondary);
  overflow: hidden;
}
.stack-card-img img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform 0.5s ease;
}
.stack-card:hover .stack-card-img img { transform: scale(1.08); }
.stack-card-body { padding: 1.5rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between; }
/* Brands */
.brands-section { background: var(--bg-secondary); }
.brands-track-wrap { overflow: hidden; margin-top: 3rem; position: relative; }
.brands-track-wrap::before, .brands-track-wrap::after {
  content: ''; position: absolute; top: 0; bottom: 0; width: 120px; z-index: 2;
}
.brands-track-wrap::before { left: 0; background: linear-gradient(to right, var(--bg-secondary), transparent); }
.brands-track-wrap::after { right: 0; background: linear-gradient(to left, var(--bg-secondary), transparent); }
.brands-track {
  display: flex; gap: 2px;
  animation: brandScroll 25s linear infinite;
}
.brand-item {
  display: flex; align-items: center; justify-content: center;
  width: 180px; height: 100px; flex-shrink: 0;
  background: var(--bg-card); border: 1px solid var(--border);
  border-radius: 2px; transition: all 0.3s;
  font-family: 'Bebas Neue', sans-serif;
  font-size: 1.4rem; color: var(--text-muted);
}
.brand-item:hover { border-color: var(--gold); color: var(--gold); transform: translateY(-4px); }
/* Why us */
.why-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5px; background: var(--border); margin-top: 3rem;
}
.why-card {
  background: var(--bg-card); padding: 2.5rem;
  position: relative; overflow: hidden;
}
.why-card:hover { background: var(--bg-card-hover); }
.why-card::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
  background: var(--gold); transform: scaleX(0); transform-origin: left;
  transition: transform 0.5s ease;
}
.why-card:hover::before { transform: scaleX(1); }
.why-icon {
  width: 56px; height: 56px;
  border: 1px solid rgba(201,168,76,0.3);
  border-radius: 3px; display: flex;
  align-items: center; justify-content: center;
  margin-bottom: 1.5rem;
  font-size: 1.4rem; color: var(--gold);
}
.why-card:hover .why-icon {
  background: var(--gold); color: #0A0A0A;
  border-color: var(--gold);
}
.why-title {
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 1.2rem; font-weight: 700;
  margin-bottom: 0.75rem; color: var(--text-primary);
}
.why-desc { font-size: 0.9rem; color: var(--text-muted); line-height: 1.6; }
.why-num {
  position: absolute; bottom: 1.5rem; right: 1.5rem;
  font-family: 'Bebas Neue', sans-serif;
  font-size: 5rem; color: var(--border); line-height: 1;
}
.why-card:hover .why-num { color: rgba(201,168,76,0.08); }
/* CTA strip */
.cta-strip {
  background: linear-gradient(135deg, var(--gold-dark), var(--gold), var(--gold-light));
  position: relative; overflow: hidden;
}
.cta-strip::before {
  content: 'IBMARKETTE';
  position: absolute; right: -2%; top: 50%; transform: translateY(-50%);
  font-family: 'Bebas Neue', sans-serif; font-size: 12rem;
  color: rgba(0,0,0,0.08); white-space: nowrap; line-height: 1;
  pointer-events: none;
}
.cta-strip-inner {
  max-width: 1400px; margin: 0 auto; padding: 4rem 2rem;
  display: flex; align-items: center; justify-content: space-between;
  gap: 2rem; flex-wrap: wrap;
  position: relative; z-index: 1;
}
.cta-strip-title {
  font-family: 'Bebas Neue', sans-serif;
  font-size: clamp(2rem, 4vw, 3.5rem);
  color: #0A0A0A; line-height: 0.95;
}
.cta-strip-title span { display: block; font-size: 0.5em; font-family: 'Barlow', sans-serif; font-weight: 300; opacity: 0.7; }
.wa-big-btn {
  display: flex; align-items: center; gap: 14px;
  background: #0A0A0A; color: white;
  border-radius: 4px; padding: 18px 36px;
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 1rem; font-weight: 700;
  text-transform: uppercase;
  box-shadow: 0 8px 30px rgba(0,0,0,0.25);
}
.wa-big-btn:hover {
  background: #1A1A1A; transform: translateY(-3px);
}
.wa-big-btn .wa-icon {
  width: 42px; height: 42px;
  background: #25D366; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.2rem;
}
/* Footer */
footer {
  background: #060606;
  border-top: 1px solid rgba(201,168,76,0.1);
  padding: 4rem 0 2rem;
}
.footer-inner { max-width: 1400px; margin: 0 auto; padding: 0 2rem; }
.footer-top {
  display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr;
  gap: 3rem; margin-bottom: 3rem;
  padding-bottom: 3rem; border-bottom: 1px solid var(--border);
}
.footer-brand .logo-text {
  font-family: 'Bebas Neue', sans-serif;
  font-size: 2rem; letter-spacing: 3px;
  background: linear-gradient(135deg, var(--gold-light), var(--gold));
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  display: block; margin-bottom: 1rem;
}
.footer-brand p { font-size: 0.85rem; color: var(--text-muted); line-height: 1.7; max-width: 280px; }
.footer-heading {
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 0.7rem; font-weight: 700; letter-spacing: 3px;
  text-transform: uppercase; color: var(--gold); margin-bottom: 1.2rem;
}
.footer-links { list-style: none; }
.footer-links li { margin-bottom: 0.6rem; }
.footer-links a {
  font-size: 0.85rem; color: var(--text-muted);
  transition: color 0.2s; display: flex; align-items: center; gap: 8px;
}
.footer-links a:hover { color: var(--gold); }
.footer-links a::before { content: '→'; font-size: 0.7rem; opacity: 0; transform: translateX(-8px); transition: all 0.2s; }
.footer-links a:hover::before { opacity: 1; transform: translateX(0); }
.footer-bottom {
  display: flex; align-items: center; justify-content: space-between;
  flex-wrap: wrap; gap: 1rem;
  font-size: 0.75rem; color: var(--text-muted);
}
.social-links { display: flex; gap: 0.5rem; }
.social-link {
  width: 36px; height: 36px;
  border: 1px solid var(--border); border-radius: 3px;
  display: flex; align-items: center; justify-content: center;
  color: var(--text-muted); font-size: 0.85rem;
}
.social-link:hover { border-color: var(--gold); color: var(--gold); background: rgba(201,168,76,0.06); }
/* Floating WhatsApp */
.floating-wa {
  position: fixed; bottom: 2rem; right: 2rem; z-index: 900;
}
.floating-wa a {
  display: flex; align-items: center; gap: 12px;
  background: #25D366; color: white;
  border-radius: 50px; padding: 14px 24px 14px 14px;
  box-shadow: 0 8px 30px rgba(37,211,102,0.4);
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 0.85rem; font-weight: 700;
  text-transform: uppercase;
  animation: floatPulse 3s ease infinite;
}
.floating-wa .wa-dot {
  width: 46px; height: 46px; background: rgba(255,255,255,0.2);
  border-radius: 50%; display: flex; align-items: center; justify-content: center;
  font-size: 1.4rem;
}
/* Modal */
.modal-overlay {
  position: fixed; inset: 0; z-index: 2000;
  background: rgba(0,0,0,0.92);
  backdrop-filter: blur(10px);
  display: none; align-items: center; justify-content: center;
  padding: 2rem;
}
.modal-overlay.open { display: flex; }
.modal-box {
  background: var(--bg-card);
  border: 1px solid var(--border);
  max-width: 1100px; width: 100%;
  max-height: 90vh; overflow-y: auto;
  border-radius: 4px;
  animation: scaleIn 0.3s ease;
}
.modal-close {
  position: sticky; top: 0; right: 0;
  display: flex; justify-content: flex-end;
  background: var(--bg-card); padding: 1rem 1.5rem;
  border-bottom: 1px solid var(--border);
  z-index: 10;
}
.modal-close button {
  background: none; border: 1px solid var(--border);
  color: var(--text-muted); padding: 6px 16px;
  font-family: 'Barlow Condensed', sans-serif;
  font-size: 0.8rem;
  text-transform: uppercase;
  cursor: pointer;
  border-radius: 2px;
}
.nav-logo img {
    height: 45px;
    width: auto;
    max-height: 45px;
    object-fit: contain;
}
.modal-close button:hover { border-color: var(--gold); color: var(--gold); }
/* Animations */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeInRight {
  from { opacity: 0; transform: translateX(30px); }
  to { opacity: 1; transform: translateX(0); }
}
@keyframes pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.5; transform: scale(0.8); }
}
@keyframes scrollPulse {
  0%, 100% { opacity: 1; transform: scaleY(1); }
  50% { opacity: 0.4; transform: scaleY(0.7); }
}
@keyframes ticker {
  from { transform: translateX(0); }
  to { transform: translateX(-50%); }
}
@keyframes brandScroll {
  from { transform: translateX(0); }
  to { transform: translateX(-50%); }
}
@keyframes floatPulse {
  0%, 100% { box-shadow: 0 8px 30px rgba(37,211,102,0.4); }
  50% { box-shadow: 0 8px 45px rgba(37,211,102,0.6); }
}
@keyframes scaleIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}
.reveal {
  opacity: 0; transform: translateY(30px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
.reveal.visible { opacity: 1; transform: translateY(0); }
/* Responsive */
@media (max-width: 1024px) {
  .featured-layout { grid-template-columns: 1fr; }
  .featured-card-stack { grid-template-columns: 1fr; }
  .footer-top { grid-template-columns: 1fr 1fr; }
  .hero-stats { display: none; }
  .nav-links { display: none; }
}
@media (max-width: 640px) {
  .search-bar { display: none; }
  .footer-top { grid-template-columns: 1fr; }
  .cars-grid { grid-template-columns: 1fr; }
  .cta-strip-inner { flex-direction: column; text-align: center; }
}
</style>
</head>
<body>
<div class="cursor-glow" id="cursorGlow"></div>

<nav class="nav-wrapper" id="mainNav">
  <div class="nav-inner">
  <div class="nav-logo">
    <?php
    $logo_path = getSetting('logo_path');
    if (!empty($logo_path) && file_exists(UPLOAD_DIR . $logo_path)):
    ?>
        <img src="<?php echo UPLOAD_URL . $logo_path; ?>" alt="<?php echo getSetting('site_title'); ?>" style="height: 45px; width: auto;">
    <?php else: ?>
        <!-- No logo uploaded – show nothing or a transparent spacer -->
        <div style="width: 45px;"></div>
    <?php endif; ?>
</div>
    <ul class="nav-links">
      <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"><?php echo t('home'); ?></a></li>
      <li><a href="products.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>"><?php echo t('products'); ?></a></li>
      <li><a href="index.php#inventory"><?php echo t('inventory'); ?></a></li>
      <li><a href="index.php#brands"><?php echo t('brands'); ?></a></li>
      <li><a href="index.php#why-us"><?php echo t('why_us'); ?></a></li>
      <li><a href="index.php#contact"><?php echo t('contact'); ?></a></li>
    </ul>
    <div class="nav-right">
      <div class="search-bar">
        <input type="text" id="navSearch" placeholder="<?php echo t('search_placeholder'); ?>">
        <button><i class="fas fa-search"></i></button>
      </div>
      <button class="lang-btn" id="langEn">EN</button>
      <button class="lang-btn active" id="langFr">FR</button>
      <button class="theme-toggle" id="themeToggle"></button>
      <a href="https://wa.me/<?php echo getSetting('whatsapp_number'); ?>" target="_blank" class="wa-btn-nav">
        <i class="fab fa-whatsapp"></i>
        <span><?php echo t('talk_to_agent'); ?></span>
      </a>
    </div>
  </div>
</nav>

<!-- Hero Slider -->
<section class="hero swiper-container">
  <div class="swiper-wrapper">
    <?php foreach ($hero_slides as $slide): ?>
    <div class="swiper-slide">
      <div class="hero-bg" style="background: linear-gradient(135deg, rgba(10,10,10,0.85), rgba(10,10,10,0.5)), url('<?php echo $slide['image']; ?>') center/cover no-repeat;"></div>
      <div class="hero-noise"></div>
      <div class="hero-content">
        <div class="hero-badge"><span><?php echo t('premier_auto_dealer'); ?></span></div>
        <h1 class="hero-title">
          <span class="outline"><?php echo $lang == 'en' ? 'DRIVE YOUR' : 'CONDUISEZ'; ?></span>
          <span class="accent"><?php echo $lang == 'en' ? $slide['title_en'] : $slide['title_fr']; ?></span>
          <span><?php echo $lang == 'en' ? 'MACHINE' : 'MACHINE'; ?></span>
        </h1>
        <p class="hero-sub"><?php echo $lang == 'en' ? $slide['subtitle_en'] : $slide['subtitle_fr']; ?></p>
        <div class="hero-cta">
          <a href="<?php echo $slide['btn_link']; ?>" class="btn-primary-hero">
            <i class="fas fa-car"></i> <?php echo $lang == 'en' ? $slide['btn_text_en'] : $slide['btn_text_fr']; ?>
          </a>
          <a href="https://wa.me/<?php echo getSetting('whatsapp_number'); ?>" target="_blank" class="btn-ghost-hero">
            <i class="fab fa-whatsapp"></i> <span><?php echo t('talk_to_agent'); ?></span>
          </a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="swiper-pagination"></div>
  <div class="swiper-button-next"></div>
  <div class="swiper-button-prev"></div>
</section>

<div class="ticker">
  <div class="ticker-inner">
    <span class="ticker-item">BMW Series 5</span>
    <span class="ticker-item">Mercedes GLE</span>
    <span class="ticker-item">Toyota Land Cruiser</span>
    <span class="ticker-item">Audi A6</span>
    <span class="ticker-item">Range Rover Sport</span>
    <span class="ticker-item">Porsche Cayenne</span>
    <span class="ticker-item">Lexus LX 570</span>
    <span class="ticker-item">Ford Mustang</span>
    <span class="ticker-item">IBMARKETTE Premium Cars</span>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
const swiper = new Swiper('.swiper-container', {
    loop: true,
    autoplay: { delay: 5000, disableOnInteraction: false },
    pagination: { el: '.swiper-pagination', clickable: true },
    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    effect: 'fade',
    fadeEffect: { crossFade: true }
});
</script>