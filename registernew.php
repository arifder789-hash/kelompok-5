<?php
session_start();
include "koneksi.php";

$pesan = "";

if (isset($_POST['btn_register'])) {

    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = trim($_POST['password']);
    $konfirm  = trim($_POST['konfirmasi']);
    $role     = isset($_POST['role']) ? $_POST['role'] : 'user';

    $allowed_role = ['admin','karyawan','user'];
    if (!in_array($role, $allowed_role)) {
        $role = 'user';
    }

    if (empty($username) || empty($password) || empty($konfirm)) {
        $pesan = "Semua field wajib diisi!";
    } elseif (strlen($username) < 4) {
        $pesan = "Username minimal 4 karakter!";
    } elseif (strlen($password) < 6) {
        $pesan = "Password minimal 6 karakter!";
    } elseif ($password !== $konfirm) {
        $pesan = "Konfirmasi password tidak cocok!";
    } else {
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
<title>Register Modern</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    height: 100vh;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    justify-content: center;
    align-items: center;
}

/* GLASS CARD */
.card {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 40px;
    width: 100%;
    max-width: 400px;
    color: white;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

.card h2 {
    text-align: center;
    margin-bottom: 25px;
}

/* FLOATING INPUT */
.input-box {
    position: relative;
    margin-bottom: 20px;
}

.input-box input,
.input-box select {
    width: 100%;
    padding: 14px 40px;
    border: none;
    border-radius: 10px;
    background: rgba(255,255,255,0.2);
    color: white;
    outline: none;
    font-size: 14px;
}

.input-box label {
    position: absolute;
    top: 50%;
    left: 40px;
    transform: translateY(-50%);
    color: #ddd;
    font-size: 13px;
    transition: 0.3s;
}

.input-box input:focus + label,
.input-box input:valid + label {
    top: 5px;
    font-size: 11px;
    color: #fff;
}

.input-box i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
}

/* PASSWORD TOGGLE */
.toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
}

/* BUTTON */
button {
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 10px;
    background: #4f46e5;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #4338ca;
}

/* ERROR */
.error {
    background: rgba(255,0,0,0.2);
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
    font-size: 13px;
}

/* LINK */
.link {
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
}

.link a {
    color: #fff;
    text-decoration: underline;
}
</style>
</head>

<body>

<div class="card">

<h2>✨ Daftar Akun</h2>

<?php if ($pesan != ""): ?>
<div class="error"><?= $pesan ?></div>
<?php endif; ?>

<form method="POST">

<div class="input-box">
<i class="fas fa-user"></i>
<input type="text" name="username" required>
<label>Username</label>
</div>

<div class="input-box">
<i class="fas fa-lock"></i>
<input type="password" name="password" id="pass1" required>
<label>Password</label>
<i class="fas fa-eye toggle" onclick="togglePass('pass1', this)"></i>
</div>

<div class="input-box">
<i class="fas fa-lock"></i>
<input type="password" name="konfirmasi" id="pass2" required>
<label>Konfirmasi Password</label>
<i class="fas fa-eye toggle" onclick="togglePass('pass2', this)"></i>
</div>

<div class="input-box">
<i class="fas fa-user-tag"></i>
<select name="role">
    <option value="user">User</option>
    <option value="karyawan">Karyawan</option>
    <option value="admin">Admin</option>
</select>
</div>

<button type="submit" name="btn_register">
    🚀 Daftar Sekarang
</button>

</form>

<div class="link">
    Sudah punya akun? <a href="login.php">Login</a>
</div>

</div>

<script>
function togglePass(id, el) {
    let input = document.getElementById(id);
    if (input.type === "password") {
        input.type = "text";
        el.classList.replace("fa-eye","fa-eye-slash");
    } else {
        input.type = "password";
        el.classList.replace("fa-eye-slash","fa-eye");
    }
}
</script>

</body>
</html>