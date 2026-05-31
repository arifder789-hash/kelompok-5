<?php
require_once __DIR__ . '/cart_helper.php';
$currentPage = basename($_SERVER['PHP_SELF']);
$scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$rootPath = preg_match('~/(pages|controller|admin)$~', '/' . $scriptDir) ? '../' : '';

function nav_active(string $page, string $currentPage): string
{
  return $page === $currentPage ? ' active' : '';
}
?>
<nav class="navbar" id="navbar" role="navigation" aria-label="Navigasi utama">
  <div class="navbar-brand">
    <a href="<?= $rootPath ?>pages/beranda.php" style="display: flex; align-items: center;">
      <img src="<?= $rootPath ?>assets/img/logo_ayam.png" class="brand-logo" alt="Logo Rameza Farm">
    </a>
    <a href="<?= $rootPath ?>login_admin.php" style="color: inherit; text-decoration: none;">
      Rameza Farm
    </a>
  </div>

  <ul class="navbar-links" role="list">
    <li><a href="<?= $rootPath ?>pages/beranda.php" class="<?= nav_active('beranda.php', $currentPage) ?>">Beranda</a></li>
    <li><a href="<?= $rootPath ?>pages/tentang.php" class="<?= nav_active('tentang.php', $currentPage) ?>">Tentang</a></li>
    <li><a href="<?= $rootPath ?>pages/produk.php" class="<?= nav_active('produk.php', $currentPage) ?>">Produk</a></li>
    <li><a href="<?= $rootPath ?>pages/kontak.php" class="<?= nav_active('kontak.php', $currentPage) ?>">Kontak</a></li>
  </ul>

  <div class="navbar-right" style="display:flex; align-items:center; gap:12px;">
    <?php if (isset($_SESSION['id_pelanggan'])): ?>
      <div class="user-dropdown" style="position: relative;">
        <button onclick="document.getElementById('user-menu').classList.toggle('show-menu')" class="nav-btn" style="display: flex; align-items: center; background: rgba(255,255,255,0.15); color: #fff; padding: 8px 16px; border-radius: 99px; text-decoration: none; font-weight: 600; font-size: 14px; border: none; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.25)'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.15)'">
          <span class="nav-icon">👤</span> <span class="nav-text">Halo, <?= htmlspecialchars($_SESSION['nama'] ?? 'Pengguna') ?></span>
        </button>
        <div id="user-menu" style="display: none; position: absolute; top: 100%; right: 0; margin-top: 8px; background: white; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); min-width: 200px; overflow: hidden; z-index: 1000;">
          <a href="<?= $rootPath ?>pages/pesanan_pelanggan.php" style="display: flex; align-items: center; padding: 12px 16px; color: #374151; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='transparent'">
            <span style="margin-right: 8px;">📦</span> Pesanan Saya
          </a>
          <a href="<?= $rootPath ?>pages/checkout.php" class="cart-btn" style="display: flex; align-items: center; padding: 12px 16px; color: #374151; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='transparent'">
            <span style="margin-right: 8px;">🛒</span> Keranjang
            <?php $cCount = cartCount(); if($cCount > 0): ?>
              <span style="background: #fcd34d; color: #1e3fa8; padding: 2px 8px; border-radius: 99px; font-size: 12px; font-weight: 800; margin-left: auto;"><?= $cCount ?></span>
            <?php endif; ?>
          </a>
          <a href="<?= $rootPath ?>logout.php" style="display: flex; align-items: center; padding: 12px 16px; color: #ef4444; text-decoration: none; border-top: 1px solid #e5e7eb; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#fef2f2'" onmouseout="this.style.backgroundColor='transparent'">
            <span style="margin-right: 8px;">🚪</span> Keluar
          </a>
        </div>
      </div>
      <style>
        .show-menu { display: block !important; }
      </style>
      <script>
        window.addEventListener('click', function(e) {
          if (!e.target.closest('.user-dropdown')) {
            var menu = document.getElementById('user-menu');
            if (menu && menu.classList.contains('show-menu')) {
              menu.classList.remove('show-menu');
            }
          }
        });
      </script>
    <?php else: ?>
      <a href="<?= $rootPath ?>pages/checkout.php" class="cart-btn nav-btn" id="cart-btn" style="display: flex; align-items: center; background: rgba(255,255,255,0.15); color: #fff; padding: 8px 16px; border-radius: 99px; text-decoration: none; font-weight: 600; font-size: 14px; transition: background 0.2s;">
        <span class="nav-icon">🛒</span> <span class="nav-text">Keranjang</span>
        <?php $cCount = cartCount(); ?>
        <span class="cart-badge" style="background: #fcd34d; color: #1e3fa8; padding: 2px 8px; border-radius: 99px; font-size: 12px; font-weight: 800; margin-left: 8px; <?= $cCount > 0 ? '' : 'display: none;' ?>"><?= $cCount ?></span>
      </a>
      <a href="<?= $rootPath ?>loginuser.php" class="nav-btn" style="display: flex; align-items: center; background: rgba(255,255,255,0.15); color: #fff; padding: 8px 16px; border-radius: 99px; text-decoration: none; font-weight: 600; font-size: 14px; transition: background 0.2s;" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.25)'" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.15)'">
        <span class="nav-icon">👤</span> <span class="nav-text">Masuk</span>
      </a>
    <?php endif; ?>
    
    <button class="navbar-hamburger" id="hamburger" type="button" aria-label="Toggle menu" aria-expanded="false" aria-controls="mobile-menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<nav class="mobile-menu" id="mobile-menu" aria-label="Navigasi mobile">
  <a href="<?= $rootPath ?>pages/beranda.php" class="<?= nav_active('beranda.php', $currentPage) ?>">Beranda</a>
  <a href="<?= $rootPath ?>pages/tentang.php" class="<?= nav_active('tentang.php', $currentPage) ?>">Tentang</a>
  <a href="<?= $rootPath ?>pages/produk.php" class="<?= nav_active('produk.php', $currentPage) ?>">Produk</a>
  <a href="<?= $rootPath ?>pages/kontak.php" class="<?= nav_active('kontak.php', $currentPage) ?>">Kontak</a>
  <?php if (isset($_SESSION['id_pelanggan'])): ?>
    <div style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 5px; padding-top: 15px; color: #fcd34d; font-weight: bold; padding-left: 1rem; margin-bottom: 5px;">Halo, <?= htmlspecialchars($_SESSION['nama'] ?? 'Pengguna') ?></div>
    <a href="<?= $rootPath ?>pages/pesanan_pelanggan.php" class="<?= nav_active('pesanan_pelanggan.php', $currentPage) ?>">📦 Pesanan Saya</a>
    <a href="<?= $rootPath ?>pages/checkout.php" class="cart-btn <?= nav_active('checkout.php', $currentPage) ?>">🛒 Keranjang</a>
    <a href="<?= $rootPath ?>logout.php" style="color: #fca5a5;">🚪 Keluar</a>
  <?php else: ?>
    <a href="<?= $rootPath ?>pages/checkout.php" class="cart-btn <?= nav_active('checkout.php', $currentPage) ?>" style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 5px; padding-top: 15px;">🛒 Keranjang</a>
    <a href="<?= $rootPath ?>loginuser.php">👤 Masuk</a>
  <?php endif; ?>
</nav>