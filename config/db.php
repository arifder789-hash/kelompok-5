<?php
/**
 * KONEKSI DATABASE
 * File: config/koneksi.php
 * Gunakan file ini di semua halaman dengan: include '../config/koneksi.php';
 */
require_once __DIR__ . '/constants.php';

// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'rameza_database');

// Koneksi ke database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set charset ke UTF-8
$conn->set_charset("utf8mb4");

// Koneksi PDO (digunakan oleh proses_checkout.php dll)
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Koneksi PDO gagal: " . $e->getMessage());
}

/**
 * FUNGSI HELPER
 */

// Escape string untuk keamanan
function clean($conn, $string)
{
    return $conn->real_escape_string(trim($string));
}

// Format harga ke rupiah
function formatRupiah($angka)
{
    return 'Rp' . number_format($angka, 0, ',', '.');
}

// Generate kode pesanan unik
function generateKodePesanan()
{
    return 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
}

// Cek apakah user sudah login
function isLoggedIn()
{
    return isset($_SESSION['id_pelanggan']) && !empty($_SESSION['id_pelanggan']);
}

// Redirect jika belum login
function requireLogin()
{
    if (!isLoggedIn()) {
        header('Location: ../loginuser.php');
        exit();
    }
}
