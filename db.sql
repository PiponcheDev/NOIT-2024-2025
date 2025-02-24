-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Време на генериране: 21 фев 2025 в 05:50
-- Версия на сървъра: 10.4.32-MariaDB
-- Версия на PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данни: `travelpass`
--

-- --------------------------------------------------------

--
-- Структура на таблица `card`
--

CREATE TABLE `card` (
  `id` int(11) NOT NULL,
  `cardToken` varchar(10000) NOT NULL,
  `cardType` char(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `purchaseDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Схема на данните от таблица `card`
--

INSERT INTO `card` (`id`, `cardToken`, `cardType`, `user_id`, `purchaseDate`) VALUES
(74, '9d690960d9495cdef89c02638f37fd0c', 'R', 74, '2025-02-17 16:54:59'),
(75, 'eba295a905a1d4222c67e9f50384aac8', 'S', 75, '2025-02-18 18:04:59'),
(76, '378132d6d51c7700aa1add2ab85a18cc', 'R', 76, '2025-02-18 17:41:33'),
(77, 'cf5af2f05536b43c0f9d3eb2f598b097', '', 77, NULL),
(78, '95b65942a034c55e10495131c349451d', '', 78, NULL),
(79, 'b750591378b797c3e465430660f5e0e6', '', 79, NULL);

-- --------------------------------------------------------

--
-- Структура на таблица `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Схема на данните от таблица `user`
--

INSERT INTO `user` (`id`, `email`, `username`, `password`) VALUES
(74, 'a@gmail.com', 'a', '$2y$10$hpaXYhOJp70/s5nc7znV0eWiOAEyC1SlxPjXy603B0fzrcSMM7jVG'),
(75, 'b@gmail.com', 'b', '$2y$10$cPy6WrxVIEUmOlCMt/zW0eDJGrZMmqJUDM3.XlMFdbYcr9b9rlWOy'),
(76, 'gfg@gmail.com', 'gfd', '$2y$10$Rixgmgwjqca3x5POXpnkKuIfPgCtWPdY5CDPru5sRBKjE0WOqJ1bS'),
(77, 'g@gmail.com', 'g', '$2y$10$uRA7tDoFV/X9EL3elzqEsuP/Ryw15UlDw8/hH9f.AjzYOqrhY.x9S'),
(78, 'fd@gmail.com', 'fd', '$2y$10$JxzymUpWFbhlEZDlmq6Y8eMGOSi8OHFnMDgFd72LZ7QF9HPm7FGyW'),
(79, 'f@gmail.com', 'f', '$2y$10$1zywNgf7UXuSjGYn0NeCLuKIgcvgMtQ1k6FDH.92PaXG2Rf9g/HlS');

--
-- Indexes for dumped tables
--

--
-- Индекси за таблица `card`
--
ALTER TABLE `card`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Индекси за таблица `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `card`
--
ALTER TABLE `card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- Ограничения за дъмпнати таблици
--

--
-- Ограничения за таблица `card`
--
ALTER TABLE `card`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
