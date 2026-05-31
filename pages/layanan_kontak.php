<?php
session_start();
include '../config/db.php';
date_default_timezone_set('Asia/Jakarta');

// 1. PROTEKSI HALAMAN
if (!isset($_SESSION['admin'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location='login_admin.php';</script>";
    exit();
}

// 2. LOGIKA HAPUS PESAN (Opsional, jika admin ingin membersihkan inbox)
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id_kontak = mysqli_real_escape_string($conn, $_GET['id']);
    $hapus = mysqli_query($conn, "DELETE FROM kontak WHERE id_kontak = '$id_kontak'");
    
    if ($hapus) {
        echo "<script>alert('Pesan berhasil dihapus!'); window.location='layanan_kontak.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus pesan: " . mysqli_error($conn) . "');</script>";
    }
}

// 3. LOGIKA SELESAIKAN / SUDAH DIHUBUNGI
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hubungi') {
    $id_kontak = mysqli_real_escape_string($conn, $_GET['id']);
    $update = mysqli_query($conn, "UPDATE kontak SET status_pesan = 'Sudah Dihubungi' WHERE id_kontak = '$id_kontak'");
    
    if ($update) {
        echo "<script>window.location='layanan_kontak.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Kontak - Rameza Admin</title>
    <link rel="stylesheet" href="../assets/css/layanan_kontak.css">
</head>
<body>

<?php include '../includes/navbar_admin.php'; ?>

<div class="container">
    <div class="card">
        <h2>Layanan Kontak Pelanggan</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pengirim</th>
                    <th>Subjek</th>
                    <th>Pesan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $ambil = mysqli_query($conn, "SELECT * FROM kontak ORDER BY id_kontak DESC");
                while ($k = mysqli_fetch_assoc($ambil)) :
                    // Parse format baru: "no_wa | email" ATAU format lama (email/WA saja)
                    $raw = trim($k['email_wa']);
                    if (strpos($raw, ' | ') !== false) {
                        // Format baru dari form: "08xxx | email@domain.com"
                        $parts   = explode(' | ', $raw, 2);
                        $no_wa   = trim($parts[0]);
                        $email   = trim($parts[1]);
                    } elseif (filter_var($raw, FILTER_VALIDATE_EMAIL)) {
                        // Format lama: hanya email
                        $no_wa  = '';
                        $email  = $raw;
                    } else {
                        // Format lama: hanya nomor WA
                        $no_wa  = $raw;
                        $email  = '';
                    }
                ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($k['tanggal_kirim'])) ?></td>
                    <td>
                        <strong><?= htmlspecialchars($k['nama_pengirim']) ?></strong><br>
                        <?php if ($no_wa): ?>
                            <small style="color:#666;">📱 <?= htmlspecialchars($no_wa) ?></small><br>
                        <?php endif; ?>
                        <?php if ($email): ?>
                            <small style="color:#666;">✉️ <?= htmlspecialchars($email) ?></small>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge" style="border: 1px solid #ddd;"><?= $k['subjek'] ?></span></td>
                    <td><p style="max-width: 300px; margin:0; font-size: 14px;"><?= htmlspecialchars($k['pesan']) ?></p></td>
                  <td>
    <?php if ($k['status_pesan'] == 'Belum Dibaca') : ?>
        <?php
            // Tombol WhatsApp
            if ($no_wa) :
                $no_wa_bersih = preg_replace('/[^0-9]/', '', $no_wa);
                if (substr($no_wa_bersih, 0, 1) === '0') {
                    $no_wa_bersih = '62' . substr($no_wa_bersih, 1);
                }
        ?>
            <a href="https://wa.me/<?= $no_wa_bersih ?>" target="_blank" class="btn btn-wa">
               💬 WhatsApp
            </a>
        <?php endif; ?>
        <?php
            // Tombol Gmail
            if ($email) :
                $subjek_email  = "Balasan Rameza Egg Farm: " . $k['subjek'];
                $pesan_pembuka = "Halo " . $k['nama_pengirim'] . ",\n\nTerima kasih telah menghubungi Rameza Egg Farm terkait " . $k['subjek'] . ".\n\n[Tulis balasan Anda di sini]";
                $link_gmail    = "https://mail.google.com/mail/?view=cm&fs=1&to=" . urlencode($email) . "&su=" . urlencode($subjek_email) . "&body=" . urlencode($pesan_pembuka);
        ?>
            <a href="<?= $link_gmail ?>" target="_blank" class="btn btn-email">
               📧 Balas via Gmail
            </a>
        <?php endif; ?>
        <a href="?aksi=hubungi&id=<?= $k['id_kontak'] ?>" class="link-selesaikan" onclick="return confirm('Tandai pesan ini sebagai selesai?')">Tandai Selesai</a>
    <?php else : ?>
        <span class="badge" style="background: #e2fbe8; color: #155724; border: 1px solid #bbf7d0; padding: 8px 16px;">✓ Sudah Dihubungi</span>
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