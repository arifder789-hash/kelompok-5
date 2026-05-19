<?php
// Halaman konfirmasi pesanan berhasil.
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/cart_helper.php';

$kode = trim($_GET['kode'] ?? '');
if (!$kode) {
    header('Location: ../beranda.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM pesanan WHERE kode_pesanan = ?");
$stmt->execute([$kode]);
$pesanan = $stmt->fetch();
if (!$pesanan) {
    header('Location: ../beranda.php');
    exit;
}

$stmtD = $pdo->prepare("SELECT * FROM detail_pesanan WHERE id_pesanan = ?");
$stmtD->execute([$pesanan['id_pesanan']]);
$details = $stmtD->fetchAll();

$waItems = '';
foreach ($details as $d) {
    $waItems .= "- {$d['nama_produk']} x{$d['jumlah']} {$d['satuan']} = " . rupiah($d['subtotal']) . "\n";
}

$waMsg = urlencode(
    "Halo Rameza Farm!\n\n" .
    "Saya ingin konfirmasi pesanan berikut:\n\n" .
    "Kode Pesanan: {$kode}\n" .
    "Nama: {$pesanan['nama_penerima']}\n" .
    "Alamat: {$pesanan['alamat_kirim']}, {$pesanan['kota']}\n\n" .
    "Detail Pesanan:\n{$waItems}\n" .
    "Total: " . rupiah($pesanan['grand_total']) . "\n" .
    "Metode Bayar: " . strtoupper($pesanan['metode_bayar']) . "\n\n" .
    "Mohon konfirmasi pesanan dan info ongkos kirimnya. Terima kasih!"
);
$waNumber = '6281914103735';
$waLink   = "https://wa.me/{$waNumber}?text={$waMsg}";
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pesanan Berhasil - Rameza Egg Farm</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="../assets/css/shop.css"/>
</head>
<body>

<nav class="navbar" id="navbar">
  <a href="../beranda.php" class="navbar-brand">Rameza Farm</a>
  <ul class="navbar-links">
    <li><a href="../beranda.php">Beranda</a></li>
    <li><a href="../pages/produk.php">Produk</a></li>
    <li><a href="../beranda.php#kontak">Kontak</a></li>
  </ul>
  <a href="../pages/produk.php" class="cart-btn">Belanja Lagi</a>
</nav>

<main class="container" style="max-width:760px;padding:60px 16px 100px;">
  <div class="success-banner">
    <div class="success-icon">OK</div>
    <h1 class="success-title">Pesanan Berhasil Dibuat!</h1>
    <p class="success-sub">
      Terima kasih, <strong><?= htmlspecialchars($pesanan['nama_penerima']) ?></strong>!
      Pesanan Anda telah kami terima dan sedang menunggu konfirmasi.
    </p>
    <div class="kode-pesanan">
      Kode Pesanan: <strong><?= htmlspecialchars($kode) ?></strong>
    </div>
  </div>

  <div class="cart-card" style="margin-top:24px;">
    <h2 class="cart-card-title">Detail Pesanan</h2>

    <div class="checkout-items">
      <?php foreach ($details as $d): ?>
        <div class="checkout-item">
          <div class="checkout-item-info">
            <span class="checkout-item-name"><?= htmlspecialchars($d['nama_produk']) ?></span>
            <span class="checkout-item-qty"><?= (int) $d['jumlah'] ?> <?= htmlspecialchars($d['satuan']) ?></span>
          </div>
          <span class="checkout-item-sub"><?= rupiah($d['subtotal']) ?></span>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="summary-divider"></div>
    <div class="summary-row summary-total">
      <span>Total</span>
      <span><?= rupiah($pesanan['grand_total']) ?></span>
    </div>
  </div>

  <div class="cart-card" style="margin-top:16px;">
    <h2 class="cart-card-title">Informasi Pemesan</h2>
    <div class="info-grid">
      <div class="info-row"><span class="info-key">Nama</span><span><?= htmlspecialchars($pesanan['nama_penerima']) ?></span></div>
      <div class="info-row"><span class="info-key">WhatsApp</span><span>+62 <?= htmlspecialchars($pesanan['no_wa']) ?></span></div>
      <div class="info-row"><span class="info-key">Alamat</span><span><?= htmlspecialchars($pesanan['alamat_kirim']) ?></span></div>
      <div class="info-row"><span class="info-key">Kota</span><span><?= htmlspecialchars($pesanan['kota']) ?></span></div>
      <div class="info-row"><span class="info-key">Pembayaran</span><span><?= strtoupper($pesanan['metode_bayar']) ?></span></div>
      <?php if ($pesanan['catatan']): ?>
        <div class="info-row"><span class="info-key">Catatan</span><span><?= htmlspecialchars($pesanan['catatan']) ?></span></div>
      <?php endif; ?>
    </div>
  </div>

  <div class="cart-card wa-card" style="margin-top:16px;text-align:center;">
    <h3 style="font-size:18px;font-weight:700;margin-bottom:8px;">Konfirmasi Pesanan via WhatsApp</h3>
    <p style="color:#475569;font-size:14px;margin-bottom:20px;line-height:1.6;">
      Klik tombol di bawah untuk mengirim detail pesanan ke WhatsApp kami.
    </p>
    <a href="<?= $waLink ?>" target="_blank" class="btn-wa">Konfirmasi via WhatsApp</a>
  </div>

  <div style="text-align:center;margin-top:32px;">
    <a href="../pages/produk.php" class="btn-outline">Kembali Belanja</a>
  </div>
</main>

<footer class="site-footer">
  <div class="container footer-inner">
    <div class="footer-brand"><strong>Rameza Egg Farm</strong></div>
    <div class="footer-copy">© 2026 Rameza Farm - Bondowoso, Jawa Timur</div>
  </div>
</footer>

</body>
</html>
