<?php
/* ============================================================
   RAMEZA FARM — register.php
   Halaman Registrasi Pelanggan
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
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $no_telp  = trim($_POST['no_telp'] ?? '');

    if (!$username || !$password || !$confirm_password) {
        $error = 'Username, password, dan konfirmasi password wajib diisi.';
    } elseif ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak cocok.';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter.';
    } elseif (!preg_match('/^[A-Z]/', $password)) {
        $error = 'Password harus diawali dengan huruf kapital.';
    } else {
        // Cek apakah username sudah dipakai
        $stmtCheck = $conn->prepare("SELECT id_pelanggan FROM pelanggan WHERE username = ? LIMIT 1");
        $stmtCheck->bind_param('s', $username);
        $stmtCheck->execute();
        if ($stmtCheck->get_result()->num_rows > 0) {
            $error = 'Username sudah digunakan. Silakan pilih username lain.';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert ke database
            $stmt = $conn->prepare("INSERT INTO pelanggan (username, password_pelanggan, email, no_telp) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $username, $hashed_password, $email, $no_telp);
            
            if ($stmt->execute()) {
                $success = 'Pendaftaran berhasil! Silakan login dengan akun Anda.';
                // Auto login opsional, tapi di sini kita biarkan user login manual saja
            } else {
                $error = 'Terjadi kesalahan sistem. Pendaftaran gagal.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="Daftar akun pelanggan Rameza Egg Farm"/>
  <title>Daftar Akun — Rameza Egg Farm</title>

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
      <div class="visual-copy-tag">Gabung Bersama Kami</div>
      <h1>
        Mulai Belanja <strong>Cerdas</strong><br/>
        kebutuhan<br/>
        <em>peternakan Anda</em>
      </h1>
      <p>
        Jadilah bagian dari keluarga pelanggan Rameza Farm. Buat akun Anda 
        sekarang untuk menikmati fitur pemesanan yang lebih cepat, melacak 
        riwayat belanja dengan mudah, serta mendapatkan berbagai penawaran 
        khusus dan harga terbaik yang hanya tersedia untuk member terdaftar.
      </p>

      <!-- Stats -->
      <div class="visual-stats">
        <div class="visual-stat">
          <div class="visual-stat-num" data-count="100" data-suffix="%">0%</div>
          <div class="visual-stat-label">Terpercaya</div>
        </div>
        <div class="visual-stat">
          <div class="visual-stat-num" data-count="24" data-suffix="/7">0</div>
          <div class="visual-stat-label">Layanan Cepat</div>
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
        <div class="login-kicker">Pelanggan Baru</div>
        <h2>Buat Akun Anda</h2>
        <p>Lengkapi data diri di bawah ini untuk mendaftar.</p>
      </div>

      <!-- Alert error / success -->
      <?php if ($error): ?>
        <div class="login-alert error" role="alert" id="login-alert">
          <span class="alert-icon">⚠️</span>
          <span><?= htmlspecialchars($error) ?></span>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="login-alert success" role="alert" style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: flex-start; gap: 12px;">
          <span class="alert-icon">✅</span>
          <div style="flex:1;">
            <strong>Pendaftaran Berhasil!</strong><br/>
            <span><?= htmlspecialchars($success) ?></span>
            <div style="margin-top: 12px;">
              <a href="loginuser.php" style="background: #10b981; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-block;">Pergi ke Halaman Login</a>
            </div>
          </div>
        </div>
      <?php else: ?>

      <!-- Form -->
      <form method="POST" class="login-form" id="login-form"
            data-anim="fadeUp" novalidate autocomplete="off">

        <div class="form-row">
          <!-- Username -->
          <div class="form-field">
            <label class="form-field-label" for="username">
              <span class="field-icon">👤</span> Username <span style="color:#ef4444">*</span>
            </label>
            <div class="input-wrap">
              <input type="text" id="username" name="username" class="form-input" placeholder="Masukkan username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required />
            </div>
          </div>

          <!-- Email -->
          <div class="form-field">
            <label class="form-field-label" for="email">
              <span class="field-icon">📧</span> Email
            </label>
            <div class="input-wrap">
              <input type="email" id="email" name="email" class="form-input" placeholder="Masukkan email (Opsional)" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
            </div>
          </div>
        </div>

        <div class="form-row">
          <!-- Password -->
          <div class="form-field">
            <label class="form-field-label" for="password">
              <span class="field-icon">🔒</span> Password <span style="color:#ef4444">*</span>
            </label>
            <div class="input-wrap">
              <input type="password" id="password" name="password" class="form-input" placeholder="Buat password" required minlength="8" pattern="^[A-Z].*" title="Minimal 8 karakter dan diawali huruf kapital" />
              <button type="button" class="pw-toggle" id="pw-toggle" aria-label="Tampilkan password">Lihat</button>
            </div>
            <!-- Strength meter -->
            <div class="pw-strength-wrap" id="pw-strength-wrap">
              <div class="pw-strength-bar-track">
                <div class="pw-strength-bar" id="pw-strength-bar"></div>
              </div>
              <div class="pw-strength-label" id="pw-strength-label"></div>
            </div>
            <div class="field-hint" id="password-hint" style="margin-top: 4px;">Min. 8 karakter & huruf kapital di awal.</div>
          </div>

          <!-- Confirm Password -->
          <div class="form-field">
            <label class="form-field-label" for="confirm_password">
              <span class="field-icon">🔒</span> Konfirmasi <span style="color:#ef4444">*</span>
            </label>
            <div class="input-wrap">
              <input type="password" id="confirm_password" name="confirm_password" class="form-input" placeholder="Ulangi password" required />
              <button type="button" class="pw-toggle" id="pw-toggle-confirm" aria-label="Tampilkan konfirmasi password">Lihat</button>
            </div>
          </div>
        </div>

        <div class="form-field">
          <!-- No Telp / WA -->
          <label class="form-field-label" for="no_telp">
            <span class="field-icon">📱</span> No. WhatsApp
          </label>
          <div class="input-wrap">
            <input type="text" id="no_telp" name="no_telp" class="form-input" placeholder="Contoh: 0812..." value="<?= htmlspecialchars($_POST['no_telp'] ?? '') ?>" />
          </div>
        </div>


        <!-- Submit -->
        <button type="submit" class="login-submit" id="login-submit" style="margin-top: 24px;">
          <span class="btn-spinner"></span>
          <span class="btn-label">✨ Buat Akun Sekarang</span>
        </button>

      </form>
      <?php endif; ?>

      <!-- Footer -->
      <p class="form-footer" data-anim="fadeIn">
        Sudah punya akun? <a href="loginuser.php">Masuk di sini</a>
      </p>

    </div>
  </main>

</div><!-- /login-shell -->

<!-- JavaScript -->
<script src="assets/js/register.js"></script>
</body>
</html>
