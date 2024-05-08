-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2024 at 03:42 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `koperasi_sp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` varchar(5) NOT NULL,
  `nama_admin` varchar(50) DEFAULT NULL,
  `no_tlp` varchar(12) DEFAULT NULL,
  `role` enum('admin') NOT NULL DEFAULT 'admin',
  `password` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `no_tlp`, `role`, `password`) VALUES
('ADM01', 'Faisal Masil', '089766545321', 'admin', '21232f297a57a5a743894a0e4a801fc3');

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` varchar(5) NOT NULL,
  `nama_anggota` varchar(50) DEFAULT NULL,
  `gender` enum('pria','wanita') DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `no_tlp` varchar(12) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `simpan_pokok` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`id_anggota`, `nama_anggota`, `gender`, `alamat`, `no_tlp`, `password`, `simpan_pokok`) VALUES
('ANG01', 'Asep Ridwan Muhasid', 'pria', 'Jl. Melati B10 ', '086544321232', '7094ed9b0059aba4cd72e81577229914', 40000),
('ANG02', 'Asep Karbu', 'pria', 'Jl. Jalan we', '098766545213', 'e7dd32332153b9a2e903ae3b164e1630', 120000),
('ANG03', 'Jajang Kopling', 'pria', 'Jl. Baru 29', '088766545213', '3d07f1dc0344782f3ee443437ae0cf6b', 70000);

-- --------------------------------------------------------

--
-- Table structure for table `angsuran_berlangsung`
--

CREATE TABLE `angsuran_berlangsung` (
  `id_angsuran` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `id_anggota` varchar(5) DEFAULT NULL,
  `jumlah_angsuran` bigint(20) DEFAULT NULL,
  `tanggal_disetujui` date NOT NULL DEFAULT curdate(),
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `status` enum('lunas','tidak_lunas') NOT NULL DEFAULT 'tidak_lunas',
  `lama_angsuran` int(11) NOT NULL,
  `tenggat_waktu` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `angsuran_berlangsung`
--

INSERT INTO `angsuran_berlangsung` (`id_angsuran`, `id_transaksi`, `id_anggota`, `jumlah_angsuran`, `tanggal_disetujui`, `bukti_transfer`, `status`, `lama_angsuran`, `tenggat_waktu`) VALUES
(7, 1, 'ANG01', 9000000, '2024-01-10', 'Bukti Bayar.jpg', 'tidak_lunas', 10, '2024-11-10'),
(8, 5, 'ANG01', 14000000, '2024-01-11', '383153015_717512213748148_7937353176107197002_n.jpg', 'tidak_lunas', 12, '2025-01-11'),
(9, 8, 'ANG03', 9000000, '2024-01-11', 'bukkktii.jpg', 'tidak_lunas', 10, '2024-11-11');

-- --------------------------------------------------------

--
-- Table structure for table `bendahara`
--

CREATE TABLE `bendahara` (
  `id_bendahara` varchar(5) NOT NULL,
  `nama_bendahara` varchar(50) DEFAULT NULL,
  `no_tlp` varchar(12) DEFAULT NULL,
  `role` enum('bendahara') NOT NULL DEFAULT 'bendahara',
  `password` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bendahara`
--

INSERT INTO `bendahara` (`id_bendahara`, `nama_bendahara`, `no_tlp`, `role`, `password`) VALUES
('BHR01', 'Siti Nuriyah', '088766543212', 'bendahara', 'c9ccd7f3c1145515a9d3f7415d5bcbea');

-- --------------------------------------------------------

--
-- Table structure for table `ketua`
--

CREATE TABLE `ketua` (
  `id_ketua` varchar(5) NOT NULL,
  `nama_ketua` varchar(50) DEFAULT NULL,
  `role` enum('ketua') NOT NULL DEFAULT 'ketua',
  `password` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ketua`
--

INSERT INTO `ketua` (`id_ketua`, `nama_ketua`, `role`, `password`) VALUES
('KTA01', 'Ningrat', 'ketua', '00719910bb805741e4b7f28527ecb3ad');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran_angsuran`
--

CREATE TABLE `pembayaran_angsuran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `jumlah_bayar` bigint(20) DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `tanggal_pembayaran` date DEFAULT curdate(),
  `status` enum('menunggu','diterima','ditolak') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran_angsuran`
--

INSERT INTO `pembayaran_angsuran` (`id_pembayaran`, `id_transaksi`, `jumlah_bayar`, `bukti_transfer`, `tanggal_pembayaran`, `status`) VALUES
(13, 1, 1000000, 'Bukti Bayar.jpg', '2024-01-11', 'diterima'),
(14, 5, 1000000, '383153015_717512213748148_7937353176107197002_n.jpg', '2024-01-11', 'diterima'),
(15, 1, 1000000, 'ANDIGA RAHMAN MAULANA_sertifikat studium generale_berinovasi diera metaverse_7april 2022.jpg', '2024-01-11', 'menunggu'),
(17, 5, 1000000, '1.jpg', '2024-01-11', 'menunggu'),
(18, 8, 1000000, 'bktiiiiaa.jpg', '2024-01-11', 'diterima');

-- --------------------------------------------------------

--
-- Table structure for table `permintaan_peminjaman`
--

CREATE TABLE `permintaan_peminjaman` (
  `id_transaksi` int(11) NOT NULL,
  `id_anggota` varchar(5) DEFAULT NULL,
  `jumlah_peminjaman` bigint(20) DEFAULT NULL,
  `lama_angsuran` int(11) DEFAULT NULL,
  `syarat_peminjaman` varchar(255) DEFAULT NULL,
  `tanggal_pengajuan` date DEFAULT curdate(),
  `status` enum('menunggu','disetujui','ditolak') DEFAULT 'menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permintaan_peminjaman`
--

INSERT INTO `permintaan_peminjaman` (`id_transaksi`, `id_anggota`, `jumlah_peminjaman`, `lama_angsuran`, `syarat_peminjaman`, `tanggal_pengajuan`, `status`) VALUES
(1, 'ANG01', 10000000, 10, 'uploads/CV Andigaaa.pdf', '2024-01-09', 'disetujui'),
(2, 'ANG01', 10000000, 12, 'uploads/Sertifikat_TOEFL_22060094_2.pdf', '2024-01-11', 'menunggu'),
(3, 'ANG01', 10000000, 24, 'uploads/bukti_transfersertifikat_course_123_2121463_010622165714.pdf', '2024-01-11', 'menunggu'),
(4, 'ANG01', 10000000, 12, 'uploads/bukti_transfer/certificate-DQLABBGINRCJJWSA.pdf', '2024-01-11', 'menunggu'),
(5, 'ANG01', 15000000, 12, 'uploads/bukti_transfer/sertifikat_course_123_2121463_010622165714.pdf', '2024-01-11', 'disetujui'),
(7, 'ANG01', 10000000, 12, 'uploads/bukti_transfer/UC-ed09fe7d-bbea-4324-88db-a1141f856d53.pdf', '2024-01-11', 'menunggu'),
(8, 'ANG03', 10000000, 10, 'uploads/bukti_transfer/dicoding-developer-coaching-103-front-end-percantik-website-mu-dengan-css-certificate.pdf', '2024-01-11', 'disetujui');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_simpan`
--

CREATE TABLE `transaksi_simpan` (
  `id_transaksi` int(11) NOT NULL,
  `id_anggota` varchar(5) DEFAULT NULL,
  `jumlah_transfer` bigint(20) DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `status` enum('menunggu','diterima','ditolak') DEFAULT 'menunggu',
  `jumlah_diterima` bigint(20) DEFAULT NULL,
  `tanggal_simpan` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_simpan`
--

INSERT INTO `transaksi_simpan` (`id_transaksi`, `id_anggota`, `jumlah_transfer`, `bukti_transfer`, `status`, `jumlah_diterima`, `tanggal_simpan`) VALUES
(1, 'ANG01', 10000, 'Bukti Bayar.jpg', 'diterima', 0, '2024-01-08'),
(2, 'ANG01', 10000, 'Bukti Bayar.jpg', 'ditolak', 0, '2024-01-08'),
(3, 'ANG01', 10000, 'cb26d36a7b8bbf81e710cb1ff63b5f6e.png', 'diterima', 10000, '2024-01-08'),
(4, 'ANG02', 10000, 'Bukti Bayar.jpg', 'diterima', 10000, '2024-01-08'),
(5, 'ANG02', 20000, '383153015_717512213748148_7937353176107197002_n.jpg', 'diterima', 20000, '2024-01-08'),
(6, 'ANG01', 10000, 'Bukti Bayar.jpg', 'diterima', 10000, '2024-01-08'),
(7, 'ANG01', 20000, 'Bukti Bayar.jpg', 'diterima', 20000, '2024-01-08'),
(8, 'ANG01', 10000, 'Bukti Bayar.jpg', 'diterima', 10000, '2024-01-08'),
(9, 'ANG01', 10000, 'Bukti Bayar.jpg', 'menunggu', NULL, '2024-01-08'),
(10, 'ANG03', 10000, 'Bukti Bayar.jpg', 'menunggu', NULL, '2024-01-08'),
(11, 'ANG03', 10000, 'Bukti Bayar.jpg', 'menunggu', NULL, '2024-01-08'),
(12, 'ANG03', 20000, 'Bukti Bayar.jpg', 'diterima', 20000, '2024-01-08'),
(13, 'ANG02', 10000, 'Bukti Bayar.jpg', 'diterima', 10000, '2024-01-09'),
(17, 'ANG03', 10000, 'RobloxScreenShot20231003_185718283.png', 'menunggu', NULL, '2024-01-11'),
(18, 'ANG03', 10000, 'RobloxScreenShot20231001_204734693.png', 'menunggu', NULL, '2024-01-11'),
(19, 'ANG03', 20000, 'apex-s18-feature-image-16x9.jpg.adapt.crop191x100.1200w.jpg', 'diterima', 20000, '2024-01-11');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_peminjaman`
--

CREATE TABLE `transfer_peminjaman` (
  `id_transfer` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transfer_peminjaman`
--

INSERT INTO `transfer_peminjaman` (`id_transfer`, `id_transaksi`, `bukti_transfer`) VALUES
(5, 1, 'Bukti Bayar.jpg'),
(6, 5, '383153015_717512213748148_7937353176107197002_n.jpg'),
(7, 8, 'bukkktii.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`);

--
-- Indexes for table `angsuran_berlangsung`
--
ALTER TABLE `angsuran_berlangsung`
  ADD PRIMARY KEY (`id_angsuran`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_anggota` (`id_anggota`);

--
-- Indexes for table `bendahara`
--
ALTER TABLE `bendahara`
  ADD PRIMARY KEY (`id_bendahara`);

--
-- Indexes for table `ketua`
--
ALTER TABLE `ketua`
  ADD PRIMARY KEY (`id_ketua`);

--
-- Indexes for table `pembayaran_angsuran`
--
ALTER TABLE `pembayaran_angsuran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indexes for table `permintaan_peminjaman`
--
ALTER TABLE `permintaan_peminjaman`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_anggota` (`id_anggota`);

--
-- Indexes for table `transaksi_simpan`
--
ALTER TABLE `transaksi_simpan`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_anggota` (`id_anggota`);

--
-- Indexes for table `transfer_peminjaman`
--
ALTER TABLE `transfer_peminjaman`
  ADD PRIMARY KEY (`id_transfer`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `angsuran_berlangsung`
--
ALTER TABLE `angsuran_berlangsung`
  MODIFY `id_angsuran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pembayaran_angsuran`
--
ALTER TABLE `pembayaran_angsuran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `permintaan_peminjaman`
--
ALTER TABLE `permintaan_peminjaman`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transaksi_simpan`
--
ALTER TABLE `transaksi_simpan`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `transfer_peminjaman`
--
ALTER TABLE `transfer_peminjaman`
  MODIFY `id_transfer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `angsuran_berlangsung`
--
ALTER TABLE `angsuran_berlangsung`
  ADD CONSTRAINT `angsuran_berlangsung_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `permintaan_peminjaman` (`id_transaksi`),
  ADD CONSTRAINT `angsuran_berlangsung_ibfk_2` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`);

--
-- Constraints for table `pembayaran_angsuran`
--
ALTER TABLE `pembayaran_angsuran`
  ADD CONSTRAINT `pembayaran_angsuran_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `angsuran_berlangsung` (`id_transaksi`);

--
-- Constraints for table `permintaan_peminjaman`
--
ALTER TABLE `permintaan_peminjaman`
  ADD CONSTRAINT `permintaan_peminjaman_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`);

--
-- Constraints for table `transaksi_simpan`
--
ALTER TABLE `transaksi_simpan`
  ADD CONSTRAINT `transaksi_simpan_ibfk_1` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`);

--
-- Constraints for table `transfer_peminjaman`
--
ALTER TABLE `transfer_peminjaman`
  ADD CONSTRAINT `transfer_peminjaman_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `permintaan_peminjaman` (`id_transaksi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
