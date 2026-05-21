<?php
session_start();
include '../config/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['admin'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location='login_admin.php';</script>";
    exit();
}

// LOGIKA KONFIRMASI PESANAN
if (isset($_GET['aksi']) && $_GET['aksi'] == 'konfirmasi') {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $update = mysqli_query($conn, "UPDATE pesanan SET status = 'Dikonfirmasi' WHERE id_pesanan = '$id'");
    
    if ($update) {
        echo "<script>alert('Pesanan #$id Berhasil Dikonfirmasi!'); window.location='admin_pesanan.php';</script>";
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
    <title>Kelola Pesanan - Rameza Admin</title>
    <link rel="stylesheet" href="../assets/css/admin_pesanan.css">
</head>
<body>

    <?php include '../includes/navbar_admin.php'; ?>

    <div class="main-content">
        <div class="card">
            <h2>Manajemen Aksi Pesanan Masuk</h2>
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
                    $query_pesanan = "SELECT pesanan.*, pelanggan.username 
                                      FROM pesanan 
                                      JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
                                      ORDER BY pesanan.id_pesanan DESC";
                    $result = mysqli_query($conn, $query_pesanan);
                    
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) :
                            $status = strtolower($row['status']);
                            $kelas_badge = ($status == 'pending') ? 'bg-pending' : 'bg-done';
                    ?>
                        <tr>
                            <td><strong>#<?= $row['id_pesanan'] ?></strong></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                            <td>
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