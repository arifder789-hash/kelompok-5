<?php
// ── Debug: tulis semua kejadian ke file log ──────────────────────────
$debug_log = __DIR__ . '/../debug_kontak.log';
$ts = date('Y-m-d H:i:s');
file_put_contents($debug_log, "[$ts] --- REQUEST START ---\n", FILE_APPEND);
file_put_contents($debug_log, "[$ts] METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'NONE') . "\n", FILE_APPEND);
file_put_contents($debug_log, "[$ts] POST: " . print_r($_POST, true) . "\n", FILE_APPEND);

// ── Session ───────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── Redirect helper (absolut) ─────────────────────────────────────────
function redirect_kontak(string $type, string $msg): void {
    $_SESSION[$type] = $msg;
    header('Location: http://localhost/ramezafarmnew/pages/kontak.php');
    exit();
}

// Pastikan request menggunakan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    file_put_contents($GLOBALS['debug_log'], "[$ts] FAIL: bukan POST\n", FILE_APPEND);
    header('Location: http://localhost/ramezafarmnew/pages/kontak.php');
    exit();
}

// Koneksi database
require_once __DIR__ . '/../config/db.php';
file_put_contents($debug_log, "[$ts] DB included, conn=" . ($conn ? 'OK' : 'FAIL') . "\n", FILE_APPEND);

// Ambil & bersihkan input
$nama_pengirim = trim($_POST['nama_pengirim'] ?? '');
$no_wa         = trim($_POST['no_wa']         ?? '');
$email         = trim($_POST['email']         ?? '');
$subjek        = trim($_POST['subjek']        ?? '');
$pesan         = trim($_POST['pesan']         ?? '');

file_put_contents($debug_log, "[$ts] DATA: nama=$nama_pengirim | wa=$no_wa | email=$email | subjek=$subjek\n", FILE_APPEND);

// Validasi field wajib
if (empty($nama_pengirim) || empty($no_wa) || empty($email) || empty($subjek)) {
    file_put_contents($debug_log, "[$ts] FAIL: validasi kosong\n", FILE_APPEND);
    redirect_kontak('error_kontak', 'Semua field wajib diisi. Silakan lengkapi formulir.');
}

// Validasi ENUM subjek
$allowed_subjek = ['Mitra', 'Komplain', 'Tanya Stok', 'Lainnya'];
if (!in_array($subjek, $allowed_subjek)) {
    file_put_contents($debug_log, "[$ts] FAIL: subjek invalid = $subjek\n", FILE_APPEND);
    redirect_kontak('error_kontak', 'Kategori keperluan tidak valid: ' . $subjek);
}

// Gabungkan no_wa dan email ke kolom email_wa
$email_wa = $no_wa . ' | ' . $email;

// Insert ke database
$sql  = "INSERT INTO kontak (nama_pengirim, email_wa, subjek, pesan) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    $err = $conn->error;
    file_put_contents($debug_log, "[$ts] FAIL: prepare gagal - $err\n", FILE_APPEND);
    redirect_kontak('error_kontak', 'Sistem error (prepare): ' . $err);
}

$stmt->bind_param('ssss', $nama_pengirim, $email_wa, $subjek, $pesan);

if ($stmt->execute()) {
    $new_id = $stmt->insert_id;
    file_put_contents($debug_log, "[$ts] SUCCESS: insert id=$new_id\n", FILE_APPEND);
    $stmt->close();
    redirect_kontak('sukses_kontak', 'Pesan Anda berhasil terkirim! Tim kami akan segera menghubungi Anda.');
} else {
    $err = $stmt->error;
    file_put_contents($debug_log, "[$ts] FAIL: execute gagal - $err\n", FILE_APPEND);
    $stmt->close();
    redirect_kontak('error_kontak', 'Gagal menyimpan pesan: ' . $err);
}
