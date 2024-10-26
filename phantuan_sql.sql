-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 23, 2024 lúc 04:19 AM
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
(15, 'ao'),
(2, 'bopo'),
(29, 'C1'),
(28, 'd3'),
(32, 'DUOC'),
(34, 'fc4'),
(33, 'FG'),
(7, 'fly'),
(3, 'plugins'),
(4, 'pofily'),
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
(2, 'SKU123', '9MAIL – WordPress Email Templates Designer', 90.00, 75.00, 'https://villatheme.com/wp-content/uploads/2022/03/01_preview-3.jpg', 'WordPress Email Templates Designer is a useful tool for creating and customizing WordPress emails. If the default WordPress email template appears plain to you and you want to customize it.', '2024-10-02 11:56:10', '2024-10-15 02:55:45'),
(3, 'ASDHSA-XLL', 'DEPART – Deposit and Part payment for WooCommerce', 80.00, 50.00, 'https://villatheme.com/wp-content/uploads/2021/08/01_preview-7.jpg', 'Description for Product B', '2024-10-02 19:04:43', '2024-10-03 12:37:24'),
(4, 'SKU123-C', 'REES – Real Estate for WooCommerce', 100.00, 90.00, 'https://villatheme.com/wp-content/uploads/2021/08/01_preview-7.jpg', 'Description for Product C', '2024-10-03 11:41:59', '2024-10-03 12:37:43'),
(5, 'SKU123-D', 'SUBRE – WooCommerce Product Subscription – Recurring Payments', 40.00, 30.00, 'https://villatheme.com/wp-content/uploads/2021/08/01_preview-7.jpg', 'Description for Product D', '2024-10-03 11:41:59', '2024-10-03 12:37:56'),
(6, 'SKU123-E', 'Clear Autoptimize Cache Automatically', 60.00, 55.00, 'https://villatheme.com/wp-content/uploads/2021/08/01_preview-7.jpg', 'Description for Product E', '2024-10-03 11:41:59', '2024-10-03 12:38:12'),
(8, 'SKU123-G', 'EPOW – WooCommerce Custom Product Options', 30.00, 25.00, 'https://villatheme.com/wp-content/uploads/2021/08/01_preview-7.jpg', 'Description for Product G', '2024-10-03 11:50:21', '2024-10-03 12:40:41'),
(9, 'SKU123-H', 'FEWC – WooCommerce Extra Checkout Fields', 20.00, 15.00, 'https://villatheme.com/wp-content/uploads/2021/08/01_preview-7.jpg', 'Description for Product H', '2024-10-03 11:50:21', '2024-10-03 12:41:03'),
(10, 'SKU123-J', 'HAPPY – Helpdesk – Support Ticket System for WordPress and WooCommerce', 40.00, 35.00, 'https://villatheme.com/wp-content/uploads/2021/08/01_preview-7.jpg', 'Description for Product J', '2024-10-03 11:50:21', '2024-10-03 12:38:58'),
(11, 'SKU123-K', 'Product K', 75.00, 65.00, 'https://villatheme.com/wp-content/uploads/2021/08/01_preview-7.jpg', 'Description for Product K', '2024-10-03 11:51:58', '2024-10-03 11:51:58'),
(18, 'SKU0015', 'Product 15', 100.00, 80.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRewwYxfQgfQHoR1abBr0xQoEEU6yYYVOvzLQ&s', 'Product 15 description', '2024-10-04 01:23:15', '2024-10-16 03:33:34'),
(71, 'SKU-EC63F3E5', 'vuong lam', 122.00, NULL, 'https://cellphones.com.vn/sforum/wp-content/uploads/2024/01/lich-chieu-phim-tien-nghich-4.jpg', NULL, '2024-10-15 08:27:34', '2024-10-16 01:15:13'),
(72, 'SKU-6774B4ED', 'hoang thien de', 1000.00, NULL, 'https://product.hstatic.net/200000425523/product/img_2468_1735630c6587484eb77e257388e8fc81.jpg', NULL, '2024-10-16 03:31:27', '2024-10-16 03:31:27'),
(73, 'SKU-411C5394', 'sona', 9999.00, NULL, 'https://thanhnien.mediacdn.vn/Uploaded/game/st.game.thanhnien.com.vn/image/25/2015/03/12.03/thanh-nien-game-esport-lmht-nu-tuong-co-vong-mot-dep-nhat-01.jpg', NULL, '2024-10-18 06:44:21', '2024-10-18 06:44:21'),
(74, 'SKU-757ECDC1', 'tran binh an', 18999.00, NULL, 'https://i.pinimg.com/736x/36/0a/df/360adf1890d453a4d219f9ac170fbac3.jpg', NULL, '2024-10-19 02:09:20', '2024-10-19 02:09:20'),
(83, 'SKU-21AC500B', 'ly mo uyen', 998.00, NULL, 'th.jfif', NULL, '2024-10-19 08:39:17', '2024-10-21 10:29:59'),
(88, 'SKU-9AE8BB67', 'vf', 100.00, NULL, 'Screenshot 2024-09-17 093655.png', NULL, '2024-10-21 04:09:05', '2024-10-21 04:09:05'),
(89, 'SKU-AA9A0103', 'de', 12.00, NULL, 'anhnahahad.png', NULL, '2024-10-21 04:25:26', '2024-10-21 04:25:26'),
(91, 'SKU-60A7472F', 'ssa', 100.00, NULL, 'images.jfif', NULL, '2024-10-21 05:00:47', '2024-10-21 09:17:49'),
(92, 'SKU-CC1B2F08', 'meo chu xa', 1999.00, NULL, 'images.jfif', NULL, '2024-10-21 09:03:36', '2024-10-22 04:05:12'),
(93, 'SKU-1318C7AC', 'gb', 130.00, NULL, 'fea698d6-936f-49b1-9385-cd5a66acad8b.jfif', NULL, '2024-10-22 01:24:07', '2024-10-22 01:24:07'),
(94, 'SKU-8AE08366', 'ong thu', 100.00, NULL, 'tải xuống.jfif', NULL, '2024-10-23 01:10:02', '2024-10-23 01:10:20'),
(95, 'SKU-D8BEE647', 'dss', 140.00, NULL, 'tải xuống.jpg', NULL, '2024-10-23 01:10:54', '2024-10-23 01:11:44');

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
(2, 1),
(2, 15),
(18, 2),
(18, 4),
(18, 27),
(18, 28),
(73, 4),
(73, 28),
(73, 30),
(73, 32),
(73, 33),
(74, 28),
(74, 29),
(74, 32),
(92, 7),
(92, 33),
(93, 3),
(93, 7),
(93, 33),
(94, 30),
(95, 4),
(95, 27);

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
(145, 72, 'https://product.hstatic.net/200000425523/product/img_2468_1735630c6587484eb77e257388e8fc81.jpg'),
(155, 71, 'https://i.pinimg.com/originals/71/83/67/71836727bdff43330b24c35446c121be.jpg'),
(164, 18, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRewwYxfQgfQHoR1abBr0xQoEEU6yYYVOvzLQ&s'),
(165, 18, 'https://i.pinimg.com/originals/71/83/67/71836727bdff43330b24c35446c121be.jpg'),
(166, 18, ' https://product.hstatic.net/200000425523/product/img_2468_1735630c6587484eb77e257388e8fc81.jpg'),
(168, 74, ''),
(169, 73, ''),
(191, 91, 'tải xuống (1).png'),
(192, 91, 'tải xuống.jfif'),
(193, 91, 'tải xuống.jpg'),
(199, 93, 'tải xuống.jfif'),
(200, 93, 'tải xuống.jpg'),
(201, 93, 'tải xuống.png'),
(202, 93, 'tải xuốngg.jpg'),
(220, 89, 'tải xuống (1).png'),
(221, 89, 'tải xuống.jfif'),
(222, 89, 'tải xuống.jpg'),
(224, 92, 'tải xuống.jfif'),
(225, 92, 'tải xuống.jpg'),
(226, 92, 'tải xuống.png'),
(227, 92, 'tải xuốngg.jpg'),
(236, 95, 'tải xuống.jfif'),
(237, 95, 'tải xuống.jpg'),
(238, 94, 'tải xuống.jfif'),
(239, 94, 'tải xuống.jpg'),
(240, 94, 'tải xuống.png'),
(241, 94, 'tải xuốngg.jpg');

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
(2, 10),
(2, 12),
(18, 10),
(18, 24),
(74, 9),
(74, 10),
(74, 12),
(74, 24),
(92, 27),
(93, 9),
(93, 10),
(93, 12),
(94, 10),
(94, 24);

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
(1, 'apartment'),
(26, 'C2'),
(2, 'ecommerce'),
(21, 'f6'),
(31, 'fc6'),
(30, 'HG'),
(25, 'l3'),
(12, 'mua dong'),
(9, 'New Tag 1'),
(10, 'New Tag 2'),
(24, 'SAPSHOST'),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT cho bảng `product_gallery`
--
ALTER TABLE `product_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT cho bảng `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
