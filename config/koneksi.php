<?php
<<<<<<< HEAD
/**
 * KONEKSI DATABASE
 * File: config/koneksi.php
 * Gunakan file ini di semua halaman dengan: include '../config/koneksi.php';
 */

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

/**
 * FUNGSI HELPER
 */

// Escape string untuk keamanan
function clean($conn, $string) {
    return $conn->real_escape_string(trim($string));
}

// Format harga ke rupiah
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Generate kode pesanan unik
function generateKodePesanan() {
    return 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
}

// Cek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['id_pelanggan']) && !empty($_SESSION['id_pelanggan']);
}

// Redirect jika belum login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../loginuser.php');
        exit();
    }
}
=======
$host = "localhost";
$user = "root";
$pass = "";
$db = "rameza_database";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn){
    die("Koneksi ke database gagal: ".mysqli_connect_error());
}
?>
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
