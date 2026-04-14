<?php
// =============================================
// FILE: dashboard_karyawan.php
// =============================================

session_start();
include "koneksi.php";

// ---- PROTEKSI HALAMAN ----
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] != 'karyawan') {
    header("Location: dashboard_admin.php");
    exit();
}

// STATUS DINAMIS (SIMULASI)
$status_absen = "Belum Absen"; // ganti jadi "Sudah Absen" jika mau tes
$jam_absen = "-";
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard karyawan</title>

<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: Arial, sans-serif;
    background-color: #ecf0f1;
}

/* NAVBAR */
.navbar {
    background: linear-gradient(100deg, #024aff, #5c98ff);
    height: 100px;
    color: white;
    padding: 14px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 0px 0px 40px 40px;
}

.navbar .judul { font-size: 26px; font-weight: bold; }

.navbar .info-user { font-size: 14px; }

.navbar a.btn-logout {
    background-color: #e74c3c;
    color: white;
    padding: 8px 12px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
    margin-left: 10px;
}

.navbar a.btn-logout:hover { background-color: #c0392b; }

/* LAYOUT */
.container {
    display: flex;
}

/* SIDEBAR */
.sidebar {
    width: 220px;
    background: #2c3e50;
    min-height: 100vh;
    color: white;
    padding: 20px;
}

.sidebar h3 { margin-bottom: 20px; }

.sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    margin: 10px 0;
    padding: 10px;
    border-radius: 6px;
}

.sidebar a:hover {
    background: #34495e;
}

/* MAIN */
.main {
    flex: 1;
}

/* KONTEN */
.konten {
    max-width: 900px;
    margin: 30px auto;
    padding: 0 15px;
}

/* STATUS */
.status-box {
    background: white;
    padding: 18px;
    border-radius: 8px;
    margin-bottom: 20px;
    border-left: 6px solid #e74c3c;
}

.status-box.sudah {
    border-left: 6px solid #2ecc71;
}

/* WELCOME */
.kartu-welcome {
    background: linear-gradient(135deg, #024aff, #5c98ff);
    color: white;
    padding: 25px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
}

.kartu-welcome h2 { margin-bottom: 8px; }

/* AKSI */
.kartu-aksi {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.aksi-btn {
    flex: 1;
    background: #3498db;
    color: white;
    padding: 15px;
    text-align: center;
    border-radius: 8px;
    cursor: pointer;
}

.aksi-btn:hover {
    background: #2980b9;
}

/* INFO */
.kartu-info {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

.kartu-info h3 { margin-bottom: 10px; }

.kartu-info table {
    width: 100%;
}

.kartu-info td {
    padding: 8px;
}

.kartu-info td:first-child {
    font-weight: bold;
    color: #0b7dff;
}

.badge {
    padding: 3px 10px;
    border-radius: 10px;
    background: #eafaf1;
    color: #27ae60;
}

/* AKSES */
.kartu-akses {
    background: #fff9e6;
    border: 1px solid #f1c40f;
    border-radius: 8px;
    padding: 18px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="judul">Portal Karyawan</div>
    <div>
        Login sebagai: <strong><?= $_SESSION['username'] ?></strong>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
</div>

<div class="container">

<!-- SIDEBAR -->
<div class="sidebar">
    <h3>📂 Menu</h3>
    <a href="#">🏠 Dashboard</a>
    <a href="#">🕒 Absensi</a>
    <a href="#">📊 Riwayat</a>
    <a href="#">👤 Profil</a>
</div>

<!-- MAIN -->
<div class="main">
<div class="konten">

<!-- STATUS -->
<div class="status-box <?= ($status_absen == 'Sudah Absen') ? 'sudah' : '' ?>">
    <h3>📌 Status Hari Ini</h3>
    <p>Status: <strong><?= $status_absen ?></strong></p>
    <p>Jam Absen: <?= $jam_absen ?></p>
</div>

<!-- WELCOME -->
<div class="kartu-welcome">
    <h2>Selamat Datang karyawan yang terhormat, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
    <p>Anda login sebagai <strong> Seorang Karyawan</strong></p>
</div>

<!-- AKSI -->
<div class="kartu-aksi">
    <div class="aksi-btn">🟢 Absen Sekarang</div>
    <div class="aksi-btn">📄 Lihat Riwayat</div>
</div>

<!-- INFO -->
<div class="kartu-info">
    <h3>📄 Informasi Akun</h3>
    <table>
        <tr>
            <td>Karyawan ke :</td>
            <td><?= $_SESSION['id_user'] ?></td>
        </tr>
        <tr>
            <td>Username</td>
            <td><?= htmlspecialchars($_SESSION['username']) ?></td>
        </tr>
        <tr>
            <td>Role</td>
            <td><span class="badge">Karyawan</span></td>
        </tr>
        <tr>
            <td>Status</td>
            <td>Aktif</td>
        </tr>
    </table>
</div>

<!-- AKSES -->
<div class="kartu-akses">
    <h3>⚠️ Hak Akses</h3>
    <ul>
        <li>✅ Login dashboard</li>
        <li>✅ Absensi</li>
        <li>❌ Kelola user, karyawan lainnya</li>
        <li>❌ Akses admin</li>
    </ul>
</div>

</div>
</div>

</div>

</body>
</html>
