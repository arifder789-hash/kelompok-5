<?php
// =============================================
// FILE: logout.php
// Fungsi: Menghancurkan session dan redirect ke login
// =============================================

session_start();       // Mulai session agar bisa diakses
session_destroy();     // Hancurkan semua data session (user keluar)

header("Location: login.php");  // Kembali ke halaman login
exit();
?>
