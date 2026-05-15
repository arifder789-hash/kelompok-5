<?php
// Memulai session agar bisa dihapus
session_start();

// 1. Menghapus semua data yang tersimpan di session
session_unset();

// 2. Menghancurkan session secara permanen dari server
session_destroy();

// 3. Mengarahkan admin kembali ke halaman login
// Kita tambahkan alert agar admin tahu mereka sudah aman keluar dari sistem
echo "<script>
    alert('Anda telah keluar dari sistem Rameza Admin.');
    window.location='login_admin.php';
</script>";
exit();
?>