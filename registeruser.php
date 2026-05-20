<?php
include 'config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
<<<<<<< HEAD
    $username = trim($_POST['username'] ?? '');
    $password_pelanggan = trim($_POST['password'] ?? '');
    $noTelp = trim($_POST['no_telp'] ?? '');
=======
    // Pastikan kunci $_POST sama dengan atribut 'name' di form HTML
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password_pelanggan = trim($_POST['password']); // Disamakan dengan name="password"
    $noTelp = trim(mysqli_real_escape_string($conn, $_POST['no_telp'])); // Disamakan dengan name="no_telp"
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
    
    // Hash password agar aman
    $password_aman = password_hash($password_pelanggan, PASSWORD_DEFAULT);

<<<<<<< HEAD
    $cek = $conn->prepare("SELECT id_pelanggan FROM pelanggan WHERE username = ?");
    $cek->bind_param('s', $username);
    $cek->execute();
    $cek_user = $cek->get_result();
=======
    // Cek apakah username sudah terdaftar
    $cek_user = mysqli_query($conn, "SELECT * FROM pelanggan WHERE username = '$username'");
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
    
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>alert('Username sudah ada!'); window.location='registeruser.php';</script>";
    } else {
<<<<<<< HEAD
        $stmt = $conn->prepare("INSERT INTO pelanggan (username, password_pelanggan, no_telp) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $username, $password_aman, $noTelp);
        
        if ($stmt->execute()) {
            echo "<script>alert('Berhasil Daftar! Silakan Login.'); window.location='loginuser.php';</script>";
        } else {
            die("Error SQL: " . mysqli_error($conn));
        }   
=======
        // Pastikan nama kolom di DB (noTelp) sudah benar
        $query = "INSERT INTO pelanggan (username, password_pelanggan, noTelp) VALUES ('$username', '$password_aman', '$noTelp')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Berhasil Daftar! Silakan Login.'); window.location='loginuser.php';</script>";
        } else {
            die("Error SQL: " . mysqli_error($conn));
        }
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register User - Rameza Egg Farm</title>
    <link rel="stylesheet" href="assets/css/registeruser.css">
</head>
<body>
    <div class="container">
        <h2>Daftar Akun Baru</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Buat username" required>
            </div>
            <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" name="no_telp" placeholder="Contoh: 08123456789" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Buat password" required>
            </div>
            <button type="submit">Daftar Sekarang</button>
        </form>
        <div class="footer-link">
            Sudah punya akun? <a href="loginuser.php">Login di sini</a>
        </div>
    </div>
</body>
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> 6091c61ef05d62631a11839af424cc438bb6f36e
