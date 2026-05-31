<?php
// ============================================
//  ajax/cart_action.php
//  Endpoint AJAX untuk operasi keranjang
//  Method: POST — Content-Type: application/json
// ============================================

// Pastikan session dimulai SEBELUM apapun
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/cart_helper.php';

header('Content-Type: application/json');

// Matikan output buffering error agar response selalu JSON bersih
error_reporting(0);
@ini_set('display_errors', 0);

// Hanya terima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['ok' => false, 'msg' => 'Method not allowed']));
}

$body   = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $body['action'] ?? '';

switch ($action) {

    // ── Tambah ke keranjang ──────────────────
    case 'add':
        $produkId = (int)($body['produk_id'] ?? 0);
        $qty      = max(1, (int)($body['qty'] ?? 1));

        if ($produkId <= 0) {
            exit(json_encode(['ok' => false, 'msg' => 'ID produk tidak valid']));
        }

        $stmt = $pdo->prepare("SELECT * FROM produk WHERE id_produk = ? AND aktif = 1");
        $stmt->execute([$produkId]);
        $row = $stmt->fetch();

        if (!$row) {
            exit(json_encode(['ok' => false, 'msg' => 'Produk tidak ditemukan']));
        }
        if ($row['stok'] < $qty) {
            exit(json_encode(['ok' => false, 'msg' => 'Stok tidak mencukupi']));
        }

        $produk = [
            'id' => $row['id_produk'],
            'kode' => $row['kode_produk'],
            'nama' => $row['nama_produk'],
            'harga' => $row['harga'],
            'satuan' => $row['satuan'],
            'gambar' => $row['gambar']
        ];

        cartAdd($produk, $qty);
        exit(json_encode([
            'ok'    => true,
            'msg'   => 'Produk ditambahkan ke keranjang',
            'count' => cartCount(),
            'total' => cartTotal(),
        ]));

    // ── Update qty ───────────────────────────
    case 'update':
        $produkId = (int)($body['produk_id'] ?? 0);
        $qty      = (int)($body['qty'] ?? 0);

        // Validasi stok dan min pesan sebelum update
        $stmt = $pdo->prepare("SELECT stok, min_pesan FROM produk WHERE id_produk = ? AND aktif = 1");
        $stmt->execute([$produkId]);
        $row = $stmt->fetch();

        if ($row) {
            if ($qty > $row['stok']) {
                exit(json_encode(['ok' => false, 'msg' => 'Maksimal stok: ' . $row['stok']]));
            }
            if ($qty > 0 && $qty < $row['min_pesan']) {
                exit(json_encode(['ok' => false, 'msg' => 'Minimal pesanan: ' . $row['min_pesan']]));
            }
        }

        cartUpdate($produkId, $qty);
        exit(json_encode([
            'ok'    => true,
            'count' => cartCount(),
            'total' => cartTotal(),
            'item_subtotal' => 0, // dihitung ulang di frontend dari response
        ]));

    // ── Hapus item ───────────────────────────
    case 'remove':
        $produkId = (int)($body['produk_id'] ?? 0);
        cartRemove($produkId);
        exit(json_encode([
            'ok'    => true,
            'count' => cartCount(),
            'total' => cartTotal(),
        ]));

    // ── Ambil ringkasan cart ─────────────────
    case 'summary':
        exit(json_encode([
            'ok'    => true,
            'count' => cartCount(),
            'total' => cartTotal(),
            'items' => cartGetAll(),
        ]));

    default:
        http_response_code(400);
        exit(json_encode(['ok' => false, 'msg' => 'Aksi tidak dikenal']));
}
