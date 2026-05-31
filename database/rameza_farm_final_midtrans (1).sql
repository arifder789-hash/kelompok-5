-- ============================================================
--  Rameza Farm — Database Final + Integrasi Midtrans
--  Gabungan: project_rameza.sql + rameza_database_with_pembayaran.sql
--  Diperbarui: 2026-05-29
--
--  Ringkasan perubahan dari versi lama (project_rameza):
--  1. Tabel admin      → tambah kolom nama_lengkap, email, created_at
--  2. Tabel pelanggan  → tambah kolom alamat, kota, created_at, updated_at
--                        rename noTelp → no_telp
--  3. Tabel produk     → tambah kolom kode_produk, kategori, satuan,
--                        stok, min_pesan, gambar, aktif, created_at, updated_at
--                        (produk baru: bibit, pakan, obat)
--  4. Tabel pesanan    → tambah kolom kode_pesanan, nama_penerima, no_wa,
--                        alamat_kirim, kota, catatan, ongkir, grand_total,
--                        metode_bayar (+ enum 'midtrans'),
--                        status_pembayaran, catatan_pembayaran, updated_at
--  5. Tabel pembayaran → REFACTOR total: kini menyimpan data Midtrans
--                        (snap_token, transaction_id, response_midtrans, dsb)
--  6. Tabel detail_pesanan → tambah kolom nama_produk, satuan, subtotal
--  7. Tabel keranjang  → tambah UNIQUE KEY per pelanggan+produk
--  8. Tabel kontak     → tambah enum 'Lainnya' pada subjek,
--                        hapus kolom id_pelanggan & id_admin (disederhanakan)
--  9. Tabel ulasan     → BARU: penilaian produk oleh pelanggan
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- ============================================================
-- Database: `project_rameza`
-- ============================================================

  DEFAULT CHARACTER SET utf8mb4
   COLLATE utf8mb4_unicode_ci;
USE `rameza_database`;

-- ============================================================
-- URUTAN DROP: dari tabel dengan FK ke tabel utama
-- ============================================================
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `ulasan`;
DROP TABLE IF EXISTS `pembayaran`;
DROP TABLE IF EXISTS `detail_pesanan`;
DROP TABLE IF EXISTS `keranjang`;
DROP TABLE IF EXISTS `kontak`;
DROP TABLE IF EXISTS `pesanan`;
DROP TABLE IF EXISTS `produk`;
DROP TABLE IF EXISTS `pelanggan`;
DROP TABLE IF EXISTS `admin`;
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- 1. Tabel `admin`
--    BARU: nama_lengkap, email, created_at
-- ============================================================
CREATE TABLE `admin` (
  `id_admin`     int(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `username`     varchar(50)         NOT NULL,
  `password`     varchar(255)        NOT NULL,
  `nama_lengkap` varchar(100)        DEFAULT NULL,
  `email`        varchar(100)        DEFAULT NULL,
  `created_at`   timestamp           NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_lengkap`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', NULL, '2026-05-19 04:18:36');
-- Catatan: password di atas adalah hash bcrypt dari 'password' (Laravel default).
--          Ganti dengan hash dari 'admin123' bila dibutuhkan:
--          $2y$10$TZzuGFuybBKmWSW4bShp5.password_lama_jika_perlu

-- ============================================================
-- 2. Tabel `pelanggan`
--    BARU: alamat, kota, created_at, updated_at
--    RENAME: noTelp → no_telp
-- ============================================================
CREATE TABLE `pelanggan` (
  `id_pelanggan`       int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`           varchar(100)     NOT NULL,
  `email`              varchar(150)     DEFAULT NULL,
  `password_pelanggan` varchar(255)     NOT NULL,
  `no_telp`            varchar(20)      DEFAULT NULL,
  `alamat`             text             DEFAULT NULL,
  `kota`               varchar(100)     DEFAULT NULL,
  `created_at`         timestamp        NOT NULL DEFAULT current_timestamp(),
  `updated_at`         timestamp        NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_pelanggan`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data gabungan: dari project_rameza (romli, arip, lii, rom) + rameza_database (galih, rip)
-- pelanggan lii & rom dari DB lama ditambahkan kembali dengan format baru
INSERT INTO `pelanggan` (`id_pelanggan`, `username`, `email`, `password_pelanggan`, `no_telp`, `alamat`, `kota`, `created_at`, `updated_at`) VALUES
(1, 'romli',  'romli@gmail.com',  '$2y$10$TZzuGFuybBKmWSW4bShp5OoeuInZzWfAihysztLulze',       '085123456789', NULL, NULL, '2026-05-15 00:00:00', '2026-05-19 04:18:36'),
(2, 'arip',   'arip123@gmail.com','$2y$10$sb0jumfJNVvsete90EX9xujZvbN/vlQOU0O3uX3Nae8',       '081234567890', NULL, NULL, '2026-05-15 00:00:00', '2026-05-19 04:18:36'),
(3, 'lii',    NULL,               '$2y$10$Lh0VyW44yKASbp/WJk2NL.EB94AjjPAUZxlKVI6WNF2',       '089998887654', NULL, NULL, '2026-05-15 00:00:00', '2026-05-15 00:00:00'),
(4, 'rom',    NULL,               '$2y$10$lALNLyKmHjWImkpefZAZheFf.dVi5qkQ6MyHi/TBJ1HOe47vVKzoi','09999999999991', NULL, NULL, '2026-05-15 00:00:00', '2026-05-15 00:00:00'),
(5, 'galih',  NULL,               '$2y$10$cmg5Z5pOJvZ00YZnfGCOB.vv9TsiUz/0akleStiFXJvKz8r0cX3E6','0812345678', NULL, NULL, '2026-05-19 11:44:24', '2026-05-19 11:44:24'),
(6, 'rip',    NULL,               '$2y$10$90LyC.u/WgLcR3mdE/ovjewCQk14xaUJnwZAIRUykyfShZ7vE1bvG', '',           NULL, NULL, '2026-05-21 01:36:44', '2026-05-21 01:36:44');

-- ============================================================
-- 3. Tabel `produk`
--    BARU: kode_produk, kategori, satuan, stok, min_pesan,
--          gambar, aktif, created_at, updated_at
--    Produk baru: bibit, pakan, obat
-- ============================================================
CREATE TABLE `produk` (
  `id_produk`    int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_produk`  varchar(20)      NOT NULL,
  `nama_produk`  varchar(150)     NOT NULL,
  `kategori`     enum('telur','bibit','pakan','obat','lainnya') NOT NULL DEFAULT 'telur',
  `harga`        decimal(12,2)    NOT NULL DEFAULT 0.00,
  `satuan`       varchar(30)      NOT NULL DEFAULT 'kg',
  `stok`         int(11)          NOT NULL DEFAULT 0,
  `min_pesan`    int(11)          NOT NULL DEFAULT 1,
  `deskripsi`    text             DEFAULT NULL,
  `gambar`       varchar(255)     DEFAULT NULL,
  `aktif`        tinyint(1)       NOT NULL DEFAULT 1,
  `created_at`   timestamp        NOT NULL DEFAULT current_timestamp(),
  `updated_at`   timestamp        NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_produk`),
  UNIQUE KEY `kode_produk` (`kode_produk`),
  KEY `idx_kategori` (`kategori`),
  KEY `idx_aktif` (`aktif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Produk dari DB lama (id 1 & 2) digantikan dengan versi baru yang lebih lengkap
INSERT INTO `produk` (`id_produk`, `kode_produk`, `nama_produk`, `kategori`, `harga`, `satuan`, `stok`, `min_pesan`, `deskripsi`, `gambar`, `aktif`, `created_at`, `updated_at`) VALUES
(1,  'TLR-001', 'Telur Ayam Segar (10 Kg)',                 'telur',  250000.00, 'pack',   100, 1,  'Paket hemat telur 10kg. Cocok untuk usaha katering atau reseller.',                                       'assets/img/telur-10kg.jpg',     1, '2026-05-19 04:18:36', '2026-05-19 04:18:36'),
(2,  'BBT-001', 'Bibit Ayam Petelur DOC',                   'bibit',  18000.00,  'ekor',   300, 10, 'Day Old Chick (DOC) ayam petelur siap pelihara. Sehat, aktif, sudah divaksin Marek.',                    'assets/img/bibit-doc.jpg',      1, '2026-05-19 04:18:36', '2026-05-19 04:18:36'),
(3,  'BBT-002', 'Ayam Petelur Siap Produksi (18-20 minggu)','bibit',  90000.00,  'ekor',   100, 5,  'Ayam petelur usia 18–20 minggu, siap masuk masa produksi. Cocok untuk peternak pemula.',                 'assets/img/ayam-produksi.jpg',  1, '2026-05-19 04:18:36', '2026-05-19 04:18:36'),
(4,  'PKN-001', 'Pakan Layer Starter (50 kg)',               'pakan',  380000.00, 'sak',     50, 1,  'Pakan ayam petelur fase starter usia 1–6 minggu. Kandungan protein 20–22%, energi optimal.',             'assets/img/pakan-starter.jpg',  1, '2026-05-19 04:18:36', '2026-05-19 04:18:36'),
(5,  'PKN-002', 'Pakan Layer Grower (50 kg)',                'pakan',  350000.00, 'sak',     50, 1,  'Pakan ayam petelur fase grower usia 7–18 minggu. Seimbang untuk pertumbuhan optimal.',                   'assets/img/pakan-grower.jpg',   1, '2026-05-19 04:18:36', '2026-05-19 04:18:36'),
(6,  'OBT-001', 'Vitamin Unggas Vita Chick (100gr)',         'obat',   25000.00,  'botol',   80, 1,  'Suplemen vitamin lengkap untuk meningkatkan imunitas dan vitalitas ayam petelur.',                       'assets/img/vitamin.jpg',        1, '2026-05-19 04:18:36', '2026-05-19 04:18:36'),
(7,  'OBT-002', 'Obat Cacing Piperazin (100ml)',             'obat',   35000.00,  'botol',   60, 1,  'Obat cacing untuk unggas. Efektif, aman, dan mudah dicampur ke air minum.',                              'assets/img/obat-cacing.jpg',    1, '2026-05-19 04:18:36', '2026-05-19 04:18:36');

-- ============================================================
-- 4. Tabel `pesanan`
--    BARU: kode_pesanan, nama_penerima, no_wa, alamat_kirim,
--          kota, catatan, ongkir, grand_total, metode_bayar,
--          status_pembayaran, catatan_pembayaran, updated_at
--    metode_bayar sekarang mendukung 'midtrans'
--    Status pesanan diperluas: diproses, dibatalkan
-- ============================================================
CREATE TABLE `pesanan` (
  `id_pesanan`         int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pelanggan`       int(10) UNSIGNED NOT NULL,
  `id_admin`           int(10) UNSIGNED DEFAULT NULL,                      -- admin yang memproses
  `kode_pesanan`       varchar(30)      NOT NULL,                          -- contoh: ORD-20260529-001
  `nama_penerima`      varchar(150)     DEFAULT NULL,
  `no_wa`              varchar(20)      DEFAULT NULL,
  `alamat_kirim`       text             DEFAULT NULL,
  `kota`               varchar(100)     DEFAULT NULL,
  `catatan`            text             DEFAULT NULL,
  `total_harga`        decimal(14,2)    NOT NULL DEFAULT 0.00,             -- subtotal sebelum ongkir
  `ongkir`             decimal(10,2)    NOT NULL DEFAULT 0.00,
  `grand_total`        decimal(14,2)    NOT NULL DEFAULT 0.00,             -- total + ongkir
  `metode_bayar`       enum('cod','transfer','wa','midtrans') NOT NULL DEFAULT 'wa',
  `status`             enum('pending','dikonfirmasi','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'pending',
  -- Kolom khusus Midtrans: status pembayaran dipisah dari status pengiriman
  `status_pembayaran`  enum('belum_dibayar','menunggu','berhasil','gagal','kadaluarsa','dibatalkan') NOT NULL DEFAULT 'belum_dibayar',
  `catatan_pembayaran` text             DEFAULT NULL,
  `tanggal_pesan`      timestamp        NOT NULL DEFAULT current_timestamp(),
  `updated_at`         timestamp        NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_pesanan`),
  UNIQUE KEY `kode_pesanan` (`kode_pesanan`),
  KEY `idx_status`             (`status`),
  KEY `idx_status_pembayaran`  (`status_pembayaran`),
  KEY `idx_pelanggan`          (`id_pelanggan`),
  KEY `fk_pesanan_admin`       (`id_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Gabungan data: project_rameza (7 pesanan) + rameza_database (2 pesanan)
-- Pesanan 1–2 diambil dari DB baru (lebih lengkap); pesanan 3–7 dari DB lama
INSERT INTO `pesanan` (`id_pesanan`, `id_pelanggan`, `id_admin`, `kode_pesanan`, `nama_penerima`, `no_wa`, `alamat_kirim`, `kota`, `catatan`, `total_harga`, `ongkir`, `grand_total`, `metode_bayar`, `status`, `status_pembayaran`, `catatan_pembayaran`, `tanggal_pesan`, `updated_at`) VALUES
(1, 1, NULL, 'ORD-20260515-001', 'Romli',  '085123456789', 'Jl. Mawar No. 12',  'Jember', NULL, 28000.00,   15000.00, 43000.00,  'transfer',  'dikonfirmasi', 'berhasil',      NULL, '2026-05-15 10:00:00', '2026-05-19 04:18:36'),
(2, 2, NULL, 'ORD-20260515-002', 'Arip',   '081234567890', 'Jl. Melati No. 5',  'Jember', NULL, 250000.00,  0.00,     250000.00, 'cod',        'selesai',      'berhasil',      NULL, '2026-05-15 11:30:00', '2026-05-19 04:18:36'),
(3, 4, NULL, 'ORD-20260515-003', NULL,     NULL,           NULL,                NULL,     NULL, 25000.00,   0.00,     25000.00,  'wa',         'pending',      'belum_dibayar', NULL, '2026-05-15 11:53:08', '2026-05-15 11:53:08'),
(4, 4, NULL, 'ORD-20260515-004', NULL,     NULL,           NULL,                NULL,     NULL, 25000.00,   0.00,     25000.00,  'wa',         'pending',      'belum_dibayar', NULL, '2026-05-15 11:55:42', '2026-05-15 11:55:42'),
(5, 4, NULL, 'ORD-20260515-005', NULL,     NULL,           NULL,                NULL,     NULL, 25000.00,   0.00,     25000.00,  'wa',         'pending',      'belum_dibayar', NULL, '2026-05-15 11:57:38', '2026-05-15 11:57:38'),
(6, 4, NULL, 'ORD-20260515-006', NULL,     NULL,           NULL,                NULL,     NULL, 250000.00,  0.00,     250000.00, 'transfer',   'dikonfirmasi', 'berhasil',      NULL, '2026-05-15 11:57:58', '2026-05-15 11:57:58'),
(7, 4, NULL, 'ORD-20260515-007', NULL,     NULL,           NULL,                NULL,     NULL, 275000.00,  0.00,     275000.00, 'transfer',   'dikonfirmasi', 'berhasil',      NULL, '2026-05-15 17:01:11', '2026-05-15 17:01:11');

-- ============================================================
-- 5. Tabel `pembayaran`  ← REFACTORED untuk Midtrans
--    Kolom baru: transaction_id, snap_token, metode_pembayaran,
--                response_midtrans (JSON dari notifikasi Midtrans)
--    Kolom dihapus: bank, bukti_transfer (dipindah ke metode lama/manual)
--
--    Alur Midtrans:
--      a. Create Snap Transaction → simpan snap_token
--      b. Pelanggan bayar → Midtrans kirim notifikasi webhook
--      c. Webhook update status_pembayaran + response_midtrans
-- ============================================================
CREATE TABLE `pembayaran` (
  `id_pembayaran`      int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pesanan`         int(10) UNSIGNED NOT NULL,
  `id_admin`           int(10) UNSIGNED DEFAULT NULL,                       -- admin konfirmasi (metode manual)
  -- Kolom Midtrans
  `transaction_id`     varchar(100)     DEFAULT NULL,                       -- order_id dari Midtrans
  `snap_token`         varchar(255)     DEFAULT NULL,                       -- token untuk Snap.js
  `jumlah`             decimal(14,2)    NOT NULL DEFAULT 0.00,
  `metode_pembayaran`  varchar(50)      DEFAULT NULL,                       -- 'bank_transfer', 'gopay', 'qris', dll
  `bank`               varchar(50)      DEFAULT NULL,                       -- untuk transfer manual: BCA, BRI, dll
  `bukti_transfer`     varchar(255)     DEFAULT NULL,                       -- untuk transfer manual
  `status_pembayaran`  enum('pending','success','failed','expired','cancelled','challenge') NOT NULL DEFAULT 'pending',
  `response_midtrans`  longtext         DEFAULT NULL,                       -- JSON response notifikasi Midtrans
  `tanggal_bayar`      timestamp        NULL DEFAULT NULL,
  `created_at`         timestamp        NOT NULL DEFAULT current_timestamp(),
  `updated_at`         timestamp        NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_pembayaran`),
  UNIQUE KEY `transaction_id` (`transaction_id`),
  KEY `idx_pesanan`    (`id_pesanan`),
  KEY `idx_status`     (`status_pembayaran`),
  KEY `idx_tanggal`    (`created_at`),
  KEY `fk_pembayaran_admin` (`id_admin`),
  CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`id_admin`)   REFERENCES `admin`   (`id_admin`)   ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contoh data pembayaran (pesanan 1 & 2 sudah selesai):
INSERT INTO `pembayaran` (`id_pembayaran`, `id_pesanan`, `id_admin`, `transaction_id`, `snap_token`, `jumlah`, `metode_pembayaran`, `bank`, `bukti_transfer`, `status_pembayaran`, `response_midtrans`, `tanggal_bayar`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'ORD-20260515-001', NULL, 43000.00,  'bank_transfer', 'BCA', NULL,       'success', NULL, '2026-05-15 10:45:00', '2026-05-15 10:45:00', '2026-05-19 04:18:36'),
(2, 2, 1, 'ORD-20260515-002', NULL, 250000.00, 'cod',           NULL,  NULL,       'success', NULL, '2026-05-15 14:00:00', '2026-05-15 14:00:00', '2026-05-19 04:18:36');

-- ============================================================
-- 6. Tabel `detail_pesanan`
--    BARU: nama_produk (snapshot), satuan, subtotal
-- ============================================================
CREATE TABLE `detail_pesanan` (
  `id_detail`       int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pesanan`      int(10) UNSIGNED NOT NULL,
  `id_produk`       int(10) UNSIGNED NOT NULL,
  `nama_produk`     varchar(150)     NOT NULL,             -- snapshot nama saat beli
  `harga_saat_beli` decimal(12,2)    NOT NULL,
  `satuan`          varchar(30)      NOT NULL DEFAULT 'kg',
  `jumlah`          int(11)          NOT NULL DEFAULT 1,
  `subtotal`        decimal(14,2)    NOT NULL,             -- harga_saat_beli × jumlah
  PRIMARY KEY (`id_detail`),
  KEY `id_pesanan` (`id_pesanan`),
  KEY `id_produk`  (`id_produk`),
  CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_produk`)  REFERENCES `produk`  (`id_produk`)  ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Gabungan: detail dari DB lama (pesanan 5,6,7) + DB baru (pesanan 1,2)
INSERT INTO `detail_pesanan` (`id_detail`, `id_pesanan`, `id_produk`, `nama_produk`, `harga_saat_beli`, `satuan`, `jumlah`, `subtotal`) VALUES
(1, 1, 2, 'Telur Ayam Segar (1 Kg)',   28000.00, 'kg',   1, 28000.00),
(2, 2, 3, 'Telur Ayam Segar (10 Kg)', 250000.00, 'pack', 1, 250000.00),
(3, 5, 2, 'Telur Ayam Segar (1 Kg)',   25000.00, 'kg',   1, 25000.00),
(4, 6, 3, 'Telur Ayam Segar (10 Kg)', 250000.00, 'pack', 1, 250000.00),
(5, 7, 2, 'Telur Ayam Segar (1 Kg)',   25000.00, 'kg',   1, 25000.00),
(6, 7, 3, 'Telur Ayam Segar (10 Kg)', 250000.00, 'pack', 1, 250000.00);

-- ============================================================
-- 7. Tabel `keranjang`
--    BARU: UNIQUE KEY (id_pelanggan, id_produk)
--          rename tanggal_tambah → created_at
-- ============================================================
CREATE TABLE `keranjang` (
  `id_keranjang` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_pelanggan` int(10) UNSIGNED NOT NULL,
  `id_produk`    int(10) UNSIGNED NOT NULL,
  `jumlah`       int(11)          NOT NULL DEFAULT 1,
  `created_at`   timestamp        NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_keranjang`),
  UNIQUE KEY `unique_cart_item` (`id_pelanggan`, `id_produk`),
  KEY `id_produk` (`id_produk`),
  CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_produk`)    REFERENCES `produk`    (`id_produk`)    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `keranjang` (`id_keranjang`, `id_pelanggan`, `id_produk`, `jumlah`, `created_at`) VALUES
(1,  5, 3, 1,  '2026-05-19 13:07:59'),
(2,  5, 2, 1,  '2026-05-19 13:08:01'),
(3,  5, 1, 1,  '2026-05-19 13:08:02'),
(4,  5, 7, 1,  '2026-05-19 13:08:06'),
(5,  6, 3, 1,  '2026-05-21 01:43:34'),
(6,  6, 2, 3,  '2026-05-21 01:43:36'),
(7,  6, 4, 20, '2026-05-21 01:43:42'),
(8,  6, 7, 1,  '2026-05-21 01:45:09'),
(9,  6, 6, 2,  '2026-05-21 01:45:11');

-- ============================================================
-- 8. Tabel `kontak`
--    BARU: tambah enum 'Lainnya' pada subjek
--    DIHAPUS: id_pelanggan, id_admin (tabel kontak bersifat publik/anonim)
-- ============================================================
CREATE TABLE `kontak` (
  `id_kontak`    int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_pengirim` varchar(100)    NOT NULL,
  `email_wa`     varchar(100)     NOT NULL,
  `subjek`       enum('Mitra','Komplain','Tanya Stok','Lainnya') NOT NULL DEFAULT 'Lainnya',
  `pesan`        text             NOT NULL,
  `tanggal_kirim` timestamp       NOT NULL DEFAULT current_timestamp(),
  `status_pesan` enum('Belum Dibaca','Sudah Dihubungi') NOT NULL DEFAULT 'Belum Dibaca',
  PRIMARY KEY (`id_kontak`),
  KEY `idx_status` (`status_pesan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `kontak` (`id_kontak`, `nama_pengirim`, `email_wa`, `subjek`, `pesan`, `tanggal_kirim`, `status_pesan`) VALUES
(1, 'Romli',       '085123456789',    'Mitra',      'Saya ingin membuka peternakan ayam di daerah saya, saya harap anda bersedia menjadi supplier bibit ayam kami',   '2026-05-15 10:29:12', 'Belum Dibaca'),
(2, 'Budi Santoso', 'budi@gmail.com', 'Tanya Stok', 'Apakah ada telur untuk hari ini? Saya butuh 5 tray.',                                                            '2026-05-15 10:30:10', 'Sudah Dihubungi'),
(3, 'Arip',        'arip123@gmail.com','Mitra',      'Saya ingin bermitra dengan anda untuk distribusi telur di kota saya.',                                           '2026-05-15 10:33:46', 'Sudah Dihubungi');


-- ============================================================
-- AUTO_INCREMENT reset
-- ============================================================
ALTER TABLE `admin`           MODIFY `id_admin`       int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `pelanggan`       MODIFY `id_pelanggan`   int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `produk`          MODIFY `id_produk`      int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
ALTER TABLE `pesanan`         MODIFY `id_pesanan`     int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
ALTER TABLE `pembayaran`      MODIFY `id_pembayaran`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `detail_pesanan`  MODIFY `id_detail`      int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `keranjang`       MODIFY `id_keranjang`   int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
ALTER TABLE `kontak`          MODIFY `id_kontak`      int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

-- ============================================================
-- FOREIGN KEY: pesanan → admin (ditambahkan di akhir untuk urutan)
-- ============================================================
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ============================================================
--  CATATAN IMPLEMENTASI MIDTRANS (PHP)
--  ============================================================
--
--  1. Install Midtrans PHP SDK:
--     composer require midtrans/midtrans-php
--
--  2. Config (config/midtrans.php atau .env):
--     MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx   (Sandbox)
--     MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
--     MIDTRANS_IS_PRODUCTION=false
--
--  3. Buat Snap Token (saat checkout):
--     \Midtrans\Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
--     \Midtrans\Config::$isProduction = false;
--     \Midtrans\Config::$isSanitized  = true;
--     \Midtrans\Config::$is3ds        = true;
--
--     $params = [
--       'transaction_details' => [
--         'order_id'      => $pesanan->kode_pesanan,
--         'gross_amount'  => $pesanan->grand_total,
--       ],
--       'customer_details' => [
--         'first_name' => $pelanggan->username,
--         'phone'      => $pelanggan->no_telp,
--         'email'      => $pelanggan->email ?? 'noemail@ramezafarm.com',
--       ],
--       'item_details' => $itemDetails, // dari detail_pesanan
--     ];
--     $snapToken = \Midtrans\Snap::getSnapToken($params);
--
--     // Simpan ke tabel pembayaran:
--     INSERT INTO pembayaran (id_pesanan, transaction_id, snap_token, jumlah, status_pembayaran)
--     VALUES ($id_pesanan, $kode_pesanan, $snapToken, $grandTotal, 'pending');
--
--  4. Webhook Notifikasi (POST /midtrans/callback):
--     $notification = new \Midtrans\Notification();
--     $orderId      = $notification->order_id;
--     $statusCode   = $notification->status_code;
--     $grossAmount  = $notification->gross_amount;
--     $signatureKey = hash('sha512', $orderId.$statusCode.$grossAmount.env('MIDTRANS_SERVER_KEY'));
--
--     if ($signatureKey !== $notification->signature_key) die('Invalid signature');
--
--     // Mapping status Midtrans → status_pembayaran tabel
--     // 'capture'   + fraud_status 'accept' → 'success'
--     // 'settlement'                         → 'success'
--     // 'pending'                            → 'pending'
--     // 'deny' / 'failure'                  → 'failed'
--     // 'expire'                             → 'expired'
--     // 'cancel'                             → 'cancelled'
--     // 'challenge'                          → 'challenge'
--
--     UPDATE pembayaran
--     SET status_pembayaran = ?, response_midtrans = ?, tanggal_bayar = NOW()
--     WHERE transaction_id = ?;
--
--     -- Jika success → update pesanan:
--     UPDATE pesanan
--     SET status = 'dikonfirmasi', status_pembayaran = 'berhasil'
--     WHERE kode_pesanan = ?;
-- ============================================================
