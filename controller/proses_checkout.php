<?php
// ============================================
//  proses_checkout.php — Simpan Pesanan ke DB
// ============================================
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/cart_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php'); exit;
}

// CSRF check
if (!isset($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
    die('Sesi tidak valid. <a href="checkout.php">Kembali</a>');
}

// Redirect jika cart kosong
if (cartEmpty()) {
    header('Location: produk.php'); exit;
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
    header('Location: checkout.php'); exit;
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
            (kode_pesanan, nama_pembeli, no_wa, email, alamat, kota, catatan,
             total_harga, ongkir, grand_total, metode_bayar, status)
        VALUES (?,?,?,?,?,?,?, ?,?,?, ?, 'pending')
    ");
    $stmt->execute([
        $kodePesanan,
        $nama, $no_wa_raw, $email,
        $alamat, $kota, $catatan,
        $total, 0, $total,
        $metode
    ]);
    $pesananId = (int) $pdo->lastInsertId();

    // 2. Insert detail items
    $stmtDetail = $pdo->prepare("
        INSERT INTO detail_pesanan (pesanan_id, produk_id, nama_produk, harga, satuan, qty, subtotal)
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
        $pdo->prepare("UPDATE produk SET stok = stok - ? WHERE id = ?")
            ->execute([$item['qty'], $item['produk_id']]);
    }

    $pdo->commit();

    // Kosongkan cart
    cartClear();
    unset($_SESSION['csrf']);

    // Redirect ke halaman sukses
    header("Location: sukses.php?kode=" . urlencode($kodePesanan));
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    $_SESSION['checkout_errors'] = ['Terjadi kesalahan sistem. Silakan coba lagi.'];
    error_log("Order error: " . $e->getMessage());
    header('Location: checkout.php');
    exit;
}
