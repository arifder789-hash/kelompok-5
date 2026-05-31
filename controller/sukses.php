<?php
// ============================================
//  controller/sukses.php — Pesanan Berhasil
// ============================================
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

require_once __DIR__ . '/../midtrans/Midtrans.php';
\Midtrans\Config::$serverKey = 'Mid-server-d267yyo9s4V5oDLRXQcE78c2';
\Midtrans\Config::$clientKey = 'Mid-client-HkgOOneCrvc9U39a';
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;
\Midtrans\Config::$curlOptions = [CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0];

$snapToken = '';
try {
  $snapToken = \Midtrans\Snap::getSnapToken([
    'transaction_details' => [
      'order_id' => $kode . '-' . time(),
      'gross_amount' => (int) $pesanan['grand_total'],
    ],
    'customer_details' => [
      'first_name' => $pesanan['nama_penerima'],
      'phone' => $pesanan['no_wa'],
    ],
  ]);
} catch (\Exception $e) {
  error_log("Midtrans Error: " . $e->getMessage());
}

$waItems = '';
foreach ($details as $d) {
  $waItems .= "- {$d['nama_produk']} x{$d['jumlah']} {$d['satuan']} = " . formatRupiah($d['subtotal']) . "\n";
}
$waMsg = urlencode("Halo Rameza Farm! Konfirmasi pesanan:\nKode: {$kode}\nNama: {$pesanan['nama_penerima']}\n\n{$waItems}\nTotal: " . formatRupiah($pesanan['grand_total']));
$waLink = "https://wa.me/6281200000000?text={$waMsg}";
$rootPath = '../';
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

  <!-- HERO -->
  <div class="s-hero">
    <div class="check-anim">✅</div>
    <h1>Pesanan Berhasil Dibuat!</h1>
    <p>Terima kasih, <strong><?= htmlspecialchars($pesanan['nama_penerima']) ?></strong>! Pesanan Anda telah kami terima
      dan sedang menunggu konfirmasi pembayaran.</p>
    <div class="kode-chip" onclick="copyKode()" title="Klik untuk salin kode">
      📋 &nbsp;<?= htmlspecialchars($kode) ?> &nbsp;<span style="opacity:.65;font-size:13px;">⎘ Salin</span>
    </div>
  </div>

  <!-- PROGRESS -->
  <div class="progress-wrap">
    <div class="p-step">
      <div class="p-dot done">✓</div>
      <div class="p-lbl done">Pesanan<br>Dibuat</div>
    </div>
    <div class="p-line done"></div>
    <div class="p-step">
      <div class="p-dot active">💳</div>
      <div class="p-lbl active">Pembayaran</div>
    </div>
    <div class="p-line pending"></div>
    <div class="p-step">
      <div class="p-dot pending">📦</div>
      <div class="p-lbl pending">Diproses</div>
    </div>
    <div class="p-line pending"></div>
    <div class="p-step">
      <div class="p-dot pending">🚚</div>
      <div class="p-lbl pending">Dikirim</div>
    </div>
    <div class="p-line pending"></div>
    <div class="p-step">
      <div class="p-dot pending">🎉</div>
      <div class="p-lbl pending">Selesai</div>
    </div>
  </div>

  <!-- BODY -->
  <div class="s-body">

    <!-- LEFT -->
    <div>
      <!-- Detail Pesanan -->
      <div class="s-card">
        <div class="s-card-head">
          <div class="head-icon hi-blue">🧾</div>
          <h2>Detail Pesanan</h2>
        </div>
        <div class="s-card-body">
          <?php foreach ($details as $d): ?>
            <div class="oi">
              <div>
                <div class="oi-name"><?= htmlspecialchars($d['nama_produk']) ?></div>
                <div class="oi-qty"><?= (int) $d['jumlah'] ?>   <?= htmlspecialchars($d['satuan']) ?></div>
              </div>
              <div class="oi-sub"><?= formatRupiah($d['subtotal']) ?></div>
            </div>
          <?php endforeach; ?>

          <div style="margin-top:10px;">
            <div class="sum-row"><span class="lbl">Subtotal</span><span
                class="val"><?= formatRupiah($pesanan['total_harga']) ?></span></div>
            <div class="sum-row"><span class="lbl">Ongkos Kirim</span><span class="val"
                style="<?= $pesanan['ongkir'] == 0 ? 'color:#16a34a' : '' ?>"><?= $pesanan['ongkir'] > 0 ? formatRupiah($pesanan['ongkir']) : 'Gratis' ?></span>
            </div>
            <div class="sum-row sum-total"><span>Total</span><span
                class="val"><?= formatRupiah($pesanan['grand_total']) ?></span></div>
          </div>
        </div>
      </div>

      <!-- Info Pemesan -->
      <div class="s-card">
        <div class="s-card-head">
          <div class="head-icon hi-green">👤</div>
          <h2>Informasi Pemesan</h2>
        </div>
        <div class="s-card-body">
          <div class="ir"><span class="k">Nama</span><span
              class="v"><?= htmlspecialchars($pesanan['nama_penerima']) ?></span></div>
          <div class="ir"><span class="k">WhatsApp</span><span class="v">+62
              <?= htmlspecialchars($pesanan['no_wa']) ?></span></div>
          <div class="ir"><span class="k">Alamat</span><span
              class="v"><?= htmlspecialchars($pesanan['alamat_kirim']) ?></span></div>
          <div class="ir"><span class="k">Kota</span><span class="v"><?= htmlspecialchars($pesanan['kota']) ?></span>
          </div>
          <div class="ir"><span class="k">Pembayaran</span><span class="v"
              style="font-weight:700;"><?= strtoupper($pesanan['metode_bayar']) ?></span></div>
          <div class="ir">
            <span class="k">Status</span>
            <span class="v">
              <span class="status-pill sp-pending"><span class="sp-dot"></span>Menunggu Konfirmasi</span>
            </span>
          </div>
          <?php if (!empty($pesanan['catatan'])): ?>
            <div class="ir"><span class="k">Catatan</span><span
                class="v"><?= htmlspecialchars($pesanan['catatan']) ?></span></div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- RIGHT -->
    <div class="s-sidebar">
      <div class="pay-card">
        <div class="pay-card-icon">💳</div>
        <h3>Selesaikan Pembayaran</h3>
        <p>Gunakan Transfer Bank, E-Wallet, QRIS, atau metode pembayaran lainnya.</p>
        <div class="pay-lbl">Total yang harus dibayar</div>
        <div class="pay-amt"><?= formatRupiah($pesanan['grand_total']) ?></div>
        <button class="btn-pay" id="pay-button"><span>💵</span> Bayar Sekarang</button>
      </div>

      <div class="action-card">
        <a href="<?= $waLink ?>" target="_blank" rel="noopener" class="btn-wa"><span>💬</span> Konfirmasi via
          WhatsApp</a>
        <a href="../pages/produk.php" class="btn-shop"><span>🛍️</span> Lanjutkan Belanja</a>
        <a href="../pages/pesanan_pelanggan.php" class="btn-hist">Lihat Riwayat Pesanan →</a>
      </div>
    </div>

  </div>

  <div class="copy-toast" id="copy-toast">✅ Kode pesanan berhasil disalin!</div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Mid-client-HkgOOneCrvc9U39a"></script>
  <script>
    function copyKode() {
      navigator.clipboard?.writeText('<?= $kode ?>').then(() => {
        const t = document.getElementById('copy-toast');
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2500);
      });
    }

    document.getElementById('pay-button').addEventListener('click', () => {
      const token = '<?= $snapToken ?>';
      if (!token) {
        Swal.fire({
          title: 'Midtrans Tidak Tersedia',
          text: 'Lanjutkan pembayaran manual?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#2d5be3',
          confirmButtonText: 'Ya, Bayar Manual',
          cancelButtonText: 'Batal',
        }).then(r => {
          if (!r.isConfirmed) return;
          fetch('update_payment_status.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ kode_pesanan: '<?= $kode ?>' }) })
            .then(r => r.json())
            .then(() => Swal.fire('Berhasil!', 'Pembayaran manual dicatat.', 'success').then(() => location.href = '../pages/pesanan_pelanggan.php'));
        });
        return;
      }
      snap.pay(token, {
        onSuccess: () => {
          fetch('update_payment_status.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ kode_pesanan: '<?= $kode ?>' }) })
            .then(r => r.json())
            .then(() => Swal.fire({ title: 'Pembayaran Berhasil!', text: 'Pesanan sedang diproses.', icon: 'success', confirmButtonColor: '#2d5be3' }).then(() => location.href = '../pages/pesanan_pelanggan.php'));
        },
        onPending: () => Swal.fire('Menunggu', 'Pembayaran sedang diproses.', 'info'),
        onError: () => Swal.fire('Gagal', 'Pembayaran gagal. Coba lagi.', 'error'),
        onClose: () => Swal.fire({ title: 'Dibatalkan', text: 'Popup ditutup sebelum selesai.', icon: 'warning', confirmButtonColor: '#2d5be3' }),
      });
    });
  </script>
  <?php include '../includes/footer.php'; ?>