<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

header('Content-Type: application/json');

// Cek login
if (!isset($_SESSION['id_pelanggan'])) {
    echo json_encode(['success' => false, 'message' => 'Harap login terlebih dahulu']);
    exit();
}

// Validasi input
if (!isset($_POST['id_produk']) || !isset($_POST['jumlah'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit();
}

$id_pelanggan = $_SESSION['id_pelanggan'];
$id_produk = (int)$_POST['id_produk'];
$jumlah = (int)$_POST['jumlah'];

// Validasi jumlah
if ($jumlah < 1) {
    echo json_encode(['success' => false, 'message' => 'Jumlah tidak valid']);
    exit();
}

// Cek stok produk
$checkStok = $conn->prepare("SELECT stok, min_pesan, nama_produk FROM produk WHERE id_produk = ?");
$checkStok->bind_param('i', $id_produk);
$checkStok->execute();
$result = $checkStok->get_result();

if ($result->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
    exit();
}

$produk = $result->fetch_assoc();

// Validasi stok
if ($produk['stok'] < $jumlah) {
    echo json_encode(['success' => false, 'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $produk['stok']]);
    exit();
}

// Validasi minimal pemesanan
if ($jumlah < $produk['min_pesan']) {
    echo json_encode(['success' => false, 'message' => 'Minimal pemesanan: ' . $produk['min_pesan']]);
    exit();
}

// Cek apakah produk sudah ada di keranjang
$checkCart = $conn->prepare("SELECT id_keranjang, jumlah FROM keranjang WHERE id_pelanggan = ? AND id_produk = ?");
$checkCart->bind_param('ii', $id_pelanggan, $id_produk);
$checkCart->execute();
$cartResult = $checkCart->get_result();

if ($cartResult->num_rows > 0) {
    // Update jumlah jika sudah ada
    $cart = $cartResult->fetch_assoc();
    $newJumlah = $cart['jumlah'] + $jumlah;
    
    // Cek stok lagi setelah ditambah
    if ($newJumlah > $produk['stok']) {
        echo json_encode(['success' => false, 'message' => 'Total melebihi stok tersedia']);
        exit();
    }
    
    $updateCart = $conn->prepare("UPDATE keranjang SET jumlah = ? WHERE id_keranjang = ?");
    $updateCart->bind_param('ii', $newJumlah, $cart['id_keranjang']);
    
    if ($updateCart->execute()) {
        echo json_encode([
            'success' => true, 
            'message' => 'Jumlah produk berhasil diperbarui',
            'cart_count' => getCartCount($conn, $id_pelanggan)
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal memperbarui keranjang']);
    }
} else {
    // Insert baru jika belum ada
    $insertCart = $conn->prepare("INSERT INTO keranjang (id_pelanggan, id_produk, jumlah) VALUES (?, ?, ?)");
    $insertCart->bind_param('iii', $id_pelanggan, $id_produk, $jumlah);
    
    if ($insertCart->execute()) {
        echo json_encode([
            'success' => true, 
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'cart_count' => getCartCount($conn, $id_pelanggan)
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menambahkan ke keranjang']);
    }
}

// Fungsi untuk menghitung total item di keranjang
function getCartCount($conn, $id_pelanggan) {
    $stmt = $conn->prepare("SELECT COALESCE(SUM(jumlah), 0) as total FROM keranjang WHERE id_pelanggan = ?");
    $stmt->bind_param('i', $id_pelanggan);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['total'];
}
