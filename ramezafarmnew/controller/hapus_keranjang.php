<?php
session_start();

// 1. Ambil ID produk dari URL
if (isset($_GET['id'])) {
    $id_produk = (int)$_GET['id']; // Cast ke integer untuk keamanan data

    // 2. Cek apakah produk tersebut memang ada di dalam keranjang session
    if (isset($_SESSION['keranjang'][$id_produk])) {
        // Hapus produk spesifik dari array keranjang
        unset($_SESSION['keranjang'][$id_produk]);
    }
}

// 3. Kembalikan pengguna ke halaman keranjang setelah menghapus
// Jalur keluar dari folder 'controller' lalu masuk ke 'pages/keranjanguser.php'
header("Location: ../pages/keranjanguser.php");
exit();