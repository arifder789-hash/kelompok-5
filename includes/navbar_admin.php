<?php
$current_page = basename($_SERVER['PHP_SELF']);
$scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$rootPath = preg_match('~/(pages|controller|admin)$~', '/' . $scriptDir) ? '../' : '';
?>
<link rel="stylesheet" href="<?= $rootPath ?>assets/css/navbar_admin.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="sidebar-admin">
    <div class="logo">RAMEZA ADMIN</div>
    <div class="sidebar-nav-links">
        <a href="<?= $rootPath ?>admin/admin_dashboard.php" class="<?= ($current_page == 'admin_dashboard.php') ? 'active' : '' ?>">Beranda</a>
        <a href="<?= $rootPath ?>controller/admin_pesanan.php" class="<?= ($current_page == 'admin_pesanan.php') ? 'active' : '' ?>">Pesanan Pelanggan</a>
        <a href="<?= $rootPath ?>pages/layanan_kontak.php" class="<?= ($current_page == 'layanan_kontak.php') ? 'active' : '' ?>">Layanan Kontak</a>
        <a href="<?= $rootPath ?>logout_admin.php" class="logout-btn" onclick="confirmLogout(event, this.href)">Logout</a>
    </div>
</div>
<script>
function confirmLogout(event, url) {
    event.preventDefault();
    Swal.fire({
        title: 'Yakin ingin keluar?',
        text: "Anda akan mengakhiri sesi admin ini.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Keluar',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>