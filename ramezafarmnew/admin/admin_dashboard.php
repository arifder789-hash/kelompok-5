<?php
session_start();
include '../config/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// 1. PROTEKSI HALAMAN
if (!isset($_SESSION['admin'])) {
    echo "<script>alert('Silakan login terlebih dahulu!'); window.location='../login_admin.php';</script>";
    exit();
}

$nama_admin = isset($_SESSION['admin']['username']) ? $_SESSION['admin']['username'] : $_SESSION['admin'];

// 2. QUERY STATISTIK
// Total Pesanan Pending
$q_pending = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan WHERE status = 'Pending'");
$data_pending = mysqli_fetch_assoc($q_pending);

// Total Pesanan Dikonfirmasi (Sudah diperbaiki dari typo tag PHP)
$q_konfirmasi = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan WHERE status = 'Dikonfirmasi'");
$data_konfirmasi = mysqli_fetch_assoc($q_konfirmasi);

// Hitung Total Pendapatan (Omzet dari pesanan sukses)
$q_pendapatan = mysqli_query($conn, "SELECT SUM(total_harga) as total FROM pesanan WHERE status IN ('Dikonfirmasi', 'selesai')");
$data_pendapatan = mysqli_fetch_assoc($q_pendapatan);
$total_omzet = $data_pendapatan['total'] ?? 0;

// Hitung Layanan Kontak yang belum dibaca/dihubungi
$q_kontak_pending = mysqli_query($conn, "SELECT COUNT(*) as total FROM kontak WHERE status_pesan = 'Belum Dibaca'");
$data_kontak = mysqli_fetch_assoc($q_kontak_pending);
$query = "SELECT MONTHNAME(tanggal_pesan) as bulan, COUNT(*) as total_pesanan 
          FROM pesanan 
          WHERE YEAR(tanggal_pesan) = YEAR(CURDATE())
          GROUP BY MONTH(tanggal_pesan)
          ORDER BY MONTH(tanggal_pesan) ASC";

$result = $conn->query($query);

$list_bulan = [];
$jumlah_pesanan = [];

// 3. Masukkan hasil database ke dalam array PHP
while ($row = $result->fetch_assoc()) {
    $list_bulan[] = $row['bulan'];         // Menampung nama bulan (Jan, Feb, dst)
    $jumlah_pesanan[] = (int)$row['total_pesanan']; // Menampung jumlah orderan masuk
}

// 4. Ubah array PHP ke format JSON agar bisa dibaca oleh JavaScript grafik
$json_bulan = json_encode($list_bulan);
$json_pesanan = json_encode($jumlah_pesanan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Rameza Admin</title>
    <link rel="stylesheet" href="../assets/css/beranda_admin.css">
    <!-- <style>
        /* CSS tambahan khusus layout baru agar makin padat dan proporsional */
        .grid-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            margin-top: 30px;
        }
        .activity-card, .shortcut-card {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        .activity-card h3, .shortcut-card h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #0056b3;
            font-size: 16px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 10px;
        }
        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .activity-item {
            padding: 12px 0;
            border-bottom: 1px dashed #f1f5f9;
            font-size: 14px;
            color: #334155;
            display: flex;
            justify-content: space-between;
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-time { color: #94a3b8; font-size: 12px; }

        .shortcut-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .btn-quick {
            display: block;
            text-align: center;
            background: #f1f5f9;
            color: #334155;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-quick:hover {
            background: #0056b3;
            color: white;
        }

        /* Warna box baru */
        .box.omzet { border-left-color: #007bff; }
        .box.kontak { border-left-color: #dc3545; }

        @media (max-width: 992px) {
            .grid-layout { grid-template-columns: 1fr; }
        }
    </style> -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"></script>
</head>
<body>

    <?php include '../includes/navbar_admin.php'; ?>

    <div class="main-content">
        
        <div class="welcome-card">
            <h2>Selamat Datang Kembali, <?= htmlspecialchars($nama_admin) ?>! 👋</h2>
            <p>Hari ini adalah hari yang baik untuk mengelola perkembangan di platform Rameza Egg Farm.</p>
        </div>
        
        <div class="info-boxes">
            <div class="box pending">
                <h3>Pesanan Pending</h3>
                <p><?= $data_pending['total'] ?> <span style="font-size: 13px; font-weight: normal; color: #64748b;">perlu diurus</span></p>
            </div>
            <div class="box done">
                <h3>Pesanan Sukses</h3>
                <p><?= $data_konfirmasi['total'] ?> <span style="font-size: 13px; font-weight: normal; color: #64748b;">selesai</span></p>
            </div>
            <div class="box omzet">
                <h3>Total Pendapatan</h3>
                <p>Rp <?= number_format($total_omzet, 0, ',', '.') ?></p>
            </div>
            <div class="box kontak">
                <h3>Pesan Belum Dibaca</h3>
                <p><?= $data_kontak['total'] ?> <span style="font-size: 13px; font-weight: normal; color: #64748b;">inbox baru</span></p>
            </div>
        </div>

        <div class="grid-layout">
            
            <div class="activity-card">
                <h3> Aktivitas Terbaru</h3>
                <div class="activity-list">
                    <?php
                    // Mengambil 3 aktivitas pesanan terbaru secara real-time
                    $q_log = mysqli_query($conn, "SELECT pesanan.id_pesanan, pesanan.total_harga, pelanggan.username 
                                                  FROM pesanan 
                                                  JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
                                                  ORDER BY pesanan.id_pesanan DESC LIMIT 3");
                    if(mysqli_num_rows($q_log) > 0) {
                        while($log = mysqli_fetch_assoc($q_log)) {
                            echo "<div class='activity-item'>";
                            echo "<span>🛒 Masuk pesanan baru dari <strong>".htmlspecialchars($log['username'])."</strong> sebesar Rp ".number_format($log['total_harga'],0,',','.')."</span>";
                            echo "<span class='activity-time'>#".$log['id_pesanan']."</span>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p style='color:#94a3b8; font-size:14px; text-align:center;'>Belum ada aktivitas tercatat.</p>";
                    }
                    ?>
        </div>
    </div>
         <div class="clock-card">
             <h3> Waktu Wilayah</h3>
               <div class="clock-body">
                   <h3 id="jam-realtime">00:00:00</h3>
               <p id="tanggal-realtime">Memuat tanggal...</p>
            </div>
                 <div class="clock-footer">
         </div>
            
        </div>
<div class="col-lg-7 connectedSortable">
                <div class="card mb-4">
                  <div class="card-header">
                    <h3 class="card-title">Sales Value</h3>
                  </div>

                  <div class="card-body">
                    <div id="revenue-chart"></div>
                  </div>
                </div>
    </div>

     <script>

  const dataBulanDariPHP = <?php echo $json_bulan; ?>;
  const dataPesananDariPHP = <?php echo $json_pesanan; ?>;

  const sales_chart_options = {
    series: [{
      name: 'Pesanan Masuk',
      data: dataPesananDariPHP 
    }],
    chart: {
      height: 300,
      type: 'area',
      toolbar: {
        show: false
      }
    },
    colors: ['#0d6efd'],
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth'
    },
    xaxis: {
      categories: dataBulanDariPHP 
    },
    tooltip: {
      x: {
        format: 'dd/MM/yy'
      }
    }
  };

  const sales_chart = new ApexCharts(
    document.querySelector('#revenue-chart'),
    sales_chart_options
  );
  sales_chart.render();

  
  function perbaruiJam() {
    const sekarang = new Date();

    // 1. Ambil data jam, menit, dan detik
    // padStart(2, '0') berguna agar angka di bawah 10 tetap ditulis dua digit (misal: 05, bukan 5)
    const jam = String(sekarang.getHours()).padStart(2, '0');
    const menit = String(sekarang.getMinutes()).padStart(2, '0');
    const detik = String(sekarang.getSeconds()).padStart(2, '0');
    
    // Satukan format jam
    const waktuLengkap = `${jam}:${menit}:${detik}`;
    
    // 2. Format Hari dan Tanggal Indonesia
    const opsiFormat = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const tanggalLengkap = sekarang.toLocaleDateString('id-ID', opsiFormat);

    // 3. Masukkan hasilnya ke dalam komponen HTML tadi
    document.getElementById('jam-realtime').textContent = waktuLengkap;
    document.getElementById('tanggal-realtime').textContent = tanggalLengkap;
  }

  // Jalankan fungsi jam pertama kali saat halaman dimuat
  perbaruiJam();

  // Atur agar fungsi jam terus berjalan otomatis setiap 1 detik (1000 ms)
  setInterval(perbaruiJam, 1000);
</script>

</body>
</html>