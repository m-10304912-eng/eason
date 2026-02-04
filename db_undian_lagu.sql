-- Database: `db_undian_lagu`

CREATE DATABASE IF NOT EXISTS `db_undian_lagu`;
USE `db_undian_lagu`;

-- --------------------------------------------------------
-- Table structure for table `kategori`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `kategori` (
  `id_kategori` varchar(5) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `kategori` (`id_kategori`, `kategori`) VALUES
('K1', 'Menengah atas'),
('K2', 'Menengah rendah');

-- --------------------------------------------------------
-- Table structure for table `lagu`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lagu` (
  `id_lagu` varchar(5) NOT NULL,
  `nama_lagu` varchar(100) NOT NULL,
  `video` varchar(100) NOT NULL,
  PRIMARY KEY (`id_lagu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `lagu` (`id_lagu`, `nama_lagu`, `video`) VALUES
('L1', 'Golden', 'golden.mp4'),
('L2', 'Flower', 'flower.mp4'),
('L4', 'Payphone', 'payphone.mp4'),
('L5', 'Blue', 'blue.mp4');

-- --------------------------------------------------------
-- Table structure for table `pengundi`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pengundi` (
  `nokp` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`nokp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `pengundi` (`nokp`, `nama`, `password`) VALUES
('091023070189', 'Hee Sen', '12345'),
('100819070345', 'Aun Ren', '12345'),
('120304070257', 'Tee Chu', '12345');

-- --------------------------------------------------------
-- Table structure for table `admins`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `admins` (`username`, `password`) VALUES
('admin', '12345');

-- --------------------------------------------------------
-- Table structure for table `undian`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `undian` (
  `id_undi` int(11) NOT NULL AUTO_INCREMENT,
  `nokp` varchar(20) DEFAULT NULL,
  `id_kategori` varchar(5) DEFAULT NULL,
  `id_lagu` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id_undi`),
  KEY `nokp` (`nokp`),
  KEY `id_kategori` (`id_kategori`),
  KEY `id_lagu` (`id_lagu`),
  CONSTRAINT `undian_ibfk_1` FOREIGN KEY (`nokp`) REFERENCES `pengundi` (`nokp`),
  CONSTRAINT `undian_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`),
  CONSTRAINT `undian_ibfk_3` FOREIGN KEY (`id_lagu`) REFERENCES `lagu` (`id_lagu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `undian` (`id_undi`, `nokp`, `id_kategori`, `id_lagu`) VALUES
(1, '091023070189', 'K1', 'L1'),
(2, '091023070189', 'K2', 'L5'),
(3, '100819070345', 'K1', 'L2'),
(4, '100819070345', 'K2', 'L5'),
(5, '120304070257', 'K1', 'L1'),
(6, '120304070257', 'K2', 'L4');