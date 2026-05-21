<?php
session_start();
include '../config/koneksi.php';

// Validasi: harus ada id_produk di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: userdash.php");
    exit();
}

$id_produk = (int) $_GET['id']; // cast ke integer untuk keamanan

// Jika keranjang belum ada, buat array baru
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = array();
}

// Cek apakah produk valid di database
$cek = mysqli_query($conn, "SELECT id_produk FROM produk WHERE id_produk = $id_produk");
if (mysqli_num_rows($cek) === 0) {
    header("Location: userdash.php");
    exit();
}

// Tambah atau inisialisasi jumlah produk di keranjang
if (isset($_SESSION['keranjang'][$id_produk])) {
    $_SESSION['keranjang'][$id_produk] += 1;
} else {
    $_SESSION['keranjang'][$id_produk] = 1;
}

// Redirect ke halaman keranjang
header("Location: keranjanguser.php");
exit();
?>