<?php
session_start();
include __DIR__ . '/../config/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// 1. PROTEKSI HALAMAN
// Jika tidak ada session admin, tendang ke halaman login
if (!isset($_SESSION['admin'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location='../login_admin.php';</script>";
    exit();
}

// 2. LOGIKA KONFIRMASI PESANAN
if (isset($_GET['aksi']) && $_GET['aksi'] == 'konfirmasi') {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Update status pesanan menjadi Dikonfirmasi
    $update = mysqli_query($conn, "UPDATE pesanan SET status = 'dikonfirmasi' WHERE id_pesanan = '$id'");
    
    if ($update) {
        echo "<script>alert('Pesanan #$id Berhasil Dikonfirmasi!'); window.location='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal konfirmasi: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pesanan - Rameza Admin</title>
    <style>
        /* Style dasar dashboard */
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            background-color: #f0f2f5; 
            color: #333;
        }
        .container { 
            padding: 40px 50px; 
        }
        .card { 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.08); 
        }
        h2 { 
            margin-top: 0; 
            color: #0056b3; 
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        /* Style Tabel */
        table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        th { 
            background-color: #f8f9fa; 
            padding: 15px; 
            text-align: left; 
            border-bottom: 2px solid #dee2e6;
            color: #555;
            text-transform: uppercase;
            font-size: 13px;
        }
        td { 
            padding: 15px; 
            border-bottom: 1px solid #eee; 
            font-size: 14px;
            vertical-align: middle;
        }

        /* Badge Status */
        .badge { 
            padding: 6px 12px; 
            border-radius: 20px; 
            font-size: 11px; 
            font-weight: bold; 
            display: inline-block;
        }
        .bg-pending { background-color: #fff3cd; color: #856404; }
        .bg-done { background-color: #d4edda; color: #155724; }

        /* Tombol Aksi */
        .btn-confirm { 
            background-color: #28a745; 
            color: white; 
            padding: 8px 16px; 
            border-radius: 6px; 
            text-decoration: none; 
            font-size: 13px; 
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-confirm:hover { 
            background-color: #218838; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .text-muted { color: #999; font-style: italic; }
    </style>
</head>
<body>

    <nav style="background:#0056b3;color:white;padding:16px 50px;font-weight:700;">
        Rameza Admin
        <a href="../login_admin.php" style="color:white;float:right;text-decoration:none;">Logout</a>
    </nav>

    <div class="container">
        <div class="card">
            <h2>Daftar Pesanan Pelanggan</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Nama Pelanggan</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query mengambil data pesanan gabung dengan tabel pelanggan
                    $query_pesanan = "SELECT pesanan.*, pelanggan.username 
                                      FROM pesanan 
                                      JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
                                      ORDER BY id_pesanan DESC";
                    
                    $result = mysqli_query($conn, $query_pesanan);
                    
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) :
                    ?>
                        <tr>
                            <td><strong>#<?= $row['id_pesanan'] ?></strong></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                            <td>
                                <?php 
                                    $status = strtolower($row['status']);
                                    $kelas_badge = ($status == 'pending') ? 'bg-pending' : 'bg-done';
                                ?>
                                <span class="badge <?= $kelas_badge ?>">
                                    <?= strtoupper($status) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($status == 'pending') : ?>
                                    <a href="?aksi=konfirmasi&id=<?= $row['id_pesanan'] ?>" 
                                       class="btn-confirm" 
                                       onclick="return confirm('Apakah Anda yakin ingin mengonfirmasi pesanan #<?= $row['id_pesanan'] ?>?')">
                                       Konfirmasi
                                    </a>
                                <?php else : ?>
                                    <span class="text-muted">Sudah Diproses</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php 
                        endwhile; 
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center;'>Belum ada pesanan masuk.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
