<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/cart_helper.php';

// Filter kategori
$kategori = $_GET['kategori'] ?? '';
$search   = trim($_GET['q'] ?? '');

$where  = [];
$params = [];

if ($kategori) {
    $where[]  = 'p.kategori = ?';
    $params[] = $kategori;
}
if ($search) {
    $where[]  = '(p.nama LIKE ? OR p.deskripsi LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql     = "SELECT * FROM produk p WHERE " . implode(' AND ', $where) . " ORDER BY p.kategori, p.nama";
$stmt    = $pdo->prepare($sql);
$stmt->execute($params);
$produkList = $stmt->fetchAll();

$cartCount = cartCount();

$categories = [
    ''       => 'Semua Produk',
    'telur'  => '🥚 Telur Ayam',
    'bibit'  => '🐣 Bibit Unggas',
    'pakan'  => '🌾 Pakan & Pulet',
    'obat'   => '💊 Vitamin & Obat',
];
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!-- PAGE HEADER -->
<div class="page-header">
  <div class="container">
    <h1 class="page-title">Produk <span class="blue">Kami</span></h1>
    <p class="page-desc">Telur segar, bibit unggas, pakan, dan vitamin berkualitas langsung dari peternak.</p>

    <!-- Search -->
    <form method="GET" class="search-form">
      <?php if ($kategori): ?>
        <input type="hidden" name="kategori" value="<?= htmlspecialchars($kategori) ?>"/>
      <?php endif; ?>
      <input type="text" name="q" class="search-input"
             placeholder="Cari produk…"
             value="<?= htmlspecialchars($search) ?>"/>
      <button type="submit" class="search-btn">🔍 Cari</button>
    </form>
  </div>
</div>

<!-- CONTENT -->
<main class="container" style="padding:40px 0 80px;">

  <!-- Category Tabs -->
  <div class="cat-tabs">
    <?php foreach ($categories as $key => $label): ?>
      <a href="produk.php?kategori=<?= $key ?><?= $search ? '&q=' . urlencode($search) : '' ?>"
         class="cat-tab <?= $kategori === $key ? 'active' : '' ?>">
        <?= $label ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- Results count -->
  <p class="result-count">
    <?= count($produkList) ?> produk ditemukan
    <?php if ($search): ?> untuk "<strong><?= htmlspecialchars($search) ?></strong>"<?php endif; ?>
  </p>

  <!-- Product Grid -->
  <?php if (empty($produkList)): ?>
    <div class="empty-state">
      <div class="empty-icon">📦</div>
      <h3>Produk tidak ditemukan</h3>
      <p>Coba kata kunci lain atau pilih kategori yang berbeda.</p>
      <a href="produk.php" class="btn-primary">Lihat Semua Produk</a>
    </div>
  <?php else: ?>
    <div class="product-grid">
      <?php foreach ($produkList as $p): ?>
        <div class="product-card" data-id="<?= $p['id'] ?>">

          <!-- Gambar -->
          <div class="product-img-wrap">
            <?php
              $imgSrc = $p['gambar'] ?: 'assets/img/no-image.png';
              $catBadge = [
                'telur' => ['label' => '🥚 Unggulan', 'class' => 'badge-amber'],
                'bibit' => ['label' => '🐣 Bibit',    'class' => 'badge-green'],
                'pakan' => ['label' => '🌾 Pakan',    'class' => 'badge-blue'],
                'obat'  => ['label' => '💊 Vitamin',  'class' => 'badge-purple'],
              ];
              $badge = $catBadge[$p['kategori']] ?? ['label' => $p['kategori'], 'class' => 'badge-blue'];
            ?>
            <img src="<?= htmlspecialchars($imgSrc) ?>"
                 alt="<?= htmlspecialchars($p['nama']) ?>"
                 onerror="this.src='https://placehold.co/400x280/e8eefb/2d5be3?text=<?= urlencode($p['nama']) ?>'"/>
            <span class="product-badge <?= $badge['class'] ?>"><?= $badge['label'] ?></span>
            <?php if ($p['stok'] <= 5 && $p['stok'] > 0): ?>
              <span class="stok-badge">Stok terbatas!</span>
            <?php elseif ($p['stok'] == 0): ?>
              <span class="stok-badge stok-habis">Stok habis</span>
            <?php endif; ?>
          </div>

          <!-- Info -->
          <div class="product-body">
            <div class="product-kode"><?= htmlspecialchars($p['kode']) ?></div>
            <h3 class="product-name"><?= htmlspecialchars($p['nama']) ?></h3>
            <p class="product-desc"><?= htmlspecialchars(substr($p['deskripsi'], 0, 90)) ?>…</p>

            <div class="product-footer">
              <div class="product-price">
                <span class="price"><?= rupiah($p['harga']) ?></span>
                <span class="price-unit">/ <?= htmlspecialchars($p['satuan']) ?></span>
              </div>

              <?php if ($p['stok'] > 0): ?>
                <div class="qty-add-wrap">
                  <div class="qty-control">
                    <button class="qty-btn minus" aria-label="Kurang">−</button>
                    <input type="number" class="qty-input" value="<?= $p['min_pesan'] ?>"
                           min="<?= $p['min_pesan'] ?>" max="<?= $p['stok'] ?>"/>
                    <button class="qty-btn plus" aria-label="Tambah">+</button>
                  </div>
                  <button class="add-to-cart-btn"
                          data-id="<?= $p['id'] ?>"
                          data-nama="<?= htmlspecialchars($p['nama']) ?>"
                          data-harga="<?= $p['harga'] ?>"
                          data-satuan="<?= htmlspecialchars($p['satuan']) ?>">
                    🛒 Tambah
                  </button>
                </div>
              <?php else: ?>
                <button class="add-to-cart-btn disabled" disabled>Stok Habis</button>
              <?php endif; ?>
            </div>
          </div>

        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</main>

<!-- TOAST NOTIFICATION -->
<div id="toast" class="toast" role="alert" aria-live="polite"></div>

<!-- CART SIDEBAR (mini preview) -->
<div id="cart-sidebar" class="cart-sidebar">
  <div class="cart-sidebar-header">
    <span>🛒 Keranjang Belanja</span>
    <button id="close-sidebar">✕</button>
  </div>
  <div id="cart-sidebar-items" class="cart-sidebar-items">
    <p class="cart-empty-msg">Keranjang masih kosong.</p>
  </div>
  <div class="cart-sidebar-footer">
    <div class="cart-sidebar-total">
      Total: <strong id="sidebar-total">Rp 0</strong>
    </div>
    <a href="keranjang.php" class="btn-primary" style="display:block;text-align:center;margin-top:12px;">
      Lihat Keranjang →
    </a>
  </div>
</div>
<div id="cart-overlay" class="cart-overlay"></div>


<?php include 'includes/footer.php'; ?>
