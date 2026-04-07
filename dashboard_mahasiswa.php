<?php
// =============================================
// FILE: dashboard_mahasiswa.php
// Fungsi: Halaman khusus untuk role MAHASISWA
// =============================================

session_start();
include "koneksi.php";

// ---- PROTEKSI HALAMAN ----
// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Cek apakah role-nya mahasiswa
// Kalau bukan mahasiswa, arahkan ke dashboard admin
if ($_SESSION['role'] != 'mahasiswa') {
    header("Location: dashboard_admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Mahasiswa</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background-color: #ecf0f1;
        }

        /* ---- NAVBAR ---- */
        .navbar {
            background-color: #27ae60;
            color: white;
            padding: 14px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .judul { font-size: 18px; font-weight: bold; }

        .navbar .info-user { font-size: 13px; }

        .navbar a.btn-logout {
            background-color: #e74c3c;
            color: white;
            padding: 7px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 13px;
            margin-left: 12px;
        }

        .navbar a.btn-logout:hover { background-color: #c0392b; }

        /* ---- KONTEN ---- */
        .konten {
            max-width: 750px;
            margin: 40px auto;
            padding: 0 15px;
        }

        /* ---- KARTU SELAMAT DATANG ---- */
        .kartu-welcome {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            padding: 30px 25px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
        }

        .kartu-welcome .icon { font-size: 48px; margin-bottom: 12px; }
        .kartu-welcome h2   { margin-bottom: 8px; font-size: 22px; }
        .kartu-welcome p    { font-size: 14px; opacity: 0.9; }

        /* ---- KARTU INFO ---- */
        .kartu-info {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 22px 25px;
            margin-bottom: 20px;
        }

        .kartu-info h3 {
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #ecf0f1;
        }

        .kartu-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .kartu-info td {
            padding: 9px 5px;
            font-size: 14px;
            border-bottom: 1px solid #f5f5f5;
        }

        .kartu-info td:first-child {
            color: #7f8c8d;
            width: 40%;
            font-weight: bold;
        }

        .kartu-info td:last-child { color: #2c3e50; }

        /* Badge */
        .badge {
            padding: 3px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            background-color: #eafaf1;
            color: #27ae60;
        }

        /* Info hak akses */
        .kartu-akses {
            background: #fff9e6;
            border: 1px solid #f1c40f;
            border-radius: 8px;
            padding: 18px 22px;
        }

        .kartu-akses h3 { color: #d68910; margin-bottom: 12px; }

        .kartu-akses ul {
            padding-left: 20px;
            color: #7d6608;
            font-size: 14px;
            line-height: 1.9;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="judul">🎓 Portal Mahasiswa</div>
    <div>
        <span class="info-user">
            Login sebagai: <strong><?= $_SESSION['username'] ?></strong>
        </span>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
</div>

<!-- KONTEN -->
<div class="konten">

    <!-- Kartu sambutan -->
    <div class="kartu-welcome">
        <div class="icon">🎓</div>
        <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
        <p>Anda berhasil login sebagai <strong>Mahasiswa</strong>.</p>
    </div>

    <!-- Informasi akun -->
    <div class="kartu-info">
        <h3>📄 Informasi Akun Anda</h3>
        <table>
            <tr>
                <td>ID User</td>
                <td><?= $_SESSION['id_user'] ?></td>
            </tr>
            <tr>
                <td>Username</td>
                <td><?= htmlspecialchars($_SESSION['username']) ?></td>
            </tr>
            <tr>
                <td>Role / Hak Akses</td>
                <td><span class="badge">Mahasiswa</span></td>
            </tr>
            <tr>
                <td>Status Login</td>
                <td>✅ Aktif</td>
            </tr>
        </table>
    </div>

    <!-- Hak akses -->
    <div class="kartu-akses">
        <h3>⚠️ Hak Akses Mahasiswa</h3>
        <ul>
            <li>✅ Dapat login ke sistem</li>
            <li>✅ Dapat melihat data profil sendiri</li>
            <li>❌ Tidak dapat melihat data user lain</li>
            <li>❌ Tidak dapat menghapus atau mengelola akun</li>
            <li>❌ Tidak dapat mengakses halaman Admin</li>
        </ul>
    </div>

</div>
</body>
</html>
