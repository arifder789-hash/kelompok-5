<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['id_pelanggan'])) {
  header('Location: loginuser.php');
  exit;
}

$id_pelanggan = $_SESSION['id_pelanggan'];

$stmt = $pdo->prepare("SELECT * FROM pesanan WHERE id_pelanggan = ? ORDER BY tanggal_pesan DESC");
$stmt->execute([$id_pelanggan]);
$pesanan = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_pesanan = count($pesanan);
$total_pending = count(array_filter($pesanan, fn($p) => strtolower($p['status']) === 'pending'));
$total_selesai = count(array_filter($pesanan, fn($p) => strtolower($p['status']) === 'selesai'));
$total_belanja = array_sum(array_column($pesanan, 'grand_total'));

$filter = isset($_GET['filter']) ? strtolower($_GET['filter']) : 'semua';
$pesanan_filtered = ($filter === 'semua')
  ? $pesanan
  : array_filter($pesanan, fn($p) => strtolower($p['status']) === $filter);
?>
<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<style>
  /* ═══════════════════════════════════════
   HERO + STATS — satu blok biru menyambung
   ═══════════════════════════════════════ */
  .po-hero-block {
    background: linear-gradient(160deg, #1a3668 0%, #1e3c72 40%, #2a5298 100%);
    padding: 110px 0 0;
    margin-top: -20px;
    position: relative;
    overflow: hidden;
  }

  /* dekorasi lingkaran blur */
  .po-hero-block::before,
  .po-hero-block::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
  }

  .po-hero-block::before {
    width: 520px;
    height: 520px;
    background: radial-gradient(circle, rgba(245, 200, 66, 0.08) 0%, transparent 70%);
    top: -120px;
    right: -80px;
  }

  .po-hero-block::after {
    width: 360px;
    height: 360px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
    bottom: 60px;
    left: -60px;
  }

  /* partikel dekoratif */
  .po-particles {
    position: absolute;
    inset: 0;
    pointer-events: none;
    overflow: hidden;
  }

  .po-particle {
    position: absolute;
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: rgba(245, 200, 66, 0.35);
    animation: po-float linear infinite;
  }

  @keyframes po-float {
    0% {
      transform: translateY(0) rotate(0deg);
      opacity: 0;
    }

    10% {
      opacity: 1;
    }

    90% {
      opacity: 0.6;
    }

    100% {
      transform: translateY(-340px) rotate(360deg);
      opacity: 0;
    }
  }

  /* ── Hero text ── */
  .po-hero-text {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
    padding-bottom: 52px;
  }

  .po-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.10);
    border: 1px solid rgba(255, 255, 255, 0.22);
    backdrop-filter: blur(6px);
    border-radius: 999px;
    padding: 6px 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    margin-bottom: 24px;
  }

  .po-hero-badge span {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #f5c842;
    display: inline-block;
    animation: po-pulse 2s ease-in-out infinite;
  }

  @keyframes po-pulse {

    0%,
    100% {
      transform: scale(1);
      opacity: 1;
    }

    50% {
      transform: scale(1.5);
      opacity: 0.7;
    }
  }

  .po-hero-text h1 {
    font-size: 2.9rem;
    font-weight: 800;
    margin-bottom: 14px;
    line-height: 1.1;
    letter-spacing: -0.5px;
  }

  .po-hero-text h1 em {
    font-style: italic;
    font-weight: 800;
    color: #f5c842;
  }

  .po-hero-text p {
    font-size: 1rem;
    opacity: 0.78;
    max-width: 420px;
    margin: 0 auto;
  }

  /* ── Stats ── */
  .po-stats-row {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0;
    border-top: 1px solid rgba(255, 255, 255, 0.10);
  }

  .po-stat {
    padding: 28px 20px;
    text-align: center;
    color: white;
    border-right: 1px solid rgba(255, 255, 255, 0.10);
    cursor: default;
    transition: background 0.25s;
    position: relative;
    overflow: hidden;
  }

  .po-stat:last-child {
    border-right: none;
  }

  .po-stat::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(255, 255, 255, 0);
    transition: background 0.25s;
  }

  .po-stat:hover::before {
    background: rgba(255, 255, 255, 0.06);
  }

  .po-stat-icon {
    font-size: 1.8rem;
    margin-bottom: 8px;
    line-height: 1;
  }

  .po-stat-val {
    font-size: 2.1rem;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 6px;
    transition: transform 0.2s;
  }

  .po-stat:hover .po-stat-val {
    transform: scale(1.08);
  }

  .po-stat-val.gold {
    color: #f5c842;
  }

  .po-stat-val.green {
    color: #4ade80;
  }

  .po-stat-val.cyan {
    color: #67e8f9;
    font-size: 1.5rem;
  }

  .po-stat-label {
    font-size: 12px;
    opacity: 0.65;
    font-weight: 600;
    letter-spacing: 0.03em;
  }

  /* wave pemisah */
  .po-wave {
    display: block;
    width: 100%;
    margin-bottom: -2px;
    line-height: 0;
  }

  /* ── Body ── */
  .po-body {
    background: #f0f4f8;
    padding: 36px 0 64px;
    min-height: 50vh;
  }

  /* ── Card ── */
  .po-card {
    background: white;
    border-radius: 18px;
    box-shadow: 0 4px 32px rgba(30, 60, 114, 0.10);
    overflow: hidden;
  }

  .po-card-head {
    padding: 20px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #e8eef5;
    flex-wrap: wrap;
    gap: 12px;
    background: #fafbfd;
  }

  .po-card-head h2 {
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
  }

  /* ── Filter tabs ── */
  .po-filters {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }

  .po-tab {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    border: 1.5px solid #dde4ef;
    color: #64748b;
    background: white;
    transition: all 0.18s;
  }

  .po-tab:hover {
    border-color: #2a5298;
    color: #2a5298;
    background: #eef2fb;
  }

  .po-tab.active {
    background: #1e3c72;
    color: white;
    border-color: #1e3c72;
    box-shadow: 0 2px 10px rgba(30, 60, 114, 0.25);
  }

  /* ── Table ── */
  .po-table {
    width: 100%;
    border-collapse: collapse;
  }

  .po-table thead tr {
    background: #f4f7fc;
  }

  .po-table th {
    padding: 13px 24px;
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    border-bottom: 1px solid #e8eef5;
    text-align: left;
    white-space: nowrap;
  }

  .po-table td {
    padding: 0;
    font-size: 14px;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
  }

  .po-table tbody tr:last-child td {
    border-bottom: none;
  }

  /* row interaktif */
  .po-row-link {
    display: table-row;
    text-decoration: none;
    color: inherit;
    cursor: pointer;
  }

  .po-row-link td {
    padding: 16px 24px;
    transition: background 0.15s;
  }

  .po-row-link:hover td {
    background: #f0f5ff;
  }

  .po-row-link:hover .po-kode {
    color: #2a5298;
    text-decoration: underline;
  }

  .po-kode {
    font-weight: 700;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    color: #1e3c72;
    transition: color 0.15s;
  }

  .po-tanggal {
    font-size: 13px;
    color: #64748b;
  }

  .po-harga {
    font-weight: 700;
    color: #1e3c72;
    font-size: 15px;
  }

  /* ── Badges ── */
  .po-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 13px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
  }

  .po-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    display: inline-block;
    flex-shrink: 0;
  }

  .po-badge-pending {
    background: #fef9c3;
    color: #854d0e;
  }

  .po-badge-selesai {
    background: #dcfce7;
    color: #166534;
  }

  .po-badge-dikirim {
    background: #fef3c7;
    color: #92400e;
  }

  .po-badge-dikonfirmasi,
  .po-badge-diproses {
    background: #dbeafe;
    color: #1e40af;
  }

  .po-badge-dibatalkan {
    background: #fee2e2;
    color: #991b1b;
  }

  .po-badge-default {
    background: #f1f5f9;
    color: #475569;
  }

  /* ── Action buttons ── */
  .po-aksi-wrap {
    display: flex;
    gap: 7px;
    align-items: center;
    flex-wrap: wrap;
  }

  .po-btn-bayar {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    color: white;
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(30, 60, 114, 0.28);
    transition: opacity 0.15s, transform 0.15s;
  }

  .po-btn-bayar:hover {
    opacity: 0.88;
    transform: translateY(-1px);
  }

  .po-btn-detail {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: white;
    color: #2a5298;
    padding: 7px 13px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    border: 1.5px solid #c5d3ea;
    transition: all 0.15s;
  }

  .po-btn-detail:hover {
    background: #1e3c72;
    color: white;
    border-color: #1e3c72;
  }

  /* ── Empty state ── */
  .po-empty {
    text-align: center;
    padding: 70px 20px;
  }

  .po-empty-icon {
    font-size: 4rem;
    margin-bottom: 16px;
  }

  .po-empty h3 {
    font-size: 1.2rem;
    color: #1e293b;
    font-weight: 700;
    margin-bottom: 8px;
  }

  .po-empty p {
    color: #64748b;
    font-size: 14px;
    margin-bottom: 24px;
  }

  .po-btn-shop {
    display: inline-block;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    color: white;
    padding: 12px 30px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 700;
    font-size: 14px;
    box-shadow: 0 4px 14px rgba(30, 60, 114, 0.28);
    transition: opacity 0.15s, transform 0.15s;
  }

  .po-btn-shop:hover {
    opacity: 0.88;
    transform: translateY(-1px);
  }

  /* ── Responsive ── */
  @media (max-width: 768px) {
    .po-stats-row {
      grid-template-columns: repeat(2, 1fr);
    }

    .po-stat {
      border-bottom: 1px solid rgba(255, 255, 255, 0.10);
    }

    .po-hero-text h1 {
      font-size: 2rem;
    }

    .po-table th:nth-child(2),
    .po-row-link td:nth-child(2) {
      display: none;
    }

    .po-card-head {
      flex-direction: column;
      align-items: flex-start;
    }
  }

  @media (max-width: 480px) {
    .po-hero-text h1 {
      font-size: 1.75rem;
    }

    .po-stat-val {
      font-size: 1.6rem;
    }

    .po-table th:nth-child(3),
    .po-row-link td:nth-child(3) {
      display: none;
    }
  }
</style>

<!-- ══════════════════════════════════
     HERO + STATS (satu blok biru)
     ══════════════════════════════════ -->
<div class="po-hero-block">

  <!-- Partikel -->
  <div class="po-particles" aria-hidden="true">
    <?php
    $positions = [
      ['12%', '10%', '3.5s', '0s'],
      ['28%', '80%', '5s', '1s'],
      ['55%', '30%', '4s', '0.5s'],
      ['70%', '60%', '6s', '2s'],
      ['85%', '15%', '4.5s', '1.5s'],
      ['42%', '90%', '5.5s', '0.8s'],
      ['92%', '45%', '3.8s', '2.5s'],
      ['18%', '55%', '5.2s', '1.2s'],
    ];
    foreach ($positions as [$l, $t, $dur, $delay]):
      ?>
      <div class="po-particle" style="left:<?= $l ?>;top:<?= $t ?>;animation-duration:<?= $dur ?>;animation-delay:<?= $delay ?>;">
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Hero text -->
  <div class="po-hero-text">
    <div class="container">
      <div class="po-hero-badge"><span></span> PESANAN SAYA • RAMEZA EGG FARM</div>
      <h1>Riwayat <em>Pesanan</em></h1>
      <p>Pantau status pesanan dan riwayat belanja Anda di Rameza Farm.</p>
    </div>
  </div>

  <!-- Stats (masih di dalam blok biru) -->
  <?php if (!empty($pesanan)): ?>
    <div class="container">
      <div class="po-stats-row">
        <div class="po-stat">
          <div class="po-stat-icon">🛒</div>
          <div class="po-stat-val"><?= $total_pesanan ?></div>
          <div class="po-stat-label">Total Pesanan</div>
        </div>
        <div class="po-stat">
          <div class="po-stat-icon">⏳</div>
          <div class="po-stat-val gold"><?= $total_pending ?></div>
          <div class="po-stat-label">Menunggu Bayar</div>
        </div>
        <div class="po-stat">
          <div class="po-stat-icon">✅</div>
          <div class="po-stat-val green"><?= $total_selesai ?></div>
          <div class="po-stat-label">Selesai</div>
        </div>
        <div class="po-stat">
          <div class="po-stat-icon">💰</div>
          <div class="po-stat-val cyan">Rp <?= number_format($total_belanja, 0, ',', '.') ?></div>
          <div class="po-stat-label">Total Belanja</div>
        </div>
      </div>
    </div>
  <?php else: ?>
    <div style="padding-bottom:52px;"></div>
  <?php endif; ?>

  <!-- Wave pemisah -->
  <svg class="po-wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 54" preserveAspectRatio="none"
    style="height:54px;">
    <path fill="#f0f4f8" d="M0,32 C240,64 480,0 720,32 C960,64 1200,0 1440,32 L1440,54 L0,54 Z" />
  </svg>
</div>

<!-- ══════════════════════════════════
     BODY
     ══════════════════════════════════ -->
<div class="po-body">
  <div class="container">

    <?php if (empty($pesanan)): ?>
      <div class="po-card">
        <div class="po-empty">
          <div class="po-empty-icon">🛒</div>
          <h3>Belum Ada Pesanan</h3>
          <p>Anda belum melakukan pemesanan apapun.</p>
          <a href="produk.php" class="po-btn-shop">🛍️ Mulai Belanja Sekarang</a>
        </div>
      </div>

    <?php else: ?>
      <div class="po-card">
        <div class="po-card-head">
          <h2>📋 Daftar Pesanan</h2>
          <div class="po-filters">
            <a href="?filter=semua" class="po-tab <?= $filter === 'semua' ? 'active' : '' ?>">Semua <span
                style="opacity:.55;">(<?= $total_pesanan ?>)</span></a>
            <a href="?filter=pending" class="po-tab <?= $filter === 'pending' ? 'active' : '' ?>">⏳ Pending</a>
            <a href="?filter=selesai" class="po-tab <?= $filter === 'selesai' ? 'active' : '' ?>">✅ Selesai</a>
            <a href="?filter=dibatalkan" class="po-tab <?= $filter === 'dibatalkan' ? 'active' : '' ?>">❌ Dibatalkan</a>
          </div>
        </div>

        <?php if (empty($pesanan_filtered)): ?>
          <div class="po-empty">
            <div class="po-empty-icon">🔍</div>
            <h3>Tidak Ditemukan</h3>
            <p>Tidak ada pesanan dengan status <strong><?= ucfirst($filter) ?></strong>.</p>
            <a href="pesanan_pelanggan.php" class="po-btn-detail" style="display:inline-flex;">← Lihat semua pesanan</a>
          </div>
        <?php else: ?>
          <table class="po-table">
            <thead>
              <tr>
                <th>Kode Pesanan</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pesanan_filtered as $p):
                $sl = strtolower($p['status']);
                $bc = match ($sl) {
                  'pending' => 'po-badge-pending',
                  'dikonfirmasi', 'diproses' => 'po-badge-dikonfirmasi',
                  'dikirim' => 'po-badge-dikirim',
                  'selesai' => 'po-badge-selesai',
                  'dibatalkan' => 'po-badge-dibatalkan',
                  default => 'po-badge-default',
                };
                $detail_url = 'detail_pesanan.php?kode=' . urlencode($p['kode_pesanan']);
                ?>
                <!-- Seluruh baris bisa diklik ke detail -->
                <tr class="po-row-link" onclick="window.location='<?= $detail_url ?>'" style="cursor:pointer;">
                  <td><span class="po-kode">#<?= htmlspecialchars($p['kode_pesanan']) ?></span></td>
                  <td><span class="po-tanggal"><?= date('d M Y, H:i', strtotime($p['tanggal_pesan'])) ?></span></td>
                  <td><span class="po-harga">Rp <?= number_format($p['grand_total'], 0, ',', '.') ?></span></td>
                  <td><span class="po-badge <?= $bc ?>"><span class="po-dot"></span><?= ucfirst($p['status']) ?></span></td>
                  <td onclick="event.stopPropagation()">
                    <div class="po-aksi-wrap">
                      <?php if ($sl === 'pending'): ?>
                        <!-- Tombol Bayar langsung ke payment, tombol Detail ke halaman detail -->
                        <a href="../controller/sukses.php?kode=<?= urlencode($p['kode_pesanan']) ?>" class="po-btn-bayar">💳
                          Bayar</a>
                        <a href="<?= $detail_url ?>" class="po-btn-detail">👁 Detail</a>
                      <?php else: ?>
                        <a href="<?= $detail_url ?>" class="po-btn-detail">👁 Detail</a>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    <?php endif; ?>

  </div>
</div>

<?php include '../includes/footer.php'; ?>