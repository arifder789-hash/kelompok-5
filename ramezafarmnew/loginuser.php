<?php
session_start();
include 'config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = trim($_POST['password']);

    $sql    = "SELECT * FROM project_rameza WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password_pelanggan'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['username']        = $row['username'];
            
            header("Location: ../dashboard.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/loginuser.css">
</head>
<body>

<div class="card">

    <div class="brand">
        <div class="brand-icon">
            <i class="fas fa-user-circle"></i>
        </div>
        <div>
            <div class="brand-name">Rameza</div>
            <div class="brand-sub">Panel Pengelola</div>
        </div>
    </div>

    <hr class="divider">

    <div class="heading">
        <h1>Masuk ke Akun Anda</h1>
        <p>Silakan isi username dan password untuk melanjutkan.</p>
    </div>

    <!-- Pesan error -->
    <?php if (!empty($error)): ?>
    <div class="alert-error">
        <i class="fas fa-circle-exclamation"></i>
        <?php echo $error; ?>
    </div>
    <?php endif; ?>

    <form action="" method="POST" autocomplete="off">

        <div class="form-group">
            <label for="username">Username</label>
            <div class="input-wrap">
                <input type="text" id="username" name="username"
                       placeholder="Masukkan username"
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                       required>
                <i class="fas fa-user icon-left"></i>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrap">
                <input type="password" id="password" name="password"
                       placeholder="Masukkan password" required>
                <i class="fas fa-lock icon-left"></i>
                <span class="toggle-pw" onclick="togglePassword()">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </span>
            </div>
        </div>

        <button type="submit" class="btn-login">
            <i class="fas fa-right-to-bracket" style="margin-right:8px;"></i>
            Masuk
        </button>

    </form>

    <div class="footer-link">
        Belum punya akun? <a href="registeruser.php">Daftar di sini</a>
    </div>

</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eyeIcon');
        const show  = input.type === 'password';
        input.type  = show ? 'text' : 'password';
        icon.className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
    }
</script>
</body>
</html>