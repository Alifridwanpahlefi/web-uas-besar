-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2025 at 04:41 AM
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
-- Database: `db_booking_futsal`
--

-- --------------------------------------------------------

--
-- Table structure for table `alif_galeri`
--

CREATE TABLE `alif_galeri` (
  `id` int(11) NOT NULL,
  `id_lapangan` int(11) DEFAULT NULL,
  `gambar_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alif_galeri`
--

INSERT INTO `alif_galeri` (`id`, `id_lapangan`, `gambar_url`) VALUES
(1, NULL, 'https://vendors.id/wp-content/uploads/2024/03/ezgif-3-639eb0bef5.webp'),
(2, NULL, 'https://storage.googleapis.com/data.ayo.co.id/photos/77445/SEO%20HDI%204/80.%20Cara%20Cepat%20dan%20Mudah%20Menyewa%20Lapangan%20Futsal%20untuk%20Tim%20Anda.jpg'),
(3, NULL, 'https://gelora-public-storage.s3-ap-southeast-1.amazonaws.com/upload/public-20221213110402.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `alif_lapangan`
--

CREATE TABLE `alif_lapangan` (
  `id_lapangan` int(11) NOT NULL,
  `nama_lapangan` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga_per_jam` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alif_lapangan`
--

INSERT INTO `alif_lapangan` (`id_lapangan`, `nama_lapangan`, `deskripsi`, `harga_per_jam`) VALUES
(2, 'azz', 'asd', 1212212);

-- --------------------------------------------------------

--
-- Table structure for table `alif_pembayaran`
--

CREATE TABLE `alif_pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_pemesanan` int(11) DEFAULT NULL,
  `total_bayar` int(11) DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `status_pembayaran` enum('belum bayar','sudah bayar') DEFAULT 'belum bayar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alif_pembayaran`
--

INSERT INTO `alif_pembayaran` (`id_pembayaran`, `id_pemesanan`, `total_bayar`, `metode_pembayaran`, `bukti_transfer`, `status_pembayaran`) VALUES
(1, 1, -14526340, 'e-wallet', 'bukti_686e9f8025fe2.png', 'sudah bayar'),
(2, 2, 20546993, 'e-wallet', 'bukti_686ea7223bb0e.png', 'sudah bayar'),
(3, 3, 3576025, 'transfer', 'bukti_686eebb002037.jpg', 'sudah bayar');

-- --------------------------------------------------------

--
-- Table structure for table `alif_pemesanan`
--

CREATE TABLE `alif_pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `id_pengguna` int(11) DEFAULT NULL,
  `id_lapangan` int(11) DEFAULT NULL,
  `tanggal_pesan` date DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `status_booking` enum('pending','diterima','ditolak') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alif_pemesanan`
--

INSERT INTO `alif_pemesanan` (`id_pemesanan`, `id_pengguna`, `id_lapangan`, `tanggal_pesan`, `jam_mulai`, `jam_selesai`, `status_booking`) VALUES
(1, 2, 2, '2025-07-09', '23:45:00', '11:46:00', ''),
(2, 2, 2, '2025-07-10', '00:32:00', '17:29:00', ''),
(3, 2, 2, '2025-07-17', '05:25:00', '08:22:00', 'diterima'),
(4, 2, 2, '2025-07-11', '01:23:00', '17:27:00', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `alif_pengguna`
--

CREATE TABLE `alif_pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `nama_pengguna` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `peran` enum('admin','pengguna') NOT NULL DEFAULT 'pengguna',
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alif_pengguna`
--

INSERT INTO `alif_pengguna` (`id_pengguna`, `nama_pengguna`, `email`, `password`, `peran`, `foto`) VALUES
(1, 'zaa', 'zaa@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'admin', 'gambar/user_1_1752101992.png'),
(2, 'alif', 'alif@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'admin', NULL),
(5, 'aaa', 'aaa@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'pengguna', NULL),
(6, 'axc', 'axc@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'pengguna', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alif_galeri`
--
ALTER TABLE `alif_galeri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_lapangan` (`id_lapangan`);

--
-- Indexes for table `alif_lapangan`
--
ALTER TABLE `alif_lapangan`
  ADD PRIMARY KEY (`id_lapangan`);

--
-- Indexes for table `alif_pembayaran`
--
ALTER TABLE `alif_pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_pemesanan` (`id_pemesanan`);

--
-- Indexes for table `alif_pemesanan`
--
ALTER TABLE `alif_pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD KEY `id_pengguna` (`id_pengguna`),
  ADD KEY `id_lapangan` (`id_lapangan`);

--
-- Indexes for table `alif_pengguna`
--
ALTER TABLE `alif_pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alif_galeri`
--
ALTER TABLE `alif_galeri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `alif_lapangan`
--
ALTER TABLE `alif_lapangan`
  MODIFY `id_lapangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `alif_pembayaran`
--
ALTER TABLE `alif_pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `alif_pemesanan`
--
ALTER TABLE `alif_pemesanan`
  MODIFY `id_pemesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `alif_pengguna`
--
ALTER TABLE `alif_pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alif_galeri`
--
ALTER TABLE `alif_galeri`
  ADD CONSTRAINT `alif_galeri_ibfk_1` FOREIGN KEY (`id_lapangan`) REFERENCES `alif_lapangan` (`id_lapangan`);

--
-- Constraints for table `alif_pembayaran`
--
ALTER TABLE `alif_pembayaran`
  ADD CONSTRAINT `alif_pembayaran_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `alif_pemesanan` (`id_pemesanan`);

--
-- Constraints for table `alif_pemesanan`
--
ALTER TABLE `alif_pemesanan`
  ADD CONSTRAINT `alif_pemesanan_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `alif_pengguna` (`id_pengguna`),
  ADD CONSTRAINT `alif_pemesanan_ibfk_2` FOREIGN KEY (`id_lapangan`) REFERENCES `alif_lapangan` (`id_lapangan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
