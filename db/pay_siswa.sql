-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 11, 2025 at 11:19 AM
-- Server version: 9.1.0
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pay_siswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_period`
--

DROP TABLE IF EXISTS `academic_period`;
CREATE TABLE IF NOT EXISTS `academic_period` (
  `id_academic_period` int NOT NULL AUTO_INCREMENT,
  `academic_period` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `academic_period_start` date NOT NULL,
  `academic_period_end` date NOT NULL,
  `academic_period_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_academic_period`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

DROP TABLE IF EXISTS `access`;
CREATE TABLE IF NOT EXISTS `access` (
  `id_access` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_access_group` int DEFAULT NULL,
  `access_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `access_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `access_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `access_password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `access_foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `access_client` tinyint(1) NOT NULL COMMENT 'If true, the account is a client.',
  PRIMARY KEY (`id_access`),
  KEY `id_access_group` (`id_access_group`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `access`
--

INSERT INTO `access` (`id_access`, `id_access_group`, `access_name`, `access_email`, `access_contact`, `access_password`, `access_foto`, `access_client`) VALUES
(1, 1, 'Solihul Hadi', 'dhiforester@gmail.com', '089601154726', '$2y$10$xd6zNVq4rgVdmAsk6SOGKu7h1.i7Htki1lmdogS2/7j2Q/F3HnNMy', 'ca6526b10323e5ffc519def7f71e10.jpg', 0),
(2, 8, 'Dewi Widiastuti', 'dewiwidiastuti@gmail.com', '08975657467', '$2y$10$YW/wCElX7HYlfipjFo80eO89RkvlUZ9iIOwZk4lK.Cf/BR8ypeygm', '4522beb0ae8aabe337284b439dcc79.png', 0),
(7, 1, 'windy Yanuariska', 'windygiga@gmail.com', '081320776867', '$2y$10$v5HEPgDDD/JgjqkWZIEN0.D8KGWHTeTAZztN52TD9C4jN/4YaX8X.', '5e261b794ba844bbba0af99d8855b4.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `access_feature`
--

DROP TABLE IF EXISTS `access_feature`;
CREATE TABLE IF NOT EXISTS `access_feature` (
  `id_access_feature` varchar(36) COLLATE utf8mb4_bin NOT NULL,
  `feature_name` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `feature_category` varchar(12) COLLATE utf8mb4_bin NOT NULL,
  `feature_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `datetime_creat` timestamp NOT NULL,
  PRIMARY KEY (`id_access_feature`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `access_feature`
--

INSERT INTO `access_feature` (`id_access_feature`, `feature_name`, `feature_category`, `feature_description`, `datetime_creat`) VALUES
('Dnd2UZLzazCqJ9WfuzQKlIOpYueb2fXxNHXA', 'Bantuan', 'Lainnya', 'Halaman untuk mengelola konten bantuan atau dokumentasi', '2025-09-06 14:36:36'),
('G2LxhMdVkih0ZJ4xPz8YGxVVCMLNmH0OnQvF', 'Komponen Biaya', 'Pembayaran', 'Halaman untuk mengelola komponen biaya siswa', '2025-09-05 20:53:56'),
('JbBByqDggzgIC8y6IH4JnbyynMUvHd0iFx5G', 'Tahun Ajaran', 'Referensi', 'Halaman untuk mengelola periode tahun ajaran', '2025-09-08 20:35:42'),
('Mt24BYzC76RJBEuHdY95bmMKrulttEQzblzH', 'Pengaturan Umum', 'Pengaturan', 'Halaman yang berfungsi untuk mengatur aplikasi secara umum', '2025-09-01 19:27:07'),
('TIVPffUE3kqw288OB1R0CJ09daM9l2TLdGVv', 'Pembayaran Siswa', 'Pembayaran', 'Halaman untuk mengelola data pembayaran siswa', '2025-09-06 16:27:53'),
('TleUu0waFsTCePkXuIqJuA1DDJ2hY3FGvzYX', 'Payment Gateway', 'Pengaturan', 'Halaman untuk menyimpan pengaturan payment gateway', '2025-09-01 19:32:14'),
('a99AXGc0fRtw8wPbfCq16dmAfETaN5jZQc8R', 'Daftar Siswa', 'Siswa', 'Halaman yang berfungsi untuk mengelola data siswa', '2025-08-31 21:43:10'),
('aziAs4ZofHmVooUohitYSojDp7oR2zbjrwpY', 'Email Gateway', 'Pengaturan', 'Halaman yang berguna untuk menyimpan pengaturan email gateway', '2025-09-01 19:32:54'),
('jO3M0NopVQeXi4VuDHpvD9SRJzntpUGAe6Sw', 'Akses Pengguna', 'Akses', 'Halaman untuk mengelola akun akses pengguna', '2025-08-31 20:23:54'),
('lInyeHHg924zNLaXZ3SmjjnuyCOYBnUyUuTD', 'Entitas Akses Pengguna', 'Akses', 'Halaman untuk mengelola entitas/group/level pengguna', '2025-08-31 20:23:01'),
('mOFQURHvlxqXre9cyx7FMjFtzqc1zWb0x2RD', 'Group Kelas', 'Siswa', 'Halaman yang berfungsi untuk mengelola kelas', '2025-08-31 21:42:36'),
('nSYinRWpCF9MHNUIlW7Up5vTip70gNNLlrqv', 'Fitur Aplikasi', 'Akses', 'Halaman untuk mengelola fitur aplikasi', '2025-08-31 20:21:48');

-- --------------------------------------------------------

--
-- Table structure for table `access_group`
--

DROP TABLE IF EXISTS `access_group`;
CREATE TABLE IF NOT EXISTS `access_group` (
  `id_access_group` int NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `group_description` text COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id_access_group`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `access_group`
--

INSERT INTO `access_group` (`id_access_group`, `group_name`, `group_description`) VALUES
(1, 'Admin', 'Pihak yang berwenang melakukan akses ke semua fitur'),
(2, 'Kepala Sekolah', 'Kepala sekolah'),
(3, 'Sekretaris', 'Pihak yang melakukan verifikasi pembayaran'),
(8, 'Bendahara', 'Pihak yang berhak menyimpan keuangan');

-- --------------------------------------------------------

--
-- Table structure for table `access_log`
--

DROP TABLE IF EXISTS `access_log`;
CREATE TABLE IF NOT EXISTS `access_log` (
  `id_access_log` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_access` int UNSIGNED NOT NULL,
  `log_datetime` datetime NOT NULL,
  `log_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `log_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id_access_log`),
  KEY `access_log_id_access_index` (`id_access`)
) ENGINE=InnoDB AUTO_INCREMENT=213 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `access_log`
--

INSERT INTO `access_log` (`id_access_log`, `id_access`, `log_datetime`, `log_category`, `log_description`) VALUES
(2, 1, '0000-00-00 00:00:00', 'Login', 'Login Berhasil'),
(3, 1, '0000-00-00 00:00:00', 'Login', 'Login Berhasil'),
(4, 1, '2025-08-31 15:13:10', 'Login', 'Login Berhasil'),
(5, 1, '2025-08-31 15:13:57', 'Login', 'Login Berhasil'),
(6, 1, '2025-08-31 15:45:55', 'Login', 'Login Berhasil'),
(7, 1, '2025-08-31 16:32:54', 'Login', 'Login Berhasil'),
(8, 1, '2025-08-31 19:36:25', 'Login', 'Login Berhasil'),
(9, 1, '2025-08-31 19:37:25', 'Login', 'Login Berhasil'),
(10, 1, '2025-08-31 21:20:05', 'Login', 'Login Berhasil'),
(11, 1, '2025-09-01 00:57:00', 'Login', 'Login Berhasil'),
(12, 1, '2025-09-01 01:31:27', 'Akses', 'Input Fitur Akses'),
(13, 1, '2025-09-01 01:38:07', 'Akses', 'Input Fitur Akses'),
(14, 1, '2025-09-01 02:55:39', 'Akses', 'Input Fitur Akses'),
(18, 1, '2025-09-01 03:14:05', 'Fitur Akses', 'Hapus Fitur Akses'),
(19, 1, '2025-09-01 03:21:29', 'Fitur Akses', 'Hapus Fitur Akses'),
(20, 1, '2025-09-01 03:21:48', 'Akses', 'Input Fitur Akses'),
(21, 1, '2025-09-01 03:23:01', 'Akses', 'Input Fitur Akses'),
(22, 1, '2025-09-01 03:23:54', 'Akses', 'Input Fitur Akses'),
(23, 1, '2025-09-01 04:42:36', 'Akses', 'Input Fitur Akses'),
(24, 1, '2025-09-01 04:43:10', 'Akses', 'Input Fitur Akses'),
(25, 1, '2025-09-01 23:51:29', 'Login', 'Login Berhasil'),
(26, 1, '2025-09-02 00:00:21', 'Entitas Akses', 'Input Entitas Akses'),
(27, 1, '2025-09-02 00:23:03', 'Entitas Akses', 'Input Entitas Akses'),
(28, 1, '2025-09-02 00:42:31', 'Entitas Akses', 'Input Entitas Akses'),
(29, 1, '2025-09-02 00:46:38', 'Entitas Akses', 'Input Entitas Akses'),
(30, 1, '2025-09-02 02:12:26', 'Entitas Akses', 'Hapus Entitas Akses'),
(31, 1, '2025-09-02 02:27:07', 'Akses', 'Input Fitur Akses'),
(32, 1, '2025-09-02 02:32:14', 'Akses', 'Input Fitur Akses'),
(33, 1, '2025-09-02 02:32:54', 'Akses', 'Input Fitur Akses'),
(34, 1, '2025-09-02 02:54:44', 'Entitas Akses', 'Edit Entitas Akses'),
(35, 1, '2025-09-02 02:54:53', 'Entitas Akses', 'Edit Entitas Akses'),
(36, 1, '2025-09-02 15:46:46', 'Login', 'Login Berhasil'),
(37, 1, '2025-09-02 16:28:44', 'Entitas Akses', 'Edit Entitas Akses'),
(38, 1, '2025-09-02 20:15:42', 'Login', 'Login Berhasil'),
(39, 1, '2025-09-03 00:46:39', 'Login', 'Login Berhasil'),
(40, 1, '2025-09-03 19:00:50', 'Login', 'Login Berhasil'),
(41, 1, '2025-09-03 21:45:12', 'Login', 'Login Berhasil'),
(42, 1, '2025-09-04 01:06:47', 'Login', 'Login Berhasil'),
(43, 1, '2025-09-04 02:01:37', 'Kelas', 'Input Kelas Berhasil'),
(44, 1, '2025-09-04 02:59:00', 'Kelas', 'Input Kelas Berhasil'),
(45, 1, '2025-09-04 03:01:19', 'Kelas', 'Input Kelas Berhasil'),
(46, 1, '2025-09-04 03:05:42', 'Kelas', 'Input Kelas Berhasil'),
(47, 1, '2025-09-04 03:07:27', 'Kelas', 'Input Kelas Berhasil'),
(48, 1, '2025-09-04 03:20:47', 'Kelas', 'Input Kelas Berhasil'),
(49, 1, '2025-09-04 03:20:52', 'Kelas', 'Input Kelas Berhasil'),
(50, 1, '2025-09-04 03:25:10', 'Kelas', 'Input Kelas Berhasil'),
(51, 1, '2025-09-04 04:05:50', 'Kelas', 'Update Kelas Berhasil'),
(52, 1, '2025-09-04 04:06:01', 'Kelas', 'Update Kelas Berhasil'),
(53, 1, '2025-09-04 04:06:25', 'Kelas', 'Update Kelas Berhasil'),
(54, 1, '2025-09-04 15:52:13', 'Login', 'Login Berhasil'),
(55, 1, '2025-09-04 20:08:56', 'Login', 'Login Berhasil'),
(56, 1, '2025-09-04 21:34:22', 'Login', 'Login Berhasil'),
(57, 1, '2025-09-04 22:42:59', 'Login', 'Login Berhasil'),
(58, 1, '2025-09-04 23:18:54', 'Siswa', 'Input Siswa Berhasil'),
(59, 1, '2025-09-05 01:08:56', 'Siswa', 'Input Siswa Berhasil'),
(60, 1, '2025-09-05 01:14:24', 'Siswa', 'Input Siswa Berhasil'),
(61, 1, '2025-09-05 03:46:41', 'Siswa', 'Input Siswa Berhasil'),
(62, 1, '2025-09-05 04:03:56', 'Siswa', 'Edit Siswa Berhasil'),
(63, 1, '2025-09-05 04:04:14', 'Siswa', 'Edit Siswa Berhasil'),
(64, 1, '2025-09-05 04:05:44', 'Siswa', 'Input Siswa Berhasil'),
(65, 1, '2025-09-05 04:08:55', 'Siswa', 'Input Siswa Berhasil'),
(66, 1, '2025-09-05 04:09:20', 'Siswa', 'Edit Siswa Berhasil'),
(67, 1, '2025-09-05 20:59:51', 'Login', 'Login Berhasil'),
(68, 1, '2025-09-06 03:34:35', 'Siswa', 'Edit Siswa Berhasil'),
(69, 1, '2025-09-06 03:34:50', 'Siswa', 'Edit Siswa Berhasil'),
(70, 1, '2025-09-06 03:53:56', 'Akses', 'Input Fitur Akses'),
(71, 1, '2025-09-06 03:54:17', 'Entitas Akses', 'Edit Entitas Akses'),
(72, 1, '2025-09-06 04:41:54', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(73, 1, '2025-09-06 04:43:25', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(74, 1, '2025-09-06 04:44:36', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(75, 1, '2025-09-06 05:11:26', 'Komponen Biaya', 'Hapus Komponen Biaya'),
(76, 1, '2025-09-06 05:26:56', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(77, 1, '2025-09-06 05:27:04', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(78, 1, '2025-09-06 05:30:05', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(79, 1, '2025-09-06 06:10:37', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(80, 1, '2025-09-06 07:37:21', 'Login', 'Login Berhasil'),
(81, 1, '2025-09-06 08:48:50', 'Login', 'Login Berhasil'),
(82, 1, '2025-09-06 08:58:17', 'Login', 'Login Berhasil'),
(83, 1, '2025-09-06 09:02:33', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(84, 1, '2025-09-06 09:40:19', 'Kelas', 'Input Kelas Berhasil'),
(85, 1, '2025-09-06 09:40:29', 'Kelas', 'Input Kelas Berhasil'),
(86, 1, '2025-09-06 09:40:36', 'Kelas', 'Input Kelas Berhasil'),
(87, 1, '2025-09-06 09:40:50', 'Kelas', 'Input Kelas Berhasil'),
(88, 1, '2025-09-06 21:15:12', 'Login', 'Login Berhasil'),
(89, 1, '2025-09-06 21:18:52', 'Kelas', 'Input Kelas Berhasil'),
(90, 1, '2025-09-06 21:19:23', 'Kelas', 'Input Kelas Berhasil'),
(91, 1, '2025-09-06 21:27:51', 'Kelas', 'Input Kelas Berhasil'),
(92, 1, '2025-09-06 21:27:57', 'Kelas', 'Input Kelas Berhasil'),
(93, 1, '2025-09-06 21:28:04', 'Kelas', 'Input Kelas Berhasil'),
(94, 1, '2025-09-06 21:28:14', 'Kelas', 'Update Kelas Berhasil'),
(95, 1, '2025-09-06 21:28:28', 'Kelas', 'Update Kelas Berhasil'),
(96, 1, '2025-09-06 21:36:36', 'Akses', 'Input Fitur Akses'),
(97, 1, '2025-09-06 21:36:53', 'Entitas Akses', 'Edit Entitas Akses'),
(98, 1, '2025-09-06 23:13:31', 'Login', 'Login Berhasil'),
(99, 1, '2025-09-06 23:27:53', 'Akses', 'Input Fitur Akses'),
(100, 1, '2025-09-06 23:41:09', 'Entitas Akses', 'Edit Entitas Akses'),
(101, 1, '2025-09-07 04:00:37', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(102, 1, '2025-09-07 04:04:29', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(103, 1, '2025-09-07 04:05:20', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(104, 1, '2025-09-07 04:06:28', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(105, 1, '2025-09-07 04:09:57', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(106, 1, '2025-09-07 04:10:31', 'Komponen Biaya', 'Hapus Komponen Biaya'),
(107, 1, '2025-09-07 04:11:04', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(108, 1, '2025-09-07 04:11:34', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(109, 1, '2025-09-07 04:11:41', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(110, 1, '2025-09-07 04:11:49', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(111, 1, '2025-09-07 04:11:54', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(112, 1, '2025-09-07 04:12:00', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(113, 1, '2025-09-07 04:13:32', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(114, 1, '2025-09-07 04:14:22', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(115, 1, '2025-09-07 04:16:10', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(116, 1, '2025-09-07 04:16:30', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(117, 1, '2025-09-07 04:17:46', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(118, 1, '2025-09-07 14:03:27', 'Login', 'Login Berhasil'),
(119, 1, '2025-09-07 15:52:30', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(120, 1, '2025-09-07 19:28:00', 'Login', 'Login Berhasil'),
(121, 1, '2025-09-07 20:53:51', 'Pembayaran', 'Input Pembayaran Berhasil'),
(122, 1, '2025-09-07 20:54:33', 'Pembayaran', 'Input Pembayaran Berhasil'),
(123, 1, '2025-09-07 20:58:56', 'Pembayaran', 'Input Pembayaran Berhasil'),
(124, 1, '2025-09-07 20:59:07', 'Pembayaran', 'Input Pembayaran Berhasil'),
(125, 1, '2025-09-07 21:01:25', 'Pembayaran', 'Input Pembayaran Berhasil'),
(126, 1, '2025-09-08 00:19:11', 'Login', 'Login Berhasil'),
(127, 1, '2025-09-08 02:52:53', 'Pembayaran', 'Hapus Pembayaran Berhasil'),
(128, 1, '2025-09-08 02:53:24', 'Pembayaran', 'Hapus Pembayaran Berhasil'),
(129, 1, '2025-09-08 02:53:42', 'Pembayaran', 'Hapus Pembayaran Berhasil'),
(130, 1, '2025-09-08 13:36:34', 'Login', 'Login Berhasil'),
(131, 1, '2025-09-08 14:13:56', 'Pembayaran', 'Hapus Pembayaran Berhasil'),
(132, 1, '2025-09-08 14:22:12', 'Pembayaran', 'Hapus Pembayaran Berhasil'),
(133, 1, '2025-09-08 15:28:16', 'Login', 'Login Berhasil'),
(135, 1, '2025-09-08 15:29:42', 'Setting', 'Setting Email'),
(136, 1, '2025-09-08 15:42:02', 'Setting', 'Setting Email'),
(137, 1, '2025-09-08 15:46:19', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(138, 1, '2025-09-08 15:46:40', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(139, 1, '2025-09-08 15:47:02', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(140, 1, '2025-09-08 15:47:34', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(141, 1, '2025-09-08 15:47:56', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(142, 1, '2025-09-08 16:12:52', 'Pembayaran', 'Input Pembayaran Berhasil'),
(143, 1, '2025-09-08 16:13:02', 'Pembayaran', 'Input Pembayaran Berhasil'),
(144, 1, '2025-09-08 19:46:33', 'Login', 'Login Berhasil'),
(145, 1, '2025-09-08 22:47:08', 'Login', 'Login Berhasil'),
(146, 1, '2025-09-08 23:49:53', 'Tagihan', 'Hapus Tagihan Berhasil'),
(147, 1, '2025-09-08 23:49:56', 'Tagihan', 'Hapus Tagihan Berhasil'),
(148, 1, '2025-09-08 23:57:18', 'Pembayaran', 'Input Pembayaran Berhasil'),
(149, 1, '2025-09-08 23:57:23', 'Pembayaran', 'Input Pembayaran Berhasil'),
(150, 1, '2025-09-08 23:57:33', 'Pembayaran', 'Input Pembayaran Berhasil'),
(151, 1, '2025-09-08 23:58:24', 'Pembayaran', 'Input Pembayaran Berhasil'),
(152, 1, '2025-09-08 23:58:43', 'Pembayaran', 'Input Pembayaran Berhasil'),
(153, 1, '2025-09-08 23:58:50', 'Pembayaran', 'Input Pembayaran Berhasil'),
(154, 1, '2025-09-09 00:00:11', 'Pembayaran', 'Input Pembayaran Berhasil'),
(155, 1, '2025-09-09 00:00:16', 'Pembayaran', 'Input Pembayaran Berhasil'),
(156, 1, '2025-09-09 00:00:22', 'Pembayaran', 'Input Pembayaran Berhasil'),
(157, 1, '2025-09-09 00:00:28', 'Pembayaran', 'Input Pembayaran Berhasil'),
(158, 1, '2025-09-09 02:14:06', 'Pembayaran', 'Hapus Pembayaran Berhasil'),
(159, 1, '2025-09-09 03:35:42', 'Akses', 'Input Fitur Akses'),
(160, 1, '2025-09-09 03:46:13', 'Entitas Akses', 'Edit Entitas Akses'),
(161, 1, '2025-09-09 04:13:35', 'Periode Akademik', 'Input Periode Akademik Akses'),
(162, 1, '2025-09-09 04:28:38', 'Periode Akademik', 'Input Periode Akademik Akses'),
(163, 1, '2025-09-09 04:40:23', 'Periode Akademik', 'Input Periode Akademik Akses'),
(164, 1, '2025-09-09 04:51:50', 'Tahun Akademik', 'Hapus Tahun Akademik Berhasil'),
(165, 1, '2025-09-09 16:51:27', 'Login', 'Login Berhasil'),
(166, 1, '2025-09-09 18:09:21', 'Periode Akademik', 'Update Periode Akademik ID 1'),
(167, 1, '2025-09-09 18:42:00', 'Periode Akademik', 'Update Periode Akademik ID 2'),
(168, 1, '2025-09-09 21:23:52', 'Login', 'Login Berhasil'),
(169, 2, '2025-09-09 21:25:36', 'Login', 'Login Berhasil'),
(170, 1, '2025-09-09 22:57:59', 'Kelas', 'Input Kelas Berhasil'),
(171, 1, '2025-09-09 22:58:11', 'Kelas', 'Input Kelas Berhasil'),
(172, 1, '2025-09-09 22:58:19', 'Kelas', 'Input Kelas Berhasil'),
(173, 1, '2025-09-09 23:03:27', 'Kelas', 'Update Kelas Berhasil'),
(174, 1, '2025-09-09 23:41:02', 'Kelas', 'Input Kelas Berhasil'),
(175, 1, '2025-09-09 23:41:12', 'Kelas', 'Input Kelas Berhasil'),
(176, 1, '2025-09-09 23:41:23', 'Kelas', 'Input Kelas Berhasil'),
(177, 1, '2025-09-09 23:41:28', 'Kelas', 'Input Kelas Berhasil'),
(178, 1, '2025-09-10 00:44:14', 'Kelas', 'Input Kelas Berhasil'),
(179, 1, '2025-09-10 00:52:15', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(180, 1, '2025-09-10 00:55:47', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(181, 1, '2025-09-10 00:59:59', 'Komponen Biaya', 'Hapus Komponen Biaya'),
(182, 1, '2025-09-10 01:00:42', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(183, 1, '2025-09-10 01:02:38', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(184, 1, '2025-09-10 01:03:27', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(185, 1, '2025-09-10 01:05:06', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(186, 1, '2025-09-10 01:19:39', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(187, 1, '2025-09-10 01:19:58', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(188, 1, '2025-09-10 01:20:41', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(189, 1, '2025-09-10 09:39:00', 'Login', 'Login Berhasil'),
(190, 1, '2025-09-10 10:40:59', 'Login', 'Login Berhasil'),
(191, 1, '2025-09-10 10:47:51', 'Siswa', 'Input Siswa Berhasil'),
(192, 1, '2025-09-10 10:51:12', 'Siswa', 'Edit Siswa Berhasil'),
(193, 1, '2025-09-10 12:59:41', 'Login', 'Login Berhasil'),
(194, 1, '2025-09-10 15:58:00', 'Login', 'Login Berhasil'),
(195, 1, '2025-09-11 12:28:06', 'Login', 'Login Berhasil'),
(196, 1, '2025-09-11 13:32:20', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(197, 1, '2025-09-11 13:32:27', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(198, 1, '2025-09-11 13:32:32', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(199, 1, '2025-09-11 13:32:39', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(200, 1, '2025-09-11 13:32:46', 'Komponen Biaya', 'Update Komponen Biaya Berhasil'),
(201, 1, '2025-09-11 13:33:21', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(202, 1, '2025-09-11 13:34:01', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(203, 1, '2025-09-11 13:35:13', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(204, 1, '2025-09-11 13:35:46', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(205, 1, '2025-09-11 13:36:17', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(206, 1, '2025-09-11 13:37:05', 'Komponen Biaya', 'Input Komponen Biaya Berhasil'),
(207, 1, '2025-09-11 14:28:54', 'Kelas', 'Input Kelas Berhasil'),
(208, 1, '2025-09-11 14:29:03', 'Kelas', 'Input Kelas Berhasil'),
(209, 1, '2025-09-11 14:29:10', 'Kelas', 'Input Kelas Berhasil'),
(210, 1, '2025-09-11 14:29:17', 'Kelas', 'Input Kelas Berhasil'),
(211, 1, '2025-09-11 14:29:27', 'Kelas', 'Input Kelas Berhasil'),
(212, 1, '2025-09-11 16:07:07', 'Login', 'Login Berhasil');

-- --------------------------------------------------------

--
-- Table structure for table `access_login`
--

DROP TABLE IF EXISTS `access_login`;
CREATE TABLE IF NOT EXISTS `access_login` (
  `id_access_login` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_access` int UNSIGNED NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `datetime_creat` datetime NOT NULL,
  `datetime_expired` datetime NOT NULL,
  PRIMARY KEY (`id_access_login`),
  KEY `access_login_id_access_index` (`id_access`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `access_login`
--

INSERT INTO `access_login` (`id_access_login`, `id_access`, `token`, `datetime_creat`, `datetime_expired`) VALUES
(38, 2, '95vO6eckafVViPIslhbUHDQY8B9Q0bbxsvSH', '2025-09-09 21:25:36', '2025-09-09 22:25:53'),
(44, 1, '1iWz1GjiQNpHAFimVjRkgLP3YKnFhJTuuYDf', '2025-09-11 16:07:07', '2025-09-11 19:18:12');

-- --------------------------------------------------------

--
-- Table structure for table `access_permission`
--

DROP TABLE IF EXISTS `access_permission`;
CREATE TABLE IF NOT EXISTS `access_permission` (
  `id_permission` int NOT NULL AUTO_INCREMENT,
  `id_access` int UNSIGNED NOT NULL,
  `id_access_feature` varchar(36) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id_permission`),
  KEY `id_access` (`id_access`),
  KEY `id_access_feature` (`id_access_feature`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `access_permission`
--

INSERT INTO `access_permission` (`id_permission`, `id_access`, `id_access_feature`) VALUES
(31, 7, 'jO3M0NopVQeXi4VuDHpvD9SRJzntpUGAe6Sw'),
(32, 7, 'lInyeHHg924zNLaXZ3SmjjnuyCOYBnUyUuTD'),
(33, 7, 'nSYinRWpCF9MHNUIlW7Up5vTip70gNNLlrqv'),
(34, 7, 'aziAs4ZofHmVooUohitYSojDp7oR2zbjrwpY'),
(35, 7, 'TleUu0waFsTCePkXuIqJuA1DDJ2hY3FGvzYX'),
(36, 7, 'Mt24BYzC76RJBEuHdY95bmMKrulttEQzblzH'),
(37, 7, 'a99AXGc0fRtw8wPbfCq16dmAfETaN5jZQc8R'),
(38, 7, 'mOFQURHvlxqXre9cyx7FMjFtzqc1zWb0x2RD'),
(96, 1, 'jO3M0NopVQeXi4VuDHpvD9SRJzntpUGAe6Sw'),
(97, 1, 'lInyeHHg924zNLaXZ3SmjjnuyCOYBnUyUuTD'),
(98, 1, 'nSYinRWpCF9MHNUIlW7Up5vTip70gNNLlrqv'),
(99, 1, 'Dnd2UZLzazCqJ9WfuzQKlIOpYueb2fXxNHXA'),
(100, 1, 'G2LxhMdVkih0ZJ4xPz8YGxVVCMLNmH0OnQvF'),
(101, 1, 'TIVPffUE3kqw288OB1R0CJ09daM9l2TLdGVv'),
(102, 1, 'aziAs4ZofHmVooUohitYSojDp7oR2zbjrwpY'),
(103, 1, 'TleUu0waFsTCePkXuIqJuA1DDJ2hY3FGvzYX'),
(104, 1, 'Mt24BYzC76RJBEuHdY95bmMKrulttEQzblzH'),
(105, 1, 'JbBByqDggzgIC8y6IH4JnbyynMUvHd0iFx5G'),
(106, 1, 'a99AXGc0fRtw8wPbfCq16dmAfETaN5jZQc8R'),
(107, 1, 'mOFQURHvlxqXre9cyx7FMjFtzqc1zWb0x2RD'),
(108, 2, 'jO3M0NopVQeXi4VuDHpvD9SRJzntpUGAe6Sw'),
(109, 2, 'lInyeHHg924zNLaXZ3SmjjnuyCOYBnUyUuTD'),
(110, 2, 'nSYinRWpCF9MHNUIlW7Up5vTip70gNNLlrqv'),
(111, 2, 'Dnd2UZLzazCqJ9WfuzQKlIOpYueb2fXxNHXA'),
(112, 2, 'G2LxhMdVkih0ZJ4xPz8YGxVVCMLNmH0OnQvF'),
(113, 2, 'TIVPffUE3kqw288OB1R0CJ09daM9l2TLdGVv'),
(114, 2, 'aziAs4ZofHmVooUohitYSojDp7oR2zbjrwpY'),
(115, 2, 'TleUu0waFsTCePkXuIqJuA1DDJ2hY3FGvzYX'),
(116, 2, 'Mt24BYzC76RJBEuHdY95bmMKrulttEQzblzH'),
(117, 2, 'JbBByqDggzgIC8y6IH4JnbyynMUvHd0iFx5G'),
(118, 2, 'a99AXGc0fRtw8wPbfCq16dmAfETaN5jZQc8R'),
(119, 2, 'mOFQURHvlxqXre9cyx7FMjFtzqc1zWb0x2RD');

-- --------------------------------------------------------

--
-- Table structure for table `access_reference`
--

DROP TABLE IF EXISTS `access_reference`;
CREATE TABLE IF NOT EXISTS `access_reference` (
  `id_access_reference` int NOT NULL AUTO_INCREMENT,
  `id_access_group` int NOT NULL,
  `id_access_feature` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id_access_reference`),
  KEY `id_access_group` (`id_access_group`),
  KEY `id_access_fitures` (`id_access_feature`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `access_reference`
--

INSERT INTO `access_reference` (`id_access_reference`, `id_access_group`, `id_access_feature`) VALUES
(15, 8, 'a99AXGc0fRtw8wPbfCq16dmAfETaN5jZQc8R'),
(16, 8, 'mOFQURHvlxqXre9cyx7FMjFtzqc1zWb0x2RD'),
(17, 2, 'jO3M0NopVQeXi4VuDHpvD9SRJzntpUGAe6Sw'),
(18, 2, 'lInyeHHg924zNLaXZ3SmjjnuyCOYBnUyUuTD'),
(19, 2, 'nSYinRWpCF9MHNUIlW7Up5vTip70gNNLlrqv'),
(20, 2, 'aziAs4ZofHmVooUohitYSojDp7oR2zbjrwpY'),
(21, 2, 'TleUu0waFsTCePkXuIqJuA1DDJ2hY3FGvzYX'),
(22, 2, 'Mt24BYzC76RJBEuHdY95bmMKrulttEQzblzH'),
(23, 2, 'a99AXGc0fRtw8wPbfCq16dmAfETaN5jZQc8R'),
(24, 2, 'mOFQURHvlxqXre9cyx7FMjFtzqc1zWb0x2RD'),
(33, 3, 'a99AXGc0fRtw8wPbfCq16dmAfETaN5jZQc8R'),
(34, 3, 'mOFQURHvlxqXre9cyx7FMjFtzqc1zWb0x2RD'),
(65, 1, 'jO3M0NopVQeXi4VuDHpvD9SRJzntpUGAe6Sw'),
(66, 1, 'lInyeHHg924zNLaXZ3SmjjnuyCOYBnUyUuTD'),
(67, 1, 'nSYinRWpCF9MHNUIlW7Up5vTip70gNNLlrqv'),
(68, 1, 'Dnd2UZLzazCqJ9WfuzQKlIOpYueb2fXxNHXA'),
(69, 1, 'G2LxhMdVkih0ZJ4xPz8YGxVVCMLNmH0OnQvF'),
(70, 1, 'TIVPffUE3kqw288OB1R0CJ09daM9l2TLdGVv'),
(71, 1, 'aziAs4ZofHmVooUohitYSojDp7oR2zbjrwpY'),
(72, 1, 'TleUu0waFsTCePkXuIqJuA1DDJ2hY3FGvzYX'),
(73, 1, 'Mt24BYzC76RJBEuHdY95bmMKrulttEQzblzH'),
(74, 1, 'JbBByqDggzgIC8y6IH4JnbyynMUvHd0iFx5G'),
(75, 1, 'a99AXGc0fRtw8wPbfCq16dmAfETaN5jZQc8R'),
(76, 1, 'mOFQURHvlxqXre9cyx7FMjFtzqc1zWb0x2RD');

-- --------------------------------------------------------

--
-- Table structure for table `app_configuration`
--

DROP TABLE IF EXISTS `app_configuration`;
CREATE TABLE IF NOT EXISTS `app_configuration` (
  `id_configuration` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `app_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `app_keyword` json NOT NULL,
  `app_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `app_favicon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `app_logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `app_base_url` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `app_author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `app_year` int NOT NULL,
  `app_company` json NOT NULL,
  PRIMARY KEY (`id_configuration`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `app_configuration`
--

INSERT INTO `app_configuration` (`id_configuration`, `app_title`, `app_keyword`, `app_description`, `app_favicon`, `app_logo`, `app_base_url`, `app_author`, `app_year`, `app_company`) VALUES
(1, 'PaySiswa v1.0', '[\"siswa\", \"sekolah\", \"pembayaran\", \"spp\"]', 'Mempermudah kelola SPP Siswa dengan Web-based application', '6299eff60cff413181f720f23d59c6.png', '3be8c5851c4c353d463d047f2ccb17.png', 'http://localhost/PaySiswa', 'Solihul Hadi', 2025, '{\"company_name\": \"An-Nur\", \"company_email\": \"annur123@gmail.com\", \"company_address\": \"Jalan RE Martadinata No.21 Ancaran Kuningan\", \"company_contact\": \"(0232) 876240\"}');

-- --------------------------------------------------------

--
-- Table structure for table `captcha`
--

DROP TABLE IF EXISTS `captcha`;
CREATE TABLE IF NOT EXISTS `captcha` (
  `id_captcha` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `captcha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `datetime_creat` datetime NOT NULL,
  `datetime_expired` datetime NOT NULL,
  PRIMARY KEY (`id_captcha`)
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `fee_by_class`
--

DROP TABLE IF EXISTS `fee_by_class`;
CREATE TABLE IF NOT EXISTS `fee_by_class` (
  `id_fee_by_class` int NOT NULL AUTO_INCREMENT,
  `id_organization_class` int NOT NULL,
  `id_fee_component` int NOT NULL,
  PRIMARY KEY (`id_fee_by_class`),
  KEY `id_organization_class` (`id_organization_class`),
  KEY `id_fee_component` (`id_fee_component`)
) ENGINE=InnoDB AUTO_INCREMENT=595 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `fee_by_student`
--

DROP TABLE IF EXISTS `fee_by_student`;
CREATE TABLE IF NOT EXISTS `fee_by_student` (
  `id_fee_by_student` int NOT NULL AUTO_INCREMENT,
  `id_organization_class` int NOT NULL,
  `id_student` int NOT NULL,
  `id_fee_component` int NOT NULL,
  `fee_nominal` decimal(12,2) NOT NULL,
  `fee_discount` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id_fee_by_student`),
  KEY `id_student` (`id_student`),
  KEY `id_fee_component` (`id_fee_component`),
  KEY `id_organization_class` (`id_organization_class`)
) ENGINE=InnoDB AUTO_INCREMENT=313 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `fee_component`
--

DROP TABLE IF EXISTS `fee_component`;
CREATE TABLE IF NOT EXISTS `fee_component` (
  `id_fee_component` int NOT NULL AUTO_INCREMENT,
  `id_academic_period` int NOT NULL,
  `component_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `component_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'SPP, Non-SPP',
  `periode_month` int DEFAULT NULL,
  `periode_year` int DEFAULT NULL,
  `periode_start` date NOT NULL,
  `periode_end` date NOT NULL,
  `fee_nominal` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id_fee_component`),
  KEY `id_academic_period` (`id_academic_period`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `help`
--

DROP TABLE IF EXISTS `help`;
CREATE TABLE IF NOT EXISTS `help` (
  `id_help` int NOT NULL AUTO_INCREMENT,
  `author` varchar(50) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `deskripsi` longtext NOT NULL,
  `datetime_creat` datetime NOT NULL,
  `datetime_update` datetime NOT NULL,
  `status` varchar(15) NOT NULL COMMENT 'Publish, Draft',
  PRIMARY KEY (`id_help`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organization_class`
--

DROP TABLE IF EXISTS `organization_class`;
CREATE TABLE IF NOT EXISTS `organization_class` (
  `id_organization_class` int NOT NULL AUTO_INCREMENT,
  `id_academic_period` int NOT NULL,
  `class_level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Contoh : 1,2,3',
  `class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id_organization_class`),
  KEY `id_academic_period` (`id_academic_period`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `id_payment` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `id_student` int NOT NULL,
  `id_organization_class` int NOT NULL,
  `id_fee_component` int NOT NULL,
  `payment_datetime` datetime NOT NULL,
  `payment_nominal` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id_payment`),
  KEY `id_student` (`id_student`),
  KEY `id_organization_class` (`id_organization_class`),
  KEY `id_fee_component` (`id_fee_component`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `setting_email_gateway`
--

DROP TABLE IF EXISTS `setting_email_gateway`;
CREATE TABLE IF NOT EXISTS `setting_email_gateway` (
  `id_setting_email_gateway` int NOT NULL AUTO_INCREMENT,
  `email_gateway` text CHARACTER SET latin1,
  `password_gateway` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `url_provider` text CHARACTER SET latin1,
  `port_gateway` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `nama_pengirim` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `url_service` text CHARACTER SET latin1 NOT NULL,
  `validasi_email` varchar(10) CHARACTER SET latin1 NOT NULL,
  `redirect_validasi` text CHARACTER SET latin1 NOT NULL,
  `pesan_validasi_email` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id_setting_email_gateway`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `setting_payment`
--

DROP TABLE IF EXISTS `setting_payment`;
CREATE TABLE IF NOT EXISTS `setting_payment` (
  `id_setting_payment` int NOT NULL AUTO_INCREMENT,
  `api_payment_url` text,
  `urll_call_back` text,
  `url_status` text NOT NULL,
  `api_key` text,
  `id_marchant` text,
  `client_key` text,
  `server_key` text,
  `snap_url` text,
  `production` varchar(10) NOT NULL,
  `aktif_payment_gateway` varchar(10) NOT NULL COMMENT 'Ya,Tidak',
  PRIMARY KEY (`id_setting_payment`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `id_student` int NOT NULL AUTO_INCREMENT,
  `id_organization_class` int DEFAULT NULL,
  `student_nis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'nomor induk siswa (sekolah)',
  `student_nisn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Nomor induk siswa (nasional)',
  `student_name` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `student_gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Male/Female',
  `place_of_birth` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `student_contact` int DEFAULT NULL,
  `student_email` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `student_address` text COLLATE utf8mb4_bin,
  `student_foto` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `student_parent` json DEFAULT NULL,
  `student_registered` date NOT NULL,
  `student_status` varchar(255) COLLATE utf8mb4_bin NOT NULL COMMENT 'Terdaftar,\r\nLulus,\r\nKeluar',
  PRIMARY KEY (`id_student`),
  KEY `id_organization_class` (`id_organization_class`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access`
--
ALTER TABLE `access`
  ADD CONSTRAINT `access_to_group` FOREIGN KEY (`id_access_group`) REFERENCES `access_group` (`id_access_group`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `access_log`
--
ALTER TABLE `access_log`
  ADD CONSTRAINT `access_log_id_access_foreign` FOREIGN KEY (`id_access`) REFERENCES `access` (`id_access`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `access_login`
--
ALTER TABLE `access_login`
  ADD CONSTRAINT `access_login_id_access_foreign` FOREIGN KEY (`id_access`) REFERENCES `access` (`id_access`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `access_permission`
--
ALTER TABLE `access_permission`
  ADD CONSTRAINT `permission_to_access` FOREIGN KEY (`id_access`) REFERENCES `access` (`id_access`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `permission_to_features` FOREIGN KEY (`id_access_feature`) REFERENCES `access_feature` (`id_access_feature`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `access_reference`
--
ALTER TABLE `access_reference`
  ADD CONSTRAINT `reference_to_feature` FOREIGN KEY (`id_access_feature`) REFERENCES `access_feature` (`id_access_feature`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reference_to_group` FOREIGN KEY (`id_access_group`) REFERENCES `access_group` (`id_access_group`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fee_by_class`
--
ALTER TABLE `fee_by_class`
  ADD CONSTRAINT `fee_class_component` FOREIGN KEY (`id_fee_component`) REFERENCES `fee_component` (`id_fee_component`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_class_organization` FOREIGN KEY (`id_organization_class`) REFERENCES `organization_class` (`id_organization_class`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fee_by_student`
--
ALTER TABLE `fee_by_student`
  ADD CONSTRAINT `fee_component_component` FOREIGN KEY (`id_fee_component`) REFERENCES `fee_component` (`id_fee_component`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_student_student` FOREIGN KEY (`id_student`) REFERENCES `student` (`id_student`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fee_to_class` FOREIGN KEY (`id_organization_class`) REFERENCES `organization_class` (`id_organization_class`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fee_component`
--
ALTER TABLE `fee_component`
  ADD CONSTRAINT `fee_component_to_academic_period ` FOREIGN KEY (`id_academic_period`) REFERENCES `academic_period` (`id_academic_period`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `organization_class`
--
ALTER TABLE `organization_class`
  ADD CONSTRAINT `class_to_academic` FOREIGN KEY (`id_academic_period`) REFERENCES `academic_period` (`id_academic_period`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_class` FOREIGN KEY (`id_organization_class`) REFERENCES `organization_class` (`id_organization_class`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_to_component` FOREIGN KEY (`id_fee_component`) REFERENCES `fee_component` (`id_fee_component`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_to_student` FOREIGN KEY (`id_student`) REFERENCES `student` (`id_student`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_to_class` FOREIGN KEY (`id_organization_class`) REFERENCES `organization_class` (`id_organization_class`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
