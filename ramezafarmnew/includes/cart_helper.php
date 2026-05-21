<?php
// ============================================
//  includes/cart_helper.php
//  Semua fungsi keranjang berbasis SESSION
// ============================================

if (session_status() === PHP_SESSION_NONE) session_start();

// ─── Inisialisasi cart ───────────────────────
function cartInit(): void {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
}

// ─── Ambil semua item cart ───────────────────
function cartGetAll(): array {
    cartInit();
    return $_SESSION['cart'];
}

// ─── Tambah / update qty item ────────────────
function cartAdd(array $produk, int $qty = 1): bool {
    cartInit();
    $id = (int) $produk['id'];

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['produk_id'] === $id) {
            $item['qty'] += $qty;
            $item['subtotal'] = $item['qty'] * $item['harga'];
            return true;
        }
    }
    unset($item);

    $_SESSION['cart'][] = [
        'produk_id' => $id,
        'kode'      => $produk['kode'],
        'nama'      => $produk['nama'],
        'harga'     => (float) $produk['harga'],
        'satuan'    => $produk['satuan'],
        'gambar'    => $produk['gambar'] ?? '',
        'qty'       => $qty,
        'subtotal'  => (float) $produk['harga'] * $qty,
    ];
    return true;
}

// ─── Update qty berdasarkan produk_id ────────
function cartUpdate(int $produkId, int $qty): bool {
    cartInit();
    if ($qty <= 0) return cartRemove($produkId);
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['produk_id'] === $produkId) {
            $item['qty']      = $qty;
            $item['subtotal'] = $qty * $item['harga'];
            return true;
        }
    }
    return false;
}

// ─── Hapus item ──────────────────────────────
function cartRemove(int $produkId): bool {
    cartInit();
    foreach ($_SESSION['cart'] as $i => $item) {
        if ($item['produk_id'] === $produkId) {
            array_splice($_SESSION['cart'], $i, 1);
            return true;
        }
    }
    return false;
}

// ─── Kosongkan cart ──────────────────────────
function cartClear(): void {
    $_SESSION['cart'] = [];
}

// ─── Hitung total item (qty) ─────────────────
function cartCount(): int {
    cartInit();
    return array_sum(array_column($_SESSION['cart'], 'qty'));
}

// ─── Hitung subtotal seluruh cart ────────────
function cartTotal(): float {
    cartInit();
    return array_sum(array_column($_SESSION['cart'], 'subtotal'));
}

// ─── Cek apakah cart kosong ──────────────────
function cartEmpty(): bool {
    return cartCount() === 0;
}

function rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}