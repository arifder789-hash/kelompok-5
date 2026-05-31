<?php
/* ============================================================
   RAMEZA FARM — loginuser.php
   Halaman Login Pelanggan
============================================================ */
session_start();
require_once 'config/db.php';

// Redirect jika sudah login
if (isset($_SESSION['id_pelanggan'])) {
    header('Location: pages/beranda.php'); 
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$username || !$password) {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM pelanggan WHERE username = ? OR email = ? OR no_telp = ? LIMIT 1");
        $stmt->bind_param('sss', $username, $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Verifikasi password dengan password_verify() karena password di-hash di database
            if (password_verify($password, $user['password_pelanggan'])) {
                $_SESSION['id_pelanggan'] = $user['id_pelanggan'];
                $_SESSION['username']     = $user['username'];
                $_SESSION['nama']         = $user['username'];
                header('Location: loginuser.php');
                exit;
            } else {
                $error = 'Password salah. Silakan coba lagi.';
            }
        } else {
            $error = 'Akun tidak ditemukan. Periksa kembali username, email, atau No. WhatsApp Anda.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="Masuk ke akun pelanggan Rameza Egg Farm"/>
  <title>Masuk — Rameza Egg Farm</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,400;1,500&display=swap" rel="stylesheet"/>

  <!-- Stylesheet -->
  <link rel="stylesheet" href="assets/css/loginuser.css?v=2"/>
</head>
<body>

<div class="login-shell">

  <!-- ════════════════════════════════════
       KIRI — VISUAL PANEL
  ════════════════════════════════════ -->
  <aside class="login-visual">
    <canvas id="particle-canvas"></canvas>
    <div class="visual-dotgrid"></div>

    <!-- Brand -->
    <div class="visual-brand" data-anim="fadeDown">
      <img src="assets/img/logo_ayam.png" class="visual-brand-logo" alt="Logo">
      <div>
        <div class="visual-brand-name">Rameza Farm</div>
        <div class="visual-brand-sub">Bondowoso, Jawa Timur</div>
      </div>
    </div>

    <!-- Copy -->
    <div class="visual-copy" data-anim="fadeUp">
      <div class="visual-copy-tag">Belanja Mudah</div>
      <h1>
        Telur <strong>Segar</strong><br/>
        langsung dari<br/>
        <em>peternak asli</em>
      </h1>
      <p>
        Masuk ke akun Anda sekarang untuk menikmati kemudahan berbelanja telur segar, 
        bibit unggas pilihan, pakan bernutrisi, hingga vitamin berkualitas terbaik. 
        Semua dikirim langsung dari peternakan Rameza Farm ke depan pintu rumah Anda 
        dengan jaminan kesegaran 100%.
      </p>

      <!-- Stats -->
      <div class="visual-stats">
        <div class="visual-stat">
          <div class="visual-stat-num" data-count="5000" data-suffix="+">0+</div>
          <div class="visual-stat-label">Ekor Ayam</div>
        </div>
        <div class="visual-stat">
          <div class="visual-stat-num" data-count="200" data-suffix="+">0+</div>
          <div class="visual-stat-label">Pelanggan</div>
        </div>
        <div class="visual-stat">
          <div class="visual-stat-num" data-count="10" data-suffix=" thn">0</div>
          <div class="visual-stat-label">Pengalaman</div>
        </div>
      </div>
    </div>
  </aside>


  <!-- ════════════════════════════════════
       KANAN — FORM PANEL
  ════════════════════════════════════ -->
  <main class="login-panel">
    <div class="login-form-wrap">

      <!-- Back -->
      <a href="pages/beranda.php" class="login-back" data-anim="fadeIn">
        <span class="login-back-arrow">←</span>
        Kembali ke Beranda
      </a>

      <!-- Heading -->
      <div class="login-heading" data-anim="fadeUp">
        <div class="login-kicker">Akun Pelanggan</div>
        <h2>Masuk ke Akun Anda</h2>
        <p>Silakan isi username/email/no. WhatsApp dan password untuk melanjutkan belanja.</p>
      </div>

      <!-- Alert error / success -->
      <?php if ($error): ?>
        <div class="login-alert error" role="alert" id="login-alert">
          <span class="alert-icon">⚠️</span>
          <span><?= htmlspecialchars($error) ?></span>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="login-alert success" role="alert">
          <span class="alert-icon">✅</span>
          <span><?= htmlspecialchars($success) ?></span>
        </div>
      <?php endif; ?>

      <!-- Form -->
      <form method="POST" class="login-form" id="login-form"
            data-anim="fadeUp" novalidate autocomplete="off">

        <!-- Username -->
        <div class="form-field">
          <label class="form-field-label" for="username">
            <span class="field-icon">👤</span> Username / Email / No. WhatsApp
          </label>
          <div class="input-wrap">
            <input
              type="text"
              id="username"
              name="username"
              class="form-input"
              placeholder="Masukkan username, email, atau No. WhatsApp"
              value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
              autocomplete="username"
              required
            />
            <span class="input-icon" id="username-icon">✏️</span>
          </div>
          <div class="field-hint" id="username-hint"></div>
        </div>

        <!-- Password -->
        <div class="form-field">
          <label class="form-field-label" for="password">
            <span class="field-icon">🔒</span> Password
          </label>
          <div class="input-wrap">
            <input
              type="password"
              id="password"
              name="password"
              class="form-input"
              placeholder="Masukkan password Anda"
              autocomplete="current-password"
              required
            />
            <button type="button" class="pw-toggle" id="pw-toggle"
                    aria-label="Tampilkan password">Lihat</button>
          </div>
          <div class="field-hint" id="password-hint"></div>
        </div>

        <!-- Submit -->
        <button type="submit" class="login-submit" id="login-submit">
          <span class="btn-spinner"></span>
          <span class="btn-label">🔐 Masuk Sekarang</span>
        </button>
      </form>

      <!-- Footer -->
      <p class="form-footer" data-anim="fadeIn">
        Belum punya akun? <a href="register.php">Daftar di sini</a>
      </p>

      <!-- Trust badges -->
      <div class="trust-badges" data-anim="fadeIn">
        <div class="trust-badge">
          <span class="trust-badge-icon">🔒</span> Aman & Terenkripsi
        </div>
        <div class="trust-badge">
          <span class="trust-badge-icon">✅</span> Terpercaya
        </div>
        <div class="trust-badge">
          <span class="trust-badge-icon">🚚</span> Pengiriman Cepat
        </div>
      </div>

    </div>
  </main>

</div><!-- /login-shell -->

<!-- JavaScript -->
<script src="assets/js/loginuser.js"></script>
</body>
</html>
