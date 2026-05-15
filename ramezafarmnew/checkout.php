<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include 'config/koneksi.php';

// Proteksi: Harus login dan keranjang tidak boleh kosong
if (!isset($_SESSION['id_pelanggan']) || empty($_SESSION['keranjang'])) {
    header("Location: userdash.php");
    exit();
}

$id_user = $_SESSION['id_pelanggan'];

// Ambil data user untuk ditampilkan
$ambil_user = mysqli_query($conn, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_user'");
$user = mysqli_fetch_assoc($ambil_user);

// Menghitung total harga dari session keranjang
$total_akhir = 0;
foreach ($_SESSION['keranjang'] as $id_produk => $jumlah) {
    $ambil_produk = mysqli_query($conn, "SELECT harga FROM produk WHERE id_produk = '$id_produk'");
    $data_produk = mysqli_fetch_assoc($ambil_produk);
    $subtotal = $data_produk['harga'] * $jumlah;
    $total_akhir += $subtotal;
}

// Jika tombol Konfirmasi ditekan
if (isset($_POST['konfirmasi'])) {
    $tanggal = date("Y-m-d H:i:s");
    $status = "Pending";

    // 1. Simpan ke tabel pesanan (sesuai gambar tabelmu)
    $query_pesanan = "INSERT INTO pesanan (id_pelanggan, total_harga, status, tanggal_pesan) 
                      VALUES ('$id_user', '$total_akhir', '$status', '$tanggal')";
    
    if (mysqli_query($conn, $query_pesanan)) {
        $id_pesanan_baru = mysqli_insert_id($conn);

        // 2. Simpan rincian ke pesanan_detail (biar admin tahu produk apa saja yang dibeli)
        foreach ($_SESSION['keranjang'] as $id_produk => $jumlah) {
            $ambil_p = mysqli_query($conn, "SELECT harga FROM produk WHERE id_produk = '$id_produk'");
            $dp = mysqli_fetch_assoc($ambil_p);
            $sub = $dp['harga'] * $jumlah;

            mysqli_query($conn, "INSERT INTO detail_pesanan (id_pesanan, id_produk, jumlah, harga_saat_beli) 
                                 VALUES ('$id_pesanan_baru', '$id_produk', '$jumlah', '$sub')");
        }

        // 3. Kosongkan keranjang
        unset($_SESSION['keranjang']);

        echo "<script>alert('Pesanan Anda telah dikirim ke Admin!'); window.location='riwayat_pesanan.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Rameza Egg Farm</title>
    <link rel="stylesheet" href="assets/css/userdash.css">
</head>
<body>
<div class="container">
    <h2>Konfirmasi Checkout</h2>
    <div class="info-user">
        <p><strong>Nama:</strong> <?= $user['username']; ?></p>
        <p><strong>No. Telp:</strong> <?= $user['noTelp']; ?></p>
        <p><strong>Total Bayar:</strong> Rp <?= number_format($total_akhir); ?></p>
    </div>

    <form method="POST">
        <p>Pastikan pesanan Anda sudah benar sebelum menekan tombol konfirmasi.</p>
        <button type="submit" name="konfirmasi" class="btn">Konfirmasi Pesanan</button>
        <a href="keranjang.php" class="btn-link">Batal</a>
    </form>
</div>
</body>
</html>