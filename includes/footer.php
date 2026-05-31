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
      <p class="footer-brand-desc">Peternakan ayam petelur modern yang berfokus pada kualitas, kebersihan, dan
        kepercayaan. Melayani dengan sepenuh hati sejak 2015.</p>
      <div class="footer-social">
        <a href="https://wa.me/6281914103735" target="_blank" rel="noopener" aria-label="WhatsApp"
          class="footer-social-link">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path
              d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
          </svg>
        </a>
        <a href="https://instagram.com/ramezafarm" target="_blank" rel="noopener" aria-label="Instagram"
          class="footer-social-link">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path
              d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
          </svg>
        </a>
        <a href="https://youtube.com/@ramezafarm" target="_blank" rel="noopener" aria-label="YouTube"
          class="footer-social-link">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path
              d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
          </svg>
        </a>
      </div>
    </div>

    <!-- Kolom 2: Quick Links -->
    <div class="footer-col">
      <div class="footer-col-title">NAVIGASI</div>
      <ul>
        <li><a href="<?= $link_prefix ?>beranda.php">Beranda</a></li>
        <li><a href="<?= $link_prefix ?>tentang.php">Tentang Kami</a></li>
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
        <a href="https://www.google.com/maps/place/Kandang+Ayam+Petelur+(RAMEZA+FARM)/@-7.8346279,113.7494089,976m/data=!3m2!1e3!4b1!4m6!3m5!1s0x2dd6e1000a5f7219:0xc02d2c91b9467ee!8m2!3d-7.8346279!4d113.7519838"
          target="_blank" rel="noopener" class="footer-map-link">Buka di Peta</a>
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.5134707119293!2d113.7519838!3d-7.8346279!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd6e1000a5f7219%3A0xc02d2c91b9467ee!2sKandang+Ayam+Petelur+(RAMEZA+FARM)!5e0!3m2!1sid!2sid!4v1717000000000!5m2!1sid!2sid"
          width="100%" height="180" style="border:0; border-radius: 12px;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <span>© <?= date('Y') ?> Rameza Egg Farm. All rights reserved.</span>
    <div class="footer-bottom-links">
      <a href="<?= $link_prefix ?>tentang.php">Tentang</a>
      <a href="<?= $link_prefix ?>kontak.php">Kontak</a>
      <a href="https://wa.me/6281914103735" target="_blank" rel="noopener">WhatsApp</a>
    </div>
  </div>
</footer>

<div id="lightbox" role="dialog" aria-label="Tampilan gambar besar" aria-modal="true">
  <button id="lightbox-close" type="button" aria-label="Tutup">✕</button>
  <img id="lightbox-img" src="" alt="" />
</div>

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