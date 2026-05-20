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
<<<<<<< HEAD
  <a href="<?= $rootPath ?>login_admin.php" class="navbar-brand">
=======
  <a href="<?= $rootPath ?>pages/beranda.php" class="navbar-brand">
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
  <img src="<?= $rootPath ?>assets/img/logo_ayam.png" class="brand-logo">
    Rameza Farm
  </a>

  <ul class="navbar-links" role="list">
<<<<<<< HEAD
    <li><a href="<?= $rootPath ?>beranda.php" class="<?= nav_active('beranda.php', $currentPage) ?>">Beranda</a></li>
    <li><a href="<?= $rootPath ?>pages/tentang.php" class="<?= nav_active('tentang.php', $currentPage) ?>">Tentang</a></li>
    <li><a href="<?= $rootPath ?>pages/produk.php" class="<?= nav_active('produk.php', $currentPage) ?>">Produk</a></li>
    <li><a href="<?= $rootPath ?>beranda.php#kontak">Kontak</a></li>
=======
    <li><a href="<?= $rootPath ?>pages/beranda.php" class="<?= nav_active('beranda.php', $currentPage) ?>">Beranda</a></li>
    <li><a href="<?= $rootPath ?>pages/tentang.php" class="<?= nav_active('tentang.php', $currentPage) ?>">Tentang</a></li>
    <li><a href="<?= $rootPath ?>pages/produk.php" class="<?= nav_active('produk.php', $currentPage) ?>">Produk</a></li>
    <li><a href="<?= $rootPath ?>pages/kontak.php" class="<?= nav_active('kontak.php', $currentPage) ?>">Kontak</a></li>
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
  </ul>

  <button class="navbar-hamburger" id="hamburger" type="button" aria-label="Toggle menu" aria-expanded="false" aria-controls="mobile-menu">
    <span></span><span></span><span></span>
  </button>
</nav>

<nav class="mobile-menu" id="mobile-menu" aria-label="Navigasi mobile">
<<<<<<< HEAD
  <a href="<?= $rootPath ?>beranda.php" class="<?= nav_active('beranda.php', $currentPage) ?>">Beranda</a>
  <a href="<?= $rootPath ?>pages/tentang.php" class="<?= nav_active('tentang.php', $currentPage) ?>">Tentang</a>
  <a href="<?= $rootPath ?>pages/produk.php" class="<?= nav_active('produk.php', $currentPage) ?>">Produk</a>
  <a href="<?= $rootPath ?>beranda.php#kontak">Kontak</a>
</nav>
=======
  <a href="<?= $rootPath ?>pages/beranda.php" class="<?= nav_active('beranda.php', $currentPage) ?>">Beranda</a>
  <a href="<?= $rootPath ?>pages/tentang.php" class="<?= nav_active('tentang.php', $currentPage) ?>">Tentang</a>
  <a href="<?= $rootPath ?>pages/produk.php" class="<?= nav_active('produk.php', $currentPage) ?>">Produk</a>
  <a href="<?= $rootPath ?>pages/kontak.php" class="<?= nav_active('kontak.php', $currentPage) ?>">Kontak</a>
</nav>
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
