-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 23, 2025 at 07:19 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `proyek1`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'default.jpg',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `email`, `no_hp`, `alamat`, `foto`, `created_at`) VALUES
(1, 'tino', '12121212', 'tino@gmail.com', '', 'bb', NULL, '2025-03-23 03:10:37'),
(2, 'a', 'aaaaaaaa', 'a@gmail.com', '08937883111111', 'no 1 jalan mawar', '6825930653256_toothless.jpg', '2025-03-23 03:16:07'),
(3, 'b', 'bbbbbbbb', 'b@gmail.com', '08937', 'jln', '68117879c179d_antibiotic.jpg', '2025-03-23 03:18:11'),
(4, 'c', 'cccccccc', 'c@gmail.com', NULL, NULL, NULL, '2025-03-23 03:20:26'),
(5, 'd', 'dddddddd', 'd@gmail.com', NULL, NULL, NULL, '2025-03-23 03:21:40'),
(6, 'e', 'eeeeeeee', 'e@gmail.com', NULL, NULL, NULL, '2025-03-23 03:24:42'),
(7, 'f', 'ffffffff', 'f@gmail.com', NULL, NULL, NULL, '2025-03-23 03:37:24'),
(9, 'k', '11111111', 'k@gmail.com', NULL, NULL, NULL, '2025-04-10 12:03:37'),
(10, 'tino nurcahya1234567890', '11111111', 't@gmail.com', '', '', 'default.jpg', '2025-05-02 15:12:10'),
(11, 'abc', '11111111', 'abc@gmail.com', NULL, NULL, 'default.jpg', '2025-05-07 04:41:03'),
(12, 'z', 'zzzzzzzz', 'z@gmail.com', NULL, NULL, 'default.jpg', '2025-05-13 14:46:18'),
(13, 'o', 'oooooooo', 'o@gmail.com', NULL, NULL, 'default.jpg', '2025-05-14 10:26:48'),
(14, 'm', 'mmmmmmmm', 'm@gmail.com', NULL, NULL, 'default.jpg', '2025-05-14 10:28:21'),
(15, '1', '11111111', '1@gmail.com', '', '', 'default.jpg', '2025-05-14 10:34:04'),
(16, 'x', 'xxxxxxxx', 'x@gmail.com', NULL, NULL, 'default.jpg', '2025-05-15 13:31:25'),
(17, 'n', 'nnnnnnnn', 'n@gmail.com', NULL, NULL, 'default.jpg', '2025-05-16 01:04:44'),
(18, 'tino nurcahyaaaaa', '11111111', 'tinonurcahya@gmail.com', '', '', '68285986d20ef_toothless.jpg', '2025-05-16 11:24:39'),
(19, 'tino nurcahya', '11111111', 'tinonurcahyaa@gmail.com', '', '', '6829d271c2ff4_toothless.jpg', '2025-05-18 12:21:09'),
(20, 'Annisa Aulia Rizki', '11111111', 'a11@gmail.com', '', '', '682b4c8b6edd9_profile.jpg', '2025-05-19 15:18:30');

-- --------------------------------------------------------

--
-- Table structure for table `arsip`
--

CREATE TABLE `arsip` (
  `id_arsip` int NOT NULL,
  `id_admin` int DEFAULT NULL,
  `nama_perusahaan` varchar(100) NOT NULL,
  `tgl_datang` date NOT NULL,
  `no_faktur` varchar(255) NOT NULL,
  `jth_tempo` date NOT NULL,
  `tgl_faktur` date NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `arsip`
--

INSERT INTO `arsip` (`id_arsip`, `id_admin`, `nama_perusahaan`, `tgl_datang`, `no_faktur`, `jth_tempo`, `tgl_faktur`, `total`, `created_at`, `updated_at`) VALUES
(64, 2, 'PT CARMELLA GUSTAVINDO', '2025-02-07', 'E19871749H8', '2025-02-28', '2025-02-07', 200000.00, '2025-05-09 08:01:34', '2025-05-22 13:01:29'),
(101, 2, 'PT PRABA BARAKA JAYA', '2025-01-22', 'erge33654', '2025-06-07', '2025-08-31', 1000000.00, '2025-05-22 12:46:09', '2025-05-22 12:46:09'),
(102, 2, 'CV ANUGERAH SUKSES MANDIRI DISTRIBUSI', '2025-02-22', 'erfwr345', '2025-05-22', '2025-04-27', 1200000.00, '2025-05-22 12:50:47', '2025-05-22 12:50:47'),
(103, 2, 'PT CARMELLA GUSTAVINDO', '2025-03-22', '35dfgre3', '2025-05-22', '2025-04-27', 950000.00, '2025-05-22 12:53:19', '2025-05-22 12:53:19'),
(104, 2, 'PT PRABA BARAKA JAYA', '2025-04-22', 'dfbe34t', '2025-05-22', '2025-04-28', 1300000.00, '2025-05-22 12:54:31', '2025-05-22 12:54:31'),
(105, 2, 'PERUSAHAAN 1', '2025-05-22', 'rthr456', '2025-05-22', '2025-04-27', 1100000.00, '2025-05-22 12:56:00', '2025-05-22 12:56:00'),
(106, 2, 'PERUSAHAAN 2', '2025-06-22', 'YJTYI768', '2025-08-22', '2025-05-06', 1500000.00, '2025-05-22 12:58:49', '2025-05-22 12:58:49'),
(107, 2, 'PERUSAHAAN 2', '2025-07-22', 'RTHRT4', '2025-05-22', '2025-05-06', 1400000.00, '2025-05-22 13:00:32', '2025-05-22 13:00:32'),
(108, 2, 'PT PRABA BARAKA JAYA', '2025-08-30', 'ERG34T', '2025-09-06', '2025-05-06', 1600000.00, '2025-05-22 13:02:39', '2025-05-22 13:02:39'),
(109, 2, 'PERUSAHAAN 2', '2025-09-23', 'URTU457', '2025-10-14', '2025-05-09', 1700000.00, '2025-05-22 13:20:13', '2025-05-22 13:20:13');

-- --------------------------------------------------------

--
-- Table structure for table `detail_produk`
--

CREATE TABLE `detail_produk` (
  `id_detail` int NOT NULL,
  `id_arsip` int NOT NULL,
  `id_produk` int NOT NULL,
  `kadaluarsa` date NOT NULL,
  `batch` varchar(100) NOT NULL,
  `jumlah` int NOT NULL,
  `satuan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_produk`
--

INSERT INTO `detail_produk` (`id_detail`, `id_arsip`, `id_produk`, `kadaluarsa`, `batch`, `jumlah`, `satuan`) VALUES
(63, 64, 26, '2029-07-01', 'HSOI43D', 5, 'box'),
(64, 64, 27, '2027-11-01', 'AHFIEY9', 5, 'pcs'),
(65, 64, 28, '2028-01-01', 'AIOHE79', 10, 'pcs'),
(66, 64, 29, '2028-11-11', 'AHEBOE8', 5, 'pcs'),
(67, 64, 30, '2028-11-11', 'WOHHWO', 10, 'pcs'),
(69, 64, 32, '2028-02-22', '938F9EJ', 12, 'meter'),
(70, 64, 33, '2029-11-11', 'SIGSKIF9', 12, 'meter'),
(71, 64, 34, '2029-02-22', 'EOINFJIE', 12, 'box'),
(72, 64, 35, '2029-10-22', 'JEIFIND', 12, 'box'),
(128, 101, 36, '2025-05-22', 'fghsr3532', 3, 'BOX'),
(129, 102, 26, '2025-06-22', 'sdfge343', 5, 'PCS'),
(130, 103, 36, '2025-05-22', 'dsgfser343', 4, 'BOX'),
(131, 103, 26, '2025-05-22', 'fgd343', 5, 'FLS'),
(132, 104, 37, '2025-05-22', 'eerg343', 4, 'BOX'),
(133, 104, 50, '2025-05-30', '34tegre', 3, 'BOX'),
(134, 105, 29, '2025-06-22', '45erge', 5, 'FLS'),
(135, 105, 33, '2025-07-22', '36dherh', 7, 'FLS'),
(136, 106, 30, '2025-08-22', '8YUK67I67', 8, 'PCS'),
(137, 107, 26, '2025-09-22', 'JYT785', 6, 'BOX'),
(138, 107, 37, '2025-09-15', 'YUII678', 5, 'PCS'),
(139, 108, 35, '2027-03-12', 'KHEFU9847', 9, 'PCS'),
(140, 109, 26, '2028-09-07', 'JRT547', 6, 'PCS');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int NOT NULL,
  `id_admin` int NOT NULL,
  `aksi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `waktu` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_admin`, `aksi`, `waktu`) VALUES
(1, 2, 'Menambah Arsip untuk perusahaan: nama pbf', '2025-04-22 14:47:56'),
(2, 2, 'Menghapus arsip dengan ID: 33', '2025-04-22 14:58:53'),
(3, 2, 'Menghapus arsip untuk perusahaan: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt; (ID: 36)', '2025-04-22 15:01:12'),
(4, 2, 'Menghapus arsip untuk perusahaan: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;, Tanggal Datang: 1111-11-11, Jatuh Tempo: 1111-11-11, Tanggal Faktur: 1111-11-11, Total: 1.00 (ID: 32) Produk yang dihapus: Nama Produk: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;, Kadaluarsa: 1111-11-11, Batch: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;, Jumlah: 1, Satuan: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;, Nomor Faktur: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt; | ', '2025-04-22 15:11:30'),
(5, 2, 'Menghapus arsip untuk perusahaan: nama pbf, Tanggal Datang: 1111-11-11, Jatuh Tempo: 1111-11-11, Tanggal Faktur: 1111-11-11, Total: 1.00 (ID: 37) Produk yang dihapus: ', '2025-04-22 15:12:38'),
(6, 2, 'Menambah Arsip untuk perusahaan: &lt;script&gt;alert(&quot;XSS&quot;);&lt;/script&gt;', '2025-04-24 18:18:57'),
(7, 2, 'Menghapus arsip untuk perusahaan: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;, Tanggal Datang: 1111-11-11, Jatuh Tempo: 1111-11-11, Tanggal Faktur: 1111-11-11, Total: 1.00 (ID: 34) Produk yang dihapus: Nama Produk: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;, Kadaluarsa: 1111-11-11, Batch: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;, Jumlah: 1, Satuan: , Nomor Faktur: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt; | ', '2025-04-24 18:24:09'),
(8, 2, 'Mengedit arsip milik perusahaan: &lt;script&gt;alert(&quot;edit-1&quot;);&lt;/script&gt;', '2025-04-24 18:35:29'),
(9, 2, 'Menghapus arsip untuk perusahaan: &lt;script&gt;alert(&quot;edit-1&quot;);&lt;/script&gt;, Tanggal Datang: 1111-11-11, Jatuh Tempo: 1111-11-11, Tanggal Faktur: 1111-11-11, Total: 1.00 (ID: 39) Produk yang dihapus: Nama Produk: &lt;script&gt;alert(&quot;XSS&quot;);&lt;/script&gt;, Kadaluarsa: 1111-11-11, Batch: <script>alert(\"XSS\");</script>, Jumlah: 1, Satuan: <script>alert(\"XSS\");</script>, Nomor Faktur: 1 | ', '2025-04-24 18:37:03'),
(10, 2, 'Menambah Arsip untuk perusahaan: perusahaan sejahtera', '2025-04-24 18:45:10'),
(11, 2, 'Mengedit arsip milik perusahaan: perusahaan sejati', '2025-04-24 18:46:07'),
(12, 2, 'Mengedit arsip milik perusahaan: perusahaan sejati', '2025-04-24 18:46:48'),
(13, 2, 'Menghapus arsip untuk perusahaan: perusahaan sejati, Tanggal Datang: 2025-03-25, Jatuh Tempo: 2025-05-30, Tanggal Faktur: 2025-02-05, Total: 150000.00 (ID: 40) Produk yang dihapus: Nama Produk: produk nomor 1, Kadaluarsa: 2027-12-12, Batch: adhfow123, Jumlah: 5, Satuan: box, Nomor Faktur: sdfh242 | ', '2025-04-24 18:47:11'),
(14, 2, 'Mengedit arsip milik perusahaan: perusahaan 5', '2025-04-24 18:59:21'),
(15, 2, 'Mengedit arsip milik perusahaan: perusahaan 5', '2025-04-24 19:09:37'),
(16, 2, 'Mengedit arsip milik perusahaan: perusahaan 2', '2025-04-24 19:10:34'),
(17, 2, 'Mengedit arsip milik perusahaan: perusahaan 2', '2025-04-24 19:14:35'),
(18, 2, 'Mengedit arsip milik perusahaan: 1', '2025-04-24 19:15:15'),
(19, 2, 'Mengedit arsip milik perusahaan: 1.2', '2025-04-24 19:17:20'),
(20, 2, 'Mengedit arsip milik perusahaan: 1', '2025-04-24 19:18:38'),
(21, 2, 'Mengedit arsip milik perusahaan: 1', '2025-04-24 19:21:56'),
(22, 2, 'Mengedit arsip milik perusahaan: perusahaan 5', '2025-04-24 19:23:48'),
(23, 2, 'Mengedit arsip milik perusahaan: perusahaan 2.22', '2025-04-24 19:25:12'),
(24, 2, 'Menambah Arsip untuk perusahaan: perusahaan hapus 2', '2025-04-24 19:27:45'),
(25, 2, 'Mengedit arsip milik perusahaan: perusahaan 2', '2025-04-24 19:36:20'),
(26, 2, 'Menambah Arsip untuk perusahaan: perusahaan 2', '2025-04-24 20:48:10'),
(27, 2, 'Mengedit arsip milik perusahaan: 1.2', '2025-04-24 21:44:33'),
(28, 2, 'Menambah Arsip untuk perusahaan: 1', '2025-04-27 20:07:51'),
(29, 2, 'Menghapus arsip untuk perusahaan: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;, Tanggal Datang: 1111-11-11, Jatuh Tempo: 1111-11-11, Tanggal Faktur: 1111-11-11, Total: 1.00 (ID: 35) Produk yang dihapus: Nama Produk: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;, Kadaluarsa: 1111-11-11, Batch: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;, Jumlah: 1, Satuan: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;, Nomor Faktur: 1 | ', '2025-04-27 20:37:26'),
(30, 2, 'Mengedit arsip milik perusahaan: 1.2', '2025-04-27 21:21:29'),
(31, 2, 'Mengedit arsip milik perusahaan: baru 1', '2025-04-27 21:34:11'),
(32, 2, 'Mengubah detail produk (ID Detail: 23) - Jumlah: 1, Satuan: pcs, Kadaluarsa: 1111-11-11, Batch: 1, Nomor Faktur: 1', '2025-04-27 21:34:11'),
(33, 2, 'Mengubah detail produk (ID Detail: 24) - Jumlah: 1, Satuan: pcs, Kadaluarsa: 1111-11-11, Batch: 1, Nomor Faktur: 1', '2025-04-27 21:34:11'),
(34, 2, 'Mengedit arsip milik perusahaan: 2', '2025-04-27 21:35:15'),
(35, 2, 'Mengubah detail produk (ID Detail: 16) - Jumlah: 2, Satuan: pcs, Kadaluarsa: 2222-02-22, Batch: 2, Nomor Faktur: 2', '2025-04-27 21:35:15'),
(36, 2, 'Mengubah nama produk (ID Produk: 15) dari \'produk no 1 edit v2.2.1.2.11\' menjadi \'produk 2\'.', '2025-04-27 21:35:15'),
(37, 2, 'Mengedit arsip milik perusahaan: baru 1.A. Mengubah detail produk (ID Detail: 23) - Jumlah: 15, Satuan: pcs, Kadaluarsa: 5555-05-05, Batch: 15, Nomor Faktur: 15. Mengubah detail produk (ID Detail: 24) - Jumlah: 15, Satuan: pcs, Kadaluarsa: 5555-05-05, Batch: 15, Nomor Faktur: 15. Mengubah nama produk (ID Produk: 8) dari \'produk no 1 edit v2.2.1.2\' menjadi \'produk no 1 edit v2.2.1.2.A\'. Mengubah nama produk (ID Produk: 8) dari \'produk no 1 edit v2.2.1.2\' menjadi \'produk no 1 edit v2.2.1.2.A\'. ', '2025-04-27 21:42:41'),
(38, 2, 'Mengedit arsip milik perusahaan: 2. Mengubah detail produk (ID Detail: 16) - Jumlah: 2, Satuan: pcs, Kadaluarsa: 2222-02-22, Batch: 2, Nomor Faktur: 2. ', '2025-04-27 21:46:39'),
(39, 2, 'Menambah Arsip untuk perusahaan: 2', '2025-04-30 21:04:16'),
(40, 2, 'Menambah Arsip untuk perusahaan: 2', '2025-04-30 21:04:40'),
(41, 2, 'Menambah Arsip untuk perusahaan: 2', '2025-04-30 21:27:19'),
(42, 2, 'Menambah Arsip untuk perusahaan: 2', '2025-04-30 21:28:58'),
(43, 2, 'Menambah Arsip untuk perusahaan: 2', '2025-04-30 21:29:18'),
(44, 2, 'Menambah Arsip untuk perusahaan: 2', '2025-04-30 21:29:33'),
(45, 2, 'Menambah Arsip untuk perusahaan: 2', '2025-04-30 21:29:49'),
(46, 2, 'Menambah Arsip untuk perusahaan: 2', '2025-04-30 21:30:07'),
(47, 2, 'Menghapus arsip untuk perusahaan: baru 1.A, Tanggal Datang: 5555-05-05, Jatuh Tempo: 5555-05-05, Tanggal Faktur: 5555-05-05, Total: 5.00 (ID: 20) Produk yang dihapus: Nama Produk: produk no 1 edit v2.2.1.2.A, Kadaluarsa: 5555-05-05, Batch: 15, Jumlah: 15, Satuan: pcs, Nomor Faktur: 15 | Nama Produk: produk no 1 edit v2.2.1.2.A, Kadaluarsa: 5555-05-05, Batch: 15, Jumlah: 15, Satuan: pcs, Nomor Faktur: 15 | ', '2025-04-30 21:31:06'),
(48, 2, 'Menghapus arsip untuk perusahaan: 2, Tanggal Datang: 2222-02-22, Jatuh Tempo: 2222-02-22, Tanggal Faktur: 2222-02-22, Total: 2.00 (ID: 50) Produk yang dihapus: ', '2025-04-30 21:33:49'),
(49, 2, 'Menambah Arsip untuk perusahaan: 6', '2025-04-30 21:34:34'),
(50, 2, 'Menghapus arsip untuk perusahaan: perusahaan hapus 2, Tanggal Datang: 2025-04-24, Jatuh Tempo: 2026-12-12, Tanggal Faktur: 2029-12-12, Total: 300000.00 (ID: 41) Produk yang dihapus: Nama Produk: produk p1, Kadaluarsa: 2026-12-12, Batch: artwe23, Jumlah: 4, Satuan: kilo king, Nomor Faktur: wer23r | Nama Produk: produk p2, Kadaluarsa: 2026-12-11, Batch: afwer23, Jumlah: 5, Satuan: kilo queen, Nomor Faktur: set3 | ', '2025-05-01 12:22:27'),
(51, 2, 'Menambah Arsip untuk perusahaan: perusahaan 2', '2025-05-02 14:53:11'),
(52, 2, 'Menambah Arsip untuk perusahaan: perusahaan 2', '2025-05-03 20:01:31'),
(53, 2, 'Mengedit arsip milik perusahaan: perusahaan 2. Mengubah detail produk (ID Detail: 9) - Jumlah: 3, Satuan: pcs, Kadaluarsa: 2026-04-25, Batch: 24fdgerg, Nomor Faktur: 13. ', '2025-05-05 17:24:13'),
(54, 1, 'Menghapus arsip untuk perusahaan: 2, Tanggal Datang: 2222-02-22, Jatuh Tempo: 2222-02-22, Tanggal Faktur: 2222-02-22, Total: 22222.00 (ID: 14) Produk yang dihapus: Nama Produk: produk 2, Kadaluarsa: 2222-02-22, Batch: 2, Jumlah: 2, Satuan: pcs, Nomor Faktur: 2 | ', '2025-05-06 19:18:25'),
(55, 1, 'Mengedit arsip milik perusahaan: 2. Mengubah detail produk (ID Detail: 42) - Jumlah: 2, Satuan: pcs, Kadaluarsa: 2222-02-22, Batch: 2, Nomor Faktur: 2. ', '2025-05-06 19:22:02'),
(56, 1, 'Menambah Arsip untuk perusahaan: 12', '2025-05-06 19:27:15'),
(57, 1, 'Mengedit arsip milik perusahaan: 12. Mengubah detail produk (ID Detail: 52) - Jumlah: 12, Satuan: pcs, Kadaluarsa: 2012-12-12, Batch: 12, Nomor Faktur: 12. ', '2025-05-06 19:28:16'),
(58, 2, 'Menambah Arsip untuk perusahaan: nama pbf', '2025-05-06 22:23:25'),
(59, 2, 'Mengedit arsip milik perusahaan: 2. Mengubah detail produk (ID Detail: 42) - Jumlah: 2, Satuan: pcs, Kadaluarsa: 2222-02-22, Batch: 2, Nomor Faktur: 2. ', '2025-05-07 10:39:45'),
(60, 2, 'Menghapus arsip untuk perusahaan: perusahaan 2, Tanggal Datang: 2025-04-23, Jatuh Tempo: 2025-04-25, Tanggal Faktur: 2025-04-22, Total: 100000.00 (ID: 42) Produk yang dihapus: Nama Produk: produk 1, Kadaluarsa: 2025-04-30, Batch: 12a, Jumlah: 1, Satuan: box, Nomor Faktur: 12 | ', '2025-05-07 10:57:34'),
(61, 2, 'Menambah Arsip untuk perusahaan: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;', '2025-05-07 18:45:07'),
(62, 2, 'Menambah Arsip untuk perusahaan: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;', '2025-05-07 18:58:40'),
(63, 2, 'Tambah arsip: perusahaan 2, Faktur: 112-turt64, Tgl: 2025-05-07, 2 produk', '2025-05-07 19:26:26'),
(64, 2, 'Hapus arsip: 2 (Faktur: , Tgl: 2222-02-22), 1 produk: 2', '2025-05-07 19:35:44'),
(65, 2, 'Hapus arsip: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt; (Faktur: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;, Tgl: 2025-05-07), 1 produk: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;', '2025-05-07 19:36:14'),
(66, 2, 'Hapus arsip: perusahaan 2 (Faktur: 112-turt64, Tgl: 2025-05-07), 2 produk: produk paracetamol, produk 1', '2025-05-07 19:36:38'),
(67, 2, 'Hapus arsip: 2 (Faktur: , Tgl: 2222-02-22), 1 produk: 2', '2025-05-07 19:40:43'),
(68, 2, 'Hapus arsip: perusahaan 2 (Faktur: , Tgl: 2025-04-19), 1 produk: produk 1', '2025-05-07 19:41:23'),
(69, 2, 'Hapus arsip: 2 (Faktur: , Tgl datang: 2222-02-22), 1 produk: 2', '2025-05-07 19:42:13'),
(70, 2, 'Mengedit arsip milik perusahaan: 2. Mengubah detail produk (ID Detail: 43) - Jumlah: 2, Satuan: kg, Kadaluarsa: 2222-02-22, Batch: 2.\n ', '2025-05-07 19:58:51'),
(71, 2, 'Mengedit arsip milik perusahaan: 2. Mengubah detail produk (ID Detail: 43) - Jumlah: 2, Satuan: kg, Kadaluarsa: 2222-02-22, Batch: 2.\n ', '2025-05-07 19:59:19'),
(72, 2, 'Mengubah jumlah produk (ID Detail: 43) menjadi: 2. ', '2025-05-07 20:05:28'),
(73, 2, 'Mengubah jumlah produk (ID Detail: 6) menjadi: 1. Mengubah jumlah produk (ID Detail: 7) menjadi: 2. ', '2025-05-07 20:06:19'),
(74, 2, 'Mengubah jumlah produk (ID Detail: 6) dari 1 menjadi: 1. Mengubah jumlah produk (ID Detail: 7) dari 2 menjadi: 2. ', '2025-05-07 20:10:29'),
(75, 2, 'Mengubah nama perusahaan menjadi: per2. ', '2025-05-07 20:14:21'),
(76, 2, 'Hapus arsip: per2 (Faktur: sfwer, Tgl datang: 2222-02-22), 1 produk: 2', '2025-05-08 13:48:28'),
(77, 4, 'Hapus arsip: 2 (Faktur: , Tgl datang: 2222-02-22), 1 produk: 2', '2025-05-08 21:17:03'),
(78, 4, 'Hapus arsip: 2 (Faktur: , Tgl datang: 2222-02-22), 1 produk: 2', '2025-05-08 21:17:08'),
(79, 4, 'Hapus arsip: 2 (Faktur: , Tgl datang: 2222-02-22), 1 produk: 2', '2025-05-08 21:17:14'),
(80, 2, 'Tambah arsip: perusahaan 1, Faktur: 231fwfs, Tgl: 2025-05-09, 1 produk', '2025-05-09 08:16:37'),
(81, 2, 'Hapus arsip: perusahaan 1 (Faktur: 231fwfs, Tgl datang: 2025-05-09), 1 produk: produk 1', '2025-05-09 14:32:41'),
(82, 2, 'Hapus arsip: nama pbf (Faktur: , Tgl datang: 2025-05-06), 1 produk: paracetamol', '2025-05-09 14:32:49'),
(83, 2, 'Hapus arsip: perusahaan 2 (Faktur: , Tgl datang: 2025-05-02), 1 produk: produk 1', '2025-05-09 14:33:00'),
(84, 2, 'Hapus arsip: perusahaan 2 (Faktur: , Tgl datang: 2025-04-19), 1 produk: produk 1 v1', '2025-05-09 14:33:06'),
(85, 2, 'Hapus arsip: perusahaan 2 (Faktur: , Tgl datang: 2025-04-19), 1 produk: produk 1', '2025-05-09 14:33:13'),
(86, 2, 'Hapus arsip: perusahaan 5 (Faktur: , Tgl datang: 2025-04-18), 2 produk: produk 1 vv50, produk 1 v51', '2025-05-09 14:33:22'),
(87, 2, 'Hapus arsip: perusahaan 2.22 (Faktur: , Tgl datang: 2025-04-18), 1 produk: produk 1', '2025-05-09 14:33:32'),
(88, 2, 'Hapus arsip: perusahaan 2 (Faktur: , Tgl datang: 2024-05-03), 1 produk: produk 1', '2025-05-09 14:33:38'),
(89, 2, 'Hapus arsip: 12 (Faktur: , Tgl datang: 2012-12-12), 1 produk: 12', '2025-05-09 14:33:44'),
(90, 2, 'Hapus arsip: perusahaan 2 (Faktur: , Tgl datang: 1212-12-12), 1 produk: produk no 1 edit v2.2.1.2', '2025-05-09 14:33:50'),
(91, 2, 'Hapus arsip: 1 (Faktur: , Tgl datang: 1111-11-11), 1 produk: produk no 1 edit v2.2.1.2', '2025-05-09 14:33:55'),
(92, 2, 'Hapus arsip: nama pbf (Faktur: , Tgl datang: 1111-11-11), 1 produk: produk no 1 edit v2.2.1.2', '2025-05-09 14:34:01'),
(93, 2, 'Hapus arsip: 1 (Faktur: , Tgl datang: 1111-11-11), 1 produk: 1', '2025-05-09 14:34:06'),
(94, 2, 'Hapus arsip: 6 (Faktur: , Tgl datang: 1111-11-11), 1 produk: 1', '2025-05-09 14:34:10'),
(95, 2, 'Tambah arsip: perusahaan 1, Faktur: 231fwfs, Tgl: 2025-05-09, 1 produk', '2025-05-09 14:36:46'),
(96, 2, 'Tambah arsip: perusahaan 2, Faktur: 123qwed, Tgl: 2025-05-08, 2 produk', '2025-05-09 14:38:36'),
(97, 2, 'Tambah arsip: perusahaan 3, Faktur: 123qwe, Tgl: 2025-04-09, 1 produk', '2025-05-09 14:40:37'),
(98, 2, 'Tambah arsip: PT CARMELLA GUSTAVINDO, Faktur: E19871749H8, Tgl: 2025-02-07, 10 produk', '2025-05-09 15:01:34'),
(99, 2, 'Tambah arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 00012721, Tgl: 2025-04-24, 2 produk', '2025-05-09 19:43:17'),
(100, 2, 'Tambah arsip: PT ANUGRAH SUKSES MANDIRI DISTRIBUSI, Faktur: 00012712, Tgl: 2025-04-05, 2 produk', '2025-05-09 19:47:24'),
(101, 2, 'Tambah arsip: PT PRABA BARAKA JAYA, Faktur: 1236459-HKJ2265, Tgl: 2025-05-10, 11 produk', '2025-05-09 20:01:23'),
(102, 2, 'Hapus arsip: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt; (Faktur: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;, Tgl datang: 2025-05-07), 1 produk: &lt;script&gt; alert(&quot;XSS&quot;);&lt;/script&gt;', '2025-05-09 20:01:56'),
(103, 2, 'Tambah arsip: PT PRABA BARAKA JAYA, Faktur: E19871749H8, Tgl: 2025-05-05, 1 produk', '2025-05-09 20:44:41'),
(104, 2, 'Tambah arsip: PT CARMELLA GUSTAVINDO, Faktur: 231fwfs, Tgl: 2025-05-01, 1 produk', '2025-05-09 20:46:24'),
(105, 2, 'Tambah arsip: PT CARMELLA GUSTAVINDO, Faktur: 123qwed, Tgl: 2025-04-30, 1 produk', '2025-05-09 20:49:04'),
(106, 2, 'Tambah arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 123qwed, Tgl: 2025-05-24, 2 produk', '2025-05-09 20:51:31'),
(107, 2, 'Mengubah tanggal datang menjadi: 2025-02-10. ', '2025-05-10 07:31:12'),
(108, 2, 'Tambah arsip: 1, Faktur: 1, Tgl: 1111-11-11, 1 produk', '2025-05-10 07:35:40'),
(109, 2, 'Mengubah nama produk (ID Produk: 31) dari \'LELAP 2X4&#039;5\' menjadi \'LELAP 2X4\'5\'. ', '2025-05-10 08:08:02'),
(110, 2, 'Mengubah nama produk (ID Produk: 39) dari \'LELAP 2X4\'5\' menjadi \'<script>alert(\"XSS\");</script>\'. ', '2025-05-10 08:10:55'),
(111, 2, 'Mengubah nama produk (ID Produk: 25) dari \'produk 2.2\' menjadi \'<script>alert(\"XSSs\");</script>\'. ', '2025-05-10 08:18:23'),
(112, 2, 'Mengubah nama produk (ID Produk: 36) dari \'SEAGUL SG-519/P NAPH 25GR\' menjadi \'&lt;script&gt;alert(&quot;XSSsa&quot;);&lt;/script&gt;\'. ', '2025-05-10 08:25:17'),
(113, 2, 'Mengubah nama produk (ID Produk: 40) dari \'&lt;script&gt;alert(&quot;XSSsa&quot;);&lt;/script&gt;\' menjadi \'&amp;lt;script&amp;gt;alert(&amp;quot;XSSsa&amp;quot;);&amp;lt;/script&amp;gt;\'. ', '2025-05-10 08:43:23'),
(114, 2, 'Mengubah nama produk (ID Produk: 39) dari \'<script>alert(\"XSS\");</script>\' menjadi \'&lt;script&gt;alert(\'. ', '2025-05-10 08:48:13'),
(115, 2, 'Mengubah nama produk (ID Produk: 40) dari \'&amp;lt;script&amp;gt;alert(&amp;quot;XSSsa&amp;quot;);&amp;lt;/script&amp;gt;\' menjadi \'&lt;script&gt;alert(&quot;XSSaa&quot;);&lt;/script&gt;\'. ', '2025-05-10 08:48:39'),
(116, 2, 'Mengubah nama produk (ID Produk: 25) dari \'<script>alert(\"XSSs\");</script>\' menjadi \'\'. ', '2025-05-10 08:50:31'),
(117, 2, 'Mengubah nama produk (ID Produk: 25) dari \'\' menjadi \'&lt;script&gt;alert(&quot;XSS&quot;);&lt;/script&gt;\'. ', '2025-05-10 08:50:49'),
(118, 2, 'Mengubah nama produk (ID Produk: 39) dari \'&lt;script&gt;alert(\' menjadi \'&lt;script&gt;alert(&quot;XSS1&quot;);&lt;/script&gt;\'. ', '2025-05-10 08:57:27'),
(119, 2, 'Mengubah nama produk (ID Produk: 39) dari \'&lt;script&gt;alert(&quot;XSS1&quot;);&lt;/script&gt;\' menjadi \'alert(\"XSS1\");\'. ', '2025-05-10 09:14:59'),
(120, 2, 'Mengubah nama produk (ID Produk: 39) dari \'alert(\"XSS1\");\' menjadi \'alert(\"XSS\");\'. ', '2025-05-10 09:21:12'),
(121, 2, 'Mengubah nama produk (ID Produk: 25) dari \'&lt;script&gt;alert(&quot;XSS&quot;);&lt;/script&gt;\' menjadi \'alert(\"XSSaa\");\'. ', '2025-05-10 09:21:33'),
(122, 2, 'Mengubah nama produk (ID Produk: 25) dari \'alert(\"XSSaa\");\' menjadi \'alert(\"XSS\");\'. ', '2025-05-10 09:21:55'),
(123, 2, 'Mengubah nama produk (ID Produk: 40) dari \'&lt;script&gt;alert(&quot;XSSaa&quot;);&lt;/script&gt;\' menjadi \'alert(\"XSS\");\'. ', '2025-05-10 09:22:07'),
(124, 2, 'Mengubah nama produk (ID Produk: 31) dari \'LELAP 2X4&#039;5\' menjadi \'LELAP 2X4\'5\'. ', '2025-05-10 09:22:20'),
(125, 2, 'Mengubah nama produk (ID Produk: 36) dari \'SEAGUL SG-519/P NAPH 25GR\' menjadi \'SEAGUL SG-519/P NAPH 25GRaaaaaaaaaaaaaaaaaaaaaaaaaa\'. Mengubah nama produk (ID Produk: 39) dari \'alert(\"XSS\");\' menjadi \'alert(\'. ', '2025-05-10 09:26:28'),
(126, 2, 'Mengubah nama produk (ID Produk: 42) dari \'SEAGUL SG-519/P NAPH 25GRaaaaaaaaaaaaaaaaaaaaaaaaaa\' menjadi \'SEAGUL SG-519/P NAPH 25GR\'. ', '2025-05-10 09:26:45'),
(127, 2, 'Mengubah nama produk (ID Produk: 39) dari \'alert(\' menjadi \'123456789009876543211234567890098765432112345678900987654321\'. ', '2025-05-10 09:28:36'),
(128, 2, 'Mengubah nama produk (ID Produk: 39) dari \'123456789009876543211234567890098765432112345678900987654321\' menjadi \'AAAAAAAAA10AAAAAAAAA20AAAAAAAAAA30AAAAAAAAA40AAAAAAAAAA50\'. ', '2025-05-10 09:29:14'),
(129, 2, 'Mengubah nama perusahaan menjadi: AAAAAAAAA10AAAAAAAAA20AAAAAAAAAA. ', '2025-05-10 09:31:45'),
(130, 2, 'Mengubah nama perusahaan menjadi: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI. ', '2025-05-10 09:33:27'),
(131, 2, 'Tambah arsip: &lt;script&gt;alert(&quot;XSS&quot;);&lt;/script&gt;, Faktur: &lt;script&gt;alert(&quot;XSS&quot;);&lt;/script&gt;, Tgl: 1111-11-11, 1 produk', '2025-05-10 09:36:53'),
(132, 2, 'Tambah arsip: alert(&quot;XSS&quot;);, Faktur: 1, Tgl: 1111-11-11, 1 produk', '2025-05-10 09:41:39'),
(134, 2, 'Tambah arsip: alert(&quot;XSS&quot;);, Faktur: alert(&quot;XSS&quot;);, Tgl: 1111-11-11, 1 produk', '2025-05-10 10:00:52'),
(135, 2, 'Mengubah nama produk (ID Produk: 25) dari \'alert(\"XSS\");\' menjadi \'alert(\'. ', '2025-05-10 10:06:19'),
(136, 2, 'Mengubah nomor faktur menjadi: alert(\"XSS\");. Mengubah satuan produk (ID Detail: 96) dari &lt;script&gt;alert(&quot;XSS&quot;);&lt;/script&gt; menjadi: <script>alert(\"XSS\");</script>. Mengubah batch produk (ID Detail: 96) dari &lt;script&gt;alert(&quot;XSS&quot;);&lt;/script&gt; menjadi: <script>alert(\"XSS\");</script>. Mengubah nama produk (ID Produk: 43) dari \'alert(&quot;XSS&quot;);\' menjadi \'alert(\"XSS\");\'. ', '2025-05-10 10:06:47'),
(137, 2, 'Tambah arsip: alert(&quot;XSS&quot;);, Faktur: alert(&quot;XSS&quot;);, Tgl: 1111-11-11, 2 produk', '2025-05-10 10:10:45'),
(138, 2, 'Hapus arsip: alert(&quot;XSS&quot;); (Faktur: alert(&quot;XSS&quot;);, Tgl datang: 1111-11-11), 2 produk: alert(&quot;XSS&quot;);, alert(&quot;XSS&quot;);', '2025-05-10 10:14:30'),
(139, 2, 'Tambah arsip: alert(&quot;XSS&quot;);, Faktur: alert(&quot;XSS&quot;);, Tgl: 1111-11-11, 2 produk', '2025-05-10 10:15:23'),
(140, 2, 'Hapus arsip: &lt;script&gt;alert(&quot;XSS&quot;);&lt;/script&gt; (Faktur: &lt;script&gt;alert(&quot;XSS&quot;);&lt;/script&gt;, Tgl datang: 1111-11-11), 1 produk: &lt;script&gt;alert(&quot;XSS&quot;);&lt;/script&gt;', '2025-05-10 10:15:51'),
(141, 2, 'Hapus arsip: alert(&quot;XSS&quot;); (Faktur: alert(\"XSS\");, Tgl datang: 1111-11-11), 1 produk: alert(\"XSS\");', '2025-05-10 10:17:04'),
(142, 2, 'Mengubah total menjadi: 1000000.00. Mengubah nama produk (ID Produk: 40) dari \'alert(\"XSS\");\' menjadi \'alert(\'. ', '2025-05-10 18:17:35'),
(143, 2, 'Mengubah tanggal datang menjadi: 2025-03-05. ', '2025-05-10 18:17:54'),
(144, 2, 'Mengubah total menjadi: 4000000.00. ', '2025-05-10 18:18:52'),
(145, 2, 'Mengubah total menjadi: 2000000.00. ', '2025-05-10 18:19:23'),
(146, 2, 'Mengubah tanggal datang menjadi: 2025-06-09. Mengubah total menjadi: 3000000.00. ', '2025-05-10 19:19:33'),
(147, 2, 'Mengubah total menjadi: 10400934.00. ', '2025-05-10 19:23:00'),
(148, 2, 'Hapus arsip: alert(&quot;XSS&quot;); (Faktur: 1, Tgl datang: 1111-11-11), 1 produk: 1', '2025-05-10 19:40:07'),
(149, 2, 'Tambah arsip: 1, Faktur: 1, Tgl: 2026-11-11, 1 produk', '2025-05-11 21:38:47'),
(150, 2, 'Hapus arsip: 1 (Faktur: 1, Tgl datang: 2026-11-11), 1 produk: 1', '2025-05-11 22:27:45'),
(151, 2, 'Tambah arsip: PT PRABA BARAKA JAYA, Faktur: E19871749H8, Tgl: 2025-01-11, 1 produk', '2025-05-13 11:18:14'),
(152, 2, 'Tambah arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: weifh38, Tgl: 2025-07-01, 1 produk', '2025-05-13 11:19:32'),
(153, 2, 'Tambah arsip: PT CARMELLA GUSTAVINDO, Faktur: dehwury923r, Tgl: 2025-08-12, 1 produk', '2025-05-13 11:21:13'),
(154, 2, 'Mengubah nama perusahaan menjadi: &lt;script&gt;alert(&quot;XSS&quot;);&lt;/script&gt;. Mengubah nomor faktur menjadi: <script>alert(\"XSS\");</script>. Mengubah satuan produk (ID Detail: 99) dari alert(&quot;XSS&quot;); menjadi: <script>alert(\"XSS\");</script>. Mengubah batch produk (ID Detail: 99) dari alert(&quot;XSS&quot;); menjadi: <script>alert(\"XSS\");</script>. Mengubah satuan produk (ID Detail: 100) dari alert(&quot;XSS&quot;); menjadi: <script>alert(\"XSS\");</script>. Mengubah batch produk (ID Detail: 100) dari alert(&quot;XSS&quot;); menjadi: <script>alert(\"XSS\");</script>. Mengubah nama produk (ID Produk: 44) dari \'alert(&quot;XSS&quot;);\' menjadi \'alert(\"XSS\");\'. Mengubah nama produk (ID Produk: 44) dari \'alert(&quot;XSS&quot;);\' menjadi \'alert(\"XSS\");\'. ', '2025-05-13 20:26:00'),
(155, 2, 'Mengubah nama perusahaan menjadi: alert(\"XSS\");. Mengubah nomor faktur menjadi: alert(. Mengubah satuan produk (ID Detail: 99) dari <script>alert(\"XSS\");</script> menjadi: <script>alert(. Mengubah batch produk (ID Detail: 99) dari <script>alert(\"XSS\");</script> menjadi: <script>alert(. Mengubah satuan produk (ID Detail: 100) dari <script>alert(\"XSS\");</script> menjadi: <script>alert(. Mengubah batch produk (ID Detail: 100) dari <script>alert(\"XSS\");</script> menjadi: <script>alert(. Mengubah nama produk (ID Produk: 45) dari \'alert(\"XSS\");\' menjadi \'alert(\'. Mengubah nama produk (ID Produk: 44) dari \'alert(\"XSS\");\' menjadi \'alert(\'. ', '2025-05-13 20:35:29'),
(156, 2, 'Mengubah nama perusahaan menjadi: alert(\"XSS1\");. Mengubah nomor faktur menjadi: <script>alert(\"XSS1\");</script>. Mengubah satuan produk (ID Detail: 93) dari PCS menjadi: <script>alert(\"XSS1\");</script>. Mengubah batch produk (ID Detail: 93) dari 1 menjadi: <script>alert(\"XSS1\");</script>. Mengubah nama produk (ID Produk: 38) dari \'111111111\' menjadi \'alert(\"XSS1\");\'. ', '2025-05-13 20:43:46'),
(157, 2, 'Mengubah nama perusahaan menjadi: alert(\"XSS2\");. Mengubah nomor faktur menjadi: alert(\"XSS2\");. Mengubah satuan produk (ID Detail: 93) dari <script>alert(\"XSS1\");</script> menjadi: . Mengubah batch produk (ID Detail: 93) dari <script>alert(\"XSS1\");</script> menjadi: alert(\"XSS2\");. Mengubah nama produk (ID Produk: 38) dari \'alert(\"XSS1\");\' menjadi \'alert(\"XSS2\");\'. ', '2025-05-13 20:46:50'),
(158, 2, 'Mengubah nama perusahaan menjadi: alert(. Mengubah nomor faktur menjadi: alert(. Mengubah satuan produk (ID Detail: 93) dari  menjadi: alert(\"XSS2\");. Mengubah batch produk (ID Detail: 93) dari alert(\"XSS2\"); menjadi: alert(. Mengubah nama produk (ID Produk: 38) dari \'alert(\"XSS2\");\' menjadi \'alert(\'. ', '2025-05-13 20:47:29'),
(159, 2, 'Mengubah satuan produk (ID Detail: 93) dari alert(\"XSS2\"); menjadi: alert(. ', '2025-05-13 20:48:05'),
(160, 2, 'Tambah arsip: alert(&quot;XSS3&quot;);, Faktur: alert(&quot;XSS3&quot;);, Tgl: 1111-11-11, 1 produk', '2025-05-13 20:56:32'),
(161, 2, 'Tambah arsip: alert(&quot;XSS4&quot;);, Faktur: alert(&quot;XSS4&quot;);, Tgl: 1111-11-11, 1 produk', '2025-05-13 21:00:34'),
(162, 2, 'Mengubah nama produk (ID Produk: 47) dari \'alert(&quot;XSS4&quot;);\' menjadi \'alert(\"XSS4\");\'. ', '2025-05-13 21:00:54'),
(163, 2, 'Mengubah nama perusahaan menjadi: alert(&quot;XSS4&quot;);. Mengubah nomor faktur menjadi: alert(&quot;XSS4&quot;);. Mengubah satuan produk (ID Detail: 93) dari alert( menjadi: alert(&quot;XSS4&quot;);. Mengubah nama produk (ID Produk: 38) dari \'alert(\' menjadi \'alert(\"XSS4\");\'. ', '2025-05-13 21:01:21'),
(164, 2, 'Mengubah batch produk (ID Detail: 93) dari alert( menjadi: alert(&quot;XSS4&quot;);. ', '2025-05-13 21:01:50'),
(165, 2, 'Tambah arsip: alert(&quot;XSS&quot;);, Faktur: alert(&quot;XSS&quot;);, Tgl: 1111-11-11, 1 produk', '2025-05-13 21:16:11'),
(166, 2, 'Hapus arsip: PT PRABA BARAKA JAYA (Faktur: E19871749H8, Tgl datang: 2025-03-05), 1 produk: alert(', '2025-05-14 11:36:25'),
(167, 2, 'Login ke sistem', '2025-05-14 10:09:14'),
(168, 2, 'Login ke sistem', '2025-05-14 10:10:21'),
(169, 2, 'Login ke sistem', '2025-05-14 10:16:42'),
(170, 12, 'Login ke sistem', '2025-05-14 10:17:04'),
(171, 2, 'Login ke sistem', '2025-05-14 17:20:01'),
(172, 2, 'Logout dari sistem', '2025-05-14 17:23:38'),
(173, 2, 'Login ke sistem', '2025-05-14 17:23:43'),
(174, 2, 'Logout dari sistem', '2025-05-14 17:26:25'),
(175, 15, 'Pendaftaran akun baru', '2025-05-14 10:34:04'),
(176, 15, 'Login ke sistem', '2025-05-14 17:34:13'),
(177, 15, 'Mengubah profil', '2025-05-14 17:53:10'),
(178, 2, 'Login ke sistem', '2025-05-14 19:47:36'),
(179, 2, 'Mengubah profil', '2025-05-14 20:10:36'),
(180, 2, 'Logout dari sistem', '2025-05-14 20:23:38'),
(181, 2, 'Login ke sistem', '2025-05-14 20:23:42'),
(182, 2, 'Logout dari sistem', '2025-05-14 20:40:15'),
(183, 2, 'Login ke sistem', '2025-05-14 20:40:19'),
(184, 2, 'Login ke sistem', '2025-05-14 21:41:25'),
(185, 2, 'Logout dari sistem', '2025-05-14 21:46:17'),
(186, 2, 'Login ke sistem', '2025-05-14 21:46:21'),
(187, 2, 'Login ke sistem', '2025-05-14 22:26:31'),
(188, 2, 'Login ke sistem', '2025-05-14 22:46:43'),
(189, 2, 'Login ke sistem', '2025-05-15 06:30:52'),
(190, 2, 'Login ke sistem', '2025-05-15 07:24:10'),
(191, 2, 'Mengubah profil', '2025-05-15 07:35:08'),
(192, 2, 'Mengubah profil', '2025-05-15 07:55:47'),
(193, 2, 'Mengubah profil', '2025-05-15 08:03:35'),
(194, 2, 'Mengubah profil', '2025-05-15 08:21:06'),
(195, 2, 'Login ke sistem', '2025-05-15 14:04:30'),
(196, 2, 'Mengubah profil', '2025-05-15 14:08:39'),
(197, 2, 'Mengubah profil', '2025-05-15 14:08:54'),
(198, 2, 'Mengubah profil', '2025-05-15 14:24:27'),
(199, 2, 'Tambah arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: E19871749H8, Tgl: 2025-05-15, 1 produk', '2025-05-15 14:58:44'),
(200, 2, 'Mengubah nama perusahaan menjadi: PT CARMELLA GUSTAVIND. ', '2025-05-15 14:59:28'),
(201, 2, 'Mengubah nama perusahaan menjadi: PT CARMELLA GUSTAVIND0. Mengubah nomor faktur menjadi: dehwury923. ', '2025-05-15 14:59:56'),
(202, 2, 'Mengubah total menjadi: 700000.00. ', '2025-05-15 15:00:27'),
(203, 2, 'Mengubah nama produk (ID Produk: 26) dari \'GRAFACHLOR TAB 100\' menjadi \'GRAFACHLOR TAB 10\'. ', '2025-05-15 15:01:20'),
(204, 2, 'Mengubah nama produk dari \'GRAFACHLOR TAB 10\' menjadi \'GRAFACHLOR TAB 100\'. ', '2025-05-15 15:03:48'),
(205, 2, 'Mengubah nomor faktur menjadi: dehwury92. ', '2025-05-15 15:05:45'),
(206, 2, 'Hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI (Faktur: weifh38, Tgl datang: 2025-07-01), 1 produk: SEAGUL SG-519/P NAPH 25GR', '2025-05-15 15:09:53'),
(207, 2, 'Login ke sistem', '2025-05-15 18:17:09'),
(208, 2, 'Tambah arsip: PT PRABA BARAKA JAYA, Faktur: 231fwfs, Tgl: 2025-05-15, 5 produk', '2025-05-15 18:19:49'),
(209, 2, 'Hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI (Faktur: 123qwed, Tgl datang: 2025-05-24), 2 produk: SEAGUL SG-519/P NAPH 25GR, AAAAAAAAA10AAAAAAAAA20AAAAAAAAAA30AAAAAAAAA40AAAAAAAAAA50', '2025-05-15 18:20:48'),
(210, 2, 'Hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI (Faktur: 00012721, Tgl datang: 2025-04-24), 2 produk', '2025-05-15 18:23:20'),
(211, 2, 'Tambah arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 123qwed, Tgl datang: 2025-05-15, 1 produk', '2025-05-15 18:25:47'),
(212, 2, 'Hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI (Faktur: E19871749H8, Tgl datang: 2025-05-15), 1 produk', '2025-05-15 18:26:13'),
(213, 2, 'Hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI (Faktur: 123qwed, Tgl datang: 2025-05-15), 1 produk', '2025-05-15 18:27:18'),
(214, 2, 'Edit arsip: PT CARMELLA GUSTAVIND, Faktur: dehwury92, Tgl datang: 2025-08-12, 1 produk', '2025-05-15 18:44:57'),
(215, 2, 'Edit arsip: PT CARMELLA GUSTAVIND, Faktur: dehwury91, Tgl datang: 2025-08-12, 1 produk', '2025-05-15 18:45:47'),
(216, 2, 'Mengubah nama perusahaan menjadi: PT CARMELLA GUSTAVINDq. ', '2025-05-15 18:50:37'),
(217, 2, 'Edit arsip: PT CARMELLA GUSTAVIND, Faktur: dehwury91, Tgl datang: 2025-08-12, 1 produk', '2025-05-15 19:00:46'),
(218, 2, 'Edit arsip: PT CARMELLA GUSTAVIND menjadi PT CARMELLA GUSTAVIND0, Faktur: dehwury91, Tgl datang: 2025-08-12, 0 produk.', '2025-05-15 19:25:06'),
(219, 2, 'Edit arsip: PT CARMELLA GUSTAVIND0 menjadi PT CARMELLA GUSTAVINDO, Faktur: dehwury91 menjadi dehwury92, Tgl datang: 2025-08-12, 1 produk.', '2025-05-15 19:26:02'),
(220, 2, 'Tambah arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 1, Tgl datang: 2025-05-15, 1 produk', '2025-05-15 19:33:04'),
(221, 2, 'Edit arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI menjadi CV ANUGRAH SIKSES MANDIRI DISTRI, Faktur: 1 menjadi 123qwed, Tgl datang: 2025-05-15 menjadi 2025-05-06, 2 produk diubah.', '2025-05-15 19:33:51'),
(222, 2, 'Hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRI Faktur: 123qwed, Tgl datang: 2025-05-06, 1 produk', '2025-05-15 19:34:33'),
(223, 2, 'Logout dari sistem', '2025-05-15 20:07:49'),
(224, 2, 'Login ke sistem', '2025-05-15 20:07:54'),
(225, 2, 'Mengubah profil', '2025-05-15 20:08:06'),
(226, 2, 'Tambah arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 1, Tgl datang: 1111-11-11, 1 produk', '2025-05-15 20:08:26'),
(227, 2, 'Mengubah profil', '2025-05-15 20:08:32'),
(228, 2, 'Edit arsip: PT CARMELLA GUSTAVINDO menjadi PT CARMELLA GUSTAVIN, Faktur: dehwury92, Tgl datang: 2025-08-12; 0 produk diubah.', '2025-05-15 20:08:48'),
(229, 2, 'Hapus arsip: PT CARMELLA GUSTAVIN, Faktur: dehwury92, Tgl datang: 2025-08-12, 1 produk', '2025-05-15 20:08:55'),
(230, 2, 'Edit arsip: perusahaan 1, Faktur: 231fwfs, Tgl datang: 2025-06-09; 1 produk diubah.', '2025-05-15 20:11:11'),
(231, 2, 'Edit arsip: perusahaan 1, Faktur: 231fwfs, Tgl datang: 2025-06-09; 1 produk diubah.', '2025-05-15 20:11:37'),
(232, 2, 'Logout dari sistem', '2025-05-15 20:27:26'),
(233, 2, 'Login ke sistem', '2025-05-15 20:27:52'),
(234, 2, 'Logout dari sistem', '2025-05-15 20:28:53'),
(235, 16, 'Pendaftaran akun baru', '2025-05-15 20:31:25'),
(236, 16, 'Login ke sistem', '2025-05-15 20:31:33'),
(237, 2, 'Login ke sistem', '2025-05-16 06:30:14'),
(238, 2, 'Hapus arsip: perusahaan 1, Faktur: 231fwfs, Tgl datang: 2025-06-09, 1 produk', '2025-05-16 06:53:58'),
(239, 2, 'Hapus arsip: alert(&quot;XSS3&quot;);, Faktur: alert(&quot;XSS3&quot;);, Tgl datang: 1111-11-11, 1 produk', '2025-05-16 06:54:20'),
(240, 2, 'Undo hapus arsip: perusahaan 1, Faktur: 231fwfs', '2025-05-16 06:55:47'),
(241, 2, 'Hapus arsip: alert(&quot;XSS3&quot;);, Faktur: alert(&quot;XSS3&quot;);, Tgl datang: 1111-11-11, 0 produk', '2025-05-16 06:56:20'),
(242, 2, 'Hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 1, Tgl datang: 1111-11-11, 1 produk', '2025-05-16 06:56:34'),
(243, 2, 'Undo hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 1', '2025-05-16 06:56:39'),
(244, 2, 'Undo hapus arsip: alert(&quot;XSS3&quot;);, Faktur: alert(&quot;XSS3&quot;);', '2025-05-16 06:56:47'),
(245, 2, 'Hapus arsip: PT PRABA BARAKA JAYA, Faktur: E19871749H8, Tgl datang: 2025-01-11, 1 produk', '2025-05-16 06:58:32'),
(246, 2, 'Undo hapus arsip: PT PRABA BARAKA JAYA, Faktur: E19871749H8', '2025-05-16 06:58:37'),
(247, 2, 'Hapus arsip: alert(&quot;XSS&quot;);, Faktur: alert(&quot;XSS&quot;);, Tgl datang: 1111-11-11, 1 produk', '2025-05-16 07:02:43'),
(248, 2, 'Hapus arsip: alert(&quot;XSS4&quot;);, Faktur: alert(&quot;XSS4&quot;);, Tgl datang: 1111-11-11, 1 produk', '2025-05-16 07:10:22'),
(249, 2, 'Undo hapus arsip: alert(&quot;XSS4&quot;);, Faktur: alert(&quot;XSS4&quot;);', '2025-05-16 07:10:30'),
(250, 2, 'Hapus arsip: alert(&quot;XSS4&quot;);, Faktur: alert(&quot;XSS4&quot;);, Tgl datang: 1111-11-11, 1 produk', '2025-05-16 07:10:50'),
(251, 2, 'Undo hapus arsip: alert(&quot;XSS4&quot;);, Faktur: alert(&quot;XSS4&quot;);', '2025-05-16 07:10:54'),
(252, 2, 'Hapus arsip: PT PRABA BARAKA JAYA, Faktur: E19871749H8, Tgl datang: 2025-01-11, 0 produk', '2025-05-16 07:11:23'),
(253, 2, 'Hapus arsip: perusahaan 1, Faktur: 231fwfs, Tgl datang: 2025-06-09, 0 produk', '2025-05-16 07:11:37'),
(254, 2, 'Hapus arsip: alert(&quot;XSS3&quot;);, Faktur: alert(&quot;XSS3&quot;);, Tgl datang: 1111-11-11, 0 produk', '2025-05-16 07:11:44'),
(255, 2, 'Hapus arsip: PT PRABA BARAKA JAYA, Faktur: 231fwfs, Tgl datang: 2025-05-15, 5 produk', '2025-05-16 07:12:04'),
(256, 2, 'Undo hapus arsip: PT PRABA BARAKA JAYA, Faktur: 231fwfs', '2025-05-16 07:12:11'),
(257, 2, 'Undo hapus arsip: alert(&quot;XSS3&quot;);, Faktur: alert(&quot;XSS3&quot;);', '2025-05-16 07:13:11'),
(258, 2, 'Hapus arsip: PT PRABA BARAKA JAYA, Faktur: 1236459-HKJ2265, Tgl datang: 2025-02-10, 11 produk', '2025-05-16 07:14:04'),
(259, 2, 'Undo hapus arsip: PT PRABA BARAKA JAYA, Faktur: 1236459-HKJ2265', '2025-05-16 07:14:11'),
(260, 2, 'Tambah arsip: asdfadsfadf, Faktur: adsfadf, Tgl datang: 2025-05-16, 1 produk', '2025-05-16 07:17:04'),
(261, 2, 'Hapus arsip: asdfadsfadf, Faktur: adsfadf, Tgl datang: 2025-05-16, 1 produk', '2025-05-16 07:33:53'),
(262, 2, 'Undo hapus arsip: asdfadsfadf, Faktur: adsfadf', '2025-05-16 07:34:13'),
(263, 2, 'Logout dari sistem', '2025-05-16 07:57:25'),
(264, 17, 'Pendaftaran akun baru', '2025-05-16 08:04:44'),
(265, 17, 'Login ke sistem', '2025-05-16 08:04:54'),
(266, 17, 'Hapus arsip: perusahaan 2, Faktur: 123qwed, Tgl datang: 2025-05-08, 1 produk', '2025-05-16 08:05:59'),
(267, 17, 'Undo hapus arsip: perusahaan 2, Faktur: 123qwed', '2025-05-16 08:06:05'),
(268, 17, 'Hapus arsip: asdfadsfadf, Faktur: adsfadf, Tgl datang: 2025-05-16, 1 produk', '2025-05-16 08:17:06'),
(269, 2, 'Login ke sistem', '2025-05-16 09:34:17'),
(270, 2, 'Hapus arsip: perusahaan 2, Faktur: 123qwed, Tgl datang: 2025-05-08, 1 produk', '2025-05-16 09:35:03'),
(271, 2, 'Edit arsip: PT PRABA BARAKA JAYA, Faktur: 231fwfs, Tgl datang: 2025-05-15; 1 produk diubah.', '2025-05-16 09:38:32'),
(272, 2, 'Undo hapus arsip: perusahaan 2, Faktur: 123qwed', '2025-05-16 09:38:48'),
(273, 2, 'Hapus arsip: PT PRABA BARAKA JAYA, Faktur: 231fwfs, Tgl datang: 2025-05-15, 5 produk', '2025-05-16 09:47:18'),
(274, 2, 'Undo hapus arsip: PT PRABA BARAKA JAYA, Faktur: 231fwfs', '2025-05-16 09:49:19'),
(275, 2, 'Hapus arsip: alert(&quot;XSS3&quot;);, Faktur: alert(&quot;XSS3&quot;);, Tgl datang: 1111-11-11, 0 produk', '2025-05-16 09:51:52'),
(276, 2, 'Hapus arsip: alert(&quot;XSS&quot;);, Faktur: alert(&quot;XSS&quot;);, Tgl datang: 1111-11-11, 0 produk', '2025-05-16 10:15:15'),
(277, 2, 'Hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 1, Tgl datang: 1111-11-11, 0 produk', '2025-05-16 10:15:25'),
(278, 2, 'Tambah arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 231fwfs, Tgl datang: 2025-05-16, 1 produk', '2025-05-16 10:17:18'),
(279, 2, 'Login ke sistem', '2025-05-16 13:59:07'),
(280, 2, 'Tambah arsip: PT NO 1, Faktur: 1, Tgl datang: 2025-05-31, 1 produk', '2025-05-16 14:21:20'),
(281, 2, 'Hapus arsip: PT NO 1, Faktur: 1, Tgl datang: 2025-05-31, 1 produk', '2025-05-16 15:25:27'),
(282, 2, 'Login ke sistem', '2025-05-16 18:21:44'),
(283, 2, 'Logout dari sistem', '2025-05-16 18:24:02'),
(284, 18, 'Pendaftaran akun baru', '2025-05-16 18:24:39'),
(285, 18, 'Login ke sistem', '2025-05-16 18:25:06'),
(286, 18, 'Logout dari sistem', '2025-05-16 18:28:52'),
(287, 18, 'Login ke sistem', '2025-05-16 18:29:05'),
(288, 18, 'Hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 231fwfs, Tgl datang: 2025-05-16, 1 produk', '2025-05-16 18:46:31'),
(289, 18, 'Undo hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 231fwfs', '2025-05-16 19:02:10'),
(290, 18, 'Hapus arsip: PT PRABA BARAKA JAYA, Faktur: 231fwfs, Tgl datang: 2025-05-15, 5 produk', '2025-05-16 19:03:58'),
(291, 18, 'Undo hapus arsip: PT PRABA BARAKA JAYA, Faktur: 231fwfs', '2025-05-16 19:06:49'),
(292, 18, 'Hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 231fwfs, Tgl datang: 2025-05-16, 1 produk', '2025-05-16 19:09:40'),
(293, 18, 'Hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 231fwfs, Tgl datang: 2025-05-16, 1 produk', '2025-05-16 19:15:47'),
(294, 18, 'Hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 231fwfs, Tgl datang: 2025-05-16, 1 produk', '2025-05-16 19:16:49'),
(295, 18, 'Undo hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 231fwfs, Tgl datang: 2025-05-16, 1 produk', '2025-05-16 19:17:33'),
(296, 18, 'Logout dari sistem', '2025-05-16 19:27:45'),
(297, 18, 'Login ke sistem', '2025-05-16 19:27:55'),
(298, 18, 'Logout dari sistem', '2025-05-16 20:30:17'),
(299, 2, 'Login ke sistem', '2025-05-16 20:31:59'),
(300, 2, 'Hapus arsip: PT ANUGRAH SUKSES MANDIRI DISTRIBUSI, Faktur: 00012712, Tgl datang: 2025-04-05, 2 produk', '2025-05-16 20:33:09'),
(301, 2, 'Undo hapus arsip: PT ANUGRAH SUKSES MANDIRI DISTRIBUSI, Faktur: 00012712, Tgl datang: 2025-04-05, 2 produk', '2025-05-16 22:03:54'),
(302, 2, 'Hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 231fwfs, Tgl datang: 2025-05-16, 1 produk', '2025-05-16 22:32:05'),
(303, 2, 'Undo hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 231fwfs, Tgl datang: 2025-05-16, 1 produk', '2025-05-16 22:32:20'),
(304, 2, 'Hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 231fwfs, Tgl datang: 2025-05-16, 1 produk', '2025-05-16 22:38:38'),
(305, 2, 'Undo hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 231fwfs, Tgl datang: 2025-05-16, 1 produk', '2025-05-16 22:42:31'),
(306, 2, 'Hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 231fwfs, Tgl datang: 2025-05-16, 1 produk', '2025-05-16 22:46:28'),
(307, 2, 'Undo hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 231fwfs, Tgl datang: 2025-05-16, 1 produk', '2025-05-16 22:49:57'),
(308, 2, 'Hapus arsip: CV ANUGRAH SIKSES MANDIRI DISTRIBUSI, Faktur: 231fwfs, Tgl datang: 2025-05-16, 1 produk', '2025-05-16 22:50:30'),
(309, 2, 'Hapus arsip: PT CARMELLA GUSTAVINDO, Faktur: 231fwfs, Tgl datang: 2025-05-01, 1 produk', '2025-05-16 22:59:14'),
(310, 2, 'Hapus arsip: alert(&quot;XSS4&quot;);, Faktur: alert(&quot;XSS4&quot;);, Tgl datang: 1111-11-11, 1 produk', '2025-05-16 23:02:54'),
(311, 2, 'Undo hapus arsip: alert(&quot;XSS4&quot;);, Faktur: alert(&quot;XSS4&quot;);, Tgl datang: 1111-11-11, 1 produk', '2025-05-16 23:03:04'),
(312, 2, 'Undo hapus arsip: PT CARMELLA GUSTAVINDO, Faktur: 231fwfs, Tgl datang: 2025-05-01, 1 produk', '2025-05-16 23:05:00'),
(313, 2, 'Hapus arsip: PT PRABA BARAKA JAYA, Faktur: 231fwfs, Tgl datang: 2025-05-15, 5 produk', '2025-05-16 23:05:14'),
(314, 2, 'Undo hapus arsip: PT PRABA BARAKA JAYA, Faktur: 231fwfs, Tgl datang: 2025-05-15, 5 produk', '2025-05-16 23:05:28'),
(315, 2, 'Logout dari sistem', '2025-05-16 23:09:10'),
(316, 2, 'Login ke sistem', '2025-05-16 23:09:14'),
(317, 2, 'Hapus arsip: PT PRABA BARAKA JAYA, Faktur: 231fwfs, Tgl datang: 2025-05-15, 5 produk', '2025-05-16 23:14:38'),
(318, 2, 'Undo hapus arsip: PT PRABA BARAKA JAYA, Faktur: 231fwfs, Tgl datang: 2025-05-15, 5 produk', '2025-05-16 23:14:46'),
(319, 2, 'Hapus arsip: PT PRABA BARAKA JAYA, Faktur: 231fwfs, Tgl datang: 2025-05-15, 5 produk', '2025-05-16 23:20:46'),
(320, 2, 'Undo hapus arsip: PT PRABA BARAKA JAYA, Faktur: 231fwfs, Tgl datang: 2025-05-15, 5 produk', '2025-05-16 23:21:01'),
(321, 2, 'Hapus arsip: alert(&quot;XSS4&quot;);, Faktur: alert(&quot;XSS4&quot;);, Tgl datang: 1111-11-11, 1 produk', '2025-05-16 23:24:29'),
(322, 2, 'Undo hapus arsip: alert(&quot;XSS4&quot;);, Faktur: alert(&quot;XSS4&quot;);, Tgl datang: 1111-11-11, 1 produk', '2025-05-16 23:24:45'),
(323, 2, 'Hapus arsip: PT PRABA BARAKA JAYA, Faktur: 231fwfs, Tgl datang: 2025-05-15, 5 produk', '2025-05-16 23:25:16'),
(324, 2, 'Undo hapus arsip: PT PRABA BARAKA JAYA, Faktur: 231fwfs, Tgl datang: 2025-05-15, 5 produk', '2025-05-16 23:25:22'),
(325, 2, 'Tambah arsip: 11111, Faktur: 1, Tgl datang: 1111-11-11, 1 produk', '2025-05-16 23:27:30'),
(326, 2, 'Hapus arsip: 11111, Faktur: 1, Tgl datang: 1111-11-11, 1 produk', '2025-05-16 23:27:42'),
(327, 2, 'Undo hapus arsip: 11111, Faktur: 1, Tgl datang: 1111-11-11, 1 produk', '2025-05-16 23:27:55'),
(328, 2, 'Login ke sistem', '2025-05-17 15:32:25'),
(329, 2, 'Tambah arsip: PT CARMELLA GUSTAVINDO, Faktur: 1, Tgl datang: 2025-05-17, 1 produk', '2025-05-17 15:33:35'),
(330, 2, 'Tambah arsip: perusahaan 1, Faktur: 1, Tgl datang: 2025-05-17, 1 produk', '2025-05-17 15:34:54'),
(331, 2, 'Tambah arsip: perusahaan 1, Faktur: 1, Tgl datang: 2025-05-17, 1 produk', '2025-05-17 15:35:21'),
(332, 2, 'Tambah arsip: perusahaan 1, Faktur: 1, Tgl datang: 2025-05-17, 1 produk', '2025-05-17 15:42:15'),
(333, 2, 'Tambah arsip: perusahaan 1, Faktur: 1, Tgl datang: 2025-05-17, 1 produk', '2025-05-17 15:42:48'),
(334, 2, 'Logout dari sistem', '2025-05-17 15:57:30'),
(335, 18, 'Login ke sistem', '2025-05-17 15:57:39'),
(336, 18, 'Hapus arsip: PT CARMELLA GUSTAVINDO, Faktur: 1, Tgl datang: 2025-05-17, 1 produk', '2025-05-17 16:25:54'),
(337, 18, 'Undo hapus arsip: PT CARMELLA GUSTAVINDO, Faktur: 1, Tgl datang: 2025-05-17, 1 produk', '2025-05-17 16:26:14'),
(338, 18, 'Hapus arsip: PT CARMELLA GUSTAVINDO, Faktur: 1, Tgl datang: 2025-05-17, 1 produk', '2025-05-17 16:33:01'),
(339, 18, 'Undo hapus arsip: PT CARMELLA GUSTAVINDO, Faktur: 1, Tgl datang: 2025-05-17, 1 produk', '2025-05-17 16:33:10'),
(340, 18, 'Mengubah profil', '2025-05-17 16:40:22'),
(341, 18, 'Edit arsip: PT CARMELLA GUSTAVINDO, Faktur: 1, Tgl datang: 2025-05-17; 1 produk diubah.', '2025-05-17 17:22:36'),
(342, 18, 'Edit arsip: PT CARMELLA GUSTAVINDO, Faktur: 1, Tgl datang: 2025-05-17; 1 produk diubah.', '2025-05-17 17:25:51'),
(343, 18, 'Edit arsip: PT CARMELLA GUSTAVINDO, Faktur: 1, Tgl datang: 2025-05-17; 1 produk diubah.', '2025-05-17 17:27:15'),
(344, 18, 'Edit arsip: PT CARMELLA GUSTAVINDO, Faktur: 1, Tgl datang: 2025-05-17; 1 produk diubah.', '2025-05-17 17:27:27'),
(345, 18, 'Edit arsip: PT CARMELLA GUSTAVINDO, Faktur: 1, Tgl datang: 2025-05-17; 1 produk diubah.', '2025-05-17 17:29:15'),
(346, 18, 'Edit arsip: PT CARMELLA GUSTAVINDO menjadi , Faktur: 1 menjadi , Tgl datang: 2025-05-17 menjadi 0001-01-01; 2 produk diubah.', '2025-05-17 17:32:04'),
(347, 18, 'Edit arsip:  menjadi 1, Faktur:  menjadi 1, Tgl datang: 0001-01-01; 2 produk diubah.', '2025-05-17 17:32:51'),
(348, 18, 'Edit arsip: 1 menjadi , Faktur: 1 menjadi , Tgl datang: 0001-01-01; 2 produk diubah.', '2025-05-17 17:33:20'),
(349, 18, 'Logout dari sistem', '2025-05-17 17:35:28'),
(350, 2, 'Login ke sistem', '2025-05-17 17:35:33'),
(351, 2, 'Edit arsip: , Faktur: , Tgl datang: 0001-01-01; 1 produk diubah.', '2025-05-17 17:36:09'),
(352, 2, 'Edit arsip: , Faktur: , Tgl datang: 0001-01-01; 1 produk diubah.', '2025-05-17 17:39:30'),
(353, 2, 'Edit arsip: perusahaan 1 menjadi 1, Faktur: 1, Tgl datang: 2025-05-17; 1 produk diubah.', '2025-05-17 17:42:38'),
(354, 2, 'Edit arsip: 1, Faktur: 1, Tgl datang: 2025-05-17; 1 produk diubah.', '2025-05-17 17:55:46'),
(355, 2, 'Edit arsip: 1 menjadi 6, Faktur: 1, Tgl datang: 2025-05-17; 0 produk diubah.', '2025-05-17 17:57:07'),
(356, 2, 'Edit arsip: 6 menjadi m, Faktur: 1, Tgl datang: 2025-05-17; 0 produk diubah.', '2025-05-17 17:58:36'),
(357, 2, 'Edit arsip: m menjadi PT CARMELLA GUSTAVINDO, Faktur: 1 menjadi PT CARMELLA GUSTAVINDO, Tgl datang: 2025-05-17; 2 produk diubah.', '2025-05-17 18:08:52'),
(358, 2, 'Hapus arsip: PT CARMELLA GUSTAVINDO, Faktur: PT CARMELLA GUSTAVINDO, Tgl datang: 2025-05-17, 1 produk', '2025-05-17 18:21:01'),
(359, 2, 'Undo hapus arsip: PT CARMELLA GUSTAVINDO, Faktur: PT CARMELLA GUSTAVINDO, Tgl datang: 2025-05-17, 1 produk', '2025-05-17 18:21:06'),
(360, 2, 'Edit arsip: PT CARMELLA GUSTAVINDO menjadi PT CARMELLA GUSTAVIND, Faktur: PT CARMELLA GUSTAVINDO, Tgl datang: 2025-05-17; 0 produk diubah.', '2025-05-17 18:22:50'),
(361, 2, 'Edit arsip: PT CARMELLA GUSTAVIND, Faktur: PT CARMELLA GUSTAVINDO menjadi PT CARMELLA GUSTAVINDO1, Tgl datang: 2025-05-17; 0 produk diubah.', '2025-05-17 18:24:31'),
(362, 2, 'Edit arsip: PT CARMELLA GUSTAVIND menjadi a, Faktur: PT CARMELLA GUSTAVINDO1 menjadi a, Tgl datang: 2025-05-17; 2 produk diubah.', '2025-05-17 18:40:19'),
(363, 2, 'Tambah arsip: perusahaan 1, Faktur: 1, Tgl datang: 1111-11-11, 2 produk', '2025-05-17 19:18:18'),
(364, 2, 'Hapus arsip: , Faktur: , Tgl datang: 0001-01-01, 1 produk', '2025-05-17 19:18:47'),
(365, 2, 'Login ke sistem', '2025-05-18 13:31:54'),
(366, 2, 'Login ke sistem', '2025-05-18 13:36:10'),
(367, 2, 'Hapus arsip: a, Faktur: a, Tgl datang: 2025-05-17, 1 produk', '2025-05-18 13:48:45'),
(368, 2, 'Undo hapus arsip: a, Faktur: a, Tgl datang: 2025-05-17, 1 produk', '2025-05-18 13:50:32'),
(369, 2, 'Logout dari sistem', '2025-05-18 17:30:19'),
(370, 2, 'Login ke sistem', '2025-05-18 17:41:22'),
(371, 2, 'Logout dari sistem', '2025-05-18 17:41:55'),
(372, 2, 'Login ke sistem', '2025-05-18 17:48:05'),
(373, 2, 'Logout dari sistem', '2025-05-18 18:05:34'),
(374, 2, 'Login ke sistem', '2025-05-18 18:05:40'),
(375, 2, 'Hapus arsip: a, Faktur: a, Tgl datang: 2025-05-17, 1 produk', '2025-05-18 18:08:28'),
(376, 2, 'Hapus arsip: perusahaan 2, Faktur: 123qwed, Tgl datang: 2025-05-08, 1 produk', '2025-05-18 18:18:45'),
(377, 2, 'Hapus arsip: perusahaan 1, Faktur: 1, Tgl datang: 2025-05-17, 1 produk', '2025-05-18 18:19:26'),
(378, 2, 'Undo hapus arsip: perusahaan 1, Faktur: 1, Tgl datang: 2025-05-17, 1 produk', '2025-05-18 18:41:09'),
(379, 2, 'Undo hapus arsip: perusahaan 2, Faktur: 123qwed, Tgl datang: 2025-05-08, 1 produk', '2025-05-18 18:41:12'),
(380, 2, 'Undo hapus arsip: a, Faktur: a, Tgl datang: 2025-05-17, 1 produk', '2025-05-18 18:41:16'),
(381, 2, 'Hapus arsip: a, Faktur: a, Tgl datang: 2025-05-17, 1 produk', '2025-05-18 19:05:00'),
(382, 2, 'Undo hapus arsip: a, Faktur: a, Tgl datang: 2025-05-17, 1 produk', '2025-05-18 19:05:26'),
(383, 2, 'Hapus arsip: alert(&quot;XSS4&quot;);, Faktur: alert(&quot;XSS4&quot;);, Tgl datang: 1111-11-11, 1 produk', '2025-05-18 19:16:51'),
(384, 2, 'Hapus arsip: alert(&quot;XSS4&quot;);, Faktur: alert(&quot;XSS4&quot;);, Tgl datang: 1111-11-11, 1 produk', '2025-05-18 19:17:04'),
(385, 2, 'Logout dari sistem', '2025-05-18 19:19:34'),
(386, 19, 'Pendaftaran akun baru', '2025-05-18 19:21:09'),
(387, 19, 'Login ke sistem', '2025-05-18 19:21:22'),
(388, 19, 'Logout dari sistem', '2025-05-18 19:21:57'),
(389, 19, 'Login ke sistem', '2025-05-18 19:22:16'),
(390, 19, 'Hapus arsip: a, Faktur: a, Tgl datang: 2025-05-17, 1 produk', '2025-05-18 19:27:21'),
(391, 19, 'Undo hapus arsip: a, Faktur: a, Tgl datang: 2025-05-17, 1 produk', '2025-05-18 19:27:47'),
(392, 19, 'Mengubah profil', '2025-05-18 19:28:33'),
(393, 19, 'Logout dari sistem', '2025-05-18 19:29:02'),
(394, 2, 'Login ke sistem', '2025-05-18 19:29:18'),
(395, 2, 'Login ke sistem', '2025-05-19 08:17:11'),
(396, 2, 'Login ke sistem', '2025-05-19 15:10:23'),
(397, 2, 'Login ke sistem', '2025-05-19 21:23:41'),
(398, 2, 'Logout dari sistem', '2025-05-19 21:23:50'),
(399, 18, 'Login ke sistem', '2025-05-19 21:23:58'),
(400, 18, 'Logout dari sistem', '2025-05-19 22:17:18'),
(401, 20, 'Pendaftaran akun baru', '2025-05-19 22:18:30'),
(402, 20, 'Login ke sistem', '2025-05-19 22:18:59'),
(403, 20, 'Mengubah profil', '2025-05-19 22:19:20'),
(404, 20, 'Mengubah profil', '2025-05-19 22:19:49'),
(405, 20, 'Mengubah profil', '2025-05-19 22:21:47'),
(406, 20, 'Hapus arsip: PT CARMELLA GUSTAVINDO, Faktur: 123qwed, Tgl datang: 2025-04-30, 1 produk', '2025-05-19 23:13:22'),
(407, 20, 'Login ke sistem', '2025-05-20 06:14:26'),
(408, 2, 'Login ke sistem', '2025-05-21 08:26:43'),
(409, 2, 'Hapus arsip: a, Faktur: a, Tgl datang: 2025-05-17, 1 produk', '2025-05-21 08:26:56'),
(410, 2, 'Undo hapus arsip: a, Faktur: a, Tgl datang: 2025-05-17, 1 produk', '2025-05-21 08:27:39'),
(411, 2, 'Login ke sistem', '2025-05-22 13:01:32'),
(412, 2, 'Hapus arsip: a, Faktur: a, Tgl datang: 2025-05-17, 1 produk', '2025-05-22 13:01:56'),
(413, 2, 'Undo hapus arsip: a, Faktur: a, Tgl datang: 2025-05-17, 1 produk', '2025-05-22 13:02:22'),
(414, 2, 'Login ke sistem', '2025-05-22 19:32:14'),
(415, 2, 'Hapus arsip: perusahaan 1, Faktur: 1, Tgl datang: 1111-11-11, 2 produk', '2025-05-22 19:36:22'),
(416, 2, 'Hapus arsip: perusahaan 1, Faktur: 1, Tgl datang: 2025-05-17, 1 produk', '2025-05-22 19:36:29'),
(417, 2, 'Hapus arsip: perusahaan 1, Faktur: 1, Tgl datang: 2025-05-17, 1 produk', '2025-05-22 19:36:36'),
(418, 2, 'Hapus arsip: perusahaan 3, Faktur: 123qwe, Tgl datang: 2025-04-09, 1 produk', '2025-05-22 19:36:42'),
(419, 2, 'Tambah arsip: PT PRABA BARAKA JAYA, Faktur: erge33654, Tgl datang: 2025-01-22, 1 produk', '2025-05-22 19:46:09'),
(420, 2, 'Tambah arsip: CV ANUGERAH SUKSES MANDIRI DISTRIBUSI, Faktur: erfwr345, Tgl datang: 2025-02-22, 1 produk', '2025-05-22 19:50:47'),
(421, 2, 'Tambah arsip: PT CARMELLA GUSTAVINDO, Faktur: 35dfgre3, Tgl datang: 2025-03-22, 2 produk', '2025-05-22 19:53:19'),
(422, 2, 'Tambah arsip: PT PRABA BARAKA JAYA, Faktur: dfbe34t, Tgl datang: 2025-04-22, 2 produk', '2025-05-22 19:54:31'),
(423, 2, 'Tambah arsip: PERUSAHAAN 1, Faktur: rthr456, Tgl datang: 2025-05-22, 2 produk', '2025-05-22 19:56:00');
INSERT INTO `log_aktivitas` (`id_log`, `id_admin`, `aksi`, `waktu`) VALUES
(424, 2, 'Tambah arsip: PERUSAHAAN 2, Faktur: YJTYI768, Tgl datang: 2025-06-22, 1 produk', '2025-05-22 19:58:49'),
(425, 2, 'Tambah arsip: PERUSAHAAN 2, Faktur: RTHRT4, Tgl datang: 2025-07-22, 2 produk', '2025-05-22 20:00:32'),
(426, 2, 'Tambah arsip: PT PRABA BARAKA JAYA, Faktur: ERG34T, Tgl datang: 2025-08-30, 1 produk', '2025-05-22 20:02:39'),
(427, 2, 'Tambah arsip: PERUSAHAAN 2, Faktur: URTU457, Tgl datang: 2025-09-23, 1 produk', '2025-05-22 20:20:13'),
(428, 2, 'Login ke sistem', '2025-05-23 09:19:42');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int NOT NULL,
  `nama_produk` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`) VALUES
(1, 'produk 1'),
(2, 'produk 2'),
(3, 'produk 3'),
(4, 'produk 4'),
(5, 'produk 4 v2'),
(6, 'produk 1 vv50'),
(7, 'produk 1 v51'),
(8, 'produk no 1 edit v2.2.1.2'),
(10, 'abc123'),
(14, 'produk nomor 1'),
(15, 'produk 2'),
(16, 'produk p1'),
(17, 'produk p2'),
(18, 'produk 1 v1'),
(20, 'produk no 1 edit v2.2.1.2.A'),
(21, 'produk no 1 edit v2.2.1.2.A'),
(23, 'paracetamol'),
(24, 'produk paracetamol'),
(26, 'GRAFACHLOR TAB 100'),
(27, 'DOBRIZOL CAPS 5X10'),
(28, 'FLASICOX 5X10 IFAR'),
(29, 'SCOPMA PLUS TAB 10X10'),
(30, 'LICODEXON 0.5 200'),
(32, 'IM BOOST COUGH KIDS SYR 60ML'),
(33, 'IM BOOST KIDS SYR 60ML'),
(34, 'IM BOOST SYR FORSE KIDS 60ML'),
(35, 'IM BOOST TAB 5X10'),
(36, 'SEAGUL SG-519/P NAPH 25GR'),
(37, 'SEAGUL SG-519/P NAPH 25GR WARNA'),
(39, 'AAAAAAAAA10AAAAAAAAA20AAAAAAAAAA30AAAAAAAAA40AAAAAAAAAA50'),
(41, 'LELAP 2X4\'5'),
(42, 'SEAGUL SG-519/P NAPH 25GR'),
(49, 'GRAFACHLOR TAB 1'),
(50, 'SEAGU0L SG-519/P NAPH 25GR');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `arsip`
--
ALTER TABLE `arsip`
  ADD PRIMARY KEY (`id_arsip`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `detail_produk`
--
ALTER TABLE `detail_produk`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_arsip` (`id_arsip`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `arsip`
--
ALTER TABLE `arsip`
  MODIFY `id_arsip` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `detail_produk`
--
ALTER TABLE `detail_produk`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=429;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `arsip`
--
ALTER TABLE `arsip`
  ADD CONSTRAINT `arsip_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `detail_produk`
--
ALTER TABLE `detail_produk`
  ADD CONSTRAINT `detail_produk_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_produk_ibfk_2` FOREIGN KEY (`id_arsip`) REFERENCES `arsip` (`id_arsip`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
