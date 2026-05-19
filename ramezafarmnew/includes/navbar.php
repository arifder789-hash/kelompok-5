<?php
$currentPage = basename($_SERVER['PHP_SELF']);

function nav_active(string $page, string $currentPage): string
{
  return $page === $currentPage ? ' active' : '';
}
?>
<nav class="navbar" id="navbar" role="navigation" aria-label="Navigasi utama">
  <a href="beranda.php" class="navbar-brand">
  <img src="assets/img/logo_ayam.png" class="brand-logo">
    Rameza Farm
  </a>

  <ul class="navbar-links" role="list">
    <li><a href="beranda.php" class="<?= nav_active('beranda.php', $currentPage) ?>">Beranda</a></li>
    <li><a href="tentang.php" class="<?= nav_active('tentang.php', $currentPage) ?>">Tentang</a></li>
    <li><a href="produk.php" class="<?= nav_active('produk.php', $currentPage) ?>">Produk</a></li>
    <li><a href="beranda.php#kontak">Kontak</a></li>
  </ul>

  <button class="navbar-hamburger" id="hamburger" type="button" aria-label="Toggle menu" aria-expanded="false" aria-controls="mobile-menu">
    <span></span><span></span><span></span>
  </button>
</nav>

<nav class="mobile-menu" id="mobile-menu" aria-label="Navigasi mobile">
  <a href="beranda.php" class="<?= nav_active('beranda.php', $currentPage) ?>">Beranda</a>
  <a href="tentang.php" class="<?= nav_active('tentang.php', $currentPage) ?>">Tentang</a>
  <a href="produk.php" class="<?= nav_active('produk.php', $currentPage) ?>">Produk</a>
  <a href="beranda.php#kontak">Kontak</a>
</nav>
