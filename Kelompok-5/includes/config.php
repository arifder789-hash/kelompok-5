<?php
/**
 * Configuration File - Rameza Farm
 * Konfigurasi global untuk aplikasi
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'rameza_farm');

// Site Configuration
define('SITE_NAME', 'Rameza Farm');
define('SITE_SLOGAN', 'Peternakan Unggas Berkualitas');
define('SITE_URL', 'http://localhost/ramezafarmnew');
define('SITE_SINCE', 2015);

// Paths
define('ASSETS_PATH', SITE_URL . '/assets');
define('CSS_PATH', ASSETS_PATH . '/css');
define('JS_PATH', ASSETS_PATH . '/js');
define('IMG_PATH', ASSETS_PATH . '/img');
define('UPLOADS_PATH', SITE_URL . '/uploads');

// Color Scheme
$colors = [
    'primary' => '#061b0e',
    'secondary' => '#9a4605',
    'tertiary' => '#171814',
    'surface' => '#fff8f3',
    'on-surface' => '#211b12',
    'error' => '#ba1a1a',
    'background' => '#fff8f3',
];

// Product Categories
$products = [
    'telur' => ['name' => 'Telur Segar', 'icon' => 'egg'],
    'bibit' => ['name' => 'Bibit Unggas', 'icon' => 'chick'],
    'pakan' => ['name' => 'Pakan Premium', 'icon' => 'feed'],
    'vitamin' => ['name' => 'Vitamin & Obat', 'icon' => 'medicine'],
];

// Page Configuration
$pages = [
    'beranda' => ['title' => 'Beranda', 'file' => 'beranda.php'],
    'tentang' => ['title' => 'Tentang Kami', 'file' => 'tentang.php'],
    'produk' => ['title' => 'Produk', 'file' => 'detailproduk.php'],
    'kontak' => ['title' => 'Kontak', 'file' => 'beranda.php#kontak'],
];

// Navigation Items
$nav_items = [
    ['url' => '/', 'label' => 'Beranda', 'icon' => 'home'],
    ['url' => '/produk', 'label' => 'Produk', 'icon' => 'shopping_bag'],
    ['url' => '/tentang', 'label' => 'Tentang Kami', 'icon' => 'info'],
    ['url' => '/kontak', 'label' => 'Kontak', 'icon' => 'mail'],
];

// Social Media Links
$social_links = [
    'facebook' => 'https://facebook.com/ramezafarm',
    'instagram' => 'https://instagram.com/ramezafarm',
    'whatsapp' => 'https://wa.me/62812345678',
    'email' => 'info@ramezafarm.com',
];

// Company Contact Info
$company_info = [
    'phone' => '(0334) XXXXXX',
    'email' => 'info@ramezafarm.com',
    'whatsapp' => '+62 812 345 678',
    'address' => 'Situbondo, Jawa Timur, Indonesia',
];
?>
