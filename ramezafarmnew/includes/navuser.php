<!-- navbar.php -->
<div class="navbar">
    <div class="logo">RAMEZA EGG FARM</div>
    <div class="menu">
        <a href="userdash.php">Katalog Produk</a>
        <a href="keranjanguser.php">
            Keranjang (<?php echo isset($_SESSION['keranjang']) ? count($_SESSION['keranjang']) : 0; ?>)
        </a>
        <a href="riwayat_pesanan.php">Riwayat</a>
        <a href="logout.php" style="color: #ff9999;">Keluar</a>
    </div>
</div>