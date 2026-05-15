<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include 'config/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: loginuser.php");
    exit();
}

$id_user = $_SESSION['id_pelanggan'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Riwayat Pesanan - Rameza Egg Farm</title>
    <link rel="stylesheet" href="assets/css/userdash.css">
</head>
<body>

<?php include 'includes/navuser.php'; ?>

<div class="container" style="margin-top: 50px;">
    <h2>Riwayat Pesanan Anda</h2>
    <p>Status pesanan telur Anda secara real-time:</p>

    <table class="table-cart" border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th>No. Pesanan</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Ambil data sesuai struktur tabel di foto (id_pesanan, id_pelanggan, total_harga, status, tanggal_pesan)
            $ambil = mysqli_query($conn, "SELECT * FROM pesanan WHERE id_pelanggan = '$id_user' ORDER BY tanggal_pesan DESC");
            
            if (mysqli_num_rows($ambil) > 0):
                while ($pecah = mysqli_fetch_assoc($ambil)):
            ?>
            <tr style="text-align: center;">
                <td>#<?php echo $pecah['id_pesanan']; ?></td>
                <td><?php echo date("d M Y, H:i", strtotime($pecah['tanggal_pesan'])); ?></td>
                <td>Rp <?php echo number_format($pecah['total_harga']); ?></td>
                <td>
                    <?php 
                    $status = $pecah['status'];
                    if ($status == 'Pending') {
                        echo "<span style='padding: 5px 10px; background: #fff3cd; color: #856404; border-radius: 4px; font-weight: bold;'>$status</span>";
                    } else {
                        echo "<span style='padding: 5px 10px; background: #d4edda; color: #155724; border-radius: 4px; font-weight: bold;'>$status</span>";
                    }
                    ?>
                </td>
            </tr>
            <?php 
                endwhile;
            else: 
            ?>
            <tr>
                <td colspan="4" style="text-align: center; padding: 20px;">
                    Belum ada riwayat pesanan. <a href="userdash.php">Ayo belanja sekarang!</a>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <a href="userdash.php" class="btn" style="text-decoration: none; padding: 10px 20px; background: #28a745; color: white; border-radius: 5px;">Kembali Belanja</a>
    </div>
</div>

</body>
</html>