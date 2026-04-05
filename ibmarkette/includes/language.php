<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'fr'; // French default
}
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'fr'])) {
    $_SESSION['lang'] = $_GET['lang'];
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}
$lang = $_SESSION['lang'];

$translations = [
    // Navigation
    'home' => ['en' => 'Home', 'fr' => 'Accueil'],
    'inventory' => ['en' => 'Inventory', 'fr' => 'Inventaire'],
    'brands' => ['en' => 'Brands', 'fr' => 'Marques'],
    'why_us' => ['en' => 'Why Us', 'fr' => 'Pourquoi Nous'],
    'contact' => ['en' => 'Contact', 'fr' => 'Contact'],
    'products' => ['en' => 'Products', 'fr' => 'Produits'],
    'talk_to_agent' => ['en' => 'Talk to an Agent', 'fr' => 'Parler à un Agent'],
    'search_placeholder' => ['en' => 'Search cars...', 'fr' => 'Rechercher...'],
    // Hero
    'premier_auto_dealer' => ['en' => 'Premier Auto Dealer — Established 2021', 'fr' => 'Concessionnaire Premier — Depuis 2021'],
    'browse_inventory' => ['en' => 'Browse Inventory', 'fr' => 'Voir l\'Inventaire'],
    'starting_at' => ['en' => 'Starting at', 'fr' => 'À partir de'],
    'view_details' => ['en' => 'VIEW DETAILS', 'fr' => 'VOIR DÉTAILS'],
    'view' => ['en' => 'View', 'fr' => 'Voir'],
    // Filters
    'all_cars' => ['en' => 'All Cars', 'fr' => 'Toutes'],
    'suv' => ['en' => 'SUV', 'fr' => 'SUV'],
    'sedan' => ['en' => 'Sedan', 'fr' => 'Berline'],
    'sports' => ['en' => 'Sports', 'fr' => 'Sport'],
    'luxury' => ['en' => 'Luxury', 'fr' => 'Luxe'],
    'trucks' => ['en' => 'Trucks', 'fr' => 'Camions'],
    // Car card
    'available' => ['en' => 'Available', 'fr' => 'Disponible'],
    'price' => ['en' => 'Price', 'fr' => 'Prix'],
    'details' => ['en' => 'Details', 'fr' => 'Détails'],
    'load_more' => ['en' => 'Load More Vehicles', 'fr' => 'Voir Plus de Véhicules'],
    // Brands
    'trusted_brands' => ['en' => 'Trusted Brands', 'fr' => 'Marques de Confiance'],
    'we_carry_all_major' => ['en' => 'WE CARRY ALL MAJOR', 'fr' => 'NOUS PORTONS TOUTES'],
    'brands_desc' => ['en' => 'From iconic European luxury to reliable Asian performance — IBMARKETTE stocks every major auto brand.', 'fr' => 'Des marques européennes de luxe aux performants asiatiques — IBMARKETTE stock toutes les grandes marques automobiles.'],
    // Why us
    'why_choose_us' => ['en' => 'Why Choose Us', 'fr' => 'Pourquoi Nous Choisir'],
    'advantage_title' => ['en' => 'THE IBMARKETTE', 'fr' => 'L\'AVANTAGE'],
    'advantage_accent' => ['en' => 'ADVANTAGE', 'fr' => 'IBMARKETTE'],
    'verified_stock_title' => ['en' => '100% Verified Stock', 'fr' => 'Stock 100% Vérifié'],
    'verified_stock_desc' => ['en' => 'Every vehicle undergoes rigorous multi-point inspection and comes with full documentation guarantee.', 'fr' => 'Chaque véhicule subit une inspection rigoureuse et est accompagné d\'une garantie documentaire complète.'],
    'expert_negotiation_title' => ['en' => 'Expert Negotiation', 'fr' => 'Négociation Experte'],
    'expert_negotiation_desc' => ['en' => 'Our agents negotiate the best deals so you never overpay. Transparent pricing, no hidden fees.', 'fr' => 'Nos agents négocient les meilleures offres. Tarification transparente, sans frais cachés.'],
    'fast_processing_title' => ['en' => 'Fast Processing', 'fr' => 'Traitement Rapide'],
    'fast_processing_desc' => ['en' => 'Complete your car purchase in as little as 24 hours. We handle all paperwork and formalities.', 'fr' => 'Finalisez votre achat en seulement 24 heures. Nous gérons tous les documents et formalités.'],
    'live_whatsapp_title' => ['en' => 'Live WhatsApp Support', 'fr' => 'Support WhatsApp en Direct'],
    'live_whatsapp_desc' => ['en' => 'Real agents, real answers. Message us anytime and get instant responses from our car experts.', 'fr' => 'De vrais agents, de vraies réponses. Écrivez-nous et obtenez des réponses instantanées de nos experts.'],
    // CTA
    'ready_to_find' => ['en' => 'Ready to find your', 'fr' => 'Prêt à trouver votre'],
    'perfect_car' => ['en' => 'PERFECT CAR?', 'fr' => 'VOITURE PARFAITE?'],
    'cta_subtitle' => ['en' => 'Our agents are available now. Contact us on WhatsApp for instant assistance.', 'fr' => 'Nos agents sont disponibles maintenant. Contactez-nous sur WhatsApp pour une assistance instantanée.'],
    'click_to_chat' => ['en' => 'CLICK TO CHAT', 'fr' => 'CLIQUEZ POUR CHATTER'],
    // Footer
    'quick_links' => ['en' => 'Quick Links', 'fr' => 'Liens Rapides'],
    'categories' => ['en' => 'Categories', 'fr' => 'Catégories'],
    'contact_us' => ['en' => 'Contact Us', 'fr' => 'Contactez-Nous'],
    'visit_showroom' => ['en' => 'Visit Showroom', 'fr' => 'Visiter la Salle d\'Exposition'],
    'all_rights' => ['en' => 'All rights reserved.', 'fr' => 'Tous droits réservés.'],
    'premium_vehicles' => ['en' => 'Premium Pre-Owned Vehicles', 'fr' => 'Véhicules d\'Occasion Premium'],
    // Modal
    'close' => ['en' => 'CLOSE', 'fr' => 'FERMER'],
    'mileage' => ['en' => 'Mileage', 'fr' => 'Kilométrage'],
    'fuel_type' => ['en' => 'Fuel Type', 'fr' => 'Carburant'],
    'engine' => ['en' => 'Engine', 'fr' => 'Moteur'],
    'color' => ['en' => 'Color', 'fr' => 'Couleur'],
    'power' => ['en' => 'Power', 'fr' => 'Puissance'],
    'transmission' => ['en' => 'Transmission', 'fr' => 'Transmission'],
    'asking_price' => ['en' => 'Asking Price', 'fr' => 'Prix Demandé'],
    'negotiable' => ['en' => 'Negotiable', 'fr' => 'Négociable'],
    // Products page
    'our_inventory' => ['en' => 'Our Inventory', 'fr' => 'Notre Inventaire'],
    'find_your_perfect_car' => ['en' => 'Find Your Perfect Car', 'fr' => 'Trouvez Votre Voiture Parfaite'],
    'all_brands' => ['en' => 'All Brands', 'fr' => 'Toutes Marques'],
    'year_min' => ['en' => 'Min Year', 'fr' => 'Année Min'],
    'year_max' => ['en' => 'Max Year', 'fr' => 'Année Max'],
    'price_min' => ['en' => 'Min Price', 'fr' => 'Prix Min'],
    'price_max' => ['en' => 'Max Price', 'fr' => 'Prix Max'],
    'filter' => ['en' => 'Filter', 'fr' => 'Filtrer'],
    'reset' => ['en' => 'Reset', 'fr' => 'Réinitialiser'],
    'cars_found' => ['en' => 'cars found', 'fr' => 'voitures trouvées'],
    'no_cars_found' => ['en' => 'No cars found', 'fr' => 'Aucune voiture trouvée'],
    // Legal
    'privacy_policy' => ['en' => 'Privacy Policy', 'fr' => 'Politique de Confidentialité'],
    'terms_of_service' => ['en' => 'Terms of Service', 'fr' => 'Conditions d\'Utilisation'],
    'page_not_found' => ['en' => 'Page Not Found', 'fr' => 'Page Non Trouvée'],
    'page_not_found_text' => ['en' => 'The page you are looking for does not exist.', 'fr' => 'La page que vous recherchez n\'existe pas.'],
    'back_home' => ['en' => 'Go Home', 'fr' => 'Retour à l\'Accueil'],
];

function t($key) {
    global $translations, $lang;
    return $translations[$key][$lang] ?? $translations[$key]['en'] ?? $key;
}
?>