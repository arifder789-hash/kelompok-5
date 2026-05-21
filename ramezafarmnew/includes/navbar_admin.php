<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<link rel="stylesheet" href="../assets/css/navbar_admin.css">
<div class="sidebar-admin">
    <div class="logo">RAMEZA ADMIN</div>
    <div class="sidebar-nav-links">
        <a href="../admin/admin_dashboard.php" class="<?= ($current_page == 'admin_dashboard.php') ? 'active' : '' ?>">Beranda</a>
        <a href="../controller/admin_pesanan.php" class="<?= ($current_page == 'admin_pesanan.php') ? 'active' : '' ?>">Pesanan Pelanggan</a>
        <a href="../pages/layanan_kontak.php" class="<?= ($current_page == 'layanan_kontak.php') ? 'active' : '' ?>">Layanan Kontak</a>
        <a href="logout_admin.php" class="logout-btn" onclick="return confirm('Yakin ingin keluar?')">Logout</a>
    </div>
</div>