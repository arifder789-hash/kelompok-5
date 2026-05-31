<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// Pastikan request menggunakan method POST dan data berupa JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    if (isset($input['kode_pesanan'])) {
        $kode = $input['kode_pesanan'];

        // Update status menjadi selesai
        $stmt = $pdo->prepare("UPDATE pesanan SET status = 'selesai' WHERE kode_pesanan = ?");
        if ($stmt->execute([$kode])) {
            echo json_encode(['status' => 'success', 'message' => 'Status berhasil diperbarui']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui status']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Kode pesanan tidak ditemukan']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
