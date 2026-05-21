<?php
session_start();
include '../config/koneksi.php';
// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../loginuser.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Rameza Egg Farm</title>
    <!-- Menggunakan font modern Plus Jakarta Sans agar tampilan teks lebih premium -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* --- CSS Reset & Base Styling --- */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #f8fafc;
            color: #334155;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: 1100px;
            width: 90%;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        h2 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        h2::before {
            content: "🛒";
        }

        /* --- Table Styling --- */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .table-cart {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 15px;
        }

        .table-cart th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            padding: 16px;
            border-bottom: 2px solid #e2e8f0;
        }

        .table-cart td {
            padding: 20px 16px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .table-cart tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Nama Produk Column */
        .product-name {
            font-weight: 600;
            color: #0f172a;
        }

        /* Badge Jumlah */
        .qty-badge {
            display: inline-block;
            background: #e2e8f0;
            color: #334155;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        /* Subtotal Column */
        .subtotal-amount {
            font-weight: 700;
            color: #0f172a;
        }

        /* Total Row di Tfoot */
        .table-cart tfoot tr {
            background-color: #f8fafc;
            font-weight: 700;
            font-size: 16px;
            color: #0f172a;
        }

        .table-cart tfoot td {
            border-top: 2px solid #cbd5e1;
            border-bottom: none;
            padding: 20px 16px;
        }

        /* Status Keranjang Kosong */
        .empty-cart-msg {
            text-align: center;
            padding: 50px 20px !important;
            color: #64748b;
            font-size: 16px;
        }

        .empty-cart-msg a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
        }

        .empty-cart-msg a:hover {
            text-decoration: underline;
        }

        /* --- Link / Button Action --- */
        .btn-delete {
            color: #ef4444;
            text-decoration: none;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-delete:hover {
            background-color: #fef2f2;
        }

        /* --- Bottom Navigation Buttons --- */
        .cart-actions {
            margin-top: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-secondary {
            background-color: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .btn-secondary:hover {
            background-color: #f1f5f9;
            color: #1e293b;
        }

        .btn-primary {
            background-color: #4f46e5; /* Bisa kamu ganti dengan kode warna khas tokomu */
            color: #ffffff;
            border: 1px solid transparent;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        }

        .btn-primary:hover {
            background-color: #4338ca;
            transform: translateY(-1px);
        }

        /* --- Responsive View for Mobile --- */
        @media (max-width: 640px) {
            .container {
                padding: 16px;
                margin: 20px auto;
            }
            .table-cart th, .table-cart td {
                padding: 12px 10px;
                font-size: 14px;
            }
            .cart-actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }
            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<?php include '../includes/navuser.php'; ?>

<div class="container">
    <h2>Keranjang Belanja</h2>

    <!-- Responsivitas tabel dibungkus dengan div class khusus -->
    <div class="table-responsive">
        <table class="table-cart">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_belanja = 0;
                if (!empty($_SESSION['keranjang'])): 
                    foreach ($_SESSION['keranjang'] as $id_produk => $jumlah):
                        $id_produk = (int) $id_produk;
                        $ambil = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk = $id_produk");
                        $pecah = mysqli_fetch_assoc($ambil);
                        if (!$pecah) continue; 
                        $subtotal = $pecah['harga'] * $jumlah;
                        $total_belanja += $subtotal;
                ?>
                <tr>
                    <td class="product-name"><?php echo htmlspecialchars($pecah['nama_produk']); ?></td>
                    <td>Rp <?php echo number_format($pecah['harga'], 0, ',', '.'); ?></td>
                    <td><span class="qty-badge"><?php echo $jumlah; ?></span></td>
                    <td class="subtotal-amount">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                    <td style="text-align: center;">
                        <a href="../controller/hapus_keranjang.php?id=<?php echo $id_produk; ?>" 
   class="btn-delete"
   onclick="return confirm('Apakah kamu yakin ingin menghapus produk ini dari keranjang?')">
   🗑️ Hapus
</a>
                    </td>
                </tr>
                <?php endforeach; 
                else: ?>
                <tr>
                    <td colspan="5" class="empty-cart-msg">
                        Keranjang kamu masih kosong. 🧐 <a href="userdash.php">Yuk, isi dengan telur segar sekarang!</a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>

            <?php if (!empty($_SESSION['keranjang'])): ?>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right; padding-right: 20px;">Total Pembayaran:</td>
                    <td colspan="2" style="color: #4f46e5; font-size: 18px;">Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?></td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>

    <!-- Group tombol aksi bagian bawah -->
    <div class="cart-actions">
        <a href="userdash.php" class="btn btn-secondary">← Kembali ke Katalog</a>
        <?php if (!empty($_SESSION['keranjang'])): ?>
            <a href="checkout.php" class="btn btn-primary">Lanjutkan Checkout ➔</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>