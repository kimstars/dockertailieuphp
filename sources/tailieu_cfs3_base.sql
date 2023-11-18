-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2023 at 07:28 PM
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
-- Database: `tailieu_cfs3`
--
CREATE DATABASE IF NOT EXISTS `tailieu_cfs3` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `tailieu_cfs3`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `password` char(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Nguyễn Anh 123', 'thuyanh1x2@gmail.com', '21232f297a57a5a743894a0e4a801fc3', '2023-09-07 20:27:49', '2023-09-09 10:24:53'),
(3, 'admin', 'admin@gmail.com', '202cb962ac59075b964b07152d234b70', '2023-09-09 15:14:24', '2023-09-09 10:15:04');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `parent_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`, `active`, `created_at`, `updated_at`, `parent_id`) VALUES
(11, 'Toán', 1, '2023-09-07 23:35:20', '2023-09-17 19:18:34', 20),
(12, 'Lớp 10', 1, '2023-09-07 23:35:22', '2023-09-17 19:19:27', 0),
(13, 'Ngữ văn', 1, '2023-09-09 20:46:25', '2023-09-17 19:18:53', 20),
(15, 'Ngữ văn', 1, '2023-09-11 21:05:46', '2023-09-17 19:19:36', 12),
(16, 'Tiếng anh', 1, '2023-09-11 21:06:04', '2023-09-17 19:19:45', 12),
(20, 'Lớp 11', 1, '2023-09-14 10:52:46', '2023-09-17 19:18:58', 0),
(21, 'Lớp 12', 1, '2023-09-14 10:52:53', '2023-09-17 19:18:28', 0),
(22, 'Ôn thi THPTQG', 1, '2023-09-14 10:53:06', '2023-09-17 19:20:01', 0),
(23, 'ĐGNL', 1, '2023-09-14 10:53:27', '2023-09-17 19:17:51', 0),
(24, 'Tài liệu khác', 1, '2023-09-14 10:53:39', '2023-09-17 19:18:01', 0),
(25, 'Tiếng anh', 1, '2023-09-14 10:54:46', '2023-09-17 19:18:42', 20),
(26, 'Toán', 1, '2023-09-14 10:55:03', '2023-09-17 19:18:08', 21),
(27, 'Ngữ văn', 1, '2023-09-14 10:55:13', '2023-09-17 19:18:23', 21),
(28, 'Tiếng anh', 1, '2023-09-14 10:55:30', '2023-09-17 19:18:46', 21),
(29, 'Toán', 1, '2023-09-16 00:45:02', '2023-09-17 19:19:14', 12);

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `upload_id` varchar(255) NOT NULL,
  `file_size` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `view_count` int(11) DEFAULT 0,
  `download_count` int(11) DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `is_admin_upload` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `name` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `category_id`, `upload_id`, `file_size`, `file_type`, `file_name`, `view_count`, `download_count`, `user_id`, `is_admin_upload`, `active`, `name`, `description`, `created_at`, `updated_at`) VALUES
(7, 20, '1N_NU_0PRTpkeVG2NpBhbS25kNuWYWmGn', '106170', 'application/pdf', 'CauhoiB1.pdf', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(8, 20, '1yortsxzLGPgFNEOWzGlHqk-CE_tPOKwR', '14172', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'Câu 4.docx', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(9, 20, '1LGvN-MK8Q9pbscJJt9tFE0agHDSuHbJT', '5292891', 'application/pdf', 'de-thi-chinh-thuc-ky-thi-tot-nghiep-thpt-nam-2022-mon-toan.pdf', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(10, 20, '1jSRqtFekEUkdv0PUi1tt0bxrIA42GwLS', '5292891', 'application/pdf', 'de-thi-chinh-thuc-ky-thi-tot-nghiep-thpt-nam-2022-mon-toan.pdf', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(11, 20, '14cFOj4dxiMfI1udBLOVwpa8XfbDVkpQi', '5292891', 'application/pdf', 'de-thi-chinh-thuc-ky-thi-tot-nghiep-thpt-nam-2022-mon-toan.pdf', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(12, 20, '1Y_diFVqD4YJH2C9TXgINkMVf0yrgWHY_', '152787', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'Câu 2 Bai3_ Chu Tuan Kiet.docx', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(13, 20, '1Y4c2bf9WsLD9h6wvEsbDZFeagpgQadNL', '20653', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'Câu 7.docx', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(14, 20, '1CUHk1SsDm0ILbN2U8jVGnD3l6dKHO5Bl', '14172', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'Câu 4.docx', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(15, 20, '1SOKaxlXMqFNzc4chUq5PgZGgdtdo8c30', '14172', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'Câu 4.docx', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(16, 20, '1rNpCItkz08n43KT607fY_JdU-uD4hLun', '14172', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'Câu 4.docx', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(17, 20, '12GEej24F2E5KVPSHXneELZ_bLZgjWchk', '359424', 'application/msword', '[Full Ver] Đáp án CNPM 2018.doc', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(18, 20, '1TDUV4IYW0leOq1ivKPfim27NcvCqxHMm', '359424', 'application/msword', '[Full Ver] Đáp án CNPM 2018.doc', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(19, 20, '1c8vwQWI-LwXif9wanDjysM8O_DZbPVHN', '5859', 'image/png', 'Screenshot 2023-09-11 001010.png', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(20, 20, '1pVJ8iaPJEyc4R3kSy7Y4wCI-RMVJ8M4y', '886354', 'application/pdf', '[Official] DE CUONG CTD-CTCT 2019 EDITTED.pdf', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(21, 20, '1ZkzgjseUZJUDkfqZ63G9kdfEew60J861', '833623', 'application/pdf', 'pin_tutorial.pdf', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(22, 20, '1jGX2h6qYKBqFPAjm_hXrP2wSshqAzPw0', '20484', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'BC_RedLine.docx', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(23, 20, '1LlnpTLav5YafTJjHCC6pD-g8-RpcoKqE', '2351108', 'application/pdf', 'code-injection.pdf', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(24, 20, '1p9gy60GfPV7Jkw8uutudyLV_ZoArh9FI', '238830', 'application/pdf', '51526-Article Text-155528-1-10-20201023.pdf', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19'),
(25, 20, '1xabgT54eqyVxZpnEAsmrC0L4tWxeKlv1', '11960', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'tài liệu toán 11.docx', 0, 0, 1, 1, 1, NULL, NULL, '2023-09-17 17:17:19', '2023-09-17 17:17:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `password` char(255) NOT NULL,
  `phone` char(10) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_docs` int(11) NOT NULL,
  `action` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

ALTER TABLE `history`
  ADD PRIMARY KEY (`id`) USING BTREE;
  
--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;


DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `update_document_download_count`(document_id INT) RETURNS int(11)
BEGIN
  DECLARE current_download_count INT;
  SELECT download_count INTO current_download_count FROM documents WHERE id = document_id;
  UPDATE documents SET download_count = current_download_count + 1, updated_at= CURRENT_TIMESTAMP WHERE id = document_id;
  RETURN current_download_count + 1;
END$$
DELIMITER ;

DELIMITER $$
CREATE DEFINER=`root`@`localhost` FUNCTION `update_document_view_count`(document_id INT) RETURNS int(11)
BEGIN
  DECLARE current_view_count INT;
  SELECT view_count INTO current_view_count FROM documents WHERE id = document_id;
  UPDATE documents SET view_count = current_view_count + 1, updated_at= CURRENT_TIMESTAMP WHERE id = document_id;
  RETURN current_view_count + 1;
END$$
DELIMITER ;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
