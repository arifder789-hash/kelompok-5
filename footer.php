<?php
// Deteksi otomatis jalur folder untuk link dan javascript
$is_subdir = (bool) preg_match('~/(pages|controller|admin)/~i', $_SERVER['PHP_SELF']);
$asset_prefix = $is_subdir ? '../' : '';
$link_prefix = $is_subdir ? '' : 'pages/';
?>
<footer>
  <div class="footer-grid">
    <!-- Kolom 1: Brand -->
    <div class="footer-brand-col">
      <div class="footer-brand-name">
        <img src="<?= $asset_prefix ?>assets/img/logo_ayam.png" alt="Logo"
          style="width: 36px; height: 36px; object-fit: contain;">
        Rameza Egg Farm
      </div>
      <p class="footer-brand-desc">Peternakan ayam petelur yang berfokus pada kualitas, kebersihan, dan
        kepercayaan pelanggan tercinta. Melayani dengan sepenuh hati sejak 2020.</p>
    </div>

    <!-- Kolom 2: Quick Links -->
    <div class="footer-col">
      <div class="footer-col-title">NAVIGASI</div>
      <ul>
        <li><a href="<?= $link_prefix ?>beranda.php">Beranda</a></li>
        <li><a href="<?= $link_prefix ?>tentang.php">Tentang</a></li>
        <li><a href="<?= $link_prefix ?>produk.php">Produk</a></li>
        <li><a href="<?= $link_prefix ?>kontak.php">Kontak</a></li>
      </ul>
    </div>

    <!-- Kolom 3: Temukan Kami + Peta -->
    <div class="footer-col footer-findus">
      <div class="footer-col-title">TEMUKAN KAMI</div>
      <div class="footer-company-name">Rameza Egg Farm</div>
      <p class="footer-address">Bondowoso, Jawa Timur, Indonesia</p>
      <div class="footer-map-wrapper">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.5134707119293!2d113.7519838!3d-7.8346279!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd6e1000a5f7219%3A0xc02d2c91b9467ee!2sKandang+Ayam+Petelur+(RAMEZA+FARM)!5e0!3m2!1sid!2sid!4v1717000000000!5m2!1sid!2sid"
          width="100%" height="180" style="border:0; border-radius: 12px;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <span>© <?= date('Y') ?> Rameza Egg Farm</span>
  </div>
</footer>



<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$scriptMap = [
  'beranda.php' => 'assets/js/beranda.js',
  'tentang.php' => 'assets/js/tentang.js',
  'kontak.php' => 'assets/js/kontak.js',
  'detailproduk.php' => 'assets/js/beranda.js',
  'produk.php' => 'assets/js/produk.js',
];
$pageScript = $scriptMap[$currentPage] ?? null;
?>
<script src="<?= $asset_prefix ?>assets/js/navbar.js?v=<?= time() ?>"></script>
<?php if ($pageScript): ?>
  <script src="<?= $asset_prefix . htmlspecialchars($pageScript, ENT_QUOTES, 'UTF-8') ?>?v=<?= time() ?>"></script>
<?php endif; ?>
</body>

</html>