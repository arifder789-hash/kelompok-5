<?php
/**
 * KONSTANTA GLOBAL
 * File: config/constants.php
 * Definisi konstanta yang digunakan di seluruh project
 */

// URL & Path
define('BASE_URL', 'http://localhost/ramezafarmnew/');
define('ASSETS_URL', BASE_URL . 'assets/');
define('UPLOAD_PATH', $_SERVER['DOCUMENT_ROOT'] . '/ramezafarmnew/uploads/');

// Informasi Toko
define('NAMA_TOKO', 'Rameza Egg Farm');
define('TAGLINE', 'Peternak Ayam Petelur Terpercaya');
define('ALAMAT_TOKO', 'Jl. Peternakan No. 123, Jember, Jawa Timur');
define('NO_WA', '081234567890');
define('EMAIL_TOKO', 'info@ramezafarm.com');

// Pengaturan Pesanan
define('ONGKIR_DALAM_KOTA', 15000);
define('ONGKIR_LUAR_KOTA', 25000);
define('MIN_BELANJA_GRATIS_ONGKIR', 500000);

// Status Pesanan
define('STATUS_PENDING', 'pending');
define('STATUS_DIKONFIRMASI', 'dikonfirmasi');
define('STATUS_DIPROSES', 'diproses');
define('STATUS_DIKIRIM', 'dikirim');
define('STATUS_SELESAI', 'selesai');
define('STATUS_DIBATALKAN', 'dibatalkan');

// Upload Setting
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB

// Kategori Produk
define('KATEGORI_PRODUK', [
    'telur' => 'Telur',
    'bibit' => 'Bibit Ayam',
    'pakan' => 'Pakan Ternak',
    'obat' => 'Obat & Vitamin',
    'lainnya' => 'Lainnya'
]);
