<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    header('Location: ../loginuser.php');
    exit();
}

// Filter kategori dan search
$kategori = $_GET['kategori'] ?? '';
$search   = trim($_GET['q'] ?? '');

// Build query dengan MySQLi
$where  = ['aktif = 1']; // Hanya produk aktif
$types  = '';
$params = [];

if ($kategori) {
    $where[] = 'kategori = ?';
    $types  .= 's';
    $params[] = $kategori;
}

if ($search) {
    $where[] = '(nama_produk LIKE ? OR deskripsi LIKE ?)';
    $types  .= 'ss';
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql = "SELECT * FROM produk WHERE " . implode(' AND ', $where) . " ORDER BY kategori, nama_produk";
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$produkList = $result->fetch_all(MYSQLI_ASSOC);

// Hitung total item di keranjang
$cartCountSql = "SELECT COALESCE(SUM(jumlah), 0) as total FROM keranjang WHERE id_pelanggan = ?";
$cartStmt = $conn->prepare($cartCountSql);
$cartStmt->bind_param('i', $_SESSION['id_pelanggan']);
$cartStmt->execute();
$cartResult = $cartStmt->get_result();
$cartCount = $cartResult->fetch_assoc()['total'];

$categories = [
    ''       => 'Semua Produk',
    'telur'  => '🥚 Telur Ayam',
    'bibit'  => '🐣 Bibit Unggas',
    'pakan'  => '🌾 Pakan & Pulet',
    'obat'   => '💊 Vitamin & Obat',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - <?= NAMA_TOKO ?></title>
    <link rel="stylesheet" href="../assets/css/shop.css">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

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
        <div class="product-card" data-id="<?= $p['id_produk'] ?>">

          <!-- Gambar -->
          <div class="product-img-wrap">
            <?php
              $imgSrc = $p['gambar'] ?: '../assets/img/no-image.png';
              $catBadge = [
                'telur' => ['label' => '🥚 Unggulan', 'class' => 'badge-amber'],
                'bibit' => ['label' => '🐣 Bibit',    'class' => 'badge-green'],
                'pakan' => ['label' => '🌾 Pakan',    'class' => 'badge-blue'],
                'obat'  => ['label' => '💊 Vitamin',  'class' => 'badge-purple'],
              ];
              $badge = $catBadge[$p['kategori']] ?? ['label' => $p['kategori'], 'class' => 'badge-blue'];
            ?>
            <img src="<?= htmlspecialchars($imgSrc) ?>"
                 alt="<?= htmlspecialchars($p['nama_produk']) ?>"
                 onerror="this.src='https://placehold.co/400x280/e8eefb/2d5be3?text=<?= urlencode($p['nama_produk']) ?>'"/>
            <span class="product-badge <?= $badge['class'] ?>"><?= $badge['label'] ?></span>
            <?php if ($p['stok'] <= 5 && $p['stok'] > 0): ?>
              <span class="stok-badge">Stok terbatas!</span>
            <?php elseif ($p['stok'] == 0): ?>
              <span class="stok-badge stok-habis">Stok habis</span>
            <?php endif; ?>
          </div>

          <!-- Info -->
          <div class="product-body">
            <div class="product-kode"><?= htmlspecialchars($p['kode_produk']) ?></div>
            <h3 class="product-name"><?= htmlspecialchars($p['nama_produk']) ?></h3>
            <p class="product-desc"><?= htmlspecialchars(substr($p['deskripsi'], 0, 90)) ?>…</p>

            <div class="product-footer">
              <div class="product-price">
                <span class="price"><?= formatRupiah($p['harga']) ?></span>
                <span class="price-unit">/ <?= htmlspecialchars($p['satuan']) ?></span>
              </div>

              <?php if ($p['stok'] > 0): ?>
                <div class="qty-add-wrap">
                  <div class="qty-control">
                    <button type="button" class="qty-btn minus" aria-label="Kurang">−</button>
                    <input type="number" class="qty-input" value="<?= $p['min_pesan'] ?>"
                           min="<?= $p['min_pesan'] ?>" max="<?= $p['stok'] ?>"/>
                    <button type="button" class="qty-btn plus" aria-label="Tambah">+</button>
                  </div>
                  <button type="button" class="add-to-cart-btn"
                          data-id="<?= $p['id_produk'] ?>"
                          data-nama="<?= htmlspecialchars($p['nama_produk']) ?>"
                          data-harga="<?= $p['harga'] ?>"
                          data-min="<?= $p['min_pesan'] ?>"
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

<?php include '../includes/footer.php'; ?>

<script>
// Quantity Control
document.querySelectorAll('.product-card').forEach(card => {
    const minusBtn = card.querySelector('.minus');
    const plusBtn = card.querySelector('.plus');
    const qtyInput = card.querySelector('.qty-input');
    
    if (minusBtn && plusBtn && qtyInput) {
        const min = parseInt(qtyInput.getAttribute('min'));
        const max = parseInt(qtyInput.getAttribute('max'));
        
        minusBtn.addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            if (val > min) {
                qtyInput.value = val - 1;
            }
        });
        
        plusBtn.addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            if (val < max) {
                qtyInput.value = val + 1;
            }
        });
    }
});

// Add to Cart
document.querySelectorAll('.add-to-cart-btn:not(.disabled)').forEach(btn => {
    btn.addEventListener('click', function() {
        const card = this.closest('.product-card');
        const qtyInput = card.querySelector('.qty-input');
        const qty = parseInt(qtyInput.value);
        
        const data = {
            id_produk: this.dataset.id,
            nama: this.dataset.nama,
            harga: this.dataset.harga,
            satuan: this.dataset.satuan,
            jumlah: qty
        };
        
        // Kirim ke server via AJAX
        fetch('../controller/tambah_keranjang.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showToast('✓ ' + data.nama + ' ditambahkan ke keranjang!', 'success');
                // Reset qty ke min
                qtyInput.value = this.dataset.min;
            } else {
                showToast('✗ ' + (result.message || 'Gagal menambahkan produk'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('✗ Terjadi kesalahan', 'error');
        });
    });
});

// Toast Notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;
    toast.className = 'toast show ' + type;
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}
</script>

</body>
</html>
