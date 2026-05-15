<?php
session_start();
include 'config/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['admin'])) {
    header("Location: login_admin.php");
    exit();
}

// Logika Tandai Selesai
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hubungi') {
    $id_k = $_GET['id'];
    mysqli_query($conn, "UPDATE kontak SET status_pesan = 'Sudah Dihubungi' WHERE id_kontak = '$id_k'");
    echo "<script>window.location='layanan_kontak.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Layanan Kontak - Rameza Admin</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f0f2f5; }
        .container { padding: 30px; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f8f9fa; padding: 15px; text-align: left; border-bottom: 2px solid #eee; }
        td { padding: 15px; border-bottom: 1px solid #eee; }
        .badge { padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .btn { padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 13px; color: white; display: inline-block; }
        .btn-wa { background: #25d366; }
        .btn-email { background: #ea4335; }
    </style>
</head>
<body>

<?php include 'includes/navbar_admin.php'; ?>

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
                    $identitas = trim($k['email_wa']);
                    $is_email = filter_var($identitas, FILTER_VALIDATE_EMAIL);
                ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($k['tanggal_kirim'])) ?></td>
                    <td>
                        <strong><?= htmlspecialchars($k['nama_pengirim']) ?></strong><br>
                        <small style="color: #666;"><?= htmlspecialchars($identitas) ?></small>
                    </td>
                    <td><span class="badge" style="border: 1px solid #ddd;"><?= $k['subjek'] ?></span></td>
                    <td><p style="max-width: 300px; margin:0; font-size: 14px;"><?= htmlspecialchars($k['pesan']) ?></p></td>
                    <td>
                        <?php if ($k['status_pesan'] == 'Belum Dibaca') : ?>
                            
                            <?php if ($is_email) : 
    // Menyiapkan teks pembuka
    $subjek_email = "Balasan Rameza Egg Farm: " . $k['subjek'];
    $pesan_pembuka = "Halo " . $k['nama_pengirim'] . ",\n\nTerima kasih telah menghubungi Rameza Egg Farm terkait " . $k['subjek'] . ". \n\n[Tulis balasan Anda di sini]";
    
    // Membuat link Gmail Web
    $link_gmail = "https://mail.google.com/mail/?view=cm&fs=1&to=" . $identitas . "&su=" . urlencode($subjek_email) . "&body=" . urlencode($pesan_pembuka);
?>
    <a href="<?= $link_gmail ?>" target="_blank" class="btn btn-email">
       📧 Balas via Gmail
    </a>
<?php else : ?>
    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $identitas) ?>" target="_blank" class="btn btn-wa">
       💬 WhatsApp
    </a>
<?php endif; ?>

                            <a href="?aksi=hubungi&id=<?= $k['id_kontak'] ?>" style="display:block; margin-top:8px; font-size:11px; color: #0056b3; text-align:center;">Selesaikan</a>
                        
                        <?php else : ?>
                            <span class="badge" style="background: #d4edda; color: #155724;">Sudah Dihubungi</span>
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