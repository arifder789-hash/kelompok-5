<?php
// ============================================
//  config/db.php — Koneksi Database
//  Sesuaikan DB_NAME, DB_USER, DB_PASS
// ============================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'project_rameza'); // ← ganti jika perlu
define('DB_USER', 'root');        // ← ganti jika perlu
define('DB_PASS', '');            // ← ganti jika perlu
define('DB_CHAR', 'utf8mb4');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );

    $conn = $pdo; // ← tambahkan baris ini saja

} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(['error' => 'Koneksi database gagal: ' . $e->getMessage()]));
}