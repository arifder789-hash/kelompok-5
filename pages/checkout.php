<?php
// ============================================
//  checkout.php — Form Pemesanan
// ============================================
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/cart_helper.php';

// Redirect jika cart kosong
if (cartEmpty()) {
  header('Location: produk.php');
  exit;
}

$items = cartGetAll();
$total = cartTotal();
$cartCount = cartCount();

// Flash error dari proses_checkout.php
$errors = $_SESSION['checkout_errors'] ?? [];
$old = $_SESSION['checkout_old'] ?? [];
unset($_SESSION['checkout_errors'], $_SESSION['checkout_old']);
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<!-- PAGE HEADER -->
<div class="page-header small">
  <div class="container">
    <div class="breadcrumb">
      <a href="beranda.php">Beranda</a> /
      <a href="produk.php">Produk</a> /
      <a href="keranjang.php">Keranjang</a> /
      <span>Checkout</span>
    </div>
    <h1 class="page-title">Informasi <span class="blue">Pemesanan</span></h1>
  </div>
</div>

<!-- STEPS INDICATOR -->
<div class="container">
  <div class="steps">
    <div class="step done">1 · Pilih Produk</div>
    <div class="step-line done"></div>
    <div class="step done">2 · Keranjang</div>
    <div class="step-line active"></div>
    <div class="step active">3 · Checkout</div>
    <div class="step-line"></div>
    <div class="step">4 · Selesai</div>
  </div>
</div>

<!-- CONTENT -->
<main class="container cart-layout" style="padding:32px 0 80px;">

  <!-- Error Banner -->
  <?php if ($errors): ?>
    <div class="error-banner" style="grid-column:1/-1;">
      <strong>⚠️ Mohon perbaiki kesalahan berikut:</strong>
      <ul><?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <!-- ── KIRI: Form ── -->
  <div class="cart-items-col">
    <form method="POST" action="../controller/proses_checkout.php" id="checkout-form" novalidate>
      <?php
      // CSRF token
      if (!isset($_SESSION['csrf']))
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
      ?>
      <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>" />

      <!-- Data Diri -->
      <div class="cart-card">
        <h2 class="cart-card-title">📋 Data Pemesan</h2>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="nama">Nama Lengkap <span class="req">*</span></label>
            <input type="text" id="nama" name="nama"
              class="form-input<?= !empty($errors) && empty($old['nama'] ?? '') ? ' input-error' : '' ?>"
              value="<?= htmlspecialchars($old['nama'] ?? '') ?>" placeholder="Masukkan nama lengkap" required />
          </div>
          <div class="form-group">
            <label class="form-label" for="no_wa">No. WhatsApp <span class="req">*</span></label>
            <div class="input-group">
              <span class="input-prefix">+62</span>
              <input type="tel" id="no_wa" name="no_wa" class="form-input"
                value="<?= htmlspecialchars($old['no_wa'] ?? '') ?>" placeholder="812-XXXX-XXXX" required />
            </div>
            <small class="form-hint">Digunakan untuk konfirmasi pesanan via WhatsApp</small>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="email">Email <span class="text-muted">(opsional)</span></label>
          <input type="email" id="email" name="email" class="form-input"
            value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder="contoh@email.com" />
        </div>
      </div>

      <!-- Alamat Pengiriman -->
      <div class="cart-card" style="margin-top:20px;">
        <h2 class="cart-card-title">📍 Alamat Pengiriman</h2>

        <div class="form-group">
          <label class="form-label" for="alamat">Alamat Lengkap <span class="req">*</span></label>
          <textarea id="alamat" name="alamat" class="form-input" rows="3"
            placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan…"
            required><?= htmlspecialchars($old['alamat'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label" for="kota">Kota / Kecamatan <span class="req">*</span></label>
            <input type="text" id="kota" name="kota" class="form-input"
              value="<?= htmlspecialchars($old['kota'] ?? '') ?>" placeholder="mis. Bondowoso" required />
          </div>
          <div class="form-group">
            <label class="form-label" for="metode_bayar">Metode Pembayaran <span class="req">*</span></label>
            <select id="metode_bayar" name="metode_bayar" class="form-input" required>
              <option value="cod" <?= ($old['metode_bayar'] ?? '') === 'cod' ? 'selected' : '' ?>>💵 COD (Bayar di
                Tempat)</option>
              <option value="transfer" <?= ($old['metode_bayar'] ?? '') === 'transfer' ? 'selected' : '' ?>>🏦 Transfer
                Bank</option>
              <option value="wa" <?= ($old['metode_bayar'] ?? 'wa') === 'wa' ? 'selected' : '' ?>>💬 Konfirmasi via
                WhatsApp</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="catatan">Catatan Pesanan <span class="text-muted">(opsional)</span></label>
          <textarea id="catatan" name="catatan" class="form-input" rows="2"
            placeholder="Instruksi khusus, waktu pengiriman yang diinginkan, dll."><?= htmlspecialchars($old['catatan'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- Submit (mobile) -->
      <button type="submit" class="btn-primary checkout-btn" style="margin-top:20px;display:none;" id="submit-mobile">
        ✅ Buat Pesanan
      </button>
    </form>
  </div>

  <!-- ── KANAN: Ringkasan ── -->
  <div class="cart-summary-col">
    <div class="cart-card">
      <h2 class="cart-card-title">🧾 Ringkasan Pesanan</h2>

      <!-- Items -->
      <div class="checkout-items">
        <?php foreach ($items as $item): ?>
          <div class="checkout-item">
            <div class="checkout-item-info">
              <span class="checkout-item-name"><?= htmlspecialchars($item['nama']) ?></span>
              <span class="checkout-item-qty"><?= $item['qty'] ?>   <?= htmlspecialchars($item['satuan']) ?></span>
            </div>
            <span class="checkout-item-sub"><?= formatRupiah($item['subtotal']) ?></span>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="summary-divider"></div>

      <div class="summary-row">
        <span>Subtotal</span>
        <span><?= formatRupiah($total) ?></span>
      </div>
      <div class="summary-row">
        <span>Ongkos Kirim</span>
        <span class="text-muted">Dikonfirmasi via WA</span>
      </div>
      <div class="summary-divider"></div>
      <div class="summary-row summary-total">
        <span>Total</span>
        <span><?= formatRupiah($total) ?></span>
      </div>

      <button type="submit" form="checkout-form" class="btn-primary checkout-btn" id="submit-btn">
        ✅ Buat Pesanan
      </button>

      <a href="produk.php" class="btn-outline" style="display:block;text-align:center;margin-top:10px;">
        ← Kembali ke Produk
      </a>
    </div>
  </div>

</main>

<!-- TOAST -->
<div id="toast" class="toast" role="alert" aria-live="polite"></div>

<!-- FOOTER -->
<?php include '../includes/footer.php'; ?>

<script>
  // Client-side form validation
  document.getElementById('checkout-form').addEventListener('submit', function (e) {
    const nama = document.getElementById('nama').value.trim();
    const noWa = document.getElementById('no_wa').value.trim();
    const alamat = document.getElementById('alamat').value.trim();
    const kota = document.getElementById('kota').value.trim();
    let ok = true;

    [['nama', nama], ['no_wa', noWa], ['alamat', alamat], ['kota', kota]].forEach(([id, val]) => {
      const el = document.getElementById(id);
      if (!val) {
        el.classList.add('input-error');
        ok = false;
      } else {
        el.classList.remove('input-error');
      }
    });

    if (!ok) {
      e.preventDefault();
      document.querySelector('.cart-items-col').scrollIntoView({ behavior: 'smooth' });
    }
  });
</script>
</body>

</html>