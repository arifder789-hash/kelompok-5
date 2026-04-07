<?php
session_start();
include "koneksi.php";

$pesan = "";

if (isset($_POST['btn_register'])) {

    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = trim($_POST['password']);
    $konfirm  = trim($_POST['konfirmasi']);
    $role     = $_POST['role'];

    // VALIDASI
    if (empty($username) || empty($password) || empty($konfirm)) {
        $pesan = "Semua field wajib diisi!";

    } elseif (strlen($username) < 4) {
        $pesan = "Username minimal 4 karakter!";

    } elseif (strlen($password) < 6) {
        $pesan = "Password minimal 6 karakter!";

    } elseif ($password !== $konfirm) {
        $pesan = "Konfirmasi password tidak cocok!";

    } else {
        // cek username
        $cek = mysqli_query($koneksi, "SELECT * FROM tb_login WHERE username='$username'");

        if (mysqli_num_rows($cek) > 0) {
            $pesan = "Username sudah digunakan!";
        } else {
            $hash = md5($password);

            $simpan = mysqli_query($koneksi,
                "INSERT INTO tb_login (username,password,role)
                 VALUES ('$username','$hash','$role')");

            if ($simpan) {
                header("Location: login.php");
                exit();
            } else {
                $pesan = "Gagal menyimpan data!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register | Sistem Login</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --primary: #4f46e5;
    --bg: linear-gradient(135deg, #667eea, #764ba2);
}

body {
    font-family: 'Inter', sans-serif;
    background: var(--bg);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.container {
    background: rgba(255,255,255,0.95);
    padding: 40px;
    border-radius: 20px;
    width: 100%;
    max-width: 400px;
}

h2 {
    text-align: center;
    margin-bottom: 25px;
}

.input-group {
    margin-bottom: 18px;
}

.input-wrapper {
    position: relative;
}

.input-wrapper i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
}

input, select {
    width: 100%;
    padding: 12px 12px 12px 40px;
    border-radius: 10px;
    border: 1px solid #ddd;
}

button {
    width: 100%;
    padding: 14px;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
}

button:hover {
    background: #4338ca;
}

.error-box {
    background: #fee2e2;
    color: #b91c1c;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
}

.link {
    text-align: center;
    margin-top: 20px;
}
</style>
</head>

<body>

<div class="container">

<h2>Daftar Akun</h2>

<?php if ($pesan != ""): ?>
<div class="error-box">
    <i class="fas fa-exclamation-circle"></i> <?= $pesan ?>
</div>
<?php endif; ?>

<form method="POST">

<div class="input-group">
<div class="input-wrapper">
<i class="fas fa-user"></i>
<input type="text" name="username" placeholder="Username">
</div>
</div>

<div class="input-group">
<div class="input-wrapper">
<i class="fas fa-lock"></i>
<input type="password" name="password" placeholder="Password">
</div>
</div>

<div class="input-group">
<div class="input-wrapper">
<i class="fas fa-lock"></i>
<input type="password" name="konfirmasi" placeholder="Konfirmasi Password">
</div>
</div>

<div class="input-group">
<div class="input-wrapper">
<i class="fas fa-user-tag"></i>
<select name="role">
    <option value="mahasiswa">Mahasiswa</option>
    <option value="admin">Admin</option>
</select>
</div>
</div>

<button type="submit" name="btn_register">
    Daftar Sekarang
</button>

</form>

<div class="link">
    Sudah punya akun? <a href="login.php">Login</a>
</div>

</div>

</body>
</html>
