<?php
// Deteksi otomatis jalur folder untuk link dan javascript
$is_pages = strpos($_SERVER['PHP_SELF'], '/pages/') !== false;
$asset_prefix = $is_pages ? '../' : '';
$link_prefix  = $is_pages ? '' : 'pages/';
?>
<footer>
  <div class="footer-grid">
    <div>
      <div class="footer-brand-name">🐔 Rameza Egg Farm</div>
      <p class="footer-brand-desc">Peternakan ayam petelur modern yang berfokus pada kualitas, kebersihan, dan kepercayaan. Melayani dengan sepenuh hati sejak 2015.</p>
      <a href="https://wa.me/6281914103735" target="_blank" rel="noopener" class="footer-wa" style="display:inline-flex;margin-top:20px;">
        <span>💬</span> Chat WhatsApp
      </a>
    </div>

    <div class="footer-col">
      <div class="footer-col-title">Navigasi</div>
      <ul>
        <li><a href="<?= $link_prefix ?>beranda.php">Beranda</a></li>
        <li><a href="<?= $link_prefix ?>tentang.php">Tentang Kami</a></li>
        <li><a href="<?= $link_prefix ?>produk.php">Produk</a></li>
        <li><a href="<?= $link_prefix ?>beranda.php#kontak">Kontak</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <div class="footer-col-title">Produk &amp; Layanan</div>
      <ul>
        <li><a href="<?= $link_prefix ?>produk.php?kategori=telur">Telur Ayam Segar</a></li>
        <li><a href="<?= $link_prefix ?>produk.php?kategori=bibit">Bibit Unggas</a></li>
        <li><a href="<?= $link_prefix ?>produk.php?kategori=pakan">Pakan &amp; Pulet</a></li>
        <li><a href="<?= $link_prefix ?>produk.php?kategori=obat">Vitamin &amp; Obat</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <div class="footer-col-title">Kontak</div>
      <ul>
        <li><a href="#">📍 Bondowoso, Jawa Timur</a></li>
        <li><a href="https://wa.me/6281914103735" target="_blank" rel="noopener">📱 +62 819-1410-3735</a></li>
        <li><a href="mailto:ramezafarm@gmail.com">✉️ ramezafarm@gmail.com</a></li>
        <li><a href="#">🕐 07.00 - 13.00 WIB</a></li>
      </ul>
    </div>
  </div>

  <div class="footer-bottom" style="max-width:1200px;margin:0 auto;">
    <span>© 2026 Rameza Egg Farm. Semua hak dilindungi.</span>
    <span>Made with ❤️ di Jember</span>
  </div>
</footer>

<div id="lightbox" role="dialog" aria-label="Tampilan gambar besar" aria-modal="true">
  <button id="lightbox-close" type="button" aria-label="Tutup">✕</button>
  <img id="lightbox-img" src="" alt=""/>
</div>

<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$scriptMap = [
  'beranda.php' => 'assets/js/beranda.js',
  'tentang.php' => 'assets/js/tentang.js',
  'detailproduk.php' => 'assets/js/beranda.js',
  'produk.php' => 'assets/js/cart.js',
];
$pageScript = $scriptMap[$currentPage] ?? null;
?>
<script src="<?= $asset_prefix ?>assets/js/navbar.js"></script>
<?php if ($pageScript): ?>
  <script src="<?= $asset_prefix . htmlspecialchars($pageScript, ENT_QUOTES, 'UTF-8') ?>"></script>
<?php endif; ?>
</body>
</html>