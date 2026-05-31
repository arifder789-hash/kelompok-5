<?php
session_start();
include 'config/db.php';

$error = '';
$success = false;

if (isset($_POST['login'])) {
  $user = $_POST['username'];
  $pass = $_POST['password'];

  $ambil = mysqli_query($conn, "SELECT * FROM admin WHERE username='$user' AND password='$pass'");
  $cocok = mysqli_num_rows($ambil);

  if ($cocok == 1) {
    $_SESSION['admin'] = mysqli_fetch_assoc($ambil);
    $success = true;
  } else {
    $error = 'Username atau Password Salah!';
  }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Admin — Rameza Egg Farm</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/loginadmin.css" />
</head>

<body class="admin-login-page">

  <main class="admin-login-shell">

    <!-- ══ KIRI: Form Panel ══ -->
    <section class="admin-login-panel" aria-label="Form login admin">

      <!-- Brand -->
      <a href="pages/beranda.php" class="admin-login-brand" aria-label="Kembali ke beranda">
        <img src="assets/img/logo_ayam.png" alt="Logo Rameza Farm" class="admin-login-logo" />
        <span>
          <strong>Rameza Farm</strong>
          <small>Admin Area</small>
        </span>
      </a>

      <!-- Heading -->
      <div class="admin-login-heading">
        <p class="admin-login-kicker">Panel Pengelola</p>
        <h1>Masuk Admin</h1>
        <p>Kelola pesanan, produk, dan aktivitas pelanggan dari satu tempat.</p>
      </div>

      <!-- Error alert digantikan oleh SweetAlert di bagian bawah -->

      <!-- Form -->
      <form method="POST" class="admin-login-form" autocomplete="off" novalidate>

        <label class="admin-field" for="username">
          <span>Username</span>
          <input type="text" id="username" name="username" placeholder="Masukkan username admin"
            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autocomplete="username" />
        </label>

        <label class="admin-field" for="password">
          <span>Password</span>
          <div class="admin-password-wrap">
            <input type="password" id="password" name="password" placeholder="Masukkan password" required
              autocomplete="current-password" />
            <button type="button" class="admin-password-toggle" aria-label="Tampilkan password" data-toggle-password>
              Lihat
            </button>
          </div>
        </label>

        <!-- Submit -->
        <button type="submit" name="login" class="admin-login-btn">
          🔐 Masuk Sekarang
        </button>

        <div class="admin-login-links">
          <span>Bukan admin?</span>
          <a href="pages/beranda.php">Kembali ke Beranda</a>
        </div>

    </section><!-- /panel -->


    <!-- ══ KANAN: Visual ══ -->
    <aside class="admin-login-visual" aria-hidden="true">

      <!-- Status card -->
      <div class="admin-visual-card">
        <span class="admin-status-dot"></span>
        <p>Status Operasional</p>
        <strong>Farm aktif hari ini</strong>
        <div class="admin-card-stats">
          <div>
            <div class="admin-card-stat-num">5K+</div>
            <div class="admin-card-stat-label">Ekor Ayam</div>
          </div>
          <div>
            <div class="admin-card-stat-num">200+</div>
            <div class="admin-card-stat-label">Pelanggan</div>
          </div>
        </div>
      </div>

      <!-- Copy -->
      <div class="admin-visual-copy">
        <span>Rameza Egg Farm</span>
        <h2>Kontrol pesanan dengan cepat dan rapi.</h2>
        <div class="admin-feature-list">
          <div class="admin-feature-item">Kelola pesanan masuk secara real-time</div>
          <div class="admin-feature-item">Pantau stok produk dan inventaris</div>
          <div class="admin-feature-item">Konfirmasi pembayaran pelanggan</div>
        </div>
      </div>

    </aside>

  </main><!-- /shell -->

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php if (!empty($error)): ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Gagal Masuk',
        text: '<?= addslashes($error) ?>',
        confirmButtonColor: '#1e3c72'
      });
    </script>
  <?php endif; ?>

  <?php if (isset($success) && $success): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Login Berhasil',
        text: 'Halo Admin!',
        confirmButtonColor: '#1e3c72',
        timer: 2000,
        timerProgressBar: true
      }).then(() => {
        window.location = 'admin/admin_dashboard.php';
      });
    </script>
  <?php endif; ?>

  <script>
    // Toggle Password
    const toggleBtn = document.querySelector('[data-toggle-password]');
    const passwordInput = document.getElementById('password');

    if (toggleBtn && passwordInput) {
      toggleBtn.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? 'Lihat' : 'Sembunyikan';
      });
    }
  </script>
</body>

</html>