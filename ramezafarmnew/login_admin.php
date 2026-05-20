<?php
session_start();
include 'config/koneksi.php';

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $ambil = mysqli_query($conn, "SELECT * FROM admin WHERE username='$user' AND password='$pass'");
    $cocok = mysqli_num_rows($ambil);

    if ($cocok == 1) {
        $_SESSION['admin'] = mysqli_fetch_assoc($ambil);
        echo "<script>alert('Login Berhasil, Halo Admin!'); window.location='admin/admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Username atau Password Salah!'); window.location='login_admin.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin - Rameza Egg Farm</title>
    <style>
        body { font-family: sans-serif; background: #2c3e50; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-box { background: white; padding: 30px; border-radius: 8px; width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #27ae60; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2 style="text-align:center">Login Admin</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Masuk</button>
        </form>
    </div>
</body>
</html>
