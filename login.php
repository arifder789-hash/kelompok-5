<?php
session_start();
include "koneksi.php";

$pesan = "";

if (isset($_POST['btn_login'])) {
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $pesan = "Username dan password wajib diisi!";
    } else {
        // Menggunakan MD5 sesuai request awal, namun disarankan beralih ke password_hash nantinya
        $query  = "SELECT * FROM tb_login WHERE username = '$username' AND password = MD5('$password')";
        $hasil  = mysqli_query($koneksi, $query);
        $jumlah = mysqli_num_rows($hasil);

        if ($jumlah == 1) {
            $data = mysqli_fetch_assoc($hasil);
            $_SESSION['id_user']  = $data['id_user'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['role']     = $data['role'];

            if ($data['role'] == 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_karyawan.php");
            }
            exit();
        } else {
            $pesan = "Username atau password salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Login | Modern UI</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #002ea1;
            --primary-hover: #1500ff;
            --bg-gradient: linear-gradient(135deg, #010d45 0%, #1b00c9 100%);
            --text-main: #1f2937;
            --text-muted: #6b7280;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-gradient);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            transition: transform 0.3s ease;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h2 {
            color: var(--text-main);
            font-size: 28px;
            font-weight: 600;
        }

        .login-header p {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 5px;
        }

        /* Form Styling */
        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 16px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgb(0, 42, 255);
        }

        /* Error Message */
        .error-box {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            border-left: 4px solid #ef4444;
        }

        .error-box i { margin-right: 10px; }

        .footer-link {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .footer-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .footer-link a:hover { text-decoration: underline; }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container { padding: 30px 20px; }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <h2>Selamat Datang</h2>
        <p>Silakan masuk ke akun Anda</p>
    </div>

    <?php if ($pesan != "") : ?>
        <div class="error-box">
            <i class="fas fa-exclamation-circle"></i>
            <?= $pesan ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-group">
            <label for="username">Username</label>
            <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input type="text" id="username" name="username" placeholder="Username Anda" required>
            </div>
        </div>

        <div class="input-group">
            <label for="password">Password</label>
            <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" name="btn_login">
            Masuk Sekarang <i class="fas fa-arrow-right" style="margin-left: 8px; font-size: 13px;"></i>
        </button>
    </form>

    <div class="footer-link">
        Belum punya akun? <a href="register.php">Buat Akun</a>
    </div>
</div>

</body>
</html>
