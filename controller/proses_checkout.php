<?php
// ============================================
//  proses_checkout.php — Simpan Pesanan ke DB
// ============================================
<<<<<<< HEAD
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/cart_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/checkout.php'); exit;
}

if (empty($_SESSION['id_pelanggan'])) {
    header('Location: ../loginuser.php'); exit;
=======
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/cart_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php'); exit;
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
}

// CSRF check
if (!isset($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
<<<<<<< HEAD
    die('Sesi tidak valid. <a href="../pages/checkout.php">Kembali</a>');
=======
    die('Sesi tidak valid. <a href="checkout.php">Kembali</a>');
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
}

// Redirect jika cart kosong
if (cartEmpty()) {
<<<<<<< HEAD
    header('Location: ../pages/produk.php'); exit;
=======
    header('Location: produk.php'); exit;
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
}

// ── Sanitize input ────────────────────────────
$nama       = trim(htmlspecialchars($_POST['nama']        ?? ''));
$no_wa_raw  = preg_replace('/\D/', '', $_POST['no_wa']   ?? '');   // angka saja
$email      = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
$alamat     = trim(htmlspecialchars($_POST['alamat']      ?? ''));
$kota       = trim(htmlspecialchars($_POST['kota']        ?? ''));
$catatan    = trim(htmlspecialchars($_POST['catatan']     ?? ''));
$metode     = in_array($_POST['metode_bayar'] ?? '', ['cod','transfer','wa'])
              ? $_POST['metode_bayar'] : 'wa';

// ── Validasi ─────────────────────────────────
$errors = [];
if (!$nama)                          $errors[] = 'Nama lengkap wajib diisi.';
if (strlen($no_wa_raw) < 8)         $errors[] = 'Nomor WhatsApp tidak valid.';
if (!$alamat)                        $errors[] = 'Alamat pengiriman wajib diisi.';
if (!$kota)                          $errors[] = 'Kota / Kecamatan wajib diisi.';

if ($errors) {
    $_SESSION['checkout_errors'] = $errors;
    $_SESSION['checkout_old']    = $_POST;
<<<<<<< HEAD
    header('Location: ../pages/checkout.php'); exit;
=======
    header('Location: checkout.php'); exit;
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
}

// ── Hitung total dari session (jangan percaya POST) ──
$items = cartGetAll();
$total = cartTotal();

// ── Simpan ke database (transaction) ─────────
try {
    $pdo->beginTransaction();

    $kodePesanan = generateKodePesanan();

    // 1. Insert header pesanan
    $stmt = $pdo->prepare("
        INSERT INTO pesanan
<<<<<<< HEAD
            (id_pelanggan, kode_pesanan, nama_penerima, no_wa, alamat_kirim, kota, catatan,
=======
            (kode_pesanan, nama_pembeli, no_wa, email, alamat, kota, catatan,
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
             total_harga, ongkir, grand_total, metode_bayar, status)
        VALUES (?,?,?,?,?,?,?, ?,?,?, ?, 'pending')
    ");
    $stmt->execute([
<<<<<<< HEAD
        $_SESSION['id_pelanggan'],
        $kodePesanan,
        $nama, $no_wa_raw,
=======
        $kodePesanan,
        $nama, $no_wa_raw, $email,
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
        $alamat, $kota, $catatan,
        $total, 0, $total,
        $metode
    ]);
    $pesananId = (int) $pdo->lastInsertId();

    // 2. Insert detail items
    $stmtDetail = $pdo->prepare("
<<<<<<< HEAD
        INSERT INTO detail_pesanan (id_pesanan, id_produk, nama_produk, harga_saat_beli, satuan, jumlah, subtotal)
=======
        INSERT INTO detail_pesanan (pesanan_id, produk_id, nama_produk, harga, satuan, qty, subtotal)
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
        VALUES (?,?,?,?,?,?,?)
    ");
    foreach ($items as $item) {
        $stmtDetail->execute([
            $pesananId,
            $item['produk_id'],
            $item['nama'],
            $item['harga'],
            $item['satuan'],
            $item['qty'],
            $item['subtotal'],
        ]);
        // Kurangi stok
<<<<<<< HEAD
        $pdo->prepare("UPDATE produk SET stok = stok - ? WHERE id_produk = ?")
=======
        $pdo->prepare("UPDATE produk SET stok = stok - ? WHERE id = ?")
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
            ->execute([$item['qty'], $item['produk_id']]);
    }

    $pdo->commit();

    // Kosongkan cart
    cartClear();
    unset($_SESSION['csrf']);

    // Redirect ke halaman sukses
<<<<<<< HEAD
    header("Location: ../controller/sukses.php?kode=" . urlencode($kodePesanan));
=======
    header("Location: sukses.php?kode=" . urlencode($kodePesanan));
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    $_SESSION['checkout_errors'] = ['Terjadi kesalahan sistem. Silakan coba lagi.'];
    error_log("Order error: " . $e->getMessage());
<<<<<<< HEAD
    header('Location: ../pages/checkout.php');
=======
    header('Location: checkout.php');
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
    exit;
}
