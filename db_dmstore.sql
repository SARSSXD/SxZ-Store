-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.7.0.6850
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for webdmstore
CREATE DATABASE IF NOT EXISTS `webdmstore` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `webdmstore`;

-- Dumping structure for event webdmstore.autoTransaksi
DELIMITER //
CREATE EVENT `autoTransaksi` ON SCHEDULE EVERY 1 SECOND STARTS '2024-06-08 13:55:46' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
UPDATE transaksi
SET status = 'cancelled'
WHERE status = 'pending' AND TIMESTAMPDIFF(HOUR, tanggal, NOW()) >= 3;
DELETE FROM promo WHERE kadaluwarsa < CURDATE();
END//
DELIMITER ;

-- Dumping structure for table webdmstore.bukti
CREATE TABLE IF NOT EXISTS `bukti` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaksiID` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `userID` int DEFAULT NULL,
  `amount` int NOT NULL DEFAULT '0',
  `tanggalBayar` timestamp NULL DEFAULT (now()),
  `buktiBayar` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaksiID` (`transaksiID`),
  KEY `userID` (`userID`),
  CONSTRAINT `FK_bukti_transaksi` FOREIGN KEY (`transaksiID`) REFERENCES `transaksi` (`noInvoice`),
  CONSTRAINT `FK_payment_users` FOREIGN KEY (`userID`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webdmstore.bukti: ~8 rows (approximately)
INSERT INTO `bukti` (`id`, `transaksiID`, `userID`, `amount`, `tanggalBayar`, `buktiBayar`) VALUES
	(1, 'SAR20240608141401', 2, 150000, '2024-06-08 16:14:26', '1.jpg'),
	(3, 'SAR20240608141408', NULL, 123123, '2024-06-09 12:08:12', '213.jpg'),
	(4, 'SAR20240609180333', NULL, 52571287, '2024-06-08 17:00:00', '4.jpeg'),
	(5, 'SAR20240609195221', NULL, 159223, '2024-06-08 17:00:00', '5.png'),
	(6, 'SAR20240610103523', 2, 500000, '2024-06-09 17:00:00', '6.png'),
	(7, 'SAR20240610104322', NULL, 500000, '2024-06-10 03:52:03', '7.png'),
	(8, 'SAR20240610104322', NULL, 20000, '2024-06-10 03:53:22', '8.jpeg'),
	(9, 'SAR20240610112645', NULL, 100, '2024-06-10 04:27:24', '9.jpeg'),
	(10, 'SAR20240613111432', 6, 45000, '2024-06-13 04:15:06', '10.jpg'),
	(11, 'SAR20240613165501', 2, 1000000, '2024-06-13 09:55:41', '11.jpg'),
	(12, 'SAR20240613221425', NULL, 500000, '2024-06-13 15:15:11', '12.gif');

-- Dumping structure for table webdmstore.databeli
CREATE TABLE IF NOT EXISTS `databeli` (
  `id` int NOT NULL AUTO_INCREMENT,
  `itemID` int DEFAULT NULL,
  `nominalID` int DEFAULT NULL,
  `dataAkun` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlahBeli` int DEFAULT NULL,
  `jenisBayar` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `promoID` int DEFAULT NULL,
  `kontak` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kategoriID` (`itemID`) USING BTREE,
  KEY `nominal` (`nominalID`),
  KEY `promoID` (`promoID`),
  CONSTRAINT `FK_databeli_item` FOREIGN KEY (`itemID`) REFERENCES `item` (`id`),
  CONSTRAINT `FK_databeli_nominal` FOREIGN KEY (`nominalID`) REFERENCES `nominal` (`id`),
  CONSTRAINT `FK_databeli_promo` FOREIGN KEY (`promoID`) REFERENCES `promo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webdmstore.databeli: ~21 rows (approximately)
INSERT INTO `databeli` (`id`, `itemID`, `nominalID`, `dataAkun`, `jumlahBeli`, `jenisBayar`, `promoID`, `kontak`) VALUES
	(1, 1, 2, '170898982(12321)', 3, 'QRIS', 1, '085159690099123'),
	(2, 1, 2, '170898982(12321)', 3, 'QRIS', 1, '085159690099123'),
	(3, 1, 2, '170898982(12321)', 3, 'QRIS', 1, '085159690099123'),
	(4, 1, 2, '170898982(12321)', 3, 'QRIS', 1, '085159690099123'),
	(7, 1, 1, '213213123123', 2, 'QRIS', NULL, '087123612313'),
	(8, 1, 2, '170898982(12321)', 2, 'QRIS', 1, '085159690099123'),
	(9, 1, 2, '170898982(12321)', 2, 'QRIS', NULL, '085159690099123'),
	(16, 1, 6, '123123214123', 3, 'QRIS', NULL, '08512412313'),
	(17, 1, 2, '170898982(12321)', 2, 'QRIS', NULL, '085159690099123'),
	(18, 1, 2, '170898982(12321)', 2, 'QRIS', NULL, '085159690099123'),
	(19, 1, 2, '170898982(12321)', 2, 'QRIS', NULL, '085159690099123'),
	(20, 1, 2, '170898982(12321)', 3, 'QRIS', NULL, '085159690099123'),
	(21, 1, 2, '170898982(12321)', 2, 'QRIS', NULL, '085159690099123'),
	(22, 1, 2, '170898982(12321)', 5, 'Convenience Store', NULL, '085159690099123'),
	(23, 1, 2, '170898982(12321)', 2, 'QRIS', 1, '085159690099123'),
	(24, 1, 2, '170898982(12321)', 1, 'Transfer Bank', NULL, '085159690099123'),
	(25, 1, 2, '170898982(12321)', 2, 'Transfer Bank', NULL, '085159690099123'),
	(26, 1, 2, '170898982(12321)', 1, 'Transfer Bank', NULL, '085159690099123'),
	(27, 1, 2, '170898982(12321)', 2, 'Transfer Bank', NULL, '085159690099123'),
	(28, 1, 2, '170898982(12321)', 2, 'Transfer Bank', NULL, '085159690099123'),
	(29, 1, 7, '170898982(12321)', 2, 'Transfer Bank', NULL, '085159690099123'),
	(30, 6, 15, '170898982(2879)', 10, 'QRIS', 1, '085159690099'),
	(31, 1, 1, '170898982(2879)', 2, 'QRIS', 1, '085159690099123'),
	(32, 1, 1, '170898982(2879)', 3, 'QRIS', NULL, '09796867');

-- Dumping structure for table webdmstore.item
CREATE TABLE IF NOT EXISTS `item` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webdmstore.item: ~5 rows (approximately)
INSERT INTO `item` (`id`, `nama`, `kategori`, `gambar`) VALUES
	(1, 'Mobile Legend', 'Topup', '1.jpeg'),
	(2, 'PUBG Mobile', 'Topup', '2.png'),
	(3, 'Free Fire', 'Topup', '3.png'),
	(4, 'COD Mobile', 'Topup', '4.png'),
	(5, 'Netflix', 'Apps', '5.png'),
	(6, 'JOKI RANK MLBB', 'Joki', '6.jpg');

-- Dumping structure for table webdmstore.leaderboard
CREATE TABLE IF NOT EXISTS `leaderboard` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userID` int DEFAULT NULL,
  `totalBeli` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  CONSTRAINT `FK_leaderboard_users` FOREIGN KEY (`userID`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webdmstore.leaderboard: ~3 rows (approximately)
INSERT INTO `leaderboard` (`id`, `userID`, `totalBeli`) VALUES
	(1, 4, 0),
	(2, 5, 0),
	(3, 2, 2250000),
	(4, 6, 90000);

-- Dumping structure for table webdmstore.nominal
CREATE TABLE IF NOT EXISTS `nominal` (
  `id` int NOT NULL AUTO_INCREMENT,
  `itemID` int DEFAULT NULL,
  `nominal` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `harga` int NOT NULL DEFAULT (0),
  `gambar` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `itemID` (`itemID`),
  CONSTRAINT `FK_nominal_item` FOREIGN KEY (`itemID`) REFERENCES `item` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webdmstore.nominal: ~16 rows (approximately)
INSERT INTO `nominal` (`id`, `itemID`, `nominal`, `harga`, `gambar`) VALUES
	(1, 1, '1000 Diamonds', 250000, '1.png'),
	(2, 1, '2000 diamonds', 500000, '2.png'),
	(3, 1, '10 Diamonds', 3000, '3.png'),
	(4, 1, '20 Diamonds', 6000, '4.png'),
	(5, 1, '50 Diamonds', 14000, '5.png'),
	(6, 1, '100 Diamonds', 27500, '6.png'),
	(7, 1, '30 Diamonds', 7000, '7.png'),
	(8, 1, '40 Diamonds', 12000, '8.png'),
	(9, 1, '200 Diamonds', 50000, '9.png'),
	(11, 4, '30 CP', 5000, '11.png'),
	(12, 4, '63 CP', 10000, '12.png'),
	(13, 4, '128 CP', 20000, '13.png'),
	(14, 5, 'NETFLIX PREMIUM 1 bulan', 30000, '14.png'),
	(15, 6, 'Grandmaster (per star)', 5000, '15.png'),
	(16, 2, '60 UC Indo', 15000, '16.png'),
	(17, 3, '75 Diamonds', 10500, '17.png');

-- Dumping structure for table webdmstore.notifikasi
CREATE TABLE IF NOT EXISTS `notifikasi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `userID` int DEFAULT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci,
  `tglSukses` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userID` (`userID`),
  CONSTRAINT `FK_notifikasi_users` FOREIGN KEY (`userID`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webdmstore.notifikasi: ~0 rows (approximately)

-- Dumping structure for table webdmstore.promo
CREATE TABLE IF NOT EXISTS `promo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `minimBeli` int DEFAULT NULL,
  `kadaluwarsa` date DEFAULT (curdate()),
  `persen` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webdmstore.promo: ~2 rows (approximately)
INSERT INTO `promo` (`id`, `nama`, `minimBeli`, `kadaluwarsa`, `persen`) VALUES
	(1, 'HematParah', 10000, '2024-06-30', 10),
	(2, 'Rada Hemat', 5000, '2024-06-30', 5);

-- Dumping structure for table webdmstore.report
CREATE TABLE IF NOT EXISTS `report` (
  `id` int NOT NULL AUTO_INCREMENT,
  `jenis` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `kontak` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webdmstore.report: ~0 rows (approximately)
INSERT INTO `report` (`id`, `jenis`, `nama`, `deskripsi`, `kontak`) VALUES
	(1, 'Masalah Akun', 'Kautsar', 'asdsadsadsadasdsad', '085159690099123');

-- Dumping structure for table webdmstore.review
CREATE TABLE IF NOT EXISTS `review` (
  `id` int NOT NULL AUTO_INCREMENT,
  `noInvoice` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nilai` int DEFAULT NULL,
  `komentar` text COLLATE utf8mb4_unicode_ci,
  `tglKomen` timestamp NULL DEFAULT (now()),
  PRIMARY KEY (`id`),
  KEY `noInvoice` (`noInvoice`),
  CONSTRAINT `FK_review_transaksi` FOREIGN KEY (`noInvoice`) REFERENCES `transaksi` (`noInvoice`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webdmstore.review: ~2 rows (approximately)
INSERT INTO `review` (`id`, `noInvoice`, `nilai`, `komentar`, `tglKomen`) VALUES
	(1, 'SAR20240609195221', 5, 'SANGAT BAGUS 5 BINTANG', '2024-06-10 07:44:51'),
	(2, 'SAR20240610103523', 5, 'MANTAPPP', '2024-06-10 08:24:59'),
	(3, 'SAR20240613111432', 4, 'Terpercaya tapi sedikit slow respon', '2024-06-13 04:17:25');

-- Dumping structure for table webdmstore.transaksi
CREATE TABLE IF NOT EXISTS `transaksi` (
  `noInvoice` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `userID` int DEFAULT NULL,
  `dataBeliID` int DEFAULT NULL,
  `itemID` int DEFAULT NULL,
  `totalBayar` int DEFAULT NULL,
  `tanggal` timestamp NULL DEFAULT (now()),
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buktiID` int DEFAULT NULL,
  PRIMARY KEY (`noInvoice`) USING BTREE,
  KEY `userID` (`userID`),
  KEY `gameID` (`dataBeliID`) USING BTREE,
  KEY `itemID` (`itemID`),
  KEY `buktiID` (`buktiID`),
  CONSTRAINT `FK_transaksi_bukti` FOREIGN KEY (`buktiID`) REFERENCES `bukti` (`id`),
  CONSTRAINT `FK_transaksi_databeli` FOREIGN KEY (`dataBeliID`) REFERENCES `databeli` (`id`),
  CONSTRAINT `FK_transaksi_item` FOREIGN KEY (`itemID`) REFERENCES `item` (`id`),
  CONSTRAINT `FK_transaksi_users` FOREIGN KEY (`userID`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webdmstore.transaksi: ~15 rows (approximately)
INSERT INTO `transaksi` (`noInvoice`, `userID`, `dataBeliID`, `itemID`, `totalBayar`, `tanggal`, `status`, `buktiID`) VALUES
	('SAR20240608141401', 2, 1, 1, 1350000, '2024-06-08 07:14:01', 'successful', 1),
	('SAR20240608141408', 2, 2, 1, 1350000, '2024-06-08 03:14:08', 'cancelled', NULL),
	('SAR20240608141849', 2, 3, 1, 1350000, '2024-06-08 03:18:49', 'cancelled', NULL),
	('SAR20240608145600', 2, 4, 1, 1350000, '2024-06-08 07:56:00', 'cancelled', NULL),
	('SAR20240609002051', 2, 8, 1, 900000, '2024-06-08 17:20:51', 'successful', NULL),
	('SAR20240609004106', NULL, 18, 1, 1000000, '2024-06-08 17:41:06', 'cancelled', NULL),
	('SAR20240609082202', NULL, 19, 1, 1000000, '2024-06-09 01:22:02', 'cancelled', NULL),
	('SAR20240609180333', NULL, 20, 1, 1500000, '2024-06-09 11:03:33', 'successful', 4),
	('SAR20240609195221', NULL, 21, 1, 1000000, '2024-06-09 12:52:21', 'successful', 5),
	('SAR20240609235412', NULL, 22, 1, 2500000, '2024-06-09 13:54:12', 'cancelled', NULL),
	('SAR20240610103523', NULL, 23, 1, 900000, '2024-06-10 03:35:23', 'successful', 6),
	('SAR20240610104322', NULL, 24, 1, 500000, '2024-06-10 03:43:22', 'cancelled', 8),
	('SAR20240610112645', NULL, 25, 1, 1000000, '2024-06-10 04:26:45', 'cancelled', 9),
	('SAR20240610112802', NULL, 26, 1, 500000, '2024-06-10 04:28:02', 'cancelled', NULL),
	('SAR20240610114139', NULL, 27, 1, 1000000, '2024-06-10 04:41:39', 'cancelled', NULL),
	('SAR20240610115206', 2, 28, 1, 1000000, '2024-06-10 04:52:06', 'cancelled', NULL),
	('SAR20240610121000', NULL, 29, 1, 14000, '2024-06-12 23:10:00', 'successful', NULL),
	('SAR20240613111432', 6, 30, 6, 45000, '2024-06-13 04:14:32', 'successful', 10),
	('SAR20240613165501', 2, 31, 1, 450000, '2024-06-13 09:55:01', 'cancelled', 11),
	('SAR20240613221425', NULL, 32, 1, 750000, '2024-06-13 15:14:25', 'successful', 12);

-- Dumping structure for table webdmstore.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table webdmstore.users: ~4 rows (approximately)
INSERT INTO `users` (`id`, `name`, `username`, `password`, `email`) VALUES
	(1, 'ADMIN', 'admin', '$2y$10$p3XWJeJLHDwLb7sSGiXDhuw6CnWWAYv.3U8NZs/EFfqfci5G4yGue', 'admin@gmail.com'),
	(2, 'Kautsar Quraisy Al Hamidy', 'sar', '$2y$10$0jJwfxi49d/LkvKTOocvOuE.546mx9KfYM5AKhk5MSvkC866ulVAu', 'kasyim15@gmail.com'),
	(3, 'user', 'username', '$2y$10$YKbR9LaF/ZxaqyiTf646Z.ZbOkpmqx/SH29XtJvQWtECg.rXRF1dq', 'user@gmail.com'),
	(4, 'user', 'username', '$2y$10$bEkFWfJDVht87SrfBfQaGurryvBvam35uzmvfT6u802l/nkUrSpfG', 'user@gmail.com'),
	(5, 'user1', 'username1', '$2y$10$6vw32v/yxyGQOZdKPHD12u1EhL2zJMwrn5Src3LfLEi0zJV53vR0O', 'user1@gmail.com'),
	(6, 'LAST CEK ', 'cek', '$2y$10$zRXMtfXhsA5LXYa/GZPc6u9LMU6.E6TJiZcJHX7H1pKzjGEKSrptG', 'cek@gmail.com');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
