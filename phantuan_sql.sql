-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 26, 2024 lúc 10:42 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `phantuan_sql`
--
CREATE DATABASE phantuan_sql;
USE phantuan_sql;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, '9mail'),
(35, 'ad'),
(42, 'adasd'),
(15, 'ao'),
(36, 'asd'),
(2, 'bopo'),
(29, 'C1'),
(28, 'd3'),
(40, 'dfsfsd'),
(32, 'DUOC'),
(43, 'f5vv'),
(34, 'fc4'),
(33, 'FG'),
(7, 'fly'),
(3, 'plugins'),
(4, 'pofily'),
(41, 'sd'),
(37, 'sdsa'),
(27, 'SNAP'),
(30, 't6');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `featured_image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `sku`, `title`, `price`, `sale_price`, `featured_image`, `description`, `created_date`, `modified_date`) VALUES
(91, 'SKU-60A7472F', 'ssa', 100.00, NULL, 'images.jfif', NULL, '2024-10-21 05:00:47', '2024-10-21 09:17:49'),
(92, 'SKU-CC1B2F08', 'meo chu xa', 1999.00, NULL, 'images.jfif', NULL, '2024-10-21 09:03:36', '2024-10-22 04:05:12'),
(93, 'SKU-1318C7AC', 'gb', 130.00, NULL, 'fea698d6-936f-49b1-9385-cd5a66acad8b.jfif', NULL, '2024-10-22 01:24:07', '2024-10-22 01:24:07'),
(94, 'SKU-8AE08366', 'ong thu', 100.00, NULL, 'tải xuống.jfif', NULL, '2024-10-23 01:10:02', '2024-10-23 01:10:20'),
(95, 'SKU-D8BEE647', 'dss', 140.00, NULL, 'tải xuống.jpg', NULL, '2024-10-23 01:10:54', '2024-10-23 01:11:44'),
(96, 'SKU-67075766', 'ds', 100.00, NULL, 'fea698d6-936f-49b1-9385-cd5a66acad8b.jfif', NULL, '2024-10-25 01:35:43', '2024-10-25 09:19:09'),
(97, 'SKU-F0A0AC4E', 'dasd', 100.00, NULL, 'th.jfif', NULL, '2024-10-25 09:21:06', '2024-10-25 09:21:06'),
(99, 'SKU-853CBDCC', 'sa', 130.00, NULL, 'tải xuống (1).png', NULL, '2024-10-26 01:19:05', '2024-10-26 01:19:05'),
(105, 'SKU-20EF6563', 'sad', 100.00, NULL, 'fea698d6-936f-49b1-9385-cd5a66acad8b.jfif', NULL, '2024-10-26 02:16:23', '2024-10-26 02:16:23'),
(106, 'SKU-B3AA48A8', 'sd', 100.00, NULL, 'fea698d6-936f-49b1-9385-cd5a66acad8b.jfif', NULL, '2024-10-26 02:19:44', '2024-10-26 02:19:44'),
(107, 'SKU-E7F189C1', 'sdsa', 100.00, NULL, 'th.jfif', NULL, '2024-10-26 02:20:24', '2024-10-26 02:20:24'),
(130, 'SKU-506CC83F', 'sads', 100.00, NULL, '', NULL, '2024-10-26 02:48:28', '2024-10-26 02:48:28'),
(131, 'SKU-F5B6FF68', 'sd', 100.00, NULL, '', NULL, '2024-10-26 02:51:52', '2024-10-26 02:51:52'),
(132, 'SKU-8D90E229', 'C', 100.00, NULL, '', NULL, '2024-10-26 02:52:10', '2024-10-26 02:52:10'),
(133, 'SKU-B65E816A', 'dsdsd', 100.00, NULL, '', NULL, '2024-10-26 02:52:29', '2024-10-26 04:32:15'),
(140, 'SKU-5028EE5A', 'sd', 100.00, NULL, 'th.jfif', NULL, '2024-10-26 03:12:04', '2024-10-26 03:12:04'),
(144, 'SKU-80F39638', 'sd', 100.00, NULL, 'fea698d6-936f-49b1-9385-cd5a66acad8b.jfif', NULL, '2024-10-26 03:19:57', '2024-10-26 03:19:57'),
(146, 'SKU-E79DC819', 'asd', 100.00, NULL, 'images.jfif', NULL, '2024-10-26 03:23:34', '2024-10-26 03:23:34'),
(150, 'SKU-61E5D9A8', 'asd', 100.00, NULL, 'tải xuống.jfif', NULL, '2024-10-26 03:30:45', '2024-10-26 03:30:45'),
(152, 'SKU-2337C3C2', 'dasdasd', 100.00, NULL, 'images.jfif', NULL, '2024-10-26 03:31:26', '2024-10-26 03:40:27'),
(158, 'SKU-D7DACA8E', 'adsssd', 100.00, NULL, 'fea698d6-936f-49b1-9385-cd5a66acad8b.jfif', NULL, '2024-10-26 04:15:34', '2024-10-26 04:32:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_categories`
--

CREATE TABLE `product_categories` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_categories`
--

INSERT INTO `product_categories` (`product_id`, `category_id`) VALUES
(92, 7),
(92, 33),
(93, 3),
(93, 7),
(93, 33),
(94, 30),
(95, 4),
(95, 27),
(96, 28),
(96, 32),
(106, 7),
(106, 33),
(107, 30),
(158, 1),
(158, 27),
(158, 32),
(158, 34),
(158, 42);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_gallery`
--

CREATE TABLE `product_gallery` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_gallery`
--

INSERT INTO `product_gallery` (`id`, `product_id`, `image`) VALUES
(191, 91, 'tải xuống (1).png'),
(192, 91, 'tải xuống.jfif'),
(193, 91, 'tải xuống.jpg'),
(199, 93, 'tải xuống.jfif'),
(200, 93, 'tải xuống.jpg'),
(201, 93, 'tải xuống.png'),
(202, 93, 'tải xuốngg.jpg'),
(224, 92, 'tải xuống.jfif'),
(225, 92, 'tải xuống.jpg'),
(226, 92, 'tải xuống.png'),
(227, 92, 'tải xuốngg.jpg'),
(236, 95, 'tải xuống.jfif'),
(237, 95, 'tải xuống.jpg'),
(238, 94, 'tải xuống.jfif'),
(239, 94, 'tải xuống.jpg'),
(240, 94, 'tải xuống.png'),
(241, 94, 'tải xuốngg.jpg'),
(242, 96, 'tải xuống (1).png'),
(243, 96, 'tải xuống.jfif'),
(244, 96, 'tải xuống.jpg'),
(248, 97, 'tải xuống.png'),
(249, 97, 'tải xuốngg.jpg'),
(250, 97, 'th.jfif'),
(251, 99, 'tải xuống (1).png'),
(252, 99, 'tải xuống.jfif'),
(253, 99, 'tải xuống.jpg'),
(256, 105, 'tải xuống.jfif'),
(257, 105, 'tải xuống.jpg'),
(258, 106, 'tải xuống.png'),
(259, 106, 'tải xuốngg.jpg'),
(260, 107, 'fea698d6-936f-49b1-9385-cd5a66acad8b.jfif'),
(261, 107, 'images.jfif'),
(265, 140, 'fea698d6-936f-49b1-9385-cd5a66acad8b.jfif'),
(266, 140, 'images.jfif'),
(267, 144, 'tải xuống.jfif'),
(268, 144, 'tải xuống.jpg'),
(269, 152, 'fea698d6-936f-49b1-9385-cd5a66acad8b.jfif'),
(270, 152, 'images.jfif'),
(271, 152, 'tải xuốngg.jpg'),
(272, 152, 'th.jfif'),
(275, 146, 'tải xuống.png'),
(276, 146, 'tải xuốngg.jpg'),
(277, 158, 'tải xuống.png'),
(278, 158, 'tải xuốngg.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_tags`
--

CREATE TABLE `product_tags` (
  `product_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_tags`
--

INSERT INTO `product_tags` (`product_id`, `tag_id`) VALUES
(92, 27),
(93, 9),
(93, 10),
(93, 12),
(94, 10),
(94, 24),
(96, 2),
(96, 21),
(106, 9),
(106, 10),
(107, 9),
(107, 10),
(107, 24),
(158, 1),
(158, 2),
(158, 31);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(36, 'ad'),
(1, 'apartment'),
(37, 'asdasd'),
(33, 'asds'),
(32, 'ass'),
(26, 'C2'),
(2, 'ecommerce'),
(21, 'f6'),
(34, 'f6d'),
(31, 'fc6'),
(30, 'HG'),
(25, 'l3'),
(12, 'mua dong'),
(9, 'New Tag 1'),
(10, 'New Tag 2'),
(24, 'SAPSHOST'),
(41, 'SAQQQ'),
(38, 'sasdsad'),
(39, 'sd'),
(35, 'sdfsd'),
(40, 'SSSA'),
(27, 't1'),
(3, 'wordpress');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`);

--
-- Chỉ mục cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `product_gallery`
--
ALTER TABLE `product_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `product_tags`
--
ALTER TABLE `product_tags`
  ADD PRIMARY KEY (`product_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Chỉ mục cho bảng `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT cho bảng `product_gallery`
--
ALTER TABLE `product_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=279;

--
-- AUTO_INCREMENT cho bảng `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_gallery`
--
ALTER TABLE `product_gallery`
  ADD CONSTRAINT `product_gallery_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_tags`
--
ALTER TABLE `product_tags`
  ADD CONSTRAINT `product_tags_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
