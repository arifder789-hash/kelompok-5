<?php
/**
 * Rameza Egg Farm - Configuration Constants
 * Central configuration file for the entire application
 */

// ============================================================
// SITE INFORMATION
// ============================================================
define('SITE_NAME', 'Rameza Egg Farm');
define('SITE_URL', 'http://localhost'); // Change for production
define('SITE_DESCRIPTION', 'Peternakan ayam petelur berkualitas');

// ============================================================
// PATHS
// ============================================================
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('ASSETS_PATH', ROOT_PATH . '/assets');
define('CSS_PATH', ASSETS_PATH . '/css');
define('JS_PATH', ASSETS_PATH . '/js');
define('IMG_PATH', ASSETS_PATH . '/images');
define('INCLUDES_PATH', ROOT_PATH . '/includes');

// ============================================================
// RELATIVE PATHS (for use in HTML/CSS)
// ============================================================
define('ASSETS_URL', 'assets');
define('CSS_URL', 'assets/css');
define('JS_URL', 'assets/js');
define('IMG_URL', 'assets/images');

// ============================================================
// DATABASE CONFIGURATION
// ============================================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'rameza_farm');

// ============================================================
// PRODUCT DATA
// ============================================================
$PRODUCTS = [
    'bibit-ayam' => [
        'name' => 'Bibit Ayam',
        'slug' => 'bibit-ayam',
        'category' => 'Bibit Unggas',
        'description' => 'Ayam petelur sehat siap produksi. Cocok untuk peternak pemula maupun skala usaha.',
        'image' => 'assets/images/bibit-ayam.jpg',
        'price' => 'Hubungi kami'
    ],
    'ayam-petelur' => [
        'name' => 'Ayam Petelur Afkir',
        'slug' => 'ayam-petelur',
        'category' => 'Ayam Afkir',
        'description' => 'Ayam petelur afkir dengan kondisi sehat dan masih layak jual untuk kebutuhan ternak maupun konsumsi.',
        'image' => 'assets/images/ayam-petelur-afkir.jpg',
        'price' => 'Hubungi kami'
    ],
    'telur-ayam' => [
        'name' => 'Telur Ayam Segar',
        'slug' => 'telur-ayam',
        'category' => 'Produk Utama',
        'description' => 'Telur ayam segar berkualitas premium langsung dari kandang kami.',
        'image' => 'assets/images/telur-ayam.jpg',
        'price' => 'Hubungi kami'
    ]
];

// ============================================================
// CONTACT INFORMATION
// ============================================================
define('CONTACT_LOCATION', 'Jember, Indonesia');
define('CONTACT_PHONE', '08xxxxxxxxxx');
define('CONTACT_HOURS', '07.00 - 16.00 WIB');
define('CONTACT_EMAIL', 'info@rameza-farm.com');

// ============================================================
// MAPS EMBED
// ============================================================
define('MAPS_EMBED_URL', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.5759078943884!2d113.74940887532571!3d-7.834627892186456!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd6e1000a5f7219%3A0xc02d2c91b9467ee!2sKandang%20Ayam%20Petelur%20(RAMEZA%20FARM)!5e0!3m2!1sen!2sus!4v1771948692631!5m2!1sen!2sus');

// ============================================================
// ERROR HANDLING
// ============================================================
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Set appropriate headers
header('Content-Type: text/html; charset=utf-8');
