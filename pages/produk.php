<?php
/* ============================================================
   RAMEZA FARM — pages/produk.php
   Halaman Katalog Produk
============================================================ */
session_start();
require_once __DIR__ . '/../config/db.php';

// Proteksi — harus login
if (!isset($_SESSION['id_pelanggan'])) {
  header('Location: ../loginuser.php');
  exit;
}

/* ── Filter & query ── */
$kategori = $_GET['kategori'] ?? '';
$search = trim($_GET['q'] ?? '');
$where = ['aktif = 1'];
$types = '';
$params = [];

if ($kategori) {
  $where[] = 'kategori = ?';
  $types .= 's';
  $params[] = $kategori;
}
if ($search) {
  $where[] = '(nama_produk LIKE ? OR deskripsi LIKE ?)';
  $types .= 'ss';
  $params[] = "%$search%";
  $params[] = "%$search%";
}

$sql = "SELECT * FROM produk WHERE " . implode(' AND ', $where) . " ORDER BY kategori, nama_produk";
$stmt = $conn->prepare($sql);
if ($params)
  $stmt->bind_param($types, ...$params);
$stmt->execute();
$produkList = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/* ── Count per kategori (for badges) ── */
$countSql = "SELECT kategori, COUNT(*) AS cnt FROM produk WHERE aktif=1 GROUP BY kategori";
$countRows = $conn->query($countSql)->fetch_all(MYSQLI_ASSOC);
$katCount = array_column($countRows, 'cnt', 'kategori');
$totalAll = array_sum($katCount);

/* ── Cart count ── */
require_once __DIR__ . '/../includes/cart_helper.php';
$cartCount = cartCount();

$categories = [
  '' => ['label' => 'Semua', 'emoji' => '🛒'],
  'telur' => ['label' => 'Telur Ayam', 'emoji' => '🥚'],
  'bibit' => ['label' => 'Bibit Unggas', 'emoji' => '🐣'],
  'pakan' => ['label' => 'Pakan & Pulet', 'emoji' => '🌾'],
  'obat' => ['label' => 'Vitamin & Obat', 'emoji' => '💊'],
];

$rootPath = '../';
?>
<?php
include '../includes/header.php';
include '../includes/navbar.php';
?>


<!-- ════════════════════════════════════
     PAGE HEADER
════════════════════════════════════ -->
<div class="page-header">
  <div class="container">
    <div class="header-tag">
      <span class="header-dot"></span>
      Katalog Produk &bull; Rameza Egg Farm
    </div>
    <h1 class="page-title">Katalog <em>Produk</em></h1>
    <p class="page-desc">Telur segar, bibit unggas, pakan, dan vitamin berkualitas langsung dari peternak.</p>

    <form method="GET" class="search-form" role="search">
      <?php if ($kategori): ?>
        <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori) ?>" />
      <?php endif; ?>
      <input type="text" name="q" class="search-input" placeholder="🔍  Cari produk… (Ctrl+K)"
        value="<?= htmlspecialchars($search) ?>" autocomplete="off" />
      <button type="submit" class="search-btn">Cari</button>
    </form>
  </div>
</div>


<!-- ════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════ -->
<main style="padding: 40px 0 120px;">
  <div class="container">

    <!-- TOOLBAR: tabs + sort + view -->
    <div class="product-toolbar">

      <!-- Category tabs -->
      <div class="cat-tabs" role="tablist">
        <?php foreach ($categories as $key => $cat):
          $count = $key === '' ? $totalAll : ($katCount[$key] ?? 0);
          ?>
          <a href="produk.php?kategori=<?= urlencode($key) ?><?= $search ? '&q=' . urlencode($search) : '' ?>"
            class="cat-tab <?= $kategori === $key ? 'active' : '' ?>" role="tab"
            aria-selected="<?= $kategori === $key ? 'true' : 'false' ?>">
            <?= $cat['emoji'] ?>   <?= $cat['label'] ?>
            <span class="cat-count"><?= $count ?></span>
          </a>
        <?php endforeach; ?>
      </div>

      <!-- Sort + view toggle -->
      <div class="toolbar-right">
        <select class="sort-select" id="sort-select" aria-label="Urutkan produk">
          <option value="">Urutan Default</option>
          <option value="harga-asc">Harga: Terendah</option>
          <option value="harga-desc">Harga: Tertinggi</option>
          <option value="nama-asc">Nama: A–Z</option>
          <option value="nama-desc">Nama: Z–A</option>
        </select>
        <div class="view-toggle" role="group" aria-label="Tampilan produk">
          <button class="view-btn active" id="view-grid" aria-label="Tampilan grid" title="Grid">⊞</button>
          <button class="view-btn" id="view-list" aria-label="Tampilan list" title="List">☰</button>
        </div>
      </div>
    </div>

    <!-- Result count -->
    <p class="result-count">
      Menampilkan <strong><?= count($produkList) ?></strong> produk
      <?php if ($search): ?> — hasil pencarian "<strong><?= htmlspecialchars($search) ?></strong>"<?php endif; ?>
      <?php if ($kategori): ?> dalam kategori
        <strong><?= htmlspecialchars($categories[$kategori]['emoji'] . ' ' . $categories[$kategori]['label']) ?></strong><?php endif; ?>
    </p>


    <!-- PRODUCT GRID -->
    <?php if (empty($produkList)): ?>
      <div class="empty-state">
        <div class="empty-icon">🔍</div>
        <h3>Produk tidak ditemukan</h3>
        <p>Coba kata kunci lain atau pilih kategori yang berbeda.</p>
        <a href="produk.php" class="btn-primary">Lihat Semua Produk</a>
      </div>

    <?php else: ?>
      <div class="product-grid" id="product-grid">

        <?php foreach ($produkList as $i => $p):
          $imgFile = !empty($p['gambar']) ? $p['gambar'] : 'logo_ayam.png';
          if (strpos($imgFile, 'assets/img/') === 0) {
              $imgSrc = '../' . $imgFile;
          } elseif (strpos($imgFile, '/') !== false) {
              $imgSrc = $imgFile;
          } else {
              $imgSrc = '../assets/img/' . $imgFile;
          }
          $badges = [
            'telur' => ['label' => '🥚 Unggulan', 'class' => 'badge-amber'],
            'bibit' => ['label' => '🐣 Bibit', 'class' => 'badge-green'],
            'pakan' => ['label' => '🌾 Pakan', 'class' => 'badge-blue'],
            'obat' => ['label' => '💊 Vitamin', 'class' => 'badge-purple'],
          ];
          $badge = $badges[$p['kategori']] ?? ['label' => ucfirst($p['kategori']), 'class' => 'badge-blue'];

          // Random rating untuk tampilan (ganti dengan data nyata jika ada)
          $rating = 4 + (($p['id_produk'] % 2) * 0.5);
          ?>
          <div class="product-card" data-id="<?= $p['id_produk'] ?>" data-harga="<?= $p['harga'] ?>"
            data-satuan="<?= htmlspecialchars($p['satuan']) ?>" data-stok="<?= $p['stok'] ?>"
            data-min="<?= $p['min_pesan'] ?>" data-deskripsi="<?= htmlspecialchars($p['deskripsi'] ?? '') ?>"
            style="animation-delay: <?= $i * 0.06 ?>s">

            <!-- Wishlist -->
            <button class="wishlist-btn" data-id="<?= $p['id_produk'] ?>" aria-label="Wishlist">🤍</button>

            <!-- Image -->
            <div class="product-img-wrap">
              <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($p['nama_produk']) ?>" loading="lazy"
                onerror="this.src='../assets/img/logo_ayam.png'" />

              <span class="product-badge <?= $badge['class'] ?>"><?= $badge['label'] ?></span>

              <?php if ($p['stok'] <= 5 && $p['stok'] > 0): ?>
                <span class="stok-badge">Stok terbatas!</span>
              <?php elseif ($p['stok'] == 0): ?>
                <span class="stok-badge stok-habis">Stok habis</span>
              <?php endif; ?>

              <!-- Quick View -->
              <div class="product-quick-view">
                <button class="quick-view-btn" data-id="<?= $p['id_produk'] ?>" type="button">
                  🔍 Lihat Detail
                </button>
              </div>
            </div>

            <!-- Body -->
            <div class="product-body">
              <div class="product-kode"><?= htmlspecialchars($p['kode_produk']) ?></div>
              <h3 class="product-name"><?= htmlspecialchars($p['nama_produk']) ?></h3>

              <!-- Stars -->
              <div class="product-rating" aria-label="Rating <?= $rating ?> dari 5">
                <div class="stars">
                  <?php for ($s = 1; $s <= 5; $s++): ?>
                    <span class="star <?= $s > $rating ? 'empty' : '' ?>">★</span>
                  <?php endfor; ?>
                </div>
                <span class="rating-count">(<?= 12 + $p['id_produk'] * 3 ?>)</span>
              </div>

              <p class="product-desc"><?= htmlspecialchars(substr($p['deskripsi'] ?? '', 0, 90)) ?>…</p>

              <div class="product-footer">
                <div class="product-price">
                  <span class="price"><?= formatRupiah($p['harga']) ?></span>
                  <span class="price-unit">/ <?= htmlspecialchars($p['satuan']) ?></span>
                </div>

                <?php if ($p['stok'] > 0): ?>
                  <div class="qty-add-wrap">
                    <div class="qty-control">
                      <button type="button" class="qty-btn minus" aria-label="Kurang">−</button>
                      <input type="number" class="qty-input" value="<?= $p['min_pesan'] ?>" min="<?= $p['min_pesan'] ?>"
                        max="<?= $p['stok'] ?>" aria-label="Jumlah" />
                      <button type="button" class="qty-btn plus" aria-label="Tambah">+</button>
                    </div>
                    <button type="button" class="add-to-cart-btn" data-id="<?= $p['id_produk'] ?>"
                      data-nama="<?= htmlspecialchars($p['nama_produk']) ?>" data-harga="<?= $p['harga'] ?>"
                      data-satuan="<?= htmlspecialchars($p['satuan']) ?>" data-min="<?= $p['min_pesan'] ?>">
                      🛒 Tambah
                    </button>
                  </div>
                <?php else: ?>
                  <button class="add-to-cart-btn disabled" disabled>Stok Habis</button>
                <?php endif; ?>
              </div>
            </div>

          </div><!-- /product-card -->
        <?php endforeach; ?>

      </div><!-- /product-grid -->
    <?php endif; ?>

  </div>
</main>


<!-- ════════════════════════════════════
     FLOATING CART BUTTON
════════════════════════════════════ -->
<button class="cart-fab" id="cart-fab" aria-label="Buka keranjang">
  🛒
  <span class="cart-badge" id="cart-badge"><?= $cartCount ?></span>
</button>


<!-- ════════════════════════════════════
     CART SIDEBAR
════════════════════════════════════ -->
<div class="cart-overlay" id="cart-overlay"></div>
<div class="cart-sidebar" id="cart-sidebar" role="dialog" aria-label="Keranjang belanja">
  <div class="cart-sidebar-header">
    <span>🛒 Keranjang Saya</span>
    <button id="close-sidebar" aria-label="Tutup keranjang">✕</button>
  </div>
  <div class="cart-sidebar-items" id="cart-sidebar-items">
    <div class="cart-empty-msg">
      <div class="cart-empty-icon">🛒</div>
      <p>Keranjang masih kosong.<br />Yuk tambah produk!</p>
    </div>
  </div>
  <div class="cart-sidebar-footer">
    <div style="display:flex; justify-content:space-between; margin-bottom:14px;">
      <span style="font-size:14px; color:#64748b; font-weight:600;">Total</span>
      <span class="cart-sidebar-total" id="sidebar-total">Rp 0</span>
    </div>
    <a href="keranjang.php" class="btn-primary" style="display:flex; justify-content:center; margin-bottom:10px;">
      Lihat Keranjang →
    </a>
    <a href="checkout.php" class="btn-outline-blue" style="display:flex; justify-content:center;">
      Checkout Langsung →
    </a>
  </div>
</div>


<!-- ════════════════════════════════════
     QUICK VIEW MODAL
════════════════════════════════════ -->
<div class="modal-overlay" id="modal-overlay" role="dialog" aria-modal="true" aria-label="Detail produk">
  <div class="modal-box">
    <button class="modal-close" id="modal-close" aria-label="Tutup">✕</button>

    <div class="modal-img">
      <img id="modal-img-el" src="" alt="Foto produk" />
    </div>

    <div class="modal-body">
      <div class="modal-kode" id="modal-kode"></div>
      <h2 class="modal-name" id="modal-name"></h2>
      <p class="modal-desc" id="modal-desc"></p>
      <div class="modal-price" id="modal-price"></div>
      <div class="modal-meta" id="modal-meta"></div>
      <div class="modal-stok" id="modal-stok"></div>

      <div class="qty-add-wrap">
        <div class="qty-control">
          <button type="button" class="qty-btn minus" onclick="
            const i=document.getElementById('modal-qty');
            i.value=Math.max(1,+i.value-1);
          ">−</button>
          <input type="number" class="qty-input" id="modal-qty" value="1" min="1" />
          <button type="button" class="qty-btn plus" onclick="
            const i=document.getElementById('modal-qty');
            i.value=+i.value+1;
          ">+</button>
        </div>
        <button type="button" class="add-to-cart-btn" id="modal-add-btn" data-id="" data-nama="">
          🛒 Tambah ke Keranjang
        </button>
      </div>
    </div>
  </div>
</div>


<!-- ════════════════════════════════════
     TOAST
════════════════════════════════════ -->
<div id="toast" class="toast" role="alert" aria-live="polite"></div>


<!-- ════════════════════════════════════
     FOOTER
════════════════════════════════════ -->
<?php include '../includes/footer.php'; ?>