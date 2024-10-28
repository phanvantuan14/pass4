-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 28, 2024 lúc 05:38 AM
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
(45, 'f1'),
(46, 'f2'),
(47, 'f3'),
(48, 'f4'),
(49, 'f5');

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
(7, 'SKU123-F', 'GIFT4U – WooCommerce Gift Cards All in One', 70.00, 60.00, 'th.jfif', 'Description for Product F', '2024-10-10 04:50:21', '2024-10-27 20:23:59'),
(8, 'SKU123-G', 'EPOW – WooCommerce Custom Product Options', 30.00, 25.00, 'fea698d6-936f-49b1-9385-cd5a66acad8b.jfif', 'Description for Product G', '2024-10-13 04:50:21', '2024-10-27 20:24:21'),
(9, 'SKU123-H', 'FEWC – WooCommerce Extra Checkout Fields', 20.00, 15.00, 'tải xuống.png', 'Description for Product H', '2024-10-13 04:50:21', '2024-10-27 20:25:15'),
(11, 'SKU123-K', 'Product K', 75.00, 65.00, 'tải xuốngg.jpg', 'Description for Product K', '2024-10-14 04:51:58', '2024-10-27 20:23:42'),
(13, 'SKU001', 'Product 1', 100.00, 80.00, 'fea698d6-936f-49b1-9385-cd5a66acad8b.jfif', 'Product 1 description', '2024-10-13 17:56:24', '2024-10-27 20:23:11'),
(15, 'SKU002', 'Product 1', 100.00, 80.00, 'images.jfif', 'Product 1 description', '2024-10-13 18:05:40', '2024-10-27 20:22:05'),
(16, 'SKU0014', 'Product 14', 100.00, 80.00, 'tải xuốngg.jpg', 'Product 14 description', '2024-10-14 18:10:42', '2024-10-27 20:21:41'),
(18, 'SKU0015', 'Product 15', 100.00, 80.00, 'tải xuống.jpg', 'Product 15 description', '2024-10-14 18:23:15', '2024-10-27 20:26:28'),
(158, 'SKU-D7DACA8E', 'adsssd', 100.00, NULL, 'tải xuống.jfif', NULL, '2024-10-25 21:15:34', '2024-10-27 18:30:11'),
(161, 'SKU-FF0960AF', 'ad', 100.00, NULL, 'tải xuống (1).png', NULL, '2024-10-27 18:23:15', '2024-10-27 20:26:15'),
(162, 'SKU-7EF52A4E', 'df', 130.00, NULL, 'tải xuống.jfif', NULL, '2024-10-27 18:23:32', '2024-10-27 20:19:47'),
(164, 'SKU-6FCE0DBD', 'fds', 100.00, NULL, 'images.jfif', NULL, '2024-10-27 18:33:29', '2024-10-27 20:26:00'),
(165, 'SKU-E27E6B82', 'sd', 1999.00, NULL, 'tải xuốngg.jpg', NULL, '2024-10-27 19:01:12', '2024-10-27 19:01:12'),
(167, 'SKU-393A3A45', 'aAVVss', 111.00, NULL, 'th.jfif', NULL, '2024-10-27 19:28:36', '2024-10-27 19:52:16'),
(168, 'SKU-72D01571', 'ccc', 100.00, NULL, 'fea698d6-936f-49b1-9385-cd5a66acad8b.jfif', NULL, '2024-10-27 19:50:59', '2024-10-27 19:55:22');

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
(7, 45),
(7, 46),
(8, 48),
(8, 49),
(9, 45),
(11, 45),
(11, 46),
(13, 48),
(13, 49),
(15, 49),
(16, 48),
(16, 49),
(18, 48),
(158, 49),
(161, 47),
(161, 48),
(162, 45),
(164, 45),
(164, 46),
(165, 48),
(167, 46),
(168, 49);

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
(338, 168, 'tải xuống (1).png'),
(339, 167, 'tải xuống.png'),
(340, 165, 'tải xuống.jpg'),
(341, 165, 'tải xuống.png'),
(344, 162, 'fea698d6-936f-49b1-9385-cd5a66acad8b.jfif'),
(345, 162, 'tải xuốngg.jpg'),
(346, 164, 'fea698d6-936f-49b1-9385-cd5a66acad8b.jfif'),
(347, 164, 'tải xuốngg.jpg'),
(348, 161, 'images.jfif'),
(349, 158, 'tải xuống.jpg'),
(350, 18, 'tải xuốngg.jpg'),
(351, 16, 'tải xuống.jfif'),
(352, 16, 'tải xuống.jpg'),
(353, 11, 'fea698d6-936f-49b1-9385-cd5a66acad8b.jfif'),
(354, 15, 'tải xuống (1).png'),
(355, 13, 'tải xuống.jfif'),
(356, 8, 'tải xuống.jpg'),
(357, 9, 'th.jfif'),
(358, 7, 'images.jfif');

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
(7, 51),
(8, 49),
(9, 48),
(11, 48),
(13, 48),
(15, 48),
(15, 49),
(16, 48),
(18, 50),
(18, 51),
(158, 50),
(158, 51),
(161, 50),
(162, 50),
(162, 51),
(164, 50),
(165, 49),
(165, 50),
(167, 48),
(167, 49),
(168, 48);

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
(48, 't1'),
(49, 't2'),
(50, 't3'),
(51, 't4'),
(52, 't5');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT cho bảng `product_gallery`
--
ALTER TABLE `product_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=359;

--
-- AUTO_INCREMENT cho bảng `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

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
