-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 30, 2026 at 06:05 PM
-- Server version: 9.1.0
-- PHP Version: 7.4.33

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
  `academic_period` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `academic_period_start` date NOT NULL,
  `academic_period_end` date NOT NULL,
  `academic_period_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_academic_period`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `access`
--

DROP TABLE IF EXISTS `access`;
CREATE TABLE IF NOT EXISTS `access` (
  `id_access` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_access_group` int DEFAULT NULL,
  `access_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `access_foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `access_client` tinyint(1) NOT NULL COMMENT 'If true, the account is a client.',
  PRIMARY KEY (`id_access`),
  KEY `id_access_group` (`id_access_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `access_feature`
--

DROP TABLE IF EXISTS `access_feature`;
CREATE TABLE IF NOT EXISTS `access_feature` (
  `id_access_feature` varchar(36) COLLATE utf8mb4_bin NOT NULL,
  `feature_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `feature_category` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `feature_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `datetime_creat` timestamp NOT NULL,
  PRIMARY KEY (`id_access_feature`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `access_group`
--

DROP TABLE IF EXISTS `access_group`;
CREATE TABLE IF NOT EXISTS `access_group` (
  `id_access_group` int NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_access_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `access_log`
--

DROP TABLE IF EXISTS `access_log`;
CREATE TABLE IF NOT EXISTS `access_log` (
  `id_access_log` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_access` int UNSIGNED NOT NULL,
  `log_datetime` datetime NOT NULL,
  `log_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `log_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_access_log`),
  KEY `access_log_id_access_index` (`id_access`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `access_reset`
--

DROP TABLE IF EXISTS `access_reset`;
CREATE TABLE IF NOT EXISTS `access_reset` (
  `id_access_reset` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_access` int UNSIGNED NOT NULL,
  `datetime_creat` datetime NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id_access_reset`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `auth_payment`
--

DROP TABLE IF EXISTS `auth_payment`;
CREATE TABLE IF NOT EXISTS `auth_payment` (
  `id_auth_payment` int NOT NULL AUTO_INCREMENT,
  `id_setting_payment` int NOT NULL,
  `x_token` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `datetime_creat` datetime NOT NULL,
  `datetime_expired` datetime NOT NULL,
  PRIMARY KEY (`id_auth_payment`),
  KEY `id_setting_payment` (`id_setting_payment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `fee_component`
--

DROP TABLE IF EXISTS `fee_component`;
CREATE TABLE IF NOT EXISTS `fee_component` (
  `id_fee_component` int NOT NULL AUTO_INCREMENT,
  `id_academic_period` int NOT NULL,
  `component_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'SPP, Non-SPP',
  `periode_month` int DEFAULT NULL,
  `periode_year` int DEFAULT NULL,
  `periode_start` date NOT NULL,
  `periode_end` date NOT NULL,
  `fee_nominal` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id_fee_component`),
  KEY `id_academic_period` (`id_academic_period`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `organization_class`
--

DROP TABLE IF EXISTS `organization_class`;
CREATE TABLE IF NOT EXISTS `organization_class` (
  `id_organization_class` int NOT NULL AUTO_INCREMENT,
  `id_academic_period` int NOT NULL,
  `class_level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contoh : 1,2,3',
  `class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_organization_class`),
  KEY `id_academic_period` (`id_academic_period`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `id_payment` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `id_fee_by_student` int NOT NULL,
  `id_student` int NOT NULL,
  `id_organization_class` int NOT NULL,
  `id_fee_component` int NOT NULL,
  `payment_datetime` datetime NOT NULL,
  `payment_nominal` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id_payment`),
  KEY `id_student` (`id_student`),
  KEY `id_organization_class` (`id_organization_class`),
  KEY `id_fee_component` (`id_fee_component`),
  KEY `id_fee_by_student` (`id_fee_by_student`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `payment_request`
--

DROP TABLE IF EXISTS `payment_request`;
CREATE TABLE IF NOT EXISTS `payment_request` (
  `id_payment_request` int NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `id_fee_by_student` int NOT NULL,
  `id_student` int NOT NULL,
  `id_organization_class` int NOT NULL,
  `id_fee_component` int NOT NULL,
  `request_datetime` date NOT NULL,
  `request_expired` date NOT NULL,
  `id_payment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Jika terisi maka lunas',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Status Dari PG',
  PRIMARY KEY (`id_payment_request`),
  KEY `request_fee_by_student` (`id_fee_by_student`),
  KEY `request_fee_component` (`id_fee_component`),
  KEY `request_organization_class` (`id_organization_class`),
  KEY `request_to_student` (`id_student`),
  KEY `request_to_payment` (`id_payment`)
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `setting_payment`
--

DROP TABLE IF EXISTS `setting_payment`;
CREATE TABLE IF NOT EXISTS `setting_payment` (
  `id_setting_payment` int NOT NULL AUTO_INCREMENT,
  `api_payment_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `USER_KEY` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `SECRET_KEY` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id_setting_payment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `id_student` int NOT NULL AUTO_INCREMENT,
  `id_organization_class` int DEFAULT NULL,
  `student_nis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'nomor induk siswa (sekolah)',
  `student_nisn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nomor induk siswa (nasional)',
  `student_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Male/Female',
  `place_of_birth` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `student_contact` int DEFAULT NULL,
  `student_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `student_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `student_foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `student_parent` json DEFAULT NULL,
  `student_registered` date NOT NULL,
  `student_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Terdaftar,\r\nLulus,\r\nKeluar',
  PRIMARY KEY (`id_student`),
  KEY `id_organization_class` (`id_organization_class`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

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
-- Constraints for table `auth_payment`
--
ALTER TABLE `auth_payment`
  ADD CONSTRAINT `auth_to_setting` FOREIGN KEY (`id_setting_payment`) REFERENCES `setting_payment` (`id_setting_payment`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `payment_to_fee_by_student` FOREIGN KEY (`id_fee_by_student`) REFERENCES `fee_by_student` (`id_fee_by_student`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_to_student` FOREIGN KEY (`id_student`) REFERENCES `student` (`id_student`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_request`
--
ALTER TABLE `payment_request`
  ADD CONSTRAINT `request_fee_by_student` FOREIGN KEY (`id_fee_by_student`) REFERENCES `fee_by_student` (`id_fee_by_student`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `request_fee_component` FOREIGN KEY (`id_fee_component`) REFERENCES `fee_component` (`id_fee_component`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `request_organization_class` FOREIGN KEY (`id_organization_class`) REFERENCES `organization_class` (`id_organization_class`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `request_to_payment` FOREIGN KEY (`id_payment`) REFERENCES `payment` (`id_payment`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `request_to_student` FOREIGN KEY (`id_student`) REFERENCES `student` (`id_student`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_to_class` FOREIGN KEY (`id_organization_class`) REFERENCES `organization_class` (`id_organization_class`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
