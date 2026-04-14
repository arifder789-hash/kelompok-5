<?php
// =============================================
// FILE: dashboard_admin.php
// =============================================

session_start();
include "koneksi.php";

// ---- PROTEKSI HALAMAN ----
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// ---- PROTEKSI ROLE ----
if ($_SESSION['role'] != 'admin') {

    if ($_SESSION['role'] == 'karyawan') {
        header("Location: dashboard_karyawan.php");
    } else {
        header("Location: dashboard_user.php");
    }

    exit();
}

// ---- AMBIL DATA USER ----
$query_user = "SELECT * FROM tb_login ORDER BY id_user ASC";
$hasil_user = mysqli_query($koneksi, $query_user);

// ---- HAPUS USER ----
if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];

    if ($id_hapus == $_SESSION['id_user']) {
        $notif = "Tidak bisa menghapus akun sendiri!";
    } else {
        mysqli_query($koneksi, "DELETE FROM tb_login WHERE id_user = $id_hapus");
        header("Location: dashboard_admin.php?notif=hapus");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background-color: #ecf0f1;
        }

        /* NAVBAR */
        .navbar {
            background-color: #2c3e50;
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

        /* KONTEN */
        .konten {
            max-width: 900px;
            margin: 30px auto;
            padding: 0 15px;
        }

        /* WELCOME */
        .kartu-welcome {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 22px 25px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .kartu-welcome h2 { margin-bottom: 5px; }
        .kartu-welcome p  { font-size: 14px; opacity: 0.85; }

        /* TABEL */
        .kartu-tabel {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .kartu-tabel .header-tabel {
            padding: 15px 20px;
            border-bottom: 1px solid #ecf0f1;
        }

        .kartu-tabel .header-tabel h3 { color: #2c3e50; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background-color: #34495e;
            color: white;
            padding: 11px 15px;
            text-align: left;
            font-size: 13px;
        }

        table td {
            padding: 11px 15px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
            color: #2c3e50;
        }

        table tr:last-child td { border-bottom: none; }
        table tr:hover td { background-color: #f8f9fa; }

        /* BADGE ROLE */
        .badge {
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-admin {
            background-color: #fdecea;
            color: #e74c3c;
        }

        .badge-karyawan {
            background-color: #e8f4fd;
            color: #2980b9;
        }

        .badge-user {
            background-color: #f4f6f7;
            color: #7f8c8d;
        }

        /* BUTTON */
        .btn-hapus {
            background-color: #e74c3c;
            color: white;
            padding: 5px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
        }

        .btn-hapus:hover { background-color: #c0392b; }

        /* NOTIF */
        .notif {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 18px;
            font-size: 13px;
        }

        .notif-sukses { background: #eafaf1; color: #1e8449; border: 1px solid #27ae60; }
        .notif-error  { background: #fdecea; color: #c0392b; border: 1px solid #e74c3c; }
    </style>
</head>
<body>

<div class="navbar">
    <div class="judul">⚙️ Sistem Login PHP</div>
    <div>
        <span class="info-user">
            Login sebagai: <strong><?= $_SESSION['username'] ?></strong>
            | Role: <strong>Admin</strong>
        </span>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
</div>

<div class="konten">

    <div class="kartu-welcome">
        <h2>Selamat datang, <?= $_SESSION['username'] ?>! 👋</h2>
        <p>Anda login sebagai <strong>Admin</strong>. Anda bisa mengelola semua pengguna.</p>
    </div>

    <?php if (isset($_GET['notif']) && $_GET['notif'] == 'hapus') : ?>
        <div class="notif notif-sukses">✅ User berhasil dihapus.</div>
    <?php endif; ?>

    <?php if (isset($notif)) : ?>
        <div class="notif notif-error">⚠️ <?= $notif ?></div>
    <?php endif; ?>

    <div class="kartu-tabel">
        <div class="header-tabel">
            <h3>📋 Daftar Semua Pengguna</h3>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($hasil_user)) : ?>

                <?php
                $role = strtolower($row['role']);
                $badge_class = 'badge-user';

                if ($role == 'admin') {
                    $badge_class = 'badge-admin';
                } elseif ($role == 'karyawan') {
                    $badge_class = 'badge-karyawan';
                }
                ?>

                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $row['id_user'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td style="font-size:11px; color:#7f8c8d;">
                        <?= substr($row['password'], 0, 20) ?>...
                    </td>
                    <td>
                        <span class="badge <?= $badge_class ?>">
                            <?= ucfirst($role) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($row['id_user'] != $_SESSION['id_user']) : ?>
                            <a href="?hapus=<?= $row['id_user'] ?>"
                               class="btn-hapus"
                               onclick="return confirm('Yakin hapus user ini?')">
                                Hapus
                            </a>
                        <?php else : ?>
                            <span style="color:#bdc3c7; font-size:12px;">(Anda)</span>
                        <?php endif; ?>
                    </td>
                </tr>

                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
