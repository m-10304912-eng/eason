-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2026 at 03:18 AM
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
-- Database: `db_undian_lagu`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '12345'),
(2, 'admin', '12345'),
(3, 'admin', '12345'),
(4, 'admin', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` varchar(5) NOT NULL,
  `kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `kategori`) VALUES
('K1', 'Menengah atas'),
('K2', 'Menengah rendah');

-- --------------------------------------------------------

--
-- Table structure for table `lagu`
--

CREATE TABLE `lagu` (
  `id_lagu` varchar(5) NOT NULL,
  `nama_lagu` varchar(100) NOT NULL,
  `video` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lagu`
--

INSERT INTO `lagu` (`id_lagu`, `nama_lagu`, `video`) VALUES
('L1', 'Golden', 'golden.mp4'),
('L2', 'Flower', 'flower.mp4'),
('L4', 'Payphone', 'payphone.mp4'),
('L5', 'Blue', 'blue.mp4');

-- --------------------------------------------------------

--
-- Table structure for table `pengundi`
--

CREATE TABLE `pengundi` (
  `nokp` varchar(12) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT '12345'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengundi`
--

INSERT INTO `pengundi` (`nokp`, `nama`, `password`) VALUES
('090212070185', 'Khaw Eason', '12345'),
('091023070189', 'Hee Sen', '12345'),
('100819070345', 'Aun Ren', '12345'),
('120304070257', 'Tee Chu', '12345'),
('124312342343', 'Lim ZI Shen', '12345'),
('234143344422', 'LEE JIA JIE', '11111');

-- --------------------------------------------------------

--
-- Table structure for table `undian`
--

CREATE TABLE `undian` (
  `id_undi` int(11) NOT NULL,
  `nokp` varchar(12) DEFAULT NULL,
  `id_kategori` varchar(5) DEFAULT NULL,
  `id_lagu` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `undian`
--

INSERT INTO `undian` (`id_undi`, `nokp`, `id_kategori`, `id_lagu`) VALUES
(0, '090212070185', 'K1', 'L5'),
(1, '091023070189', 'K1', 'L1'),
(2, '091023070189', 'K2', 'L5'),
(3, '100819070345', 'K1', 'L2'),
(4, '100819070345', 'K2', 'L5'),
(5, '120304070257', 'K1', 'L1'),
(6, '120304070257', 'K2', 'L4');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `lagu`
--
ALTER TABLE `lagu`
  ADD PRIMARY KEY (`id_lagu`);

--
-- Indexes for table `pengundi`
--
ALTER TABLE `pengundi`
  ADD PRIMARY KEY (`nokp`);

--
-- Indexes for table `undian`
--
ALTER TABLE `undian`
  ADD PRIMARY KEY (`id_undi`),
  ADD KEY `nokp` (`nokp`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_lagu` (`id_lagu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `undian`
--
ALTER TABLE `undian`
  ADD CONSTRAINT `undian_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`),
  ADD CONSTRAINT `undian_ibfk_3` FOREIGN KEY (`id_lagu`) REFERENCES `lagu` (`id_lagu`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
