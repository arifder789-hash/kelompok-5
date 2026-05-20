<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$rootPath = preg_match('~/(pages|controller|admin)$~', '/' . $scriptDir) ? '../' : '';

function nav_active(string $page, string $currentPage): string
{
  return $page === $currentPage ? ' active' : '';
}
?>
<nav class="navbar" id="navbar" role="navigation" aria-label="Navigasi utama">
  <a href="<?= $rootPath ?>login_admin.php" class="navbar-brand">
  <img src="<?= $rootPath ?>assets/img/logo_ayam.png" class="brand-logo">
    Rameza Farm
  </a>

  <ul class="navbar-links" role="list">
    <li><a href="<?= $rootPath ?>beranda.php" class="<?= nav_active('beranda.php', $currentPage) ?>">Beranda</a></li>
    <li><a href="<?= $rootPath ?>pages/tentang.php" class="<?= nav_active('tentang.php', $currentPage) ?>">Tentang</a></li>
    <li><a href="<?= $rootPath ?>pages/produk.php" class="<?= nav_active('produk.php', $currentPage) ?>">Produk</a></li>
    <li><a href="<?= $rootPath ?>beranda.php#kontak">Kontak</a></li>
  </ul>

  <button class="navbar-hamburger" id="hamburger" type="button" aria-label="Toggle menu" aria-expanded="false" aria-controls="mobile-menu">
    <span></span><span></span><span></span>
  </button>
</nav>

<nav class="mobile-menu" id="mobile-menu" aria-label="Navigasi mobile">
  <a href="<?= $rootPath ?>beranda.php" class="<?= nav_active('beranda.php', $currentPage) ?>">Beranda</a>
  <a href="<?= $rootPath ?>pages/tentang.php" class="<?= nav_active('tentang.php', $currentPage) ?>">Tentang</a>
  <a href="<?= $rootPath ?>pages/produk.php" class="<?= nav_active('produk.php', $currentPage) ?>">Produk</a>
  <a href="<?= $rootPath ?>beranda.php#kontak">Kontak</a>
</nav>
