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
    <link rel="stylesheet" href="assets/css/login_admin.css">
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