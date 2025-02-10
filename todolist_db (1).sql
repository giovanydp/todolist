-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2025 at 01:00 AM
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
-- Database: `todolist_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `subtugas`
--

CREATE TABLE `subtugas` (
  `id` int(11) NOT NULL,
  `tugas_id` int(11) DEFAULT NULL,
  `nama_subtugas` varchar(255) DEFAULT NULL,
  `status` enum('belum','selesai') DEFAULT 'belum'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subtugas`
--

INSERT INTO `subtugas` (`id`, `tugas_id`, `nama_subtugas`, `status`) VALUES
(1, 6, 'asd', 'belum'),
(2, 6, 'asd', 'belum'),
(22, 15, 'asd', 'belum'),
(23, 15, 'asdasd', 'belum'),
(24, 16, 'asdasd', 'belum'),
(25, 16, 'asdasd', 'belum'),
(26, 17, 'asdasd', 'belum'),
(27, 17, 'asd', 'belum'),
(28, 18, 'asda', 'belum'),
(29, 18, 'asdasda', 'selesai'),
(30, 19, 'asd', 'belum'),
(31, 19, 'asd', 'selesai'),
(34, 20, 'easa', '');

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_tugas` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tenggat` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`id`, `user_id`, `nama_tugas`, `deskripsi`, `tenggat`, `status`) VALUES
(2, 2, 'tugas rumah', 'a', '2025-02-04 05:19:00', 1),
(3, 5, 'tugas rumah', 'asd', '2025-02-04 05:34:00', 1),
(4, 2, 'ads', 'asd', '2025-02-04 05:39:00', 1),
(5, 2, 'asd', 'asd', '2025-02-01 05:42:00', 0),
(6, 3, 'asda', 'asd', '2025-02-04 06:03:00', 1),
(15, 1, 'sdfsdf', 'sdfsdf', '2025-02-04 06:43:00', 1),
(16, 1, 'asd', 'asd', '2025-02-04 06:45:00', 1),
(17, 1, 'ads', 'asd', '2025-02-04 06:46:00', 1),
(18, 1, 'asdas', 'asdasd', '2025-02-04 06:47:00', 1),
(19, 1, 'asd', 'asd', '2025-02-04 06:47:00', 1),
(20, 1, 'asd', 'asd', '2025-02-04 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'giovanys', 'akusukangoding'),
(2, 'giovany', '12345678'),
(3, 'iniel', '12345678'),
(5, 'ikan', 'akuikan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `subtugas`
--
ALTER TABLE `subtugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_id` (`tugas_id`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `subtugas`
--
ALTER TABLE `subtugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `subtugas`
--
ALTER TABLE `subtugas`
  ADD CONSTRAINT `subtugas_ibfk_1` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tugas`
--
ALTER TABLE `tugas`
  ADD CONSTRAINT `tugas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
