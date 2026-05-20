<?php
session_start();
<<<<<<< HEAD
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
=======
include 'config/koneksi.php';

// Pastikan user login
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: project2/loginuser.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Katalog - Rameza Egg Farm</title>
    <link rel="stylesheet" href="assets/css/userdash.css">
</head>
<body>

<?php include 'includes/navuser.php'; ?>

<div class="container">
    <div style="margin-bottom: 30px;">
        <h2>Selamat Datang, <?php echo $_SESSION['nama_user']; ?>!</h2>
        <p>Silahkan pilih telur kualitas terbaik kami.</p>
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
    </div>

    <div class="grid-produk">
        <?php
        // Sintaks untuk mengambil data produk dari database
        $query = mysqli_query($conn, "SELECT * FROM produk");
<<<<<<< HEAD
        
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
=======
        while ($row = mysqli_fetch_assoc($query)) :
        ?>
        <div class="card">
            <h3><?php echo $row['nama_produk']; ?></h3>
            <p><?php echo $row['deskripsi']; ?></p>
            <div class="price">Rp <?php echo number_format($row['harga']); ?></div>
            <<!-- Tombol tambah ke keranjang → ke tambah_keranjang.php -->
<a href="tambahkeranjang.php?id=<?php echo $row['id_produk']; ?>" class="btn">
    Tambah ke Keranjang
</a>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
