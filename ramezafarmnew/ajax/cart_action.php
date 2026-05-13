<?php
// ============================================
//  ajax/cart_action.php
//  Endpoint AJAX untuk operasi keranjang
//  Method: POST — Content-Type: application/json
// ============================================

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/cart_helper.php';

header('Content-Type: application/json');

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

        $stmt = $pdo->prepare("SELECT * FROM produk WHERE id = ? AND aktif = 1");
        $stmt->execute([$produkId]);
        $produk = $stmt->fetch();

        if (!$produk) {
            exit(json_encode(['ok' => false, 'msg' => 'Produk tidak ditemukan']));
        }
        if ($produk['stok'] < $qty) {
            exit(json_encode(['ok' => false, 'msg' => 'Stok tidak mencukupi']));
        }

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
