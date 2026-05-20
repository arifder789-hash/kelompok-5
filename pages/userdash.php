<?php
session_start();
include '../config/koneksi.php';

// Pastikan user login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: ../loginuser.php");
    exit();
}
?>
<?php include '../includes/header.php'; ?>
<link rel="stylesheet" href="../assets/css/userdash.css">
<?php include '../includes/navbar.php'; ?>

<main class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama_user'] ?? 'Pelanggan'); ?>!</h2>
            <p>Silahkan pilih produk dan telur kualitas terbaik dari peternakan kami. Kami siap memberikan layanan terbaik untuk Anda.</p>
        </div>
        <div class="dashboard-welcome-icon">👋</div>
    </div>

    <div class="grid-produk">
        <?php
        // Sintaks untuk mengambil data produk dari database
        $query = mysqli_query($conn, "SELECT * FROM produk");
        
        if ($query && mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) :
        ?>
        <div class="card">
            <h3><?php echo htmlspecialchars($row['nama_produk']); ?></h3>
            <p><?php echo htmlspecialchars(strlen($row['deskripsi']) > 100 ? substr($row['deskripsi'], 0, 100) . '...' : $row['deskripsi']); ?></p>
            <div class="price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></div>
            <!-- Tombol tambah ke keranjang -->
            <a href="produk.php" class="btn">Lihat Produk</a>
        </div>
        <?php 
            endwhile; 
        } else {
            echo '<div class="empty-state"><h3>Belum ada produk</h3><p>Saat ini belum ada produk yang tersedia di katalog.</p></div>';
        }
        ?>
    </div>
</main>

<?php 
// Sertakan footer jika ada
if(file_exists('../includes/footer.php')) {
    include '../includes/footer.php'; 
}
?>
