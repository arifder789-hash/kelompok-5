<?php
session_start();
include 'config/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../loginuser.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Keranjang - Rameza Egg Farm</title>
    <link rel="stylesheet" href="assets/css/userdash.css">
</head>
<body>

<?php include 'includes/navuser.php'; ?>

<div class="container">
    <h2>Keranjang Belanja</h2>

    <table class="table-cart">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_belanja = 0;
            if (!empty($_SESSION['keranjang'])): 
                foreach ($_SESSION['keranjang'] as $id_produk => $jumlah):
                    $id_produk = (int) $id_produk;
                    $ambil = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = $id_produk");
                    $pecah = mysqli_fetch_assoc($ambil);
                    if (!$pecah) continue; // skip jika produk tidak ditemukan
                    $subtotal = $pecah['harga'] * $jumlah;
                    $total_belanja += $subtotal;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($pecah['nama_produk']); ?></td>
                <td>Rp <?php echo number_format($pecah['harga']); ?></td>
                <td><?php echo $jumlah; ?></td>
                <td>Rp <?php echo number_format($subtotal); ?></td>
                <td>
                    <a href="hapus_keranjang.php?id=<?php echo $id_produk; ?>" 
                       onclick="return confirm('Hapus produk ini?')">
                       Hapus
                    </a>
                </td>
            </tr>
            <?php endforeach; 
            else: ?>
            <tr>
                <td colspan="5">Keranjang kosong. <a href="userdash.php">Belanja sekarang?</a></td>
            </tr>
            <?php endif; ?>
        </tbody>

        <?php if (!empty($_SESSION['keranjang'])): ?>
        <tfoot>
            <tr style="font-weight: bold; background: #f9f9f9;">
                <td colspan="4" style="text-align: right; padding-right: 30px;">Total Pembayaran:</td>
                <td>Rp <?php echo number_format($total_belanja); ?></td>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <a href="userdash.php" class="btn btn-secondary">Kembali ke Katalog</a>
        <?php if (!empty($_SESSION['keranjang'])): ?>
            <a href="checkout.php" class="btn">Lanjutkan Checkout</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>