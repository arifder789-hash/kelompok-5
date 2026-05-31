-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2026 at 04:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rameza_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_lengkap`, `email`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', NULL, '2026-05-19 04:18:36');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` int(10) UNSIGNED NOT NULL,
  `id_pesanan` int(10) UNSIGNED NOT NULL,
  `id_produk` int(10) UNSIGNED NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `harga_saat_beli` decimal(12,2) NOT NULL,
  `satuan` varchar(30) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail`, `id_pesanan`, `id_produk`, `nama_produk`, `harga_saat_beli`, `satuan`, `jumlah`, `subtotal`) VALUES
(1, 1, 2, 'Telur Ayam Segar (1 Kg)', 28000.00, 'kg', 1, 28000.00),
(2, 2, 3, 'Telur Ayam Segar (10 Kg)', 250000.00, 'pack', 1, 250000.00);

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(10) UNSIGNED NOT NULL,
  `id_pelanggan` int(10) UNSIGNED NOT NULL,
  `id_produk` int(10) UNSIGNED NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id_keranjang`, `id_pelanggan`, `id_produk`, `jumlah`, `created_at`) VALUES
(1, 5, 3, 1, '2026-05-19 13:07:59'),
(2, 5, 2, 1, '2026-05-19 13:08:01'),
(3, 5, 1, 1, '2026-05-19 13:08:02'),
(4, 5, 8, 1, '2026-05-19 13:08:06'),
(5, 6, 3, 1, '2026-05-21 01:43:34'),
(6, 6, 2, 3, '2026-05-21 01:43:36'),
(7, 6, 4, 20, '2026-05-21 01:43:42'),
(8, 6, 7, 1, '2026-05-21 01:45:09'),
(9, 6, 6, 1, '2026-05-21 01:45:11'),
(10, 6, 8, 1, '2026-05-21 01:50:07');

-- --------------------------------------------------------

--
-- Table structure for table `kontak`
--

CREATE TABLE `kontak` (
  `id_kontak` int(10) UNSIGNED NOT NULL,
  `nama_pengirim` varchar(100) NOT NULL,
  `email_wa` varchar(100) NOT NULL,
  `subjek` enum('Mitra','Komplain','Tanya Stok','Lainnya') NOT NULL DEFAULT 'Lainnya',
  `pesan` text NOT NULL,
  `tanggal_kirim` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_pesan` enum('Belum Dibaca','Sudah Dihubungi') NOT NULL DEFAULT 'Belum Dibaca'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kontak`
--

INSERT INTO `kontak` (`id_kontak`, `nama_pengirim`, `email_wa`, `subjek`, `pesan`, `tanggal_kirim`, `status_pesan`) VALUES
(1, 'Romli', '085123456789', 'Mitra', 'Saya ingin membuka peternakan ayam di daerah saya, saya harap anda bersedia menjadi supplier bibit ayam kami', '2026-05-15 10:29:12', 'Belum Dibaca'),
(2, 'Budi Santoso', 'budi@gmail.com', 'Tanya Stok', 'Apakah ada telur untuk hari ini? Saya butuh 5 tray.', '2026-05-15 10:30:10', 'Sudah Dihubungi'),
(3, 'Arip', 'arip123@gmail.com', 'Mitra', 'Saya ingin bermitra dengan anda untuk distribusi telur di kota saya.', '2026-05-15 10:33:46', 'Sudah Dihubungi');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_pelanggan` varchar(255) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `username`, `password_pelanggan`, `email`, `no_telp`, `alamat`, `kota`, `created_at`, `updated_at`) VALUES
(1, 'romli', '$2y$10$TZzuGFuybBKmWSW4bShp5OoeuInZzWfAihysztLulze', 'romli@gmail.com', '085123456789', NULL, NULL, '2026-05-19 04:18:36', '2026-05-19 04:18:36'),
(2, 'arip', '$2y$10$sb0jumfJNVvsete90EX9xujZvbN/vlQOU0O3uX3Nae8', 'arip123@gmail.com', '081234567890', NULL, NULL, '2026-05-19 04:18:36', '2026-05-19 04:18:36'),
(5, 'galih', '$2y$10$cmg5Z5pOJvZ00YZnfGCOB.vv9TsiUz/0akleStiFXJvKz8r0cX3E6', NULL, '0812345678', NULL, NULL, '2026-05-19 11:44:24', '2026-05-19 11:44:24'),
(6, 'rip', '$2y$10$90LyC.u/WgLcR3mdE/ovjewCQk14xaUJnwZAIRUykyfShZ7vE1bvG', NULL, '', NULL, NULL, '2026-05-21 01:36:44', '2026-05-21 01:36:44');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int(10) UNSIGNED NOT NULL,
  `id_pelanggan` int(10) UNSIGNED NOT NULL,
  `kode_pesanan` varchar(30) NOT NULL,
  `nama_penerima` varchar(150) DEFAULT NULL,
  `no_wa` varchar(20) DEFAULT NULL,
  `alamat_kirim` text DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `total_harga` decimal(14,2) NOT NULL DEFAULT 0.00,
  `ongkir` decimal(10,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(14,2) NOT NULL DEFAULT 0.00,
  `metode_bayar` enum('cod','transfer','wa') NOT NULL DEFAULT 'wa',
  `status` enum('pending','dikonfirmasi','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'pending',
  `tanggal_pesan` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `id_pelanggan`, `kode_pesanan`, `nama_penerima`, `no_wa`, `alamat_kirim`, `kota`, `catatan`, `total_harga`, `ongkir`, `grand_total`, `metode_bayar`, `status`, `tanggal_pesan`, `updated_at`) VALUES
(1, 1, 'ORD-20260515-001', 'Romli', '085123456789', 'Jl. Mawar No. 12', 'Jember', NULL, 28000.00, 15000.00, 43000.00, 'transfer', 'dikonfirmasi', '2026-05-15 10:00:00', '2026-05-19 04:18:36'),
(2, 2, 'ORD-20260515-002', 'Arip', '081234567890', 'Jl. Melati No. 5', 'Jember', NULL, 250000.00, 0.00, 250000.00, 'cod', 'selesai', '2026-05-15 11:30:00', '2026-05-19 04:18:36');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(10) UNSIGNED NOT NULL,
  `kode_produk` varchar(20) NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `kategori` enum('telur','bibit','pakan','obat','lainnya') NOT NULL DEFAULT 'telur',
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `satuan` varchar(30) NOT NULL DEFAULT 'kg',
  `stok` int(11) NOT NULL DEFAULT 0,
  `min_pesan` int(11) NOT NULL DEFAULT 1,
  `deskripsi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `kode_produk`, `nama_produk`, `kategori`, `harga`, `satuan`, `stok`, `min_pesan`, `deskripsi`, `gambar`, `aktif`, `created_at`, `updated_at`) VALUES
(1, 'TLR-001', 'Telur Ayam Segar (Tray 30 butir)', 'telur', 55000.00, 'tray', 200, 1, 'Telur ayam segar dipanen setiap pagi. 1 tray berisi 30 butir. Higienis dan bergizi tinggi.', 'assets/img/telur-tray.jpg', 1, '2026-05-19 04:18:36', '2026-05-19 04:18:36'),
(2, 'TLR-002', 'Telur Ayam Segar (1 Kg)', 'telur', 28000.00, 'kg', 500, 1, 'Telur segar dijual per kilogram, rata-rata 16–18 butir per kg. Cocok untuk pembelian satuan.', 'assets/img/telur-kg.jpg', 1, '2026-05-19 04:18:36', '2026-05-19 04:18:36'),
(3, 'TLR-003', 'Telur Ayam Segar (10 Kg)', 'telur', 250000.00, 'pack', 100, 1, 'Paket hemat telur 10kg. Cocok untuk usaha katering atau reseller.', 'assets/img/telur-10kg.jpg', 1, '2026-05-19 04:18:36', '2026-05-19 04:18:36'),
(4, 'BBT-001', 'Bibit Ayam Petelur DOC', 'bibit', 18000.00, 'ekor', 300, 10, 'Day Old Chick (DOC) ayam petelur siap pelihara. Sehat, aktif, sudah divaksin Marek.', 'assets/img/bibit-doc.jpg', 1, '2026-05-19 04:18:36', '2026-05-19 04:18:36'),
(5, 'BBT-002', 'Ayam Petelur Siap Produksi (18-20 minggu)', 'bibit', 90000.00, 'ekor', 100, 5, 'Ayam petelur usia 18–20 minggu, siap masuk masa produksi. Cocok untuk peternak pemula.', 'assets/img/ayam-produksi.jpg', 1, '2026-05-19 04:18:36', '2026-05-19 04:18:36'),
(6, 'PKN-001', 'Pakan Layer Starter (50 kg)', 'pakan', 380000.00, 'sak', 50, 1, 'Pakan ayam petelur fase starter usia 1–6 minggu. Kandungan protein 20–22%, energi optimal.', 'assets/img/pakan-starter.jpg', 1, '2026-05-19 04:18:36', '2026-05-19 04:18:36'),
(7, 'PKN-002', 'Pakan Layer Grower (50 kg)', 'pakan', 350000.00, 'sak', 50, 1, 'Pakan ayam petelur fase grower usia 7–18 minggu. Seimbang untuk pertumbuhan optimal.', 'assets/img/pakan-grower.jpg', 1, '2026-05-19 04:18:36', '2026-05-19 04:18:36'),
(8, 'OBT-001', 'Vitamin Unggas Vita Chick (100gr)', 'obat', 25000.00, 'botol', 80, 1, 'Suplemen vitamin lengkap untuk meningkatkan imunitas dan vitalitas ayam petelur.', 'assets/img/vitamin.jpg', 1, '2026-05-19 04:18:36', '2026-05-19 04:18:36'),
(9, 'OBT-002', 'Obat Cacing Piperazin (100ml)', 'obat', 35000.00, 'botol', 60, 1, 'Obat cacing untuk unggas. Efektif, aman, dan mudah dicampur ke air minum.', 'assets/img/obat-cacing.jpg', 1, '2026-05-19 04:18:36', '2026-05-19 04:18:36');

-- --------------------------------------------------------

--
-- Table structure for table `ulasan`
--

CREATE TABLE `ulasan` (
  `id_ulasan` int(10) UNSIGNED NOT NULL,
  `id_produk` int(10) UNSIGNED NOT NULL,
  `id_pelanggan` int(10) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `komentar` text DEFAULT NULL,
  `tanggal_ulasan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ulasan`
--

INSERT INTO `ulasan` (`id_ulasan`, `id_produk`, `id_pelanggan`, `rating`, `komentar`, `tanggal_ulasan`) VALUES
(1, 2, 1, 5, 'Telurnya segar banget! Kuning telurnya pekat. Pasti beli lagi.', '2026-05-15 14:30:00'),
(2, 3, 2, 4, 'Harga ekonomis untuk 10kg. Cuma pengemasan bisa lebih rapi.', '2026-05-15 16:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD UNIQUE KEY `unique_cart_item` (`id_pelanggan`,`id_produk`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `kontak`
--
ALTER TABLE `kontak`
  ADD PRIMARY KEY (`id_kontak`),
  ADD KEY `idx_status` (`status_pesan`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_username` (`username`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD UNIQUE KEY `kode_pesanan` (`kode_pesanan`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD UNIQUE KEY `kode_produk` (`kode_produk`),
  ADD KEY `idx_kategori` (`kategori`),
  ADD KEY `idx_aktif` (`aktif`);

--
-- Indexes for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id_ulasan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `idx_produk` (`id_produk`),
  ADD KEY `idx_rating` (`rating`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `kontak`
--
ALTER TABLE `kontak`
  MODIFY `id_kontak` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id_ulasan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON UPDATE CASCADE;

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE,
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD CONSTRAINT `ulasan_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE,
  ADD CONSTRAINT `ulasan_ibfk_2` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
