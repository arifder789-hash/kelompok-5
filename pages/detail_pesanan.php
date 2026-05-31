<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['id_pelanggan'])) {
  header('Location: loginuser.php');
  exit;
}

$id_pelanggan = $_SESSION['id_pelanggan'];
$kode = isset($_GET['kode']) ? trim($_GET['kode']) : '';

if (empty($kode)) {
  header('Location: pesanan_pelanggan.php');
  exit;
}

$stmt = $pdo->prepare("
    SELECT p.*, pl.username, pl.email, pl.no_telp
    FROM pesanan p
    JOIN pelanggan pl ON pl.id_pelanggan = p.id_pelanggan
    WHERE p.kode_pesanan = ? AND p.id_pelanggan = ?
    LIMIT 1
");
$stmt->execute([$kode, $id_pelanggan]);
$pesanan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pesanan) {
  header('Location: pesanan_pelanggan.php');
  exit;
}

$stmt2 = $pdo->prepare("
    SELECT dp.*, pr.gambar, pr.kategori
    FROM detail_pesanan dp
    LEFT JOIN produk pr ON pr.id_produk = dp.id_produk
    WHERE dp.id_pesanan = ?
    ORDER BY dp.id_detail ASC
");
$stmt2->execute([$pesanan['id_pesanan']]);
$items = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$stmt3 = $pdo->prepare("SELECT * FROM pembayaran WHERE id_pesanan = ? ORDER BY created_at DESC LIMIT 1");
$stmt3->execute([$pesanan['id_pesanan']]);
$pembayaran = $stmt3->fetch(PDO::FETCH_ASSOC);

function labelMetodeBayar(string $m): string {
  return match($m) {
    'cod'      => '💵 Bayar di Tempat (COD)',
    'transfer' => '🏦 Transfer Bank',
    'wa'       => '💬 Konfirmasi via WhatsApp',
    'midtrans' => '💳 Midtrans (Online)',
    default    => ucfirst($m),
  };
}

function badgeStatus(string $s): string {
  [$cls, $dot] = match(strtolower($s)) {
    'pending'                  => ['dp-badge-pending',      '#854d0e'],
    'dikonfirmasi','diproses'  => ['dp-badge-dikonfirmasi', '#1e40af'],
    'dikirim'                  => ['dp-badge-dikirim',      '#92400e'],
    'selesai'                  => ['dp-badge-selesai',      '#166534'],
    'dibatalkan'               => ['dp-badge-dibatalkan',   '#991b1b'],
    default                    => ['dp-badge-default',      '#475569'],
  };
  return "<span class=\"dp-badge {$cls}\"><span class=\"dp-dot\" style=\"background:{$dot}\"></span>" . ucfirst($s) . "</span>";
}

function badgeStatusBayar(string $s): string {
  [$cls, $lbl] = match(strtolower($s)) {
    'berhasil'      => ['dp-badge-selesai',    '✅ Berhasil'],
    'menunggu'      => ['dp-badge-pending',    '⏳ Menunggu Verifikasi'],
    'gagal'         => ['dp-badge-dibatalkan', '❌ Gagal'],
    'kadaluarsa'    => ['dp-badge-dibatalkan', '⌛ Kadaluarsa'],
    'dibatalkan'    => ['dp-badge-dibatalkan', '🚫 Dibatalkan'],
    'belum_dibayar' => ['dp-badge-default',    '💤 Belum Dibayar'],
    default         => ['dp-badge-default',    ucfirst($s)],
  };
  return "<span class=\"dp-badge {$cls}\">{$lbl}</span>";
}

$timeline_steps = [
  ['key' => 'pending',      'label' => 'Pesanan Dibuat', 'icon' => '📋'],
  ['key' => 'dikonfirmasi', 'label' => 'Dikonfirmasi',   'icon' => '✅'],
  ['key' => 'diproses',     'label' => 'Diproses',       'icon' => '📦'],
  ['key' => 'dikirim',      'label' => 'Dikirim',        'icon' => '🚚'],
  ['key' => 'selesai',      'label' => 'Selesai',        'icon' => '🎉'],
];
$status_order  = array_column($timeline_steps, 'key');
$current_index = array_search(strtolower($pesanan['status']), $status_order);
$is_cancelled  = strtolower($pesanan['status']) === 'dibatalkan';
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<style>
  /* ── Hero ── */
  .dp-hero {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    padding: 110px 0 80px;
    margin-top: -20px;
    color: white;
    position: relative;
    overflow: hidden;
  }
  .dp-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at 80% 40%, rgba(255,255,255,0.06) 0%, transparent 55%);
    pointer-events: none;
  }
  .dp-hero-inner { position: relative; z-index: 1; }

  .dp-back {
    display: inline-flex; align-items: center; gap: 6px;
    color: rgba(255,255,255,0.7); text-decoration: none;
    font-size: 13px; font-weight: 500;
    margin-bottom: 18px; transition: color 0.15s;
  }
  .dp-back:hover { color: white; }

  .dp-hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.22);
    border-radius: 999px; padding: 6px 18px;
    font-size: 11px; font-weight: 700; letter-spacing: 0.1em;
    text-transform: uppercase; margin-bottom: 22px;
  }
  .dp-hero-badge span {
    width: 7px; height: 7px; border-radius: 50%;
    background: #f5c842; display: inline-block;
  }

  .dp-hero-top {
    display: flex; align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap; gap: 20px;
  }
  .dp-hero h1 {
    font-size: 2.6rem; font-weight: 800;
    margin-bottom: 10px; line-height: 1.1;
    letter-spacing: -0.5px;
  }
  /* ✅ italic kuning BOLD */
  .dp-hero h1 em {
    font-style: italic;
    font-weight: 800;
    color: #f5c842;
  }
  .dp-hero-kode {
    font-family: 'Courier New', monospace;
    font-size: 1rem; font-weight: 700;
    color: #f5c842; letter-spacing: 0.03em;
    margin-bottom: 6px;
  }
  .dp-hero-date { font-size: 13px; opacity: 0.72; }
  .dp-hero-badges {
    display: flex; flex-direction: column;
    align-items: flex-end; gap: 8px;
    padding-top: 4px;
  }

  /* ── Badges ── */
  .dp-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 14px; border-radius: 999px;
    font-size: 12px; font-weight: 700;
  }
  .dp-dot {
    width: 6px; height: 6px; border-radius: 50%;
    display: inline-block; flex-shrink: 0;
  }
  .dp-badge-pending      { background: #fef9c3; color: #854d0e; }
  .dp-badge-selesai      { background: #dcfce7; color: #166534; }
  .dp-badge-dikirim      { background: #fef3c7; color: #92400e; }
  .dp-badge-dikonfirmasi { background: #dbeafe; color: #1e40af; }
  .dp-badge-dibatalkan   { background: #fee2e2; color: #991b1b; }
  .dp-badge-default      { background: #f1f5f9; color: #475569; }

  /* ── Body ── */
  .dp-body { background: #f0f4f8; padding: 40px 0 64px; min-height: 50vh; }

  /* ── Cards ── */
  .dp-card {
    background: white; border-radius: 18px;
    box-shadow: 0 4px 28px rgba(30,60,114,0.10);
    overflow: hidden; margin-bottom: 20px;
  }
  .dp-card-head {
    padding: 18px 26px; border-bottom: 1px solid #e8eef5;
    display: flex; align-items: center; gap: 10px;
    background: #fafbfd;
  }
  .dp-card-head h2 {
    font-size: 14px; font-weight: 700; color: #1e3c72;
  }
  .dp-card-body { padding: 26px 28px; }

  /* ── Grid ── */
  .dp-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  @media (max-width: 720px) { .dp-grid { grid-template-columns: 1fr; } }

  /* ── Info rows ── */
  .dp-info-row { display: flex; flex-direction: column; gap: 18px; }
  .dp-info-item { display: flex; flex-direction: column; gap: 4px; }
  .dp-info-label {
    font-size: 11px; text-transform: uppercase;
    letter-spacing: 0.07em; color: #94a3b8; font-weight: 700;
  }
  .dp-info-value { font-size: 14px; color: #1e293b; font-weight: 500; }
  .dp-info-value.mono {
    font-family: 'Courier New', monospace;
    font-weight: 700; color: #1e3c72;
  }
  .dp-info-value.muted { color: #94a3b8; font-style: italic; font-weight: 400; }

  /* ── Note box ── */
  .dp-note {
    background: #fffbeb;
    border-left: 3px solid #f5c842;
    border-radius: 0 8px 8px 0;
    padding: 12px 16px;
    font-size: 13px; color: #92400e;
    display: flex; gap: 8px;
  }

  /* ── Timeline ── */
  .dp-timeline {
    display: flex; align-items: flex-start;
    padding: 8px 0 4px; overflow-x: auto; gap: 0;
  }
  .dp-tl-step {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; position: relative; min-width: 84px;
  }
  .dp-tl-step:not(:last-child)::after {
    content: ''; position: absolute; top: 19px; left: 50%;
    width: 100%; height: 3px; background: #e2e8f0; z-index: 0;
  }
  .dp-tl-step.done:not(:last-child)::after {
    background: linear-gradient(90deg, #00A254, #4ade80);
  }
  .dp-tl-icon {
    width: 40px; height: 40px; border-radius: 50%;
    background: #f1f5f9; border: 2.5px solid #e2e8f0;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; position: relative; z-index: 1;
    transition: all 0.2s;
  }
  .dp-tl-step.done   .dp-tl-icon { background: #dcfce7; border-color: #00A254; }
  .dp-tl-step.active .dp-tl-icon {
    background: #dbeafe; border-color: #2a5298;
    box-shadow: 0 0 0 5px rgba(42,82,152,0.13);
  }
  .dp-tl-label {
    font-size: 11px; color: #94a3b8;
    margin-top: 10px; text-align: center; font-weight: 600;
  }
  .dp-tl-step.done   .dp-tl-label { color: #00A254; }
  .dp-tl-step.active .dp-tl-label { color: #2a5298; font-weight: 800; }

  /* ── Product table ── */
  .dp-prod-table { width: 100%; border-collapse: collapse; }
  .dp-prod-table th {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.07em;
    color: #94a3b8; padding: 0 0 14px;
    border-bottom: 2px solid #f1f5f9; text-align: left;
  }
  .dp-prod-table th:last-child { text-align: right; }
  .dp-prod-table td { padding: 16px 0; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
  .dp-prod-table tbody tr:last-child td { border-bottom: none; }
  .dp-prod-name { font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 3px; }
  .dp-prod-meta { font-size: 12px; color: #94a3b8; }
  .dp-prod-thumb {
    width: 50px; height: 50px; border-radius: 10px;
    object-fit: cover; border: 1px solid #e2e8f0; background: #f8fafc;
  }
  .dp-prod-placeholder {
    width: 50px; height: 50px; border-radius: 10px;
    background: #f1f5f9; border: 1px solid #e2e8f0;
    display: flex; align-items: center; justify-content: center; font-size: 24px;
  }

  /* ── Summary ── */
  .dp-summary { border-top: 2px dashed #e8eef5; padding-top: 18px; margin-top: 8px; }
  .dp-sum-row {
    display: flex; justify-content: space-between;
    padding: 5px 0; font-size: 14px; color: #64748b;
  }
  .dp-sum-row.free  { color: #00A254; font-weight: 600; }
  .dp-sum-row.total {
    font-size: 17px; font-weight: 800; color: #1e3c72;
    border-top: 2px solid #e8eef5; padding-top: 14px; margin-top: 8px;
  }

  /* ── Cancelled ── */
  .dp-cancelled {
    display: flex; align-items: center; gap: 14px;
    padding: 22px 28px;
    background: #fff5f5; border-left: 4px solid #ef4444;
  }
  .dp-cancelled-icon  { font-size: 2rem; }
  .dp-cancelled-title { font-weight: 700; font-size: 15px; color: #991b1b; }
  .dp-cancelled-note  { font-size: 13px; color: #64748b; margin-top: 4px; }

  /* ── Action buttons ── */
  .dp-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 4px; }
  .dp-btn-bayar {
    display: inline-flex; align-items: center; gap: 7px;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    color: white; padding: 12px 26px; border-radius: 10px;
    font-size: 14px; font-weight: 700; text-decoration: none;
    box-shadow: 0 4px 14px rgba(30,60,114,0.30);
    transition: opacity 0.15s, transform 0.15s;
  }
  .dp-btn-bayar:hover { opacity: 0.88; transform: translateY(-1px); }
  .dp-btn-back {
    display: inline-flex; align-items: center; gap: 6px;
    background: white; color: #2a5298;
    padding: 12px 22px; border-radius: 10px;
    font-size: 14px; font-weight: 700; text-decoration: none;
    border: 2px solid #c5d3ea; transition: all 0.15s;
  }
  .dp-btn-back:hover { background: #1e3c72; color: white; border-color: #1e3c72; }

  @media (max-width: 640px) {
    .dp-hero h1  { font-size: 1.8rem; }
    .dp-hero-top { flex-direction: column; }
    .dp-hero-badges { align-items: flex-start; }
    .dp-card-body { padding: 18px; }
  }
</style>

<!-- Hero -->
<div class="dp-hero">
  <div class="container">
    <div class="dp-hero-inner">
      <a href="pesanan_pelanggan.php" class="dp-back">← Kembali ke Pesanan Saya</a>
      <div class="dp-hero-badge"><span></span> DETAIL PESANAN • RAMEZA EGG FARM</div>
      <div class="dp-hero-top">
        <div>
          <h1>Detail <em>Pesanan</em></h1>
          <div class="dp-hero-kode">#<?= htmlspecialchars($pesanan['kode_pesanan']) ?></div>
          <div class="dp-hero-date">📅 Dipesan <?= date('d M Y, H:i', strtotime($pesanan['tanggal_pesan'])) ?> WIB</div>
        </div>
        <div class="dp-hero-badges">
          <?= badgeStatus($pesanan['status']) ?>
          <?= badgeStatusBayar($pesanan['status_pembayaran']) ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Body -->
<div class="dp-body">
  <div class="container">

    <!-- Timeline / Cancelled -->
    <?php if ($is_cancelled): ?>
      <div class="dp-card">
        <div class="dp-cancelled">
          <div class="dp-cancelled-icon">❌</div>
          <div>
            <div class="dp-cancelled-title">Pesanan Dibatalkan</div>
            <?php if (!empty($pesanan['catatan_pembayaran'])): ?>
              <div class="dp-cancelled-note"><?= htmlspecialchars($pesanan['catatan_pembayaran']) ?></div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="dp-card">
        <div class="dp-card-head">
          <span style="font-size:18px;">🗺️</span>
          <h2>Status Pengiriman</h2>
        </div>
        <div class="dp-card-body">
          <div class="dp-timeline">
            <?php foreach ($timeline_steps as $i => $step):
              $done   = ($current_index !== false) && $i < $current_index;
              $active = ($current_index !== false) && $i === $current_index;
              $cls    = $done ? 'done' : ($active ? 'active' : '');
            ?>
              <div class="dp-tl-step <?= $cls ?>">
                <div class="dp-tl-icon"><?= $step['icon'] ?></div>
                <div class="dp-tl-label"><?= $step['label'] ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Info grid -->
    <div class="dp-grid">

      <!-- Pengiriman -->
      <div class="dp-card">
        <div class="dp-card-head">
          <span style="font-size:18px;">📍</span>
          <h2>Informasi Pengiriman</h2>
        </div>
        <div class="dp-card-body">
          <div class="dp-info-row">
            <div class="dp-info-item">
              <span class="dp-info-label">Nama Penerima</span>
              <span class="dp-info-value">
                <?= !empty($pesanan['nama_penerima']) ? htmlspecialchars($pesanan['nama_penerima']) : '<span class="dp-info-value muted">—</span>' ?>
              </span>
            </div>
            <div class="dp-info-item">
              <span class="dp-info-label">No. WhatsApp</span>
              <span class="dp-info-value">
                <?php if (!empty($pesanan['no_wa'])): ?>
                  <a href="https://wa.me/<?= htmlspecialchars($pesanan['no_wa']) ?>" target="_blank"
                     style="color:#00A254;text-decoration:none;font-weight:600;">
                    📱 <?= htmlspecialchars($pesanan['no_wa']) ?>
                  </a>
                <?php else: ?>
                  <span class="dp-info-value muted">—</span>
                <?php endif; ?>
              </span>
            </div>
            <div class="dp-info-item">
              <span class="dp-info-label">Alamat Pengiriman</span>
              <span class="dp-info-value">
                <?php if (!empty($pesanan['alamat_kirim'])): ?>
                  <?= htmlspecialchars($pesanan['alamat_kirim']) ?>
                  <?php if (!empty($pesanan['kota'])): ?>, <?= htmlspecialchars($pesanan['kota']) ?><?php endif; ?>
                <?php else: ?>
                  <span class="dp-info-value muted">—</span>
                <?php endif; ?>
              </span>
            </div>
            <?php if (!empty($pesanan['catatan'])): ?>
              <div class="dp-note">
                <span>📝</span>
                <span><?= htmlspecialchars($pesanan['catatan']) ?></span>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Pembayaran -->
      <div class="dp-card">
        <div class="dp-card-head">
          <span style="font-size:18px;">💳</span>
          <h2>Informasi Pembayaran</h2>
        </div>
        <div class="dp-card-body">
          <div class="dp-info-row">
            <div class="dp-info-item">
              <span class="dp-info-label">Metode Pembayaran</span>
              <span class="dp-info-value"><?= labelMetodeBayar($pesanan['metode_bayar']) ?></span>
            </div>
            <div class="dp-info-item">
              <span class="dp-info-label">Status Pembayaran</span>
              <span class="dp-info-value"><?= badgeStatusBayar($pesanan['status_pembayaran']) ?></span>
            </div>
            <?php if ($pembayaran): ?>
              <?php if (!empty($pembayaran['bank'])): ?>
                <div class="dp-info-item">
                  <span class="dp-info-label">Bank / Channel</span>
                  <span class="dp-info-value"><?= strtoupper(htmlspecialchars($pembayaran['bank'])) ?></span>
                </div>
              <?php endif; ?>
              <?php if (!empty($pembayaran['transaction_id'])): ?>
                <div class="dp-info-item">
                  <span class="dp-info-label">ID Transaksi</span>
                  <span class="dp-info-value mono"><?= htmlspecialchars($pembayaran['transaction_id']) ?></span>
                </div>
              <?php endif; ?>
              <?php if (!empty($pembayaran['tanggal_bayar'])): ?>
                <div class="dp-info-item">
                  <span class="dp-info-label">Tanggal Bayar</span>
                  <span class="dp-info-value"><?= date('d M Y, H:i', strtotime($pembayaran['tanggal_bayar'])) ?> WIB</span>
                </div>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Item Pesanan -->
    <div class="dp-card">
      <div class="dp-card-head">
        <span style="font-size:18px;">🛍️</span>
        <h2>Item Pesanan</h2>
      </div>
      <div class="dp-card-body">
        <table class="dp-prod-table">
          <thead>
            <tr>
              <th style="width:58px;"></th>
              <th>Produk</th>
              <th style="text-align:center;">Qty</th>
              <th style="text-align:right;">Harga Satuan</th>
              <th style="text-align:right;">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
              <td>
                <?php $gp = '../' . ($item['gambar'] ?? '');
                if (!empty($item['gambar']) && file_exists($gp)): ?>
                  <img src="<?= htmlspecialchars('../' . $item['gambar']) ?>"
                       alt="<?= htmlspecialchars($item['nama_produk']) ?>"
                       class="dp-prod-thumb">
                <?php else: ?>
                  <div class="dp-prod-placeholder">
                    <?= match($item['kategori'] ?? '') {
                      'telur' => '🥚', 'bibit' => '🐣',
                      'pakan' => '🌾', 'obat'  => '💊',
                      default => '📦',
                    } ?>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <div class="dp-prod-name"><?= htmlspecialchars($item['nama_produk']) ?></div>
                <div class="dp-prod-meta">per <?= htmlspecialchars($item['satuan']) ?></div>
              </td>
              <td style="text-align:center;font-weight:700;color:#2a5298;"><?= $item['jumlah'] ?></td>
              <td style="text-align:right;color:#64748b;font-size:14px;">
                Rp <?= number_format($item['harga_saat_beli'], 0, ',', '.') ?>
              </td>
              <td style="text-align:right;font-weight:800;color:#1e3c72;font-size:15px;">
                Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <!-- Summary -->
        <div class="dp-summary">
          <div class="dp-sum-row">
            <span>Subtotal Produk</span>
            <span>Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></span>
          </div>
          <div class="dp-sum-row <?= $pesanan['ongkir'] == 0 ? 'free' : '' ?>">
            <span>Ongkos Kirim</span>
            <span>
              <?= $pesanan['ongkir'] == 0
                ? 'Gratis 🎉'
                : 'Rp ' . number_format($pesanan['ongkir'], 0, ',', '.') ?>
            </span>
          </div>
          <div class="dp-sum-row total">
            <span>Grand Total</span>
            <span>Rp <?= number_format($pesanan['grand_total'], 0, ',', '.') ?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Actions -->
    <div class="dp-actions">
      <?php if (strtolower($pesanan['status']) === 'pending'): ?>
        <a href="../controller/sukses.php?kode=<?= urlencode($pesanan['kode_pesanan']) ?>" class="dp-btn-bayar">
          💳 Bayar Sekarang
        </a>
      <?php endif; ?>
      <a href="pesanan_pelanggan.php" class="dp-btn-back">← Kembali</a>
    </div>

  </div>
</div>

<?php include '../includes/footer.php'; ?>
