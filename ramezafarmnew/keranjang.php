<?php
// ============================================
//  keranjang.php — Halaman Keranjang Belanja
// ============================================
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/cart_helper.php';

$items     = cartGetAll();
$total     = cartTotal();
$cartCount = cartCount();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Keranjang Belanja — Rameza Egg Farm</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/shop.css"/>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
  <a href="beranda.php" class="navbar-brand">🐔 Rameza Farm</a>
  <ul class="navbar-links">
    <li><a href="beranda.php">Beranda</a></li>
    <li><a href="tentang.php">Tentang</a></li>
    <li><a href="produk.php">Produk</a></li>
    <li><a href="kontak.php">Kontak</a></li>
  </ul>
  <a href="keranjang.php" class="cart-btn active" id="cart-btn">
    🛒 Keranjang
    <span class="cart-badge" id="cart-badge"><?= $cartCount ?></span>
  </a>
</nav>

<!-- PAGE HEADER -->
<div class="page-header small">
  <div class="container">
    <div class="breadcrumb">
      <a href="beranda.php">Beranda</a> / <a href="produk.php">Produk</a> / <span>Keranjang</span>
    </div>
    <h1 class="page-title">Keranjang <span class="blue">Belanja</span></h1>
  </div>
</div>

<!-- CONTENT -->
<main class="container cart-layout" style="padding:40px 0 80px;">

  <?php if (empty($items)): ?>
    <!-- Empty State -->
    <div class="empty-state" style="grid-column:1/-1;">
      <div class="empty-icon">🛒</div>
      <h3>Keranjang Masih Kosong</h3>
      <p>Yuk, pilih produk dari katalog kami dan tambahkan ke keranjang!</p>
      <a href="produk.php" class="btn-primary">Lihat Produk</a>
    </div>

  <?php else: ?>

    <!-- ── KIRI: Daftar Item ── -->
    <div class="cart-items-col">
      <div class="cart-card">
        <div class="cart-card-header">
          <h2>Item Pesanan <span class="text-muted">(<?= count($items) ?> produk)</span></h2>
          <button id="clear-cart-btn" class="btn-danger-sm">🗑 Kosongkan</button>
        </div>

        <div id="cart-items-list">
          <?php foreach ($items as $item): ?>
            <div class="cart-item" id="item-<?= $item['produk_id'] ?>" data-id="<?= $item['produk_id'] ?>">

              <div class="cart-item-img">
                <img src="<?= htmlspecialchars($item['gambar'] ?: 'assets/img/no-image.png') ?>"
                     alt="<?= htmlspecialchars($item['nama']) ?>"
                     onerror="this.src='https://placehold.co/80x80/e8eefb/2d5be3?text=📦'"/>
              </div>

              <div class="cart-item-info">
                <div class="cart-item-name"><?= htmlspecialchars($item['nama']) ?></div>
                <div class="cart-item-price"><?= rupiah($item['harga']) ?> / <?= htmlspecialchars($item['satuan']) ?></div>
              </div>

              <div class="cart-item-qty">
                <button class="qty-btn minus cart-qty-btn"
                        data-id="<?= $item['produk_id'] ?>" data-action="minus">−</button>
                <span class="qty-display" id="qty-<?= $item['produk_id'] ?>"><?= $item['qty'] ?></span>
                <button class="qty-btn plus cart-qty-btn"
                        data-id="<?= $item['produk_id'] ?>" data-action="plus">+</button>
              </div>

              <div class="cart-item-subtotal" id="sub-<?= $item['produk_id'] ?>">
                <?= rupiah($item['subtotal']) ?>
              </div>

              <button class="remove-item-btn"
                      data-id="<?= $item['produk_id'] ?>"
                      aria-label="Hapus item">✕</button>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Lanjut belanja -->
      <a href="produk.php" class="btn-outline" style="margin-top:16px;display:inline-flex;align-items:center;gap:8px;">
        ← Lanjutkan Belanja
      </a>
    </div>

    <!-- ── KANAN: Ringkasan & Checkout ── -->
    <div class="cart-summary-col">
      <div class="cart-card">
        <h2 class="cart-card-title">Ringkasan Pesanan</h2>

        <div class="summary-row">
          <span>Subtotal</span>
          <span id="summary-subtotal"><?= rupiah($total) ?></span>
        </div>
        <div class="summary-row">
          <span>Ongkos Kirim</span>
          <span class="text-muted">Dihitung saat checkout</span>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-row summary-total">
          <span>Total</span>
          <span id="summary-total"><?= rupiah($total) ?></span>
        </div>

        <a href="checkout.php" class="btn-primary checkout-btn">
          Lanjut ke Checkout →
        </a>

        <div class="cart-note">
          <span>💬</span>
          <span>Konfirmasi pembayaran via WhatsApp setelah order ditempatkan.</span>
        </div>
      </div>

      <!-- Info tambahan -->
      <div class="cart-card" style="margin-top:16px;">
        <h3 style="font-size:14px;font-weight:700;margin-bottom:12px;color:#475569;">ℹ️ Informasi Pengiriman</h3>
        <ul class="info-list">
          <li>📍 Melayani pengiriman area Bondowoso & sekitarnya</li>
          <li>🕐 Pengiriman setiap hari pukul 07.00 – 13.00 WIB</li>
          <li>📞 Hubungi kami untuk pengiriman di luar area</li>
        </ul>
      </div>
    </div>

  <?php endif; ?>
</main>

<!-- TOAST -->
<div id="toast" class="toast" role="alert" aria-live="polite"></div>

<!-- FOOTER -->
<footer class="site-footer">
  <div class="container footer-inner">
    <div class="footer-brand">🐔 <strong>Rameza Egg Farm</strong></div>
    <div class="footer-copy">© 2025 Rameza Farm · Bondowoso, Jawa Timur</div>
  </div>
</footer>

<script src="assets/js/cart.js"></script>
<script>
// Tombol kosongkan keranjang
document.getElementById('clear-cart-btn')?.addEventListener('click', async () => {
  if (!confirm('Yakin ingin mengosongkan keranjang?')) return;
  const res = await cartAction('remove_all');
  if (res.ok) location.reload();
});
</script>
</body>
</html>
