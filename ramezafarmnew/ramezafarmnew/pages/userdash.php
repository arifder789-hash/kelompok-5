<?php
session_start();
require_once __DIR__ . '/../config/db.php';

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
    </div>

    <div class="grid-produk">
        <?php
        // Sintaks untuk mengambil data produk dari database
        $query = mysqli_query($conn, "SELECT * FROM produk");
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