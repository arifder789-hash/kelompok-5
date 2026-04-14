-- =============================================
-- DATABASE: db_login (VERSI FIX)
-- =============================================

CREATE DATABASE IF NOT EXISTS db_login;
USE db_login;

-- Tabel login
CREATE TABLE IF NOT EXISTS tb_login (
    id_user  INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- sudah siap untuk password_hash
    role     ENUM('admin','karyawan','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Data contoh (sementara masih MD5 biar kompatibel dengan kode lama)
INSERT INTO tb_login (username, password, role) VALUES
('admin',    MD5('admin123'),    'admin'),
('budi',     MD5('budi123'),     'user'),
('andi',     MD5('andi123'),     'karyawan');
