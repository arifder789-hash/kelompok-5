<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/constants.php';

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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f8f9fa;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #2d5be3 0%, #1e40af 100%);
            color: white;
            padding: 60px 0 40px;
            text-align: center;
        }
        
        .page-title {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .page-title .blue {
            color: #fbbf24;
        }
        
        .page-desc {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 30px;
        }
        
        /* Search Form */
        .search-form {
            display: flex;
            gap: 10px;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .search-input {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .search-btn {
            padding: 12px 30px;
            background: #fbbf24;
            color: #1e40af;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .search-btn:hover {
            background: #f59e0b;
            transform: translateY(-2px);
        }
        
        /* Category Tabs */
        .cat-tabs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 30px 0;
            padding: 0;
        }
        
        .cat-tab {
            padding: 10px 20px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            text-decoration: none;
            color: #6b7280;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .cat-tab:hover {
            border-color: #2d5be3;
            color: #2d5be3;
        }
        
        .cat-tab.active {
            background: #2d5be3;
            border-color: #2d5be3;
            color: white;
        }
        
        /* Result Count */
        .result-count {
            margin: 20px 0;
            color: #6b7280;
        }
        
        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
            margin-top: 30px;
        }
        
        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .product-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            transform: translateY(-4px);
        }
        
        .product-img-wrap {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
            background: #f3f4f6;
        }
        
        .product-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .badge-amber { background: #fbbf24; color: #78350f; }
        .badge-green { background: #10b981; color: white; }
        .badge-blue { background: #3b82f6; color: white; }
        .badge-purple { background: #8b5cf6; color: white; }
        
        .stok-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 4px 12px;
            background: #f59e0b;
            color: white;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .stok-badge.stok-habis {
            background: #ef4444;
        }
        
        .product-body {
            padding: 16px;
        }
        
        .product-kode {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 4px;
        }
        
        .product-name {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #111827;
        }
        
        .product-desc {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 16px;
            line-height: 1.5;
        }
        
        .product-footer {
            border-top: 1px solid #e5e7eb;
            padding-top: 16px;
        }
        
        .product-price {
            margin-bottom: 12px;
        }
        
        .price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2d5be3;
        }
        
        .price-unit {
            font-size: 0.9rem;
            color: #6b7280;
        }
        
        /* Quantity Control */
        .qty-add-wrap {
            display: flex;
            gap: 8px;
        }
        
        .qty-control {
            display: flex;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .qty-btn {
            width: 32px;
            height: 36px;
            border: none;
            background: #f3f4f6;
            cursor: pointer;
            font-size: 1.2rem;
            color: #374151;
            transition: all 0.2s;
        }
        
        .qty-btn:hover {
            background: #e5e7eb;
        }
        
        .qty-input {
            width: 50px;
            border: none;
            text-align: center;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .qty-input::-webkit-inner-spin-button,
        .qty-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        .add-to-cart-btn {
            flex: 1;
            padding: 8px 16px;
            background: #2d5be3;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .add-to-cart-btn:hover {
            background: #1e40af;
        }
        
        .add-to-cart-btn.disabled {
            background: #d1d5db;
            cursor: not-allowed;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
        }
        
        .empty-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        
        .empty-state p {
            color: #6b7280;
            margin-bottom: 24px;
        }
        
        .btn-primary {
            display: inline-block;
            padding: 12px 24px;
            background: #2d5be3;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background: #1e40af;
        }
        
        /* Toast Notification */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            background: #10b981;
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateX(400px);
            transition: transform 0.3s;
            z-index: 9999;
        }
        
        .toast.show {
            transform: translateX(0);
        }
        
        .toast.error {
            background: #ef4444;
        }
    </style>
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
              if (str_starts_with($imgSrc, 'assets/')) {
                $imgSrc = '../' . $imgSrc;
              }
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
