-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2020 at 02:30 PM
-- Server version: 10.3.13-MariaDB
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `109`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `usually_question_id` int(11) NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `question` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `answer` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` int(11) DEFAULT 1,
  `active` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted` char(1) COLLATE utf8_unicode_ci DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `background`
--

CREATE TABLE `background` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `layout_config_id` int(11) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `text_color` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `attachment` varchar(50) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'Y',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` smallint(6) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `photo` varchar(255) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT 1,
  `left_ads` char(1) NOT NULL DEFAULT 'N',
  `right_ads` char(1) NOT NULL DEFAULT 'N',
  `md_banner_2` char(1) DEFAULT 'N',
  `md_banner_3` char(1) DEFAULT 'N',
  `vertical_slider` char(1) DEFAULT 'N',
  `active` char(1) NOT NULL DEFAULT 'Y',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`id`, `subdomain_id`, `language_id`, `depend_id`, `name`, `link`, `photo`, `type`, `sort`, `left_ads`, `right_ads`, `md_banner_2`, `md_banner_3`, `vertical_slider`, `active`, `deleted`, `created_at`, `modified_in`) VALUES
(49115, 3863, 1, 0, '', '', 'product_banner_2_image_Zt1vHMcS.png', NULL, 1, 'N', 'N', 'N', 'N', 'N', 'N', 'N', '2019-09-04 19:36:13', '0000-00-00 00:00:00'),
(49123, 3863, 1, 0, '', '', 'gold-tola_OUfqUQuT.jpg', NULL, 1, 'N', 'N', 'N', 'N', 'N', 'Y', 'N', '2019-09-04 19:36:13', '0000-00-00 00:00:00'),
(49124, 3863, 1, 0, 'đối tác', '', 'o_9V0Hk5Xc.jpg', NULL, 1, 'N', 'N', 'N', 'N', 'N', 'Y', 'N', '2019-09-04 19:36:13', '0000-00-00 00:00:00'),
(49125, 3863, 1, 0, '', '', 'samsung_Krr9jQZW.jpg', NULL, 1, 'N', 'N', 'N', 'N', 'N', 'Y', 'N', '2019-09-04 19:36:13', '0000-00-00 00:00:00'),
(49126, 3863, 1, 0, '', 'procam.vn/', '12procamt983g_L9tAN91X.png', NULL, 1, 'N', 'N', 'N', 'N', 'N', 'Y', 'N', '2019-09-04 19:36:13', '0000-00-00 00:00:00'),
(49127, 3863, 1, 0, '', '', 'brother_rqMr210f.jpg', NULL, 1, 'N', 'N', 'N', 'N', 'N', 'Y', 'N', '2019-09-04 19:36:13', '0000-00-00 00:00:00'),
(49128, 3863, 1, 0, '', '', 'laptop_kYGnxgV5.jpg', NULL, 1, 'N', 'N', 'N', 'N', 'N', 'Y', 'N', '2019-09-04 19:36:13', '0000-00-00 00:00:00'),
(49129, 3863, 1, 0, 'NK', 'https://www.nguyenkim.com/may-tinh-xach-tay/', 'sonysamsungapple_nZSLxOIh.jpg', NULL, 1, 'N', 'N', 'N', 'N', 'N', 'Y', 'N', '2019-09-04 19:36:13', '0000-00-00 00:00:00'),
(49133, 3863, 1, 0, '', '', '02_Lu1KeJs6.jpg', NULL, 1, 'N', 'N', 'N', 'N', 'N', 'Y', 'N', '2019-09-04 19:36:13', '2019-09-04 21:05:10'),
(49137, 3863, 1, 0, '', '', '01_kXw8Glnj.jpg', NULL, 1, 'N', 'N', 'N', 'N', 'N', 'Y', 'N', '2019-09-04 19:36:13', '2019-09-04 21:05:04');

-- --------------------------------------------------------

--
-- Table structure for table `banner_html`
--

CREATE TABLE `banner_html` (
  `id` int(11) NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sort` int(11) NOT NULL DEFAULT 1,
  `active` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `deleted` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banner_type`
--

CREATE TABLE `banner_type` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `module_item_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sort` int(11) NOT NULL,
  `slider` char(1) NOT NULL DEFAULT 'N',
  `partner` char(1) NOT NULL DEFAULT 'N',
  `type` int(11) NOT NULL,
  `active` char(1) NOT NULL,
  `deleted` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `banner_type`
--

INSERT INTO `banner_type` (`id`, `subdomain_id`, `module_item_id`, `name`, `created_at`, `modified_in`, `sort`, `slider`, `partner`, `type`, `active`, `deleted`) VALUES
(11069, 3863, 281791, 'Slider', '2019-09-04 19:36:13', '0000-00-00 00:00:00', 1, 'N', 'N', 2, 'Y', 'N'),
(11070, 3863, 281792, 'Đối tác', '2019-09-04 19:36:13', '0000-00-00 00:00:00', 2, 'N', 'N', 3, 'Y', 'N'),
(11071, 3863, 281793, 'Banner: Hình ảnh 1', '2019-09-04 19:36:13', '0000-00-00 00:00:00', 3, 'N', 'N', 1, 'Y', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` smallint(6) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `parent_id` int(11) NOT NULL,
  `row_id` varchar(75) DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `banner_md_sole` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `font_class` varchar(255) DEFAULT NULL,
  `icon_type` smallint(6) DEFAULT 1,
  `content` text DEFAULT NULL,
  `hits` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `sort_home` int(11) DEFAULT 1,
  `active` char(1) DEFAULT 'Y',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `hot` char(1) NOT NULL DEFAULT 'N',
  `menu` char(1) DEFAULT NULL,
  `show_home` char(1) NOT NULL DEFAULT 'N',
  `list` char(1) DEFAULT NULL,
  `picture` char(1) DEFAULT 'N',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8
PARTITION BY RANGE COLUMNS(`subdomain_id`)
(
PARTITION p0 VALUES LESS THAN (500) ENGINE=InnoDB,
PARTITION p1 VALUES LESS THAN (1000) ENGINE=InnoDB,
PARTITION p2 VALUES LESS THAN (1500) ENGINE=InnoDB,
PARTITION p3 VALUES LESS THAN (2000) ENGINE=InnoDB,
PARTITION p4 VALUES LESS THAN (2500) ENGINE=InnoDB,
PARTITION p5 VALUES LESS THAN (3000) ENGINE=InnoDB,
PARTITION p6 VALUES LESS THAN (3500) ENGINE=InnoDB,
PARTITION p7 VALUES LESS THAN (4000) ENGINE=InnoDB,
PARTITION p8 VALUES LESS THAN (MAXVALUE) ENGINE=InnoDB
);

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `subdomain_id`, `language_id`, `depend_id`, `parent_id`, `row_id`, `level`, `name`, `slug`, `banner`, `banner_md_sole`, `icon`, `font_class`, `icon_type`, `content`, `hits`, `title`, `keywords`, `description`, `sort`, `sort_home`, `active`, `deleted`, `hot`, `menu`, `show_home`, `list`, `picture`, `create_at`, `modified_in`) VALUES
(63263, 3863, 1, 0, 0, '911328aedd503c58a547', 0, 'Đồ khô ', 'do-kho', 'img532697_Z0sLzyOW.png', NULL, 'img532697_50sljIQH.png', '', 2, '', 0, '', '', '', 1, 1, 'Y', 'N', 'N', 'N', 'Y', NULL, 'N', '2019-09-04 20:11:41', '2019-09-04 20:27:26'),
(63264, 3863, 1, 0, 63263, '607f247df36d3761dbaf', 1, 'táo tàu', 'tao-tau', NULL, NULL, NULL, '', 1, '', 0, '', '', '', 1, 1, 'Y', 'N', 'N', 'N', 'N', NULL, 'N', '2019-09-04 20:11:54', '2019-09-04 20:27:26'),
(63265, 3863, 1, 0, 63263, '823f29f5ebee0417dc0f', 1, 'hồng sấy dẻo', 'hong-say-deo', NULL, NULL, NULL, '', 1, '', 0, '', '', '', 1, 1, 'Y', 'N', 'N', 'N', 'N', NULL, 'N', '2019-09-04 20:12:07', '2019-09-04 20:27:26'),
(63266, 3863, 1, 0, 0, '80c67c4a77a14b871940', 0, 'Các loại sữa của bé', 'cac-loai-sua-cua-be', NULL, NULL, 'suatamicon_pcTY74Ir.png', '', 2, '', 0, '', '', '', 1, 1, 'Y', 'N', 'N', 'N', 'Y', NULL, 'N', '2019-09-04 20:14:23', '2019-09-04 20:27:26'),
(63267, 3863, 1, 0, 0, '63267', 0, 'Mỹ phẩm, sữa tắm', 'my-pham-sua-tam', NULL, NULL, 'iconkemmakeup_7zuXCFqA.png', '', 2, '', 0, '', '', '', 1, 1, 'Y', 'N', 'N', 'N', 'Y', NULL, 'N', '2019-09-04 20:15:46', '2019-09-04 20:27:26'),
(63268, 3863, 1, 0, 0, '1527e8643cd927ebb47e', 0, 'Các loại sâm', 'cac-loai-sam', NULL, NULL, 'images_VjWpS394.png', '', 2, '', 0, '', '', '', 1, 1, 'Y', 'N', 'N', 'N', 'Y', NULL, 'N', '2019-09-04 20:16:42', '2019-09-04 20:27:26'),
(63269, 3863, 1, 0, 63268, '689f49b610fc1010ec2a', 1, 'sâm baby', 'sam-baby', NULL, NULL, NULL, '', 1, '', 0, '', '', '', 1, 1, 'Y', 'N', 'N', 'N', 'N', NULL, 'N', '2019-09-04 20:16:59', '2019-09-04 20:27:26'),
(63270, 3863, 1, 0, 63268, 'd1aeecfbda150f89fb2c', 1, 'sâm người lớn', 'sam-nguoi-lon', NULL, NULL, NULL, '', 1, '', 0, '', '', '', 1, 1, 'Y', 'N', 'N', 'N', 'N', NULL, 'N', '2019-09-04 20:17:23', '2019-09-04 20:27:26'),
(63271, 3863, 1, 0, 0, 'c760e5cedb1b007411e1', 0, 'Thuốc thực phẩm chức năng', 'thuoc-thuc-pham-chuc-nang', NULL, NULL, 'dongtrunghathao3_dXlP9qRZ.png', '', 2, '', 0, '', '', '', 1, 1, 'Y', 'N', 'N', 'N', 'Y', NULL, 'N', '2019-09-04 20:20:44', '2019-09-04 20:27:26');

-- --------------------------------------------------------

--
-- Table structure for table `clip`
--

CREATE TABLE `clip` (
  `id` int(11) NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` smallint(6) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `row_id` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `folder` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `summary` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `hits` int(11) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 1,
  `active` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `clip`
--

INSERT INTO `clip` (`id`, `subdomain_id`, `language_id`, `depend_id`, `row_id`, `name`, `slug`, `photo`, `code`, `folder`, `title`, `keywords`, `description`, `summary`, `hits`, `sort`, `active`, `created_at`, `modified_in`, `deleted`) VALUES
(11447, 3863, 1, 0, NULL, 'adasdasd', 'https://www.youtube.com/watch?v=ZwDxaM5VBJM', 'DV6jEMDW.jpg', 'ZwDxaM5VBJM', '17-05-2019', '', '', '', '', 0, 1, 'Y', '2019-09-04 19:36:21', '0000-00-00 00:00:00', 'N'),
(11448, 3863, 1, 0, NULL, 'Đất Kinh Bắc - Bắc Ninh', 'dat-kinh-bac-bac-ninh', 'lyBpFY0d.jpg', '9u6TuzrIhfI', '20-07-2019', 'Đất Kinh Bắc - Bắc Ninh', 'Đất Kinh Bắc - Bắc Ninh // Thành phố // Khu Công Nghiệp // Khách sạn // Du lịch', '', '', 0, 1, 'Y', '2019-09-04 19:36:21', '0000-00-00 00:00:00', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `config_core`
--

CREATE TABLE `config_core` (
  `id` int(11) UNSIGNED NOT NULL,
  `config_group_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `level` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `field` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `min_value` int(11) DEFAULT NULL,
  `max_value` int(11) DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `guide` text DEFAULT NULL,
  `place_holder` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL,
  `deleted` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config_core`
--

INSERT INTO `config_core` (`id`, `config_group_id`, `parent_id`, `level`, `name`, `field`, `value`, `min_value`, `max_value`, `type`, `description`, `guide`, `place_holder`, `created_at`, `modified_in`, `sort`, `active`, `deleted`) VALUES
(2, 1, 0, 0, 'Giao diện mobile', '_turn_off_mobile', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', NULL, NULL, 'radio', '', NULL, NULL, '2018-01-08 18:47:14', '2018-04-11 02:40:45', 3, 'Y', 'N'),
(3, 1, 0, 0, 'Bình luận facebook', '_turn_off_comment_facebook', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', '', NULL, '2018-01-22 17:55:29', '2018-08-13 00:08:58', 1, 'Y', 'N'),
(4, 1, 0, 0, 'Bật tắt nút gọi', '_turn_off_phone_alo', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-01-26 10:19:39', '2018-05-15 21:41:08', 4, 'Y', 'N'),
(5, 1, 4, 0, 'Số điện thoại nút gọi', '_txt_phone_alo', '', 0, 0, 'textarea', '', NULL, NULL, '2018-01-26 10:20:15', '2019-05-26 07:16:17', 2, 'Y', 'N'),
(6, 2, 0, 0, 'Banner top: Hiện giỏ hàng', '_turn_off_cart_banner', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', 'Ẩn hiện giỏ hàng', NULL, NULL, '2018-03-15 05:20:32', '2018-07-05 18:41:29', 8, 'Y', 'N'),
(7, 2, 6, 0, 'Nút mua hàng sản phẩm', '_turn_off_cart_btn', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', 'Ẩn hiện nút mua hàng', NULL, NULL, '2018-03-15 05:26:00', '2018-05-02 06:02:18', 2, 'Y', 'N'),
(8, 2, 6, 0, 'Giá sản phẩm', '_turn_off_product_price', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', 'Ẩn hiện giá sản phẩm', NULL, NULL, '2018-03-16 05:13:13', '2018-05-02 06:02:13', 1, 'Y', 'N'),
(9, 1, 4, 0, 'Vị trí nút gọi', '_positon_phone_ring', '[{\"name\":\"Trái\",\"value\":\"1\",\"select\":0},{\"name\":\"Phải\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-03-20 15:41:17', '2018-05-15 21:41:16', 1, 'Y', 'N'),
(10, 1, 2, 0, 'Cột trái, cột phải trên di động', '_turn_off_column_left_right_mobile', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-04-01 17:27:19', '2018-05-02 06:04:24', 1, 'Y', 'N'),
(11, 2, 14, 0, 'Hiện tên công ty ra sản phẩm', '_show_company_to_product', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-04-04 01:27:29', '2018-05-02 06:00:15', 1, 'Y', 'N'),
(12, 2, 14, 0, 'Hiện Hotline ra sản phẩm', '_show_hotline_to_product', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-04-04 01:29:39', '2018-05-02 06:00:20', 2, 'Y', 'N'),
(13, 2, 14, 0, 'Hiện email ra sản phẩm', '_show_email_to_product', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-04-04 01:30:31', '2018-05-02 06:00:24', 3, 'Y', 'N'),
(14, 2, 0, 0, 'Hiện nút xem chi tiết sản phẩm', '_show_btn_view_product_detail', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', NULL, NULL, 'radio', '', NULL, NULL, '2018-04-05 21:55:20', '2018-04-05 21:58:13', 13, 'Y', 'N'),
(15, 2, 0, 0, 'Số lượng sản phẩm mới', '_number_product_new', '9', 0, 100, 'text', '', NULL, NULL, '2018-04-12 04:40:49', '2018-04-12 04:47:53', 14, 'Y', 'N'),
(16, 2, 15, 0, 'Số lượng sản phẩm nổi bật', '_number_product_hot', '9', 0, 100, 'text', '', NULL, NULL, '2018-04-12 04:49:35', '2018-05-02 06:07:56', 1, 'Y', 'N'),
(17, 2, 15, 0, 'Số lượng sản phẩm theo danh mục', '_number_product_category_home', '9', 0, 100, 'text', '', NULL, NULL, '2018-04-12 04:51:23', '2018-05-02 06:08:02', 2, 'Y', 'N'),
(18, 1, 0, 0, 'Box hỗ trợ khách hàng', '_cf_customer_mesage', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-04-17 00:34:26', '2018-04-27 01:40:31', 100, 'Y', 'N'),
(19, 1, 18, 0, 'Vị trí box hỗ trợ', '_positon_customer_message', '[{\"name\":\"Trái\",\"value\":\"1\",\"select\":1},{\"name\":\"Phải\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-04-17 09:32:01', '2018-05-02 06:09:58', 0, 'Y', 'N'),
(20, 1, 0, 0, 'Popup yêu cầu báo giá', '_cf_frm_ycbg', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-04-18 00:17:56', '2018-04-27 01:40:45', 101, 'Y', 'N'),
(21, 1, 0, 0, 'Bật/Tắt tính năng gửi mail', '_cf_send_mail', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-04-22 07:39:07', '0000-00-00 00:00:00', 20, 'Y', 'N'),
(22, 3, 15, 0, 'Số lượng tin tức theo danh mục', '_number_news_menu_home', '9', 0, 100, 'text', '', NULL, NULL, '2018-04-25 18:53:58', '2018-05-02 06:08:06', 3, 'Y', 'N'),
(23, 1, 20, 0, 'Họ tên', '_frm_ycbg_name', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-04-26 09:09:59', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(24, 1, 20, 0, 'SĐT', '_frm_ycbg_phone', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-04-26 09:10:40', '2018-05-04 18:36:39', 2, 'Y', 'N'),
(25, 1, 20, 0, 'Email', '_frm_ycbg_email', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-04-26 18:03:07', '2018-04-26 18:07:21', 3, 'Y', 'N'),
(26, 1, 20, 0, 'Tiêu đề', '_frm_ycbg_subject', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-04-26 18:05:41', '2018-04-26 20:46:15', 4, 'Y', 'N'),
(28, 1, 20, 0, 'Yêu cầu khác', '_frm_ycbg_comment', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-04-26 18:15:24', '0000-00-00 00:00:00', 5, 'Y', 'N'),
(29, 1, 18, 0, 'Họ tên', '_customer_message_name', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-04-27 01:30:14', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(30, 1, 18, 0, 'SĐT', '_customer_message_phone', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-04-27 01:38:50', '2018-05-04 19:18:37', 2, 'Y', 'N'),
(31, 1, 18, 0, 'Tin nhắn', '_customer_message_comment', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-04-27 01:39:32', '2018-04-27 01:39:41', 3, 'Y', 'N'),
(32, 1, 21, 0, 'Email nhận đơn hàng', '_cf_text_email_order', '', 0, 0, 'email', '', NULL, NULL, '2018-05-02 14:12:31', '2018-05-02 14:41:15', 1, 'Y', 'N'),
(33, 1, 0, 0, 'Yêu cầu tư vấn', '_cf_mic_support', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-05-05 06:12:58', '2018-05-05 06:15:09', 102, 'Y', 'N'),
(34, 1, 33, 0, 'Vị trí', '_positon_box_mic_support', '[{\"name\":\"Trái\",\"value\":\"1\",\"select\":0},{\"name\":\"Phải\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-05-05 06:21:27', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(36, 2, 14, 0, 'Hiện mô tả sản phẩm', '_cf_radio_show_summary_to_product', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-05-10 18:32:28', '0000-00-00 00:00:00', 4, 'Y', 'N'),
(37, 1, 20, 0, 'Nơi cần đón', '_cf_radio_frm_ycbg_place_pic', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-05-12 19:41:43', '2018-05-13 06:57:15', 7, 'Y', 'N'),
(38, 1, 20, 0, 'Nơi cần đến', '_cf_radio_frm_ycbg_place_arrive', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-05-12 19:44:06', '2018-05-13 06:57:33', 6, 'Y', 'N'),
(39, 1, 20, 0, 'Loại xe', '_cf_radio_frm_ycbg_type', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-05-12 19:59:58', '0000-00-00 00:00:00', 8, 'Y', 'N'),
(40, 1, 20, 0, 'Ngày', '_cf_radio_frm_ycbg_day', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-05-12 20:02:33', '0000-00-00 00:00:00', 9, 'Y', 'N'),
(41, 1, 20, 0, 'Giờ', '_cf_radio_frm_ycbg_hour', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-05-12 20:10:06', '2018-05-13 06:15:40', 10, 'Y', 'N'),
(42, 1, 20, 0, 'Phút', '_cf_radio_frm_ycbg_minute', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-05-12 20:14:59', '2018-05-13 06:18:32', 11, 'Y', 'N'),
(44, 1, 0, 0, 'Banner lề trái', '_cf_radio_banner_ads_left', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-05-20 02:23:06', '2018-11-30 05:56:40', 9, 'Y', 'N'),
(45, 1, 0, 0, 'Banner lề phải', '_cf_radio_banner_ads_right', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-05-20 02:27:01', '2018-11-30 05:56:35', 10, 'Y', 'N'),
(46, 1, 44, 0, 'Chiều rộng (px)', '_cf_text_width_banner_ads_left', '120', 0, 0, 'text', '', NULL, NULL, '2018-05-20 02:32:18', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(47, 1, 44, 0, 'Cách Top (px)', '_cf_text_margin_top_banner_ads_left', '192', 0, 0, 'text', '', NULL, NULL, '2018-05-20 02:53:33', '0000-00-00 00:00:00', 2, 'Y', 'N'),
(48, 1, 44, 0, 'Canh trái tùy chỉnh (px)', '_cf_text_left_adjust_banner_ads_left', '0', 0, 0, 'text', '', NULL, NULL, '2018-05-20 03:09:27', '0000-00-00 00:00:00', 3, 'Y', 'N'),
(49, 1, 45, 0, 'Chiều rộng (px)', '_cf_text_width_banner_ads_right', '120', 0, 0, 'text', '', NULL, NULL, '2018-05-20 03:11:57', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(50, 1, 45, 0, 'Cách Top (px)', '_cf_text_margin_top_banner_ads_right', '192', 0, 0, 'text', '', NULL, NULL, '2018-05-20 03:16:36', '0000-00-00 00:00:00', 2, 'Y', 'N'),
(51, 1, 45, 0, 'Canh phải tùy chỉnh (px)', '_cf_text_right_adjust_banner_ads_right', '0', 0, 0, 'text', '', NULL, NULL, '2018-05-20 03:18:27', '0000-00-00 00:00:00', 3, 'Y', 'N'),
(52, 1, 2, 0, 'Menu mobile', '_cf_radio_menu_mobile', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-05-24 07:08:46', '0000-00-00 00:00:00', 2, 'Y', 'N'),
(53, 1, 54, 0, 'Chọn ngôn ngữ (Module đa ngôn ngữ)', '_cf_checkbox_select_language', '[{\"name\":\"Việt Nam\",\"value\":\"1\",\"select\":1},{\"name\":\"Anh\",\"value\":\"1\",\"select\":1},{\"name\":\"Đức\",\"value\":\"1\",\"select\":1},{\"name\":\"Hàn quốc\",\"value\":\"1\",\"select\":1},{\"name\":\"Nga\",\"value\":\"1\",\"select\":1},{\"name\":\"Trung quốc\",\"value\":\"1\",\"select\":1}]', 0, 0, 'checkbox', '', NULL, NULL, '2018-05-31 20:06:43', '2018-06-01 07:50:03', 1, 'Y', 'N'),
(54, 1, 0, 0, 'Hiện ngôn ngữ ra menu', '_cf_radio_menu_google_translate', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-06-01 07:27:44', '0000-00-00 00:00:00', 103, 'Y', 'N'),
(57, 1, 4, 0, 'Chọn kiểu nút gọi', '_cf_select_phone_alo_type', '[{\"name\":\"Kiểu 1: Kiểu 2: nút gọi và SĐT\",\"value\":\"1\",\"select\":1},{\"name\":\"Kiểu 2: chỉ nút gọi\",\"value\":\"2\",\"select\":0}]', 0, 0, 'select', '', NULL, NULL, '2018-06-19 04:39:13', '2018-06-19 05:27:33', 0, 'Y', 'N'),
(58, 1, 0, 0, 'Thanh sms trên mobile', '_cf_radio_bar_sms_mobile', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-06-19 09:12:15', '0000-00-00 00:00:00', 105, 'Y', 'N'),
(59, 1, 6, 0, 'Banner top: Hiện holine, địa chỉ, email', '_cf_radio_banner_hotline', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-05 18:38:03', '0000-00-00 00:00:00', -1, 'Y', 'N'),
(60, 1, 6, 0, 'Banner top: Hiện tìm kiếm', '_cf_radio_banner_search', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-05 18:40:18', '2018-07-07 20:38:35', 0, 'Y', 'N'),
(61, 1, 20, 0, 'Hiện yêu cầu báo giá ra menu bài viết', '_cf_radio_frm_ycbg_news', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-08 22:33:24', '2018-07-27 03:57:02', 0, 'Y', 'N'),
(62, 1, 20, 0, 'Lớp', '_frm_ycbg_class', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-21 08:16:52', '2018-07-21 08:23:59', 12, 'Y', 'N'),
(63, 1, 20, 0, 'Môn học', '_frm_ycbg_subjects', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-21 08:24:53', '2018-07-21 08:45:40', 13, 'Y', 'N'),
(64, 1, 20, 0, 'Số lượng học sinh', '_frm_ycbg_student_number', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-21 08:30:32', '2018-07-21 18:57:39', 14, 'Y', 'N'),
(65, 1, 20, 0, 'Học lực hiện tại', '_frm_ycbg_learning_level', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-21 08:35:58', '2018-07-21 21:04:24', 15, 'Y', 'N'),
(66, 1, 20, 0, 'Số buổi', '_frm_ycbg_learning_time', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-21 08:57:12', '0000-00-00 00:00:00', 15, 'Y', 'N'),
(67, 1, 20, 0, 'Thời gian học', '_frm_ycbg_learning_day', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-21 08:59:27', '0000-00-00 00:00:00', 16, 'Y', 'N'),
(68, 1, 20, 0, 'Yêu cầu', '_frm_ycbg_request', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-21 09:06:45', '0000-00-00 00:00:00', 17, 'Y', 'N'),
(69, 1, 20, 0, 'Mã số gia sư đã chọn', '_frm_ycbg_teacher_code', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-21 09:08:17', '0000-00-00 00:00:00', 18, 'Y', 'N'),
(70, 1, 18, 0, 'Hiện nhãn', '_cf_radio_cmg_label', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-23 18:44:32', '2018-07-23 18:45:33', 0, 'Y', 'N'),
(71, 1, 18, 0, 'Tỉnh/Thành dạy', '_cf_radio_cmg_work_province', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-23 18:47:56', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(72, 1, 18, 0, 'Ngày sinh', '_cf_radio_cmg_birthday', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-23 18:51:36', '2018-07-23 18:52:21', 2, 'Y', 'N'),
(73, 1, 18, 0, 'Tỉnh/Thành trên CMND', '_cf_radio_cmg_home_town', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-23 18:55:00', '2018-07-23 18:55:26', 4, 'Y', 'N'),
(74, 1, 18, 0, 'Chọn giọng nói', '_cf_radio_cmg_voice', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-23 18:57:23', '0000-00-00 00:00:00', 5, 'Y', 'N'),
(75, 1, 18, 0, 'Địa chỉ', '_cf_radio_cmg_address', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-23 18:58:43', '0000-00-00 00:00:00', 6, 'Y', 'N'),
(76, 1, 18, 0, 'Email', '_cf_radio_cmg_email', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-23 18:59:58', '0000-00-00 00:00:00', 7, 'Y', 'N'),
(77, 1, 18, 0, 'Ảnh thẻ', '_cf_radio_cmg_portrait', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-23 19:01:54', '0000-00-00 00:00:00', 8, 'Y', 'N'),
(78, 1, 18, 0, 'Ảnh bằng cấp', '_cf_radio_cmg_certificate', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-23 19:06:51', '2018-07-24 18:20:34', 9, 'Y', 'N'),
(79, 1, 18, 0, 'Sinh viên(giáo viên) trường', '_cf_radio_cmg_college_address', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-24 07:46:36', '0000-00-00 00:00:00', 10, 'Y', 'N'),
(80, 1, 18, 0, 'Ngành học', '_cf_radio_cmg_major', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-24 07:48:33', '0000-00-00 00:00:00', 11, 'Y', 'N'),
(81, 1, 18, 0, 'Năm tốt nghiệp', '_cf_radio_cmg_graduation_year', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-24 07:50:48', '0000-00-00 00:00:00', 11, 'Y', 'N'),
(82, 1, 18, 0, 'Hiện là', '_cf_radio_cmg_level', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-24 07:57:41', '2018-07-24 18:39:05', 12, 'Y', 'N'),
(83, 1, 18, 0, 'Ưu điểm', '_cf_radio_cmg_forte', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-24 08:02:06', '0000-00-00 00:00:00', 13, 'Y', 'N'),
(84, 1, 18, 0, 'Môn dạy', '_cf_radio_cmg_subjects', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-24 08:03:41', '0000-00-00 00:00:00', 13, 'Y', 'N'),
(85, 1, 18, 0, 'Lớp dạy', '_cf_radio_cmg_class', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-24 08:05:02', '0000-00-00 00:00:00', 14, 'Y', 'N'),
(86, 1, 18, 0, 'Thời gian dạy', '_cf_radio_cmg_teaching_time', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-24 08:12:22', '0000-00-00 00:00:00', 15, 'Y', 'N'),
(87, 1, 18, 0, 'Yêu cầu lương tối thiểu', '_cf_radio_cmg_salary', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-24 08:13:38', '0000-00-00 00:00:00', 16, 'Y', 'N'),
(88, 1, 18, 0, 'Giới tính', '_cf_radio_cmg_gender', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-24 18:37:45', '0000-00-00 00:00:00', 13, 'Y', 'N'),
(89, 1, 18, 0, 'Yêu cầu khác', '_cf_radio_cmg_other_request', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-25 17:56:49', '0000-00-00 00:00:00', 17, 'Y', 'N'),
(90, 2, 6, 0, 'Nút mua hàng chi tiết sản phẩm', '_cf_radio_cart_product_detail', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-27 00:33:08', '0000-00-00 00:00:00', 5, 'Y', 'N'),
(91, 1, 0, 0, 'Box đăng ký nhanh', '_cf_radio_fast_register', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-27 03:02:53', '0000-00-00 00:00:00', 102, 'Y', 'N'),
(92, 1, 91, 0, 'Hiện trong chi tiết sản phẩm', '_cf_radio_fast_register_product', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-27 03:53:51', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(93, 1, 91, 0, 'Số điện thoại', '_cf_radio_fast_register_phone', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-27 03:56:17', '2018-07-27 04:01:54', 2, 'Y', 'N'),
(94, 1, 91, 0, 'Hình thức', '_cf_radio_fast_register_method', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-27 03:58:35', '0000-00-00 00:00:00', 3, 'Y', 'N'),
(95, 1, 91, 0, 'Ngày', '_cf_radio_fast_register_day', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-27 04:03:45', '2018-07-27 04:05:09', 4, 'Y', 'N'),
(96, 1, 91, 0, 'Giờ', '_cf_radio_fast_register_hour', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-27 04:04:45', '0000-00-00 00:00:00', 5, 'Y', 'N'),
(97, 1, 91, 0, 'Yêu cầu thêm', '_cf_radio_fast_register_comment', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-07-27 04:06:29', '0000-00-00 00:00:00', 6, 'Y', 'N'),
(98, 1, 91, 0, 'Họ tên', '_cf_radio_fast_register_name', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-08-01 17:51:03', '2018-08-01 17:52:58', 2, 'Y', 'N'),
(99, 1, 91, 0, 'Địa chỉ', '_cf_radio_fast_register_address', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-08-01 17:54:37', '0000-00-00 00:00:00', 3, 'Y', 'N'),
(100, 1, 91, 0, 'Email', '_cf_radio_fast_register_email', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-08-01 17:57:14', '0000-00-00 00:00:00', 4, 'Y', 'N'),
(101, 1, 91, 0, 'Ngày/ giờ khởi hành', '_cf_radio_fast_register_start_time', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-08-01 18:08:52', '0000-00-00 00:00:00', 5, 'Y', 'N'),
(102, 1, 91, 0, 'Ngày giờ về', '_cf_radio_fast_register_end_time', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-08-01 18:11:12', '0000-00-00 00:00:00', 6, 'Y', 'N'),
(103, 1, 91, 0, 'Số lượng vé', '_cf_radio_fast_register_number_ticket', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-08-01 18:13:50', '0000-00-00 00:00:00', 7, 'Y', 'N'),
(104, 1, 3, 0, 'Chat facebook', '_cf_radio_facebook_messenger', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-08-11 07:18:42', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(105, 1, 3, 0, 'ID Fanpage', '_cf_text_fanpage_id', '', 0, 0, 'text', '', '<p style=\"text-align: center;\"><span style=\"font-size:16px;\"><strong>Hướng dẫn c&agrave;i đặt Facebook Messenger</strong></span></p>\r\n\r\n<p><span style=\"font-size:14px;\"><b>Ch&uacute; &yacute;: </b>Facebook chỉ li&ecirc;n kết được với Fanpage, kh&ocirc;ng li&ecirc;n kết được với trang c&aacute; nh&acirc;n</span></p>\r\n\r\n<p><span style=\"font-size:14px;\"><strong>Bước 1:</strong> T&igrave;m ID Fanpage tại đ&acirc;y:&nbsp; <a href=\"https://findmyfbid.com/\">https://findmyfbid.com/</a>&nbsp; - Sau đ&oacute; nhập ID Fanpage v&agrave;o cấu h&igrave;nh&nbsp;</span></p>\r\n\r\n<p><span style=\"font-size:14px;\"><img alt=\"KhÃ´ng cÃ³ vÄn báº£n thay tháº¿ tá»± Äá»ng nÃ o.\" src=\"https://scontent.fdad3-2.fna.fbcdn.net/v/t1.0-9/39061427_685305575182382_104170660783194112_n.png?_nc_cat=0&amp;oh=c55c2f04b404888f9924babb36323a98&amp;oe=5C08FDBE\" /></span></p>\r\n\r\n<p><span style=\"font-size:14px;\"><strong>Bước 2: </strong>C&agrave;i đặt li&ecirc;n kết&nbsp;&nbsp;Facebook với website</span></p>\r\n\r\n<p><span style=\"font-size:14px;\">-&nbsp;V&agrave;o Fanpage&nbsp; &gt; Chọn c&agrave;i đặt &gt; Nền tảng messenger &gt; Miền được đưa v&agrave;o danh s&aacute;ch hợp lệ ( tại đ&acirc;y th&ecirc;m link website v&agrave;o ).&nbsp;</span></p>\r\n\r\n<p><span style=\"font-size:14px;\"><img alt=\"\" src=\"https://scontent.fdad3-2.fna.fbcdn.net/v/t1.15752-9/39071445_290206001532051_6586854032323641344_n.png?_nc_cat=0&amp;oh=04a2d440957196602a691f1f2e6a0f69&amp;oe=5C0C4DA7\" /></span></p>\r\n', NULL, '2018-08-11 07:24:39', '2018-08-13 01:34:30', 2, 'Y', 'N'),
(106, 1, 58, 0, 'Chọn kiểu', '_cf_select_sms_mobile_type', '[{\"name\":\"Kiểu 1\",\"value\":\"1\",\"select\":1},{\"name\":\"Kiểu 2\",\"value\":\"2\",\"select\":0}]', 0, 0, 'select', '', NULL, NULL, '2018-08-14 17:57:29', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(107, 2, 6, 0, 'Link khác nút mua hàng', '_cf_text_cart_other_url', '', 0, 0, 'text', '', NULL, NULL, '2018-08-17 19:07:41', '0000-00-00 00:00:00', 6, 'Y', 'N'),
(108, 2, 6, 0, 'Link khác cho tất cả sản phẩm', '_cf_text_all_product_url', '', 0, 0, 'text', '', NULL, NULL, '2018-08-17 23:18:08', '0000-00-00 00:00:00', 7, 'Y', 'N'),
(109, 2, 15, 0, 'Số lượng sản phẩm nổi bật trên 1 hàng', '_cf_select_number_product_hot_in_line', '[{\"name\":\"2\",\"value\":\"2\",\"select\":0},{\"name\":\"3\",\"value\":\"3\",\"select\":1},{\"name\":\"4\",\"value\":\"4\",\"select\":0},{\"name\":\"5\",\"value\":\"5\",\"select\":0}]', 0, 0, 'select', '', NULL, NULL, '2018-08-23 03:03:11', '0000-00-00 00:00:00', 5, 'Y', 'N'),
(110, 2, 15, 0, 'Số lượng sản phẩm trên 1 trang', '_cf_text_number_item_on_page', '24', 2, 200, 'text', '', NULL, NULL, '2018-09-05 20:42:04', '0000-00-00 00:00:00', 5, 'Y', 'N'),
(111, 3, 15, 0, 'Số lượng tin tức trên 1 trang', '_cf_text_number_news_on_page', '24', 2, 200, 'text', '', NULL, NULL, '2018-09-11 17:54:29', '0000-00-00 00:00:00', 6, 'Y', 'N'),
(112, 2, 14, 0, 'Nút xem chi tiết Menu sản phẩm ( kèm hình )', '_cf_radio_category_hot_btn_view_more', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-09-25 18:32:17', '2018-09-25 18:37:18', 5, 'Y', 'N'),
(113, 1, 0, 0, 'Javascript resize hình đại diện', '_cf_radio_javascript_resize', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-10-05 18:26:12', '0000-00-00 00:00:00', 110, 'Y', 'N'),
(114, 2, 14, 0, 'Ghi chú sau giá ở chi tiết sản phẩm', '_cf_text_note_price_product', '', 0, 0, 'text', '', NULL, NULL, '2018-10-08 18:25:05', '0000-00-00 00:00:00', 12, 'Y', 'N'),
(115, 1, 0, 0, 'Hiện ngôn ngữ (tự nhập) ra menu', '_cf_radio_menu_language_database', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-10-12 01:07:18', '0000-00-00 00:00:00', 106, 'Y', 'N'),
(116, 1, 33, 0, 'Hiện trên bài viết', '_cf_radio_show_header_news_menu', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-10-27 19:01:23', '0000-00-00 00:00:00', 2, 'Y', 'N'),
(117, 1, 33, 0, 'Hiện dưới bài viết', '_cf_radio_show_footer_news_menu', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-10-27 19:03:12', '0000-00-00 00:00:00', 3, 'Y', 'N'),
(118, 1, 33, 0, 'Hiện trên bài trang chủ', '_cf_radio_show_header_home_article', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-10-30 08:28:03', '0000-00-00 00:00:00', 4, 'Y', 'N'),
(119, 1, 33, 0, 'Hiện dưới bài trang chủ', '_cf_radio_show_footer_home_article', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-10-30 17:56:26', '0000-00-00 00:00:00', 5, 'Y', 'N'),
(120, 1, 33, 0, 'Hiện trên bài liên hệ', '_cf_radio_show_header_contact_content', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-10-30 18:01:33', '0000-00-00 00:00:00', 6, 'Y', 'N'),
(121, 1, 33, 0, 'Hiện dưới bài liên hệ', '_cf_radio_show_footer_contact_content', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-10-30 18:03:39', '0000-00-00 00:00:00', 7, 'Y', 'N'),
(122, 1, 0, 0, 'Module quản trị', '_cf_radio_module_administrator', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-11-06 18:42:38', '0000-00-00 00:00:00', 130, 'Y', 'N'),
(123, 1, 0, 0, 'Module zalo, fb, hotline', '_cf_radio_module_zalo_fb_hotline', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-11-10 08:49:33', '0000-00-00 00:00:00', 140, 'Y', 'N'),
(124, 1, 123, 0, 'Link Zalo', '_cf_text_link_zalo', '', 0, 0, 'textarea', '', NULL, NULL, '2018-11-10 08:53:40', '2019-05-28 06:30:40', 1, 'Y', 'N'),
(125, 1, 123, 0, 'Link Facebook', '_cf_text_link_fb', '', 0, 0, 'text', '', NULL, NULL, '2018-11-10 08:54:31', '0000-00-00 00:00:00', 2, 'Y', 'N'),
(126, 1, 123, 0, 'Số Hotline', '_cf_text_hotline_number', '', 0, 0, 'textarea', '', NULL, NULL, '2018-11-10 08:55:43', '2019-05-28 06:32:30', 3, 'Y', 'N'),
(127, 1, 6, 0, 'Banner top: Hiện ô tìm kiếm', '_cf_radio_banner_search_input', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-11-15 06:45:08', '2018-11-15 06:46:34', 1, 'Y', 'N'),
(128, 1, 6, 0, 'Banner top: Hiện danh mục tìm kiếm', '_cf_radio_banner_search_category', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-11-15 06:48:46', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(129, 1, 123, 0, 'Ẩn/Hiện Zalo', '_cf_radio_zalo_show_hide', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-11-16 19:27:19', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(130, 1, 123, 0, 'Ẩn/Hiện Facebook', '_cf_radio_facebook_show_hide', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-11-16 19:29:55', '0000-00-00 00:00:00', 2, 'Y', 'N'),
(131, 1, 123, 0, 'Ẩn/Hiện Hotline', '_cf_radio_hotline_show_hide', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-11-16 19:32:13', '0000-00-00 00:00:00', 3, 'Y', 'N'),
(132, 2, 14, 0, 'Hiện tìm kiếm ra module sản phẩm theo danh mục', '_cf_radio_search_for_home_category', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-12-04 21:08:40', '0000-00-00 00:00:00', 7, 'Y', 'N'),
(133, 2, 14, 0, 'Hiện lượt xem ra sản phẩm', '_cf_radio_show_views_to_product', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-12-20 18:14:40', '0000-00-00 00:00:00', 5, 'Y', 'N'),
(134, 2, 14, 0, 'Hiện lượt mua ra sản phẩm', '_cf_radio_show_purchase_number_to_product', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-12-20 18:17:02', '0000-00-00 00:00:00', 5, 'Y', 'N'),
(135, 1, 18, 0, 'Box tùy chọn - Select', '_cf_radio_box_option', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2018-12-21 18:43:36', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(136, 2, 14, 0, 'Chọn danh sách sản phẩm cùng loại', '_cf_select_type_other_product', '[{\"name\":\"Kiểu 1: sản phẩm thuộc cùng danh mục\",\"value\":\"1\",\"select\":1},{\"name\":\"Kiểu 2: sản phẩm thuộc cùng danh mục cha\",\"value\":\"2\",\"select\":0}]', 0, 0, 'select', '', NULL, NULL, '2018-12-28 20:15:38', '0000-00-00 00:00:00', 11, 'Y', 'N'),
(137, 1, 0, 0, 'Thống kê truy cập', '_cf_radio_access_online', '', 0, 0, 'radio', '', NULL, NULL, '2018-12-29 02:04:53', '0000-00-00 00:00:00', 150, 'Y', 'N'),
(139, 1, 137, 0, 'Đang online', '_cf_radio_access_online_now', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-12-29 02:08:53', '2018-12-29 02:10:53', 1, 'Y', 'N'),
(140, 1, 137, 0, 'Hôm qua', '_cf_radio_access_online_yesterday', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-12-29 02:13:15', '0000-00-00 00:00:00', 2, 'Y', 'N'),
(141, 1, 137, 0, 'Hôm nay', '_cf_radio_access_online_today', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-12-29 02:14:55', '0000-00-00 00:00:00', 3, 'Y', 'N'),
(142, 1, 137, 0, 'Tuần này', '_cf_radio_access_online_this_week', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-12-29 02:16:22', '0000-00-00 00:00:00', 4, 'Y', 'N'),
(143, 1, 137, 0, 'Tháng này', '_cf_radio_access_online_this_month', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-12-29 02:18:24', '0000-00-00 00:00:00', 5, 'Y', 'N'),
(144, 1, 137, 0, 'Năm nay', '_cf_radio_access_online_this_year', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-12-29 02:20:15', '0000-00-00 00:00:00', 6, 'Y', 'N'),
(145, 1, 137, 0, 'Tổng truy cập', '_cf_radio_access_online_total', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2018-12-29 02:21:16', '0000-00-00 00:00:00', 7, 'Y', 'N'),
(146, 2, 0, 0, 'Đơn vị tiền tệ', '_cf_text_price_text', '', 0, 0, 'text', '', NULL, NULL, '2019-01-17 06:21:05', '2019-01-17 06:34:18', 15, 'Y', 'N'),
(147, 2, 146, 0, 'Vị trí hiển thị', '_cf_radio_price_unit_position', '[{\"name\":\"Trước giá\",\"value\":\"1\",\"select\":0},{\"name\":\"Sau giá\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2019-01-17 06:24:29', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(148, 1, 20, 0, 'Gửi CV', '_frm_ycbg_file', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2019-03-08 21:43:04', '0000-00-00 00:00:00', 19, 'Y', 'N'),
(149, 2, 14, 0, 'Kiểu hiển thị Menu trái theo danh mục sản phẩm', '_cf_select_display_menu_category_left', '[{\"name\":\"Kiểu 1: Hiên trực quan menu con\",\"value\":\"1\",\"select\":1},{\"name\":\"Kiểu 2: Rê chuột hiện menu con\",\"value\":\"2\",\"select\":0}]', 0, 0, 'select', '', NULL, NULL, '2019-03-17 09:29:06', '0000-00-00 00:00:00', 7, 'Y', 'N'),
(150, 1, 0, 0, 'Chọn ngôn ngữ thông báo mặc định', '_cf_radio_select_message_lang_default', '[{\"name\":\"Tiếng Việt\",\"value\":\"vi\",\"select\":1},{\"name\":\"English\",\"value\":\"en\",\"select\":0},{\"name\":\"Pháp\",\"value\":\"fr\",\"select\":0},{\"name\":\"Đức\",\"value\":\"de\",\"select\":0},{\"name\":\"Trung quốc\",\"value\":\"zh-CN\",\"select\":0},{\"name\":\"Nhật Bản\",\"value\":\"ja\",\"select\":0},{\"name\":\"Hàn Quốc\",\"value\":\"ko\",\"select\":0},{\"name\":\"Nga\",\"value\":\"ru\",\"select\":0}]', 0, 0, 'select', '', NULL, NULL, '2019-03-25 18:07:57', '2019-03-25 20:08:22', 160, 'Y', 'N'),
(151, 2, 0, 0, 'Kiểu giỏ hàng', '_cf_select_cart_type', '[{\"name\":\"Chuyển link\",\"value\":\"1\",\"select\":1},{\"name\":\"Thông báo\",\"value\":\"2\",\"select\":0}]', 0, 0, 'select', '', NULL, NULL, '2019-04-05 19:45:56', '2019-04-05 19:50:19', 170, 'Y', 'N'),
(152, 1, 0, 0, 'Hình thumbnail sản phẩm', '_cf_radio_enable_thumbnail', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2019-04-06 20:36:51', '2019-05-17 18:26:32', 180, 'Y', 'N'),
(153, 1, 152, 0, 'Chiều rộng', '_cf_text_thumbnail_width', '300', 200, 500, 'text', '', NULL, NULL, '2019-04-06 20:40:28', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(154, 1, 152, 0, 'Chiều cao', '_cf_text_thumbnail_height', '300', 200, 500, 'text', '', NULL, NULL, '2019-04-06 20:41:57', '0000-00-00 00:00:00', 2, 'Y', 'N'),
(155, 2, 0, 0, 'Hiệu ứng module sản phẩm kèm hình', '_cf_radio_category_hot_effect', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2019-05-03 07:21:57', '0000-00-00 00:00:00', 15, 'Y', 'N'),
(156, 2, 155, 0, 'Số lượng sản phẩm', '_cf_text_number_product_category_effect', '150', 1, 0, 'text', '', NULL, NULL, '2019-05-03 07:24:32', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(157, 1, 0, 0, 'Hiệu ứng lazyload hình ảnh', '_cf_radio_enable_lazyload_image', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', NULL, NULL, '2019-05-17 18:23:05', '2019-05-20 07:36:30', 180, 'Y', 'N'),
(158, 1, 152, 0, 'Hình thumbnail tin tức', '_cf_radio_enable_thumbnail_news', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2019-05-17 18:32:01', '0000-00-00 00:00:00', 3, 'Y', 'N'),
(159, 1, 152, 0, 'Chiều rộng', '_cf_text_thumbnail_width_news', '300', 0, 0, 'text', '', NULL, NULL, '2019-05-17 18:36:00', '0000-00-00 00:00:00', 4, 'Y', 'N'),
(160, 1, 152, 0, 'Chiều cao', '_cf_text_thumbnail_height_news', '300', 0, 0, 'text', '', NULL, NULL, '2019-05-17 18:37:41', '0000-00-00 00:00:00', 5, 'Y', 'N'),
(161, 1, 152, 0, 'Hình thumbnail slider', '_cf_radio_enable_thumbnail_slider', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, 'radio', '', NULL, NULL, '2019-05-17 18:45:17', '0000-00-00 00:00:00', 6, 'Y', 'N'),
(162, 1, 152, 0, 'Hình thumbnail đối tác', '_cf_radio_enable_thumbnail_partner', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2019-05-17 18:47:24', '2019-05-21 06:12:51', 9, 'Y', 'N'),
(163, 1, 152, 0, 'Chiều rộng', '_cf_text_thumbnail_width_slider', '1366', 0, 0, 'text', '', NULL, NULL, '2019-05-17 18:48:44', '0000-00-00 00:00:00', 7, 'Y', 'N'),
(164, 1, 152, 0, 'Chiều rộng', '_cf_text_thumbnail_height_slider', '450', 0, 0, 'text', '', NULL, NULL, '2019-05-17 18:50:35', '0000-00-00 00:00:00', 8, 'Y', 'N'),
(165, 1, 152, 0, 'Chiều rộng', '_cf_text_thumbnail_width_partner', '240', 0, 0, 'text', '', NULL, NULL, '2019-05-17 18:52:34', '0000-00-00 00:00:00', 10, 'Y', 'N'),
(166, 1, 152, 0, 'Chiều cao', '_cf_text_thumbnail_height_partner', '180', 0, 0, 'text', '', NULL, NULL, '2019-05-17 18:53:40', '0000-00-00 00:00:00', 11, 'Y', 'N'),
(167, 1, 123, 0, 'Chọn kiểu nút Zalo', '_cf_select_zalo_button_type', '[{\"name\":\"Kiểu 1: Mặc định\",\"value\":\"1\",\"select\":1},{\"name\":\"Kiểu 2: Hiệu ứng\",\"value\":\"2\",\"select\":0}]', 0, 0, 'select', '', NULL, NULL, '2019-07-20 19:39:15', '2019-07-20 20:11:05', 1, 'Y', 'N'),
(168, 2, 0, 0, 'Ẩn danh sách danh mục con trong trang sản phẩm', '_cf_radio_enable_list_category_in_product_page', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, 'radio', '', NULL, NULL, '2019-10-03 05:41:23', '2019-10-03 05:58:11', 190, 'Y', 'N'),
(169, 2, 146, 0, 'Đơn vị tiền tệ (USD)', '_cf_text_price_usd_currency', '$', 0, 0, 'text', '', NULL, NULL, '2020-03-12 06:42:05', '0000-00-00 00:00:00', 2, 'Y', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `config_group`
--

CREATE TABLE `config_group` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'Y',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config_group`
--

INSERT INTO `config_group` (`id`, `name`, `module`, `type`, `sort`, `active`, `deleted`, `create_at`, `modified_in`) VALUES
(1, 'Cấu hình chung', 'gerenal', NULL, 1, 'Y', 'N', '2017-11-04 02:21:29', '2017-11-04 02:42:52'),
(2, 'Sản phẩm', 'product', NULL, 2, 'Y', 'N', '2017-11-04 14:01:28', '0000-00-00 00:00:00'),
(3, 'Tin tức', 'news', NULL, 3, 'Y', 'N', '2018-04-25 18:51:52', '2018-04-25 18:51:57');

-- --------------------------------------------------------

--
-- Table structure for table `config_item`
--

CREATE TABLE `config_item` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `config_group_id` int(11) NOT NULL,
  `config_core_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `field` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `min_value` int(11) DEFAULT NULL,
  `max_value` int(11) DEFAULT NULL,
  `value_actual` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `place_holder` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL,
  `deleted` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8
PARTITION BY RANGE COLUMNS(`subdomain_id`)
(
PARTITION p0 VALUES LESS THAN (600) ENGINE=InnoDB,
PARTITION p1 VALUES LESS THAN (1200) ENGINE=InnoDB,
PARTITION p2 VALUES LESS THAN (1800) ENGINE=InnoDB,
PARTITION p3 VALUES LESS THAN (2400) ENGINE=InnoDB,
PARTITION p4 VALUES LESS THAN (3000) ENGINE=InnoDB,
PARTITION p5 VALUES LESS THAN (3600) ENGINE=InnoDB,
PARTITION p6 VALUES LESS THAN (4200) ENGINE=InnoDB,
PARTITION p7 VALUES LESS THAN (MAXVALUE) ENGINE=InnoDB
);

--
-- Dumping data for table `config_item`
--

INSERT INTO `config_item` (`id`, `subdomain_id`, `config_group_id`, `config_core_id`, `name`, `field`, `value`, `min_value`, `max_value`, `value_actual`, `type`, `description`, `place_holder`, `created_at`, `modified_in`, `sort`, `active`, `deleted`) VALUES
(544699, 3863, 1, 2, 'Giao diện mobile', '_turn_off_mobile', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', NULL, NULL, NULL, 'radio', '', NULL, '2018-09-29 00:08:30', '2019-07-22 20:02:54', 3, 'Y', 'N'),
(544700, 3863, 1, 3, 'Bình luận facebook', '_turn_off_comment_facebook', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:30', '2019-07-22 20:02:54', 1, 'Y', 'N'),
(544701, 3863, 1, 4, 'Bật tắt nút gọi', '_turn_off_phone_alo', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:30', '2019-09-04 21:39:36', 4, 'Y', 'N'),
(544702, 3863, 1, 5, 'Số điện thoại nút gọi', '_txt_phone_alo', '0975 516 751', 0, 0, NULL, 'textarea', '', NULL, '2018-09-29 00:08:30', '2019-09-04 21:39:36', 2, 'Y', 'N'),
(544703, 3863, 2, 6, 'Banner top: Hiện giỏ hàng', '_turn_off_cart_banner', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', 'Ẩn hiện giỏ hàng', NULL, '2018-09-29 00:08:30', '2019-07-22 20:02:54', 8, 'Y', 'N'),
(544704, 3863, 2, 7, 'Nút mua hàng sản phẩm', '_turn_off_cart_btn', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', 'Ẩn hiện nút mua hàng', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 2, 'Y', 'N'),
(544705, 3863, 2, 8, 'Giá sản phẩm', '_turn_off_product_price', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', 'Ẩn hiện giá sản phẩm', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 1, 'Y', 'N'),
(544706, 3863, 1, 9, 'Vị trí nút gọi', '_positon_phone_ring', '[{\"name\":\"Trái\",\"value\":\"1\",\"select\":1},{\"name\":\"Phải\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-09-04 21:39:36', 1, 'Y', 'N'),
(544707, 3863, 1, 10, 'Cột trái, cột phải trên di động', '_turn_off_column_left_right_mobile', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 1, 'Y', 'N'),
(544708, 3863, 2, 11, 'Hiện tên công ty ra sản phẩm', '_show_company_to_product', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 1, 'Y', 'N'),
(544709, 3863, 2, 12, 'Hiện Hotline ra sản phẩm', '_show_hotline_to_product', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 2, 'Y', 'N'),
(544710, 3863, 2, 13, 'Hiện email ra sản phẩm', '_show_email_to_product', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 3, 'Y', 'N'),
(544711, 3863, 2, 14, 'Hiện nút xem chi tiết sản phẩm', '_show_btn_view_product_detail', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', NULL, NULL, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 13, 'Y', 'N'),
(544712, 3863, 2, 15, 'Số lượng sản phẩm mới', '_number_product_new', '12', 0, 100, NULL, 'text', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 14, 'Y', 'N'),
(544713, 3863, 2, 16, 'Số lượng sản phẩm nổi bật', '_number_product_hot', '12', 0, 100, NULL, 'text', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 1, 'Y', 'N'),
(544714, 3863, 2, 17, 'Số lượng sản phẩm theo danh mục', '_number_product_category_home', '5', 0, 100, NULL, 'text', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 2, 'Y', 'N'),
(544715, 3863, 1, 18, 'Box hỗ trợ khách hàng', '_cf_customer_mesage', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 100, 'Y', 'N'),
(544716, 3863, 1, 19, 'Vị trí box hỗ trợ', '_positon_customer_message', '[{\"name\":\"Trái\",\"value\":\"1\",\"select\":0},{\"name\":\"Phải\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 0, 'Y', 'N'),
(544717, 3863, 1, 20, 'Popup yêu cầu báo giá', '_cf_frm_ycbg', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 101, 'Y', 'N'),
(544718, 3863, 1, 21, 'Bật/Tắt tính năng gửi mail', '_cf_send_mail', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 20, 'Y', 'N'),
(544719, 3863, 3, 22, 'Số lượng tin tức theo danh mục', '_number_news_menu_home', '12', 0, 100, NULL, 'text', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 3, 'Y', 'N'),
(544720, 3863, 1, 23, 'Họ tên', '_frm_ycbg_name', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 1, 'Y', 'N'),
(544721, 3863, 1, 24, 'SĐT', '_frm_ycbg_phone', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 2, 'Y', 'N'),
(544722, 3863, 1, 25, 'Email', '_frm_ycbg_email', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 3, 'Y', 'N'),
(544723, 3863, 1, 26, 'Tiêu đề', '_frm_ycbg_subject', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 4, 'Y', 'N'),
(544724, 3863, 1, 28, 'Yêu cầu khác', '_frm_ycbg_comment', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 5, 'Y', 'N'),
(544725, 3863, 1, 29, 'Họ tên', '_customer_message_name', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 1, 'Y', 'N'),
(544726, 3863, 1, 30, 'SĐT', '_customer_message_phone', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 2, 'Y', 'N'),
(544727, 3863, 1, 31, 'Tin nhắn', '_customer_message_comment', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 3, 'Y', 'N'),
(544728, 3863, 1, 32, 'Email nhận đơn hàng', '_cf_text_email_order', 'topbacninh@gmail.com', 0, 0, NULL, 'email', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 1, 'Y', 'N'),
(544729, 3863, 1, 33, 'Yêu cầu tư vấn', '_cf_mic_support', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:56', 102, 'Y', 'N'),
(544730, 3863, 1, 34, 'Vị trí', '_positon_box_mic_support', '[{\"name\":\"Trái\",\"value\":\"1\",\"select\":0},{\"name\":\"Phải\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:56', 1, 'Y', 'N'),
(544731, 3863, 2, 36, 'Hiện mô tả sản phẩm', '_cf_radio_show_summary_to_product', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 4, 'Y', 'N'),
(544732, 3863, 1, 37, 'Nơi cần đón', '_cf_radio_frm_ycbg_place_pic', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 7, 'Y', 'N'),
(544733, 3863, 1, 38, 'Nơi cần đến', '_cf_radio_frm_ycbg_place_arrive', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 6, 'Y', 'N'),
(544734, 3863, 1, 39, 'Loại xe', '_cf_radio_frm_ycbg_type', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 8, 'Y', 'N'),
(544735, 3863, 1, 40, 'Ngày', '_cf_radio_frm_ycbg_day', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 9, 'Y', 'N'),
(544736, 3863, 1, 41, 'Giờ', '_cf_radio_frm_ycbg_hour', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 10, 'Y', 'N'),
(544737, 3863, 1, 42, 'Phút', '_cf_radio_frm_ycbg_minute', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 11, 'Y', 'N'),
(544738, 3863, 1, 44, 'Banner lề trái', '_cf_radio_banner_ads_left', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2018-11-30 05:56:45', 9, 'Y', 'N'),
(544739, 3863, 1, 45, 'Banner lề phải', '_cf_radio_banner_ads_right', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2018-11-30 05:56:40', 10, 'Y', 'N'),
(544740, 3863, 1, 46, 'Chiều rộng (px)', '_cf_text_width_banner_ads_left', '120', 0, 0, NULL, 'text', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 1, 'Y', 'N'),
(544741, 3863, 1, 47, 'Cách Top (px)', '_cf_text_margin_top_banner_ads_left', '215', 0, 0, NULL, 'text', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 2, 'Y', 'N'),
(544742, 3863, 1, 48, 'Canh trái tùy chỉnh (px)', '_cf_text_left_adjust_banner_ads_left', '0', 0, 0, NULL, 'text', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 3, 'Y', 'N'),
(544743, 3863, 1, 49, 'Chiều rộng (px)', '_cf_text_width_banner_ads_right', '120', 0, 0, NULL, 'text', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 1, 'Y', 'N'),
(544744, 3863, 1, 50, 'Cách Top (px)', '_cf_text_margin_top_banner_ads_right', '215', 0, 0, NULL, 'text', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 2, 'Y', 'N'),
(544745, 3863, 1, 51, 'Canh phải tùy chỉnh (px)', '_cf_text_right_adjust_banner_ads_right', '5', 0, 0, NULL, 'text', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 3, 'Y', 'N'),
(544746, 3863, 1, 52, 'Menu mobile', '_cf_radio_menu_mobile', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 2, 'Y', 'N'),
(544747, 3863, 1, 53, 'Chọn ngôn ngữ (Module đa ngôn ngữ)', '_cf_checkbox_select_language', '[{\"name\":\"Việt Nam\",\"value\":\"1\",\"select\":1},{\"name\":\"Anh\",\"value\":\"1\",\"select\":1},{\"name\":\"Đức\",\"value\":\"1\",\"select\":0},{\"name\":\"Hàn quốc\",\"value\":\"1\",\"select\":1},{\"name\":\"Nga\",\"value\":\"1\",\"select\":0},{\"name\":\"Trung quốc\",\"value\":\"1\",\"select\":1}]', 0, 0, NULL, 'checkbox', '', NULL, '2018-09-29 00:08:31', '2019-07-20 04:30:37', 1, 'Y', 'N'),
(544748, 3863, 1, 54, 'Hiện ngôn ngữ ra menu', '_cf_radio_menu_google_translate', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-20 04:30:37', 103, 'Y', 'N'),
(544749, 3863, 1, 57, 'Chọn kiểu nút gọi', '_cf_select_phone_alo_type', '[{\"name\":\"Kiểu 1: Kiểu 2: nút gọi và SĐT\",\"value\":\"1\",\"select\":1},{\"name\":\"Kiểu 2: chỉ nút gọi\",\"value\":\"2\",\"select\":0}]', 0, 0, NULL, 'select', '', NULL, '2018-09-29 00:08:31', '2019-09-04 21:39:36', 0, 'Y', 'N'),
(544750, 3863, 1, 58, 'Thanh sms trên mobile', '_cf_radio_bar_sms_mobile', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:56', 105, 'Y', 'N'),
(544751, 3863, 1, 59, 'Banner top: Hiện holine, địa chỉ, email', '_cf_radio_banner_hotline', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', -1, 'Y', 'N'),
(544752, 3863, 1, 60, 'Banner top: Hiện tìm kiếm', '_cf_radio_banner_search', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 0, 'Y', 'N'),
(544753, 3863, 1, 61, 'Hiện yêu cầu báo giá ra menu bài viết', '_cf_radio_frm_ycbg_news', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 0, 'Y', 'N'),
(544754, 3863, 1, 62, 'Lớp', '_frm_ycbg_class', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 12, 'Y', 'N'),
(544755, 3863, 1, 63, 'Môn học', '_frm_ycbg_subjects', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 13, 'Y', 'N'),
(544756, 3863, 1, 64, 'Số lượng học sinh', '_frm_ycbg_student_number', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 14, 'Y', 'N'),
(544757, 3863, 1, 65, 'Học lực hiện tại', '_frm_ycbg_learning_level', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 15, 'Y', 'N'),
(544758, 3863, 1, 66, 'Số buổi', '_frm_ycbg_learning_time', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 15, 'Y', 'N'),
(544759, 3863, 1, 67, 'Thời gian học', '_frm_ycbg_learning_day', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 16, 'Y', 'N'),
(544760, 3863, 1, 68, 'Yêu cầu', '_frm_ycbg_request', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 17, 'Y', 'N'),
(544761, 3863, 1, 69, 'Mã số gia sư đã chọn', '_frm_ycbg_teacher_code', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 18, 'Y', 'N'),
(544762, 3863, 1, 70, 'Hiện nhãn', '_cf_radio_cmg_label', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 0, 'Y', 'N'),
(544763, 3863, 1, 71, 'Tỉnh/Thành dạy', '_cf_radio_cmg_work_province', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 1, 'Y', 'N'),
(544764, 3863, 1, 72, 'Ngày sinh', '_cf_radio_cmg_birthday', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 2, 'Y', 'N'),
(544765, 3863, 1, 73, 'Tỉnh/Thành trên CMND', '_cf_radio_cmg_home_town', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 4, 'Y', 'N'),
(544766, 3863, 1, 74, 'Chọn giọng nói', '_cf_radio_cmg_voice', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 5, 'Y', 'N'),
(544767, 3863, 1, 75, 'Địa chỉ', '_cf_radio_cmg_address', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 6, 'Y', 'N'),
(544768, 3863, 1, 76, 'Email', '_cf_radio_cmg_email', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 7, 'Y', 'N'),
(544769, 3863, 1, 77, 'Ảnh thẻ', '_cf_radio_cmg_portrait', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 8, 'Y', 'N'),
(544770, 3863, 1, 78, 'Ảnh bằng cấp', '_cf_radio_cmg_certificate', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 9, 'Y', 'N'),
(544771, 3863, 1, 79, 'Sinh viên(giáo viên) trường', '_cf_radio_cmg_college_address', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 10, 'Y', 'N'),
(544772, 3863, 1, 80, 'Ngành học', '_cf_radio_cmg_major', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 11, 'Y', 'N'),
(544773, 3863, 1, 81, 'Năm tốt nghiệp', '_cf_radio_cmg_graduation_year', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 11, 'Y', 'N'),
(544774, 3863, 1, 82, 'Hiện là', '_cf_radio_cmg_level', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 12, 'Y', 'N'),
(544775, 3863, 1, 83, 'Ưu điểm', '_cf_radio_cmg_forte', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 13, 'Y', 'N'),
(544776, 3863, 1, 84, 'Môn dạy', '_cf_radio_cmg_subjects', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 13, 'Y', 'N'),
(544777, 3863, 1, 85, 'Lớp dạy', '_cf_radio_cmg_class', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 14, 'Y', 'N'),
(544778, 3863, 1, 86, 'Thời gian dạy', '_cf_radio_cmg_teaching_time', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 15, 'Y', 'N'),
(544779, 3863, 1, 87, 'Yêu cầu lương tối thiểu', '_cf_radio_cmg_salary', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 16, 'Y', 'N'),
(544780, 3863, 1, 88, 'Giới tính', '_cf_radio_cmg_gender', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 13, 'Y', 'N'),
(544781, 3863, 1, 89, 'Yêu cầu khác', '_cf_radio_cmg_other_request', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 17, 'Y', 'N'),
(544782, 3863, 2, 90, 'Nút mua hàng chi tiết sản phẩm', '_cf_radio_cart_product_detail', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 5, 'Y', 'N'),
(544783, 3863, 1, 91, 'Box đăng ký nhanh', '_cf_radio_fast_register', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 102, 'Y', 'N'),
(544784, 3863, 1, 92, 'Hiện trong chi tiết sản phẩm', '_cf_radio_fast_register_product', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 1, 'Y', 'N'),
(544785, 3863, 1, 93, 'Số điện thoại', '_cf_radio_fast_register_phone', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 2, 'Y', 'N'),
(544786, 3863, 1, 94, 'Hình thức', '_cf_radio_fast_register_method', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 3, 'Y', 'N'),
(544787, 3863, 1, 95, 'Ngày', '_cf_radio_fast_register_day', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 4, 'Y', 'N'),
(544788, 3863, 1, 96, 'Giờ', '_cf_radio_fast_register_hour', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 5, 'Y', 'N'),
(544789, 3863, 1, 97, 'Yêu cầu thêm', '_cf_radio_fast_register_comment', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:56', 6, 'Y', 'N'),
(544790, 3863, 1, 98, 'Họ tên', '_cf_radio_fast_register_name', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 2, 'Y', 'N'),
(544791, 3863, 1, 99, 'Địa chỉ', '_cf_radio_fast_register_address', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 3, 'Y', 'N'),
(544792, 3863, 1, 100, 'Email', '_cf_radio_fast_register_email', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 4, 'Y', 'N'),
(544793, 3863, 1, 101, 'Ngày/ giờ khởi hành', '_cf_radio_fast_register_start_time', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:55', 5, 'Y', 'N'),
(544794, 3863, 1, 102, 'Ngày giờ về', '_cf_radio_fast_register_end_time', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:56', 6, 'Y', 'N'),
(544795, 3863, 1, 103, 'Số lượng vé', '_cf_radio_fast_register_number_ticket', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:56', 7, 'Y', 'N'),
(544796, 3863, 1, 104, 'Chat facebook', '_cf_radio_facebook_messenger', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 1, 'Y', 'N'),
(544797, 3863, 1, 105, 'ID Fanpage', '_cf_text_fanpage_id', NULL, 0, 0, NULL, 'text', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 2, 'Y', 'N'),
(544798, 3863, 1, 106, 'Chọn kiểu', '_cf_select_sms_mobile_type', '[{\"name\":\"Kiểu 1\",\"value\":\"1\",\"select\":0},{\"name\":\"Kiểu 2\",\"value\":\"2\",\"select\":1}]', 0, 0, NULL, 'select', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:56', 1, 'Y', 'N'),
(544799, 3863, 2, 107, 'Link khác nút mua hàng', '_cf_text_cart_other_url', '', 0, 0, NULL, 'text', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 6, 'Y', 'N'),
(544800, 3863, 2, 108, 'Link khác cho tất cả sản phẩm', '_cf_text_all_product_url', '', 0, 0, NULL, 'text', '', NULL, '2018-09-29 00:08:31', '2019-07-22 20:02:54', 7, 'Y', 'N'),
(544801, 3863, 2, 109, 'Số lượng sản phẩm nổi bật trên 1 hàng', '_cf_select_number_product_hot_in_line', '[{\"name\":\"2\",\"value\":\"2\",\"select\":0},{\"name\":\"3\",\"value\":\"3\",\"select\":1},{\"name\":\"4\",\"value\":\"4\",\"select\":0},{\"name\":\"5\",\"value\":\"5\",\"select\":0}]', 0, 0, NULL, 'select', '', NULL, '2018-09-29 00:08:32', '2019-07-22 20:02:54', 5, 'Y', 'N'),
(544802, 3863, 2, 110, 'Số lượng sản phẩm trên 1 trang', '_cf_text_number_item_on_page', '50', 2, 200, NULL, 'text', '', NULL, '2018-09-29 00:08:32', '2019-07-22 20:02:54', 5, 'Y', 'N'),
(544803, 3863, 3, 111, 'Số lượng tin tức trên 1 trang', '_cf_text_number_news_on_page', '24', 2, 200, NULL, 'text', '', NULL, '2018-09-29 00:08:32', '2019-07-22 20:02:54', 6, 'Y', 'N'),
(544804, 3863, 2, 112, 'Nút xem chi tiết Menu sản phẩm ( kèm hình )', '_cf_radio_category_hot_btn_view_more', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-09-29 00:08:32', '2019-07-22 20:02:54', 5, 'Y', 'N'),
(544805, 3863, 1, 113, 'Javascript resize hình đại diện', '_cf_radio_javascript_resize', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-10-05 18:26:24', '2019-07-22 20:02:56', 110, 'Y', 'N'),
(544806, 3863, 2, 114, 'Ghi chú sau giá ở chi tiết sản phẩm', '_cf_text_note_price_product', '', 0, 0, NULL, 'text', '', NULL, '2018-10-08 18:25:19', '2019-07-22 20:02:54', 12, 'Y', 'N'),
(544807, 3863, 1, 115, 'Hiện ngôn ngữ (tự nhập) ra menu', '_cf_radio_menu_language_database', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-10-12 01:07:32', '2019-07-20 04:30:37', 106, 'Y', 'N'),
(544808, 3863, 1, 116, 'Hiện trên bài viết', '_cf_radio_show_header_news_menu', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-10-27 19:01:26', '2019-07-22 20:02:56', 2, 'Y', 'N'),
(544809, 3863, 1, 117, 'Hiện dưới bài viết', '_cf_radio_show_footer_news_menu', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-10-27 19:03:14', '2019-07-22 20:02:56', 3, 'Y', 'N'),
(544810, 3863, 1, 118, 'Hiện trên bài trang chủ', '_cf_radio_show_header_home_article', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-10-30 08:28:05', '2019-07-22 20:02:56', 4, 'Y', 'N'),
(544811, 3863, 1, 119, 'Hiện dưới bài trang chủ', '_cf_radio_show_footer_home_article', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-10-30 17:56:29', '2019-07-22 20:02:56', 5, 'Y', 'N'),
(544812, 3863, 1, 120, 'Hiện trên bài liên hệ', '_cf_radio_show_header_contact_content', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-10-30 18:01:35', '2019-07-22 20:02:56', 6, 'Y', 'N'),
(544813, 3863, 1, 121, 'Hiện dưới bài liên hệ', '_cf_radio_show_footer_contact_content', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-10-30 18:03:41', '2019-07-22 20:02:56', 7, 'Y', 'N'),
(544814, 3863, 1, 122, 'Module quản trị', '_cf_radio_module_administrator', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-11-06 18:42:41', '2019-07-22 20:02:56', 130, 'Y', 'N'),
(544815, 3863, 1, 123, 'Module zalo, fb, hotline', '_cf_radio_module_zalo_fb_hotline', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-11-10 08:49:35', '2019-09-04 21:39:36', 140, 'Y', 'N'),
(544816, 3863, 1, 124, 'Link Zalo', '_cf_text_link_zalo', 'https://zalo.me/0975516751', 0, 0, NULL, 'textarea', '', NULL, '2018-11-10 08:53:42', '2019-09-04 21:39:36', 1, 'Y', 'N'),
(544817, 3863, 1, 125, 'Link Facebook', '_cf_text_link_fb', 'https://www.facebook.com/nhan.linh.5437', 0, 0, NULL, 'text', '', NULL, '2018-11-10 08:54:33', '2019-09-04 21:39:36', 2, 'Y', 'N'),
(544818, 3863, 1, 126, 'Số Hotline', '_cf_text_hotline_number', '0975 516 751', 0, 0, NULL, 'textarea', '', NULL, '2018-11-10 08:55:45', '2019-09-04 21:39:36', 3, 'Y', 'N'),
(544819, 3863, 1, 127, 'Banner top: Hiện ô tìm kiếm', '_cf_radio_banner_search_input', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-11-15 06:45:11', '2019-07-22 20:02:54', 1, 'Y', 'N'),
(544820, 3863, 1, 128, 'Banner top: Hiện danh mục tìm kiếm', '_cf_radio_banner_search_category', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-11-15 06:48:48', '2019-07-22 20:02:54', 1, 'Y', 'N'),
(544821, 3863, 1, 129, 'Ẩn/Hiện Zalo', '_cf_radio_zalo_show_hide', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-11-16 19:27:22', '2019-09-04 21:39:36', 1, 'Y', 'N'),
(544822, 3863, 1, 130, 'Ẩn/Hiện Facebook', '_cf_radio_facebook_show_hide', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-11-16 19:29:57', '2019-09-04 21:39:36', 2, 'Y', 'N'),
(544823, 3863, 1, 131, 'Ẩn/Hiện Hotline', '_cf_radio_hotline_show_hide', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-11-16 19:32:16', '2019-09-04 21:39:36', 3, 'Y', 'N'),
(544824, 3863, 2, 132, 'Hiện tìm kiếm ra module sản phẩm theo danh mục', '_cf_radio_search_for_home_category', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-12-04 21:08:45', '2019-07-22 20:02:54', 7, 'Y', 'N'),
(544825, 3863, 2, 133, 'Hiện lượt xem ra sản phẩm', '_cf_radio_show_views_to_product', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-12-20 18:14:43', '2019-07-22 20:02:54', 5, 'Y', 'N'),
(544826, 3863, 2, 134, 'Hiện lượt mua ra sản phẩm', '_cf_radio_show_purchase_number_to_product', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":0},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-12-20 18:17:05', '2019-07-22 20:02:54', 5, 'Y', 'N'),
(544827, 3863, 1, 135, 'Box tùy chọn - Select', '_cf_radio_box_option', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-12-21 18:43:38', '2019-07-22 20:02:54', 1, 'Y', 'N'),
(544828, 3863, 2, 136, 'Chọn danh sách sản phẩm cùng loại', '_cf_select_type_other_product', '[{\"name\":\"Kiểu 1: sản phẩm thuộc cùng danh mục\",\"value\":\"1\",\"select\":1},{\"name\":\"Kiểu 2: sản phẩm thuộc cùng danh mục cha\",\"value\":\"2\",\"select\":0}]', 0, 0, NULL, 'select', '', NULL, '2018-12-28 20:15:43', '2019-07-22 20:02:54', 11, 'Y', 'N'),
(544829, 3863, 1, 137, 'Thống kê truy cập', '_cf_radio_access_online', '', 0, 0, NULL, 'radio', '', NULL, '2018-12-29 02:04:57', '0000-00-00 00:00:00', 150, 'Y', 'N'),
(544830, 3863, 1, 139, 'Đang online', '_cf_radio_access_online_now', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-12-29 02:08:56', '2019-07-22 20:02:56', 1, 'Y', 'N'),
(544831, 3863, 1, 140, 'Hôm qua', '_cf_radio_access_online_yesterday', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-12-29 02:13:19', '2019-07-22 20:02:56', 2, 'Y', 'N'),
(544832, 3863, 1, 141, 'Hôm nay', '_cf_radio_access_online_today', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-12-29 02:14:58', '2019-07-22 20:02:56', 3, 'Y', 'N'),
(544833, 3863, 1, 142, 'Tuần này', '_cf_radio_access_online_this_week', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-12-29 02:16:27', '2019-07-22 20:02:56', 4, 'Y', 'N'),
(544834, 3863, 1, 143, 'Tháng này', '_cf_radio_access_online_this_month', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-12-29 02:18:28', '2019-07-22 20:02:56', 5, 'Y', 'N'),
(544835, 3863, 1, 144, 'Năm nay', '_cf_radio_access_online_this_year', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2018-12-29 02:20:18', '2019-07-22 20:02:56', 6, 'Y', 'N'),
(544836, 3863, 1, 145, 'Tổng truy cập', '_cf_radio_access_online_total', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2018-12-29 02:21:19', '2019-07-22 20:02:56', 7, 'Y', 'N'),
(544837, 3863, 2, 146, 'Đơn vị tiền tệ', '_cf_text_price_text', '', 0, 0, NULL, 'text', '', NULL, '2019-01-17 06:21:11', '2019-07-22 20:02:54', 15, 'Y', 'N'),
(544838, 3863, 2, 147, 'Vị trí hiển thị', '_cf_radio_price_unit_position', '[{\"name\":\"Trước giá\",\"value\":\"1\",\"select\":0},{\"name\":\"Sau giá\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2019-01-17 06:24:33', '2019-07-22 20:02:54', 1, 'Y', 'N'),
(544839, 3863, 1, 148, 'Gửi CV', '_frm_ycbg_file', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2019-03-08 21:43:09', '2019-07-22 20:02:55', 19, 'Y', 'N'),
(544840, 3863, 2, 149, 'Kiểu hiển thị Menu trái theo danh mục sản phẩm', '_cf_select_display_menu_category_left', '[{\"name\":\"Kiểu 1: Hiên trực quan menu con\",\"value\":\"1\",\"select\":1},{\"name\":\"Kiểu 2: Rê chuột hiện menu con\",\"value\":\"2\",\"select\":0}]', 0, 0, NULL, 'select', '', NULL, '2019-03-17 09:29:15', '2019-07-22 20:02:54', 7, 'Y', 'N'),
(544841, 3863, 1, 150, 'Chọn ngôn ngữ thông báo mặc định', '_cf_radio_select_message_lang_default', '[{\"name\":\"Tiếng Việt\",\"value\":\"vi\",\"select\":1},{\"name\":\"English\",\"value\":\"en\",\"select\":0},{\"name\":\"Pháp\",\"value\":\"fr\",\"select\":0},{\"name\":\"Đức\",\"value\":\"de\",\"select\":0},{\"name\":\"Trung quốc\",\"value\":\"zh-CN\",\"select\":0},{\"name\":\"Nhật Bản\",\"value\":\"ja\",\"select\":0},{\"name\":\"Hàn Quốc\",\"value\":\"ko\",\"select\":0},{\"name\":\"Nga\",\"value\":\"ru\",\"select\":0}]', 0, 0, NULL, 'select', '', NULL, '2019-03-25 18:08:00', '2019-07-22 20:02:56', 160, 'Y', 'N'),
(544842, 3863, 2, 151, 'Kiểu giỏ hàng', '_cf_select_cart_type', '[{\"name\":\"Chuyển link\",\"value\":\"1\",\"select\":1},{\"name\":\"Thông báo\",\"value\":\"2\",\"select\":0}]', 0, 0, NULL, 'select', '', NULL, '2019-04-05 19:45:59', '2019-07-22 20:02:56', 170, 'Y', 'N'),
(544843, 3863, 1, 152, 'Hình thumbnail sản phẩm', '_cf_radio_enable_thumbnail', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2019-04-06 20:36:54', '2019-07-22 20:02:56', 180, 'Y', 'N'),
(544844, 3863, 1, 153, 'Chiều rộng', '_cf_text_thumbnail_width', '300', 200, 500, NULL, 'text', '', NULL, '2019-04-06 20:40:30', '2019-07-22 20:02:56', 1, 'Y', 'N'),
(544845, 3863, 1, 154, 'Chiều cao', '_cf_text_thumbnail_height', '300', 200, 500, NULL, 'text', '', NULL, '2019-04-06 20:41:59', '2019-07-22 20:02:56', 2, 'Y', 'N'),
(544846, 3863, 2, 155, 'Hiệu ứng module sản phẩm kèm hình', '_cf_radio_category_hot_effect', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2019-05-03 07:21:59', '2019-07-22 20:02:54', 15, 'Y', 'N'),
(544847, 3863, 2, 156, 'Số lượng sản phẩm', '_cf_text_number_product_category_effect', '150', 1, 0, NULL, 'text', '', NULL, '2019-05-03 07:24:34', '2019-07-22 20:02:54', 1, 'Y', 'N'),
(544848, 3863, 1, 157, 'Hiệu ứng lazyload hình ảnh', '_cf_radio_enable_lazyload_image', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', NULL, '2019-05-17 18:23:22', '2019-07-22 20:02:56', 180, 'Y', 'N'),
(544849, 3863, 1, 158, 'Hình thumbnail tin tức', '_cf_radio_enable_thumbnail_news', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2019-05-17 18:32:15', '2019-07-22 20:02:57', 3, 'Y', 'N'),
(544850, 3863, 1, 159, 'Chiều rộng', '_cf_text_thumbnail_width_news', '300', 0, 0, NULL, 'text', '', NULL, '2019-05-17 18:36:16', '2019-07-22 20:02:57', 4, 'Y', 'N'),
(544851, 3863, 1, 160, 'Chiều cao', '_cf_text_thumbnail_height_news', '300', 0, 0, NULL, 'text', '', NULL, '2019-05-17 18:37:55', '2019-07-22 20:02:57', 5, 'Y', 'N'),
(544852, 3863, 1, 161, 'Hình thumbnail slider', '_cf_radio_enable_thumbnail_slider', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":0},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":1}]', 0, 0, NULL, 'radio', '', NULL, '2019-05-17 18:45:32', '2019-07-22 20:02:57', 6, 'Y', 'N'),
(544853, 3863, 1, 162, 'Hình thumbnail đối tác', '_cf_radio_enable_thumbnail_partner', '[{\"name\":\"Bật\",\"value\":\"1\",\"select\":1},{\"name\":\"Tắt\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2019-05-17 18:47:40', '2019-07-22 20:02:57', 9, 'Y', 'N'),
(544854, 3863, 1, 163, 'Chiều rộng', '_cf_text_thumbnail_width_slider', '1366', 0, 0, NULL, 'text', '', NULL, '2019-05-17 18:49:02', '2019-07-22 20:02:57', 7, 'Y', 'N'),
(544855, 3863, 1, 164, 'Chiều rộng', '_cf_text_thumbnail_height_slider', '450', 0, 0, NULL, 'text', '', NULL, '2019-05-17 18:50:49', '2019-07-22 20:02:57', 8, 'Y', 'N'),
(544856, 3863, 1, 165, 'Chiều rộng', '_cf_text_thumbnail_width_partner', '240', 0, 0, NULL, 'text', '', NULL, '2019-05-17 18:52:49', '2019-07-22 20:02:57', 10, 'Y', 'N'),
(544857, 3863, 1, 166, 'Chiều cao', '_cf_text_thumbnail_height_partner', '180', 0, 0, NULL, 'text', '', NULL, '2019-05-17 18:53:54', '2019-07-22 20:02:57', 11, 'Y', 'N'),
(544858, 3863, 1, 167, 'Chọn kiểu nút Zalo', '_cf_select_zalo_button_type', '[{\"name\":\"Kiểu 1: Mặc định\",\"value\":\"1\",\"select\":1},{\"name\":\"Kiểu 2: Hiệu ứng\",\"value\":\"2\",\"select\":0}]', 0, 0, NULL, 'select', '', NULL, '2019-07-20 20:11:27', '2019-09-04 21:39:36', 1, 'Y', 'N'),
(566706, 3863, 2, 168, 'Ẩn danh sách danh mục con trong trang sản phẩm', '_cf_radio_enable_list_category_in_product_page', '[{\"name\":\"Hiện\",\"value\":\"1\",\"select\":1},{\"name\":\"Ẩn\",\"value\":\"0\",\"select\":0}]', 0, 0, NULL, 'radio', '', NULL, '2019-10-03 05:52:06', '0000-00-00 00:00:00', 190, 'Y', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `config_kernel`
--

CREATE TABLE `config_kernel` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `field` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `value_actual` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `place_holder` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL,
  `deleted` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config_kernel`
--

INSERT INTO `config_kernel` (`id`, `name`, `field`, `value`, `value_actual`, `type`, `description`, `place_holder`, `created_at`, `modified_in`, `sort`, `active`, `deleted`) VALUES
(1, 'Tùy chọn  lưu cache', '_type_save_cache', '[{\"name\":\"Memcached\",\"value\":\"1\",\"select\":0},{\"name\":\"File\",\"value\":\"0\",\"select\":1}]', NULL, 'radio', '', NULL, '2018-02-09 08:23:29', '2019-11-19 20:32:59', 0, 'Y', 'N'),
(2, 'Tên miền css gốc', '_css_origin_link', 'macdinh', NULL, 'text', '', NULL, '2018-04-04 14:04:49', '2019-11-19 20:32:59', 1, 'Y', 'N'),
(3, 'Số tiền cộng sẵn cho tên miền tạo mới', '_cf_kerner_text_money_amount_website_create', '0', NULL, 'text', '', NULL, '2018-05-25 09:40:27', '2019-11-19 20:32:59', 1, 'Y', 'N'),
(4, 'Số tiền giới hạn kích hoạt web', '_cf_kerner_text_money_active_min', '-50000', NULL, 'text', '', NULL, '2018-05-25 09:42:51', '2019-11-19 20:32:59', 1, 'Y', 'N'),
(5, 'Số tiền kích hoạt web đầu tiên', '_cf_kerner_text_money_amount_website_active', '95000', NULL, 'text', '', NULL, '2018-05-25 09:44:14', '2019-11-19 20:32:59', 1, 'Y', 'N'),
(6, 'Số tiền gia hạn web', '_cf_kerner_text_money_amount_website_renewal', '399000', NULL, 'text', '', NULL, '2018-05-25 09:45:47', '2019-11-19 20:32:59', 1, 'Y', 'N'),
(7, 'Số tiền bị trừ khi tạo web mới', '_cf_kerner_text_money_minus_create_website', '4500', NULL, 'text', '', NULL, '2018-05-27 06:10:53', '2019-11-19 20:32:59', 1, 'Y', 'N'),
(8, 'Số tiền kích hoạt web thứ 2 trở đi', '_cf_kerner_text_money_amount_website_active_second', '190000', NULL, 'text', '', NULL, '2018-05-27 06:39:31', '2019-11-19 20:32:59', 1, 'Y', 'N'),
(9, 'Tên miền xem thông tin đơn hàng', '_cf_kernel_text_domain_view_order', '1099,thaothao,1092,quangcao,nhunggoogle,tienads,ngoc,trangtranads,phitanads,tkweb,phuocweb,demoduong,lamweb164,qcbaokim,qcthuyvi,anhweb', NULL, 'text', '', NULL, '2018-06-04 07:28:21', '2019-11-19 20:32:59', 1, 'Y', 'N'),
(10, 'Chèn code cho body', '_cf_kernel_textarea_body_code', '', NULL, 'textarea', '', NULL, '2018-06-10 08:12:10', '2019-11-19 20:32:59', 2, 'Y', 'N'),
(11, 'Chèn code cho thẻ head', '_cf_kernel_textarea_head_code', '<!-- Global site tag (gtag.js) - Google Ads: 721162448 -->\r\n<script async src=\"https://www.googletagmanager.com/gtag/js?id=AW-721162448\"></script>\r\n<script>\r\n  window.dataLayer = window.dataLayer || [];\r\n  function gtag(){dataLayer.push(arguments);}\r\n  gtag(\'js\', new Date());\r\n\r\n  gtag(\'config\', \'AW-721162448\');\r\n</script>\r\n', NULL, 'textarea', '', NULL, '2018-06-10 08:12:53', '2019-11-19 20:32:59', 2, 'Y', 'N'),
(12, 'Tên miền không cho phép xóa đơn hàng', '_cf_kernel_text_not_enable_delete_orders', '109vn', NULL, 'text', '', NULL, '2019-02-14 06:04:30', '2019-11-19 20:32:59', 1, 'Y', 'N'),
(13, 'Số tiền kích hoạt SSL', '_cf_kernel_active_ssl', '200000', NULL, 'text', '', NULL, '2019-04-16 06:46:32', '2019-11-19 20:32:59', 1, 'Y', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(10) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `name` varchar(70) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `phone` varchar(70) NOT NULL,
  `email` varchar(70) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `address` varchar(70) DEFAULT NULL,
  `subject` varchar(70) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `deleted` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customer_comment`
--

CREATE TABLE `customer_comment` (
  `id` int(10) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `phone` varchar(70) NOT NULL,
  `email` varchar(70) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `active` char(1) DEFAULT 'Y',
  `deleted` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customer_comment`
--

INSERT INTO `customer_comment` (`id`, `subdomain_id`, `name`, `phone`, `email`, `photo`, `comment`, `address`, `subject`, `sort`, `created_at`, `active`, `deleted`) VALUES
(5027, 3863, 'Thúy Hằng Minh', '09238887263', 'thuyhangza@gmail.com', '/uploads/3519/cdn/customer_photo/lehangthuydungtanbotrenduongphomy38111142.jpg', 'Tôi cảm thấy rất hài lòng về chất lượng ,phong cách phục vụ rất chuyên nghiệp, nhân viên nhiệt tình chu đáo', 'Hồ Chí Minh', NULL, 1, '2019-09-04 19:36:22', 'Y', 'N'),
(5028, 3863, 'Quang Hòa Nguyễn', '0988374663', 'quanghoakhotoai@gmail.com', '/uploads/3519/cdn/customer_photo/20180428114024d99f.jpg', 'Đội ngũ trẻ trung, phong cách lịch thiệp. Tôi sẽ tiếp tục ủng hộ cho dịch vụ lâu dài', 'Đà Nẵng', NULL, 1, '2019-09-04 19:36:22', 'Y', 'N'),
(5029, 3863, 'Thanh Lịch Trần', '0988338476', 'lichtran999@gmail.com', '/uploads/3519/cdn/customer_photo/1450322403img1267.jpg', 'Nhân viên tư vấn rất nhiệt tình hỗ trợ khách hàng tôi vô cùng hài lòng cám ơn a/c rất nhiều !', 'Huế', NULL, 1, '2019-09-04 19:36:22', 'Y', 'N'),
(5030, 3863, 'Nguyễn Đức Vinh', '0164829927', 'nguyenducvinhbq@gmail.com', '', 'Sản phẩm chất lượng tốt. Giá thành hợp lí', 'Hà Giang', NULL, 1, '2019-09-04 19:36:22', 'Y', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `customer_message`
--

CREATE TABLE `customer_message` (
  `id` int(10) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `box_select_option` varchar(255) DEFAULT NULL,
  `work_province` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `home_town` varchar(255) DEFAULT NULL,
  `voice` varchar(50) DEFAULT NULL,
  `teaching_time` varchar(255) DEFAULT NULL,
  `portrait_image` varchar(255) DEFAULT NULL,
  `certificate_image` varchar(255) DEFAULT NULL,
  `college_address` varchar(255) DEFAULT NULL,
  `major` varchar(255) DEFAULT NULL,
  `graduation_year` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `forte` text DEFAULT NULL,
  `subjects` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `salary` varchar(255) DEFAULT NULL,
  `other_request` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted` char(1) DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `domain`
--

CREATE TABLE `domain` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT 1,
  `active` char(1) NOT NULL DEFAULT 'Y',
  `suspended` char(1) NOT NULL DEFAULT 'N',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `domain`
--

INSERT INTO `domain` (`id`, `subdomain_id`, `name`, `slug`, `sort`, `active`, `suspended`, `deleted`, `create_at`, `modified_in`) VALUES
(2054, 3863, '109.kmt', NULL, 1, 'Y', 'N', 'N', '2019-09-19 02:11:06', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `form_group`
--

CREATE TABLE `form_group` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT 1,
  `active` char(1) NOT NULL DEFAULT 'Y',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `form_group`
--

INSERT INTO `form_group` (`id`, `name`, `module`, `type`, `sort`, `active`, `deleted`, `create_at`, `modified_in`) VALUES
(1, 'Yêu cầu báo giá', '_yeu_cau_bao_gia', NULL, 1, 'Y', 'N', '2018-04-17 12:14:39', '0000-00-00 00:00:00'),
(2, 'Đăng ký nhanh', '_dang_ky_nhanh', NULL, 2, 'Y', 'N', '2018-07-26 17:38:36', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `form_item`
--

CREATE TABLE `form_item` (
  `id` int(10) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `form_group_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `place_arrive` varchar(2555) DEFAULT NULL,
  `place_pic` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `day` date DEFAULT NULL,
  `hour` varchar(50) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `subjects` varchar(255) DEFAULT NULL,
  `studen_number` varchar(255) DEFAULT NULL,
  `learning_level` varchar(255) DEFAULT NULL,
  `learning_time` varchar(255) DEFAULT NULL,
  `learning_day` varchar(255) DEFAULT NULL,
  `request` varchar(255) DEFAULT NULL,
  `teacher_code` varchar(255) DEFAULT NULL,
  `minute` varchar(50) DEFAULT NULL,
  `method` varchar(255) DEFAULT NULL,
  `number_ticket` int(11) DEFAULT 1,
  `file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted` char(1) DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ip_note`
--

CREATE TABLE `ip_note` (
  `id` int(11) NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `ip_address` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `note` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `landing_page`
--

CREATE TABLE `landing_page` (
  `id` int(11) NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `row_id` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hide_header` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `hide_left` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `hide_right` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `hide_footer` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `menu` char(1) COLLATE utf8_unicode_ci DEFAULT 'N',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `hits` int(11) NOT NULL,
  `font_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon_type` int(11) DEFAULT 1,
  `sort` int(11) NOT NULL,
  `active` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `deleted` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sort` int(11) DEFAULT 1,
  `active` char(1) COLLATE utf8_unicode_ci DEFAULT 'Y',
  `deleted` char(1) COLLATE utf8_unicode_ci DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `created_at`, `modified_in`, `sort`, `active`, `deleted`) VALUES
(1, 'Việt Nam', 'vi', '2018-09-14 13:42:45', '2019-07-30 05:56:13', 1, 'Y', 'N'),
(2, 'English', 'en', '2018-09-14 13:57:21', '2019-07-30 05:56:13', 2, 'Y', 'N'),
(3, 'Pháp', 'fr', '2018-09-14 17:30:30', '2019-07-30 05:56:13', 3, 'Y', 'N'),
(4, 'Đức', 'de', '2018-09-15 02:14:45', '2019-07-30 05:56:13', 4, 'Y', 'N'),
(5, 'Trung quốc', 'zh', '2018-09-16 11:17:00', '2019-07-30 05:56:13', 5, 'Y', 'N'),
(6, 'Nhật Bản', 'ja', '2018-09-17 03:30:19', '2019-07-30 05:56:13', 6, 'Y', 'N'),
(7, 'Hàn Quốc', 'ko', '2018-09-17 11:14:52', '2019-07-30 05:56:13', 7, 'Y', 'N'),
(8, 'Nga', 'ru', '2018-09-19 01:05:24', '2019-07-30 05:56:13', 8, 'Y', 'N'),
(9, 'Italia', 'it', '2019-07-29 20:06:25', '2019-07-30 05:56:13', 9, 'Y', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `layout`
--

CREATE TABLE `layout` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'Y',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `layout`
--

INSERT INTO `layout` (`id`, `name`, `photo`, `slug`, `sort`, `active`, `deleted`, `create_at`, `modified_in`) VALUES
(1, 'Layout 1', 'layout-1.jpg', NULL, 1, 'Y', 'N', '2017-09-26 04:41:58', '2018-01-08 11:54:54'),
(2, 'Layout 2', 'layout-2.jpg', NULL, 2, 'Y', 'N', '2017-09-26 04:41:59', '2017-12-09 20:23:37'),
(3, 'Layout 3', 'layout-3.jpg', NULL, 3, 'Y', 'N', '2017-09-26 04:41:59', '2017-12-09 20:22:59'),
(4, 'Layout 4', 'layout-4.jpg', NULL, 4, 'Y', 'N', '2017-09-26 04:41:59', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `layout_config`
--

CREATE TABLE `layout_config` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL,
  `main_color` varchar(50) DEFAULT NULL,
  `main_text_color` varchar(50) DEFAULT NULL,
  `css` longtext DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `enable_color` tinyint(1) NOT NULL DEFAULT 1,
  `enable_css` tinyint(1) NOT NULL DEFAULT 0,
  `hide_header` char(1) NOT NULL DEFAULT 'N',
  `hide_left` char(1) NOT NULL DEFAULT 'N',
  `hide_right` char(1) NOT NULL DEFAULT 'N',
  `hide_footer` char(1) NOT NULL DEFAULT 'N',
  `show_left_inner` char(1) NOT NULL DEFAULT 'Y',
  `show_right_inner` char(1) NOT NULL DEFAULT 'Y',
  `active` char(1) NOT NULL DEFAULT 'Y',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `layout_config`
--

INSERT INTO `layout_config` (`id`, `subdomain_id`, `layout_id`, `main_color`, `main_text_color`, `css`, `sort`, `enable_color`, `enable_css`, `hide_header`, `hide_left`, `hide_right`, `hide_footer`, `show_left_inner`, `show_right_inner`, `active`, `deleted`, `create_at`, `modified_in`) VALUES
(4181, 3863, 2, '#1e84cc', '#fff', '{\"background_type\":\"repeat\",\"background_attachment\":\"fixed\",\"background_active\":\"Y\",\"font_web\":\"Roboto\",\"container\":\"\",\"bar_web_bgr\":\"\",\"bar_web_color\":\"\",\"txt_web_color\":\"\",\"menu_top_color\":\"\"}', 1, 1, 1, 'N', 'Y', 'Y', 'N', 'Y', 'N', 'Y', 'N', '2018-04-02 13:52:38', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `email` varchar(40) DEFAULT NULL,
  `password` char(60) NOT NULL,
  `mustChangePassword` char(1) DEFAULT NULL,
  `role` int(11) NOT NULL DEFAULT 1,
  `fullName` varchar(50) DEFAULT NULL,
  `sex` char(1) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `creditCard` varchar(25) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `balance` int(11) DEFAULT 0,
  `address` varchar(100) DEFAULT NULL,
  `cityRegion` varchar(25) DEFAULT NULL,
  `banned` char(1) DEFAULT 'N',
  `suspended` char(1) DEFAULT 'N',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `sort` int(11) DEFAULT 1,
  `active` char(1) DEFAULT 'Y',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` smallint(6) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `module_item_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `main` char(1) NOT NULL DEFAULT 'N',
  `style` enum('horizontal','vertical') NOT NULL DEFAULT 'horizontal',
  `display_type` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL,
  `deleted` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `subdomain_id`, `language_id`, `depend_id`, `module_item_id`, `name`, `main`, `style`, `display_type`, `created_at`, `modified_in`, `sort`, `active`, `deleted`) VALUES
(7977, 3863, 1, 0, 281789, 'Menu Top: Tùy Chỉnh', 'Y', 'horizontal', 1, '2019-09-04 19:36:21', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(7978, 3863, 1, 0, 281790, 'Menu Trái: Tùy Chỉnh', 'N', 'vertical', 1, '2019-09-04 19:36:21', '0000-00-00 00:00:00', 1, 'Y', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `menu_item`
--

CREATE TABLE `menu_item` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` smallint(6) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `menu_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `level` int(11) NOT NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `module_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `module_id` int(11) NOT NULL DEFAULT 0,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `other_url` varchar(255) DEFAULT NULL,
  `title_attribute` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `font_class` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `icon_type` tinyint(4) DEFAULT 1,
  `new_blank` char(1) DEFAULT 'N',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL,
  `deleted` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu_item`
--

INSERT INTO `menu_item` (`id`, `subdomain_id`, `language_id`, `depend_id`, `menu_id`, `parent_id`, `level`, `name`, `module_name`, `module_id`, `url`, `other_url`, `title_attribute`, `font_class`, `photo`, `icon_type`, `new_blank`, `created_at`, `modified_in`, `sort`, `active`, `deleted`) VALUES
(64091, 3863, 1, 0, 7977, 0, 0, 'TRANG CHỦ', 'index', 0, '/', NULL, NULL, 'home', 'contacticon_2dfsu3DP.png', 1, 'N', '2019-09-04 19:36:21', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(64092, 3863, 1, 0, 7977, 0, 0, 'Liên hệ', 'contact', 0, 'lien-he', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:21', '0000-00-00 00:00:00', 7, 'Y', 'N'),
(64094, 3863, 1, 0, 7977, 0, 0, 'Sản phẩm', 'product', 0, 'san-pham', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:21', '0000-00-00 00:00:00', 3, 'Y', 'N'),
(64095, 3863, 1, 0, 7977, 0, 0, 'Giới Thiệu', 'news_menu', 37473, 'gioi-thieu', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:21', '0000-00-00 00:00:00', 2, 'Y', 'N'),
(64097, 3863, 1, 0, 7977, 0, 0, 'Tin tức - Bài Viết', 'news_menu', 37471, 'tin-tuc-bai-viet', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:21', '0000-00-00 00:00:00', 7, 'Y', 'N'),
(64098, 3863, 1, 0, 7978, 0, 0, 'Sản Phẩm Nổi Bật', 'news_menu', 0, 'san-pham-noi-bat', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:21', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(64099, 3863, 1, 0, 7978, 0, 0, 'Liên hệ', 'contact', 0, 'lien-he', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:21', '0000-00-00 00:00:00', 5, 'Y', 'N'),
(64100, 3863, 1, 0, 7978, 0, 0, 'LAPTOP', 'category', 0, 'laptop', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:21', '0000-00-00 00:00:00', 6, 'Y', 'N'),
(64101, 3863, 1, 0, 7978, 0, 0, 'CAMERA', 'category', 63238, 'camera', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:21', '0000-00-00 00:00:00', 7, 'Y', 'N'),
(64102, 3863, 1, 0, 7978, 0, 0, 'Khuyến Mãi', 'category', 0, 'khuyen-mai', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:21', '0000-00-00 00:00:00', 8, 'Y', 'N'),
(64103, 3863, 1, 0, 7978, 0, 0, 'Sửa Chữa', 'category', 0, 'sua-chua', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:21', '0000-00-00 00:00:00', 9, 'N', 'N'),
(64104, 3863, 1, 0, 7978, 0, 0, 'Trang chủ', 'index', 0, '/', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:21', '0000-00-00 00:00:00', 10, 'Y', 'N'),
(64105, 3863, 1, 0, 7978, 0, 0, 'Chính Sách Bảo Hành', 'news_menu', 0, 'chinh-sach-bao-hanh', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:21', '0000-00-00 00:00:00', 11, 'Y', 'N'),
(64106, 3863, 1, 0, 7978, 0, 0, 'Giới Thiệu', 'news_menu', 37473, 'gioi-thieu', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:21', '0000-00-00 00:00:00', 12, 'Y', 'N'),
(64107, 3863, 1, 0, 7978, 0, 0, 'Đăng Ký', 'news_menu', 37472, 'dang-ky1', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:22', '0000-00-00 00:00:00', 13, 'Y', 'N'),
(64108, 3863, 1, 0, 7978, 0, 0, 'Tin tức - Bài Viết', 'news_menu', 37471, 'tin-tuc-bai-viet', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:22', '0000-00-00 00:00:00', 14, 'Y', 'N'),
(64109, 3863, 1, 0, 7978, 0, 0, 'Sản phẩm', 'product', 0, 'san-pham', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:22', '0000-00-00 00:00:00', 15, 'Y', 'N'),
(64110, 3863, 1, 0, 7978, 0, 0, 'LAPTOP', 'category', 0, 'laptop', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:22', '0000-00-00 00:00:00', 16, 'Y', 'N'),
(64111, 3863, 1, 0, 7978, 0, 0, 'CAMERA', 'category', 63238, 'camera', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:22', '0000-00-00 00:00:00', 17, 'Y', 'N'),
(64112, 3863, 1, 0, 7978, 0, 0, 'Khuyến Mãi', 'category', 0, 'khuyen-mai', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:22', '0000-00-00 00:00:00', 18, 'Y', 'N'),
(64113, 3863, 1, 0, 7978, 0, 0, 'Sửa Chữa', 'category', 0, 'sua-chua', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:22', '0000-00-00 00:00:00', 19, 'Y', 'N'),
(64114, 3863, 1, 0, 7978, 0, 0, 'Video', 'clip', 0, 'video', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:22', '0000-00-00 00:00:00', 20, 'Y', 'N'),
(64115, 3863, 1, 0, 7978, 0, 0, 'Liên hệ', 'contact', 0, 'lien-he', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:22', '0000-00-00 00:00:00', 21, 'Y', 'N'),
(64116, 3863, 1, 0, 7978, 0, 0, 'Ý kiến khách hàng', 'customer_comment', 0, 'y-kien-khach-hang', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:22', '0000-00-00 00:00:00', 22, 'Y', 'N'),
(64117, 3863, 1, 0, 7978, 0, 0, 'Dự án đã thực hiện', 'subdomain_list', 0, 'du-an-da-thuc-hien', NULL, NULL, NULL, NULL, 1, 'N', '2019-09-04 19:36:22', '0000-00-00 00:00:00', 23, 'Y', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `module_group`
--

CREATE TABLE `module_group` (
  `id` int(11) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `type` varchar(100) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'Y',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `module_group`
--

INSERT INTO `module_group` (`id`, `parent_id`, `level`, `name`, `photo`, `type`, `link`, `sort`, `active`, `deleted`, `create_at`, `modified_in`) VALUES
(1, 0, 0, 'Banner: Tên cty, hotline, email', NULL, '_header_top', 'setting/content', 1, 'Y', 'N', '2017-11-27 01:57:18', '2018-11-30 23:45:42'),
(2, 0, 0, 'Banner: Logo - Tìm kiếm - Giỏ hàng', NULL, '_header_logo_search_cart', 'setting/content', 2, 'Y', 'N', '2017-11-27 01:57:52', '2018-11-30 23:55:20'),
(3, 1, 1, 'Tên công ty', NULL, '_header_company_name', NULL, 1, 'Y', 'N', '2017-11-27 02:01:19', '0000-00-00 00:00:00'),
(4, 1, 1, 'Hotline - Email', NULL, '_header_hotline_email', NULL, 2, 'Y', 'N', '2017-11-27 02:01:54', '2017-11-27 02:02:09'),
(5, 2, 1, 'Logo', NULL, '_header_logo', NULL, 1, 'Y', 'N', '2017-11-27 02:02:43', '2018-11-15 06:07:07'),
(6, 2, 1, 'Tìm kiếm', NULL, '_header_search', NULL, 2, 'Y', 'N', '2017-11-27 02:02:57', '2018-11-15 06:07:35'),
(7, 2, 1, 'Giỏ hàng', NULL, '_header_cart', NULL, 3, 'Y', 'N', '2017-11-27 02:03:19', '2018-11-15 06:08:42'),
(8, 0, 0, 'Tin Tức: Nổi bật', NULL, '_home_news_hot_index', 'news', 1, 'Y', 'N', '2017-11-27 02:03:57', '2018-11-30 23:52:08'),
(9, 0, 0, 'Sản phẩm: Nổi bật (Trang chủ)', NULL, '_home_product_hot', 'product', 1, 'Y', 'N', '2017-11-27 10:38:49', '2018-11-30 23:50:06'),
(10, 0, 0, 'Footer 1: Liên hệ, menu 1, menu 2', NULL, '_footer_top', NULL, 1, 'Y', 'N', '2017-11-27 10:39:53', '2018-03-28 20:25:25'),
(11, 0, 0, 'Footer 2: Facebook, mạng xã hội, thống kê', NULL, '_footer_bottom', 'setting/content', 2, 'Y', 'N', '2017-11-27 10:40:42', '2018-11-30 23:55:14'),
(12, 10, 1, 'Thông tin công ty', NULL, '_footer_company_info', NULL, 1, 'Y', 'N', '2017-11-27 10:41:37', '2018-08-27 01:29:45'),
(13, 10, 1, 'Danh mục sản phẩm', NULL, '_footer_product_category', NULL, 2, 'Y', 'N', '2017-11-27 10:42:56', '2018-08-27 01:30:16'),
(14, 10, 1, 'Tin tức footer', NULL, '_footer_policy', NULL, 3, 'Y', 'N', '2017-11-27 10:45:36', '2018-08-27 01:30:32'),
(15, 11, 1, 'Fanpage - Copyright', NULL, '_footer_weblink_copyright', NULL, 1, 'Y', 'N', '2017-11-27 10:46:06', '2018-08-27 01:31:39'),
(16, 11, 1, 'Logo Mạng xã hội', NULL, '_footer_social_industry_minister', NULL, 2, 'Y', 'N', '2017-11-27 10:46:44', '2018-08-27 01:31:48'),
(17, 11, 1, 'Thống kê truy cập', NULL, '_footer_online_access', NULL, 3, 'Y', 'N', '2017-11-27 10:47:31', '2018-08-27 01:31:59'),
(18, 0, 0, 'Menu Trái: Theo Menu Sản Phẩm', NULL, '_product_category_left_right', NULL, 1, 'Y', 'N', '2017-12-01 17:39:19', '2018-04-11 14:11:45'),
(19, 0, 0, 'Sản phẩm: Bán chạy (trái, phải)', NULL, '_product_selling_left_right', 'product', 1, 'Y', 'N', '2017-12-01 17:39:45', '2018-11-30 23:49:38'),
(20, 0, 0, 'Facebook: Fangage', NULL, '_fanpage_left_right', 'setting/content', 1, 'Y', 'N', '2017-12-01 17:40:28', '2018-11-30 23:47:40'),
(21, 0, 0, 'Thông tin công ty(trái, phải)', NULL, '_company_info_left_right', 'setting/content', 1, 'Y', 'N', '2017-12-01 17:40:44', '2018-11-30 23:58:09'),
(22, 0, 0, 'Đăng nhập hệ thống', NULL, '_admin_login', NULL, 1, 'Y', 'N', '2018-01-13 14:24:48', '0000-00-00 00:00:00'),
(23, 0, 0, 'Facebook: Bình Luận', NULL, '_comment_facebook', NULL, 1, 'Y', 'N', '2018-01-22 11:15:40', '2018-03-23 00:37:23'),
(24, 0, 0, 'Footer 3: Liên hệ, mạng xã hội, thống Kê', NULL, '_footer_3', 'setting/content', 3, 'Y', 'N', '2018-01-29 11:23:14', '2018-11-30 23:55:27'),
(25, 0, 0, 'Banner: Tự động', NULL, '_banner_top', NULL, 1, 'Y', 'N', '2018-03-06 02:25:16', '2018-03-23 00:13:44'),
(27, 0, 0, 'Sản phẩm: Mới (Trang chủ)', NULL, '_home_product_new', 'product', 1, 'Y', 'N', '2018-03-10 18:46:05', '2018-11-30 23:49:47'),
(29, 0, 0, 'Sản phẩm: Theo Menu', NULL, '_home_product_category', 'category', 1, 'Y', 'N', '2018-03-14 18:26:55', '2018-11-30 23:50:58'),
(30, 0, 0, 'Đăng ký nhận mail', NULL, '_newsletter', 'orders', 1, 'Y', 'N', '2018-03-20 09:08:06', '2018-11-30 23:54:00'),
(31, 0, 0, 'Đa ngôn ngữ google', NULL, '_multi_language_google', 'setting/language', 1, 'Y', 'N', '2018-03-22 02:36:55', '2018-11-30 23:54:23'),
(32, 0, 0, 'Thống kê truy cập: Cột Trái', NULL, '_access_online', NULL, 1, 'Y', 'N', '2018-03-23 00:45:36', '2018-03-23 01:02:34'),
(33, 0, 0, 'Bài trang chủ', NULL, '_home_article', 'setting/content', 1, 'Y', 'N', '2018-04-01 16:55:55', '2018-11-30 23:43:52'),
(34, 0, 0, 'Tìm kiếm', NULL, '_search', NULL, 1, 'Y', 'N', '2018-04-09 00:40:43', '0000-00-00 00:00:00'),
(35, 0, 0, 'Box: Gửi tin nhắn', NULL, '_customer_message', 'orders', 1, 'Y', 'N', '2018-04-17 23:44:28', '2018-11-30 23:47:03'),
(36, 0, 0, 'Box: Yêu cầu báo giá', NULL, '_frm_ycbg', 'orders', 1, 'Y', 'N', '2018-04-17 23:46:12', '2018-11-30 23:47:18'),
(37, 0, 0, 'Tin tức: Theo menu tin tức', NULL, '_home_news_menu', 'news_menu', 1, 'Y', 'N', '2018-04-25 12:01:17', '2018-11-30 23:51:53'),
(38, 0, 0, 'Tin Tức: Xem nhiều', NULL, '_news_most_view', 'news', 1, 'Y', 'N', '2018-04-28 12:14:48', '2018-11-30 23:52:02'),
(39, 0, 0, 'Tin Tức: Mới', NULL, '_news_new', 'news', 1, 'Y', 'N', '2018-04-28 12:15:42', '2018-11-30 23:52:14'),
(40, 0, 0, 'Tin tức: Giới thiệu chung', NULL, '_module_news_introduct', 'news', 1, 'Y', 'N', '2018-05-11 19:37:28', '2018-11-30 23:51:35'),
(41, 0, 0, 'Menu Trái: Theo Menu Tin tức', NULL, '_md_lr_news_menu', NULL, 1, 'Y', 'N', '2018-06-02 13:23:30', '2018-06-16 17:37:58'),
(42, 0, 0, 'Vận chuyển thanh toán', NULL, '_md_policy_service', 'news_menu', 1, 'Y', 'N', '2018-06-16 17:39:08', '2018-11-30 23:52:49'),
(43, 0, 0, 'Menu chung: Gộp (Menu sản phẩm, slider, menu top)', NULL, '_md_combo_menu_slider', NULL, 1, 'Y', 'N', '2018-06-19 11:04:59', '2018-07-08 17:13:10'),
(44, 0, 0, 'Tìm kiếm nâng cao', NULL, '_md_search_advanced', NULL, 1, 'Y', 'N', '2018-06-20 23:16:46', '0000-00-00 00:00:00'),
(45, 0, 0, 'Menu top 2: Tùy chỉnh', NULL, '_md_menu_top_2', NULL, 1, 'Y', 'N', '2018-06-24 11:18:09', '0000-00-00 00:00:00'),
(46, 0, 0, 'Menu ngang: Sản phẩm ( kèm hình )', NULL, '_md_category_hot', 'category', 1, 'Y', 'N', '2018-06-29 14:47:24', '2018-11-30 23:58:44'),
(47, 0, 0, 'Banner: Tên cty, hotline, email, đăng ký', NULL, '_md_register_login', 'setting/content', 1, 'Y', 'N', '2018-06-29 18:50:58', '2018-11-30 23:59:28'),
(48, 0, 0, 'Sản phẩm: Nổi bật (Slide)', NULL, '_md_product_hot_slide', 'product', 1, 'Y', 'N', '2018-07-03 21:40:08', '2018-11-30 23:49:55'),
(49, 0, 0, 'Slide chạy chữ', NULL, '_text_marquee_horizontal', 'setting/content', 1, 'Y', 'N', '2018-07-12 13:48:13', '2018-11-30 23:57:30'),
(50, 0, 0, 'Ý kiến khách hàng', NULL, '_md_customer_comment', 'orders', 1, 'Y', 'N', '2018-07-27 12:44:29', '2018-11-30 23:53:27'),
(51, 0, 0, 'Liên hệ', NULL, '_md_contact', 'orders', 1, 'Y', 'N', '2018-07-31 03:31:11', '2018-11-30 23:48:36'),
(52, 0, 0, 'Banner: Hình ảnh 2', NULL, '_md_banner_2', 'banner', 1, 'Y', 'N', '2018-08-05 16:54:25', '2018-11-30 23:44:50'),
(53, 0, 0, 'Banner: Hình ảnh 3', NULL, '_md_banner_3', 'banner', 1, 'Y', 'N', '2018-08-05 16:54:44', '2018-11-30 23:45:16'),
(54, 0, 0, 'Bản đồ', NULL, '_md_google_map', 'setting/content', 1, 'Y', 'N', '2018-08-06 16:23:53', '2018-11-30 23:44:30'),
(55, 0, 0, 'Footer tổng hợp', NULL, '_footer_total', NULL, 1, 'Y', 'N', '2018-08-26 13:32:20', '0000-00-00 00:00:00'),
(56, 55, 1, 'Thông tin công ty', NULL, '_footer_total_company_info', 'setting/content', 1, 'Y', 'N', '2018-08-26 13:36:50', '2018-12-30 01:06:55'),
(57, 55, 1, 'Danh mục sản phẩm', NULL, '_footer_total_category', 'category', 2, 'Y', 'N', '2018-08-26 13:38:18', '2018-12-30 01:07:23'),
(58, 55, 1, 'Tin tức footer', NULL, '_footer_total_news', 'news', 3, 'Y', 'N', '2018-08-26 13:41:07', '2018-12-30 01:08:20'),
(59, 55, 1, 'Fanpage - Copyright', NULL, '_footer_total_fanpage_copyright', NULL, 4, 'Y', 'N', '2018-08-26 13:55:50', '0000-00-00 00:00:00'),
(60, 55, 1, 'Logo mạng xã hội', NULL, '_footer_total_social_logo', 'setting/content', 5, 'Y', 'N', '2018-08-26 13:57:07', '2018-12-30 01:08:29'),
(61, 55, 1, 'Thống kê truy cập', NULL, '_footer_total_access_online', NULL, 6, 'Y', 'N', '2018-08-26 13:58:12', '0000-00-00 00:00:00'),
(62, 55, 1, 'Bản đồ', NULL, '_footer_total_map', 'setting/content', 7, 'Y', 'N', '2018-08-27 11:06:33', '2018-12-30 01:08:39'),
(63, 55, 1, 'Tự soạn thảo', NULL, 'footer_total_posts', NULL, 8, 'Y', 'N', '2018-08-27 16:39:49', '2018-08-28 00:13:16'),
(64, 0, 0, 'Đa ngôn ngữ tự nhập', NULL, '_multi_language_database', 'setting/language', 1, 'Y', 'N', '2018-10-11 17:58:57', '2018-11-30 23:54:12'),
(66, 0, 0, 'Hỏi đáp', NULL, '_usually_question', 'usually_question', 1, 'Y', 'N', '2018-10-24 06:35:47', '2018-11-30 23:48:08'),
(67, 0, 0, 'Module ngày giờ', NULL, '_md_date_time', NULL, 1, 'Y', 'N', '2018-11-04 09:05:27', '0000-00-00 00:00:00'),
(68, 0, 0, 'Dự án đã thực hiện', NULL, '_subdomain_implement', '', 1, 'Y', 'N', '2018-12-14 19:58:41', '0000-00-00 00:00:00'),
(69, 0, 0, 'Module tạo web', NULL, '_module_create_website', '', 1, 'Y', 'N', '2018-12-15 01:20:46', '0000-00-00 00:00:00'),
(70, 0, 0, 'Slider tin tức', NULL, '_slider_news', '/news', 1, 'Y', 'N', '2019-01-11 05:44:25', '0000-00-00 00:00:00'),
(71, 0, 0, 'Tin tức: Hiệu ứng nổi bật', NULL, '_news_hot_effect', '/news', 1, 'Y', 'N', '2019-01-24 18:18:23', '0000-00-00 00:00:00'),
(72, 0, 0, 'Sản phẩm: Nổi bật (Giới thiệu)', NULL, '_product_hot_about', '/product', 1, 'Y', 'N', '2019-03-03 18:17:35', '0000-00-00 00:00:00'),
(73, 0, 0, 'Tin tức: Thống kê', NULL, '_news_statistical', '/news', 1, 'Y', 'N', '2019-03-03 18:20:47', '0000-00-00 00:00:00'),
(74, 0, 0, 'Slider + Tin tức', NULL, '_md_slider_news', '', 1, 'Y', 'N', '2019-03-20 07:03:44', '0000-00-00 00:00:00'),
(75, 0, 0, 'Banner: Slider dọc', NULL, '_banner_slider_vertical', '/banner', 1, 'Y', 'N', '2019-04-19 20:50:59', '0000-00-00 00:00:00'),
(76, 55, 1, 'Bài liên hệ', NULL, '_footer_total_contact', '/setting', 9, 'Y', 'N', '2019-05-09 06:27:01', '2019-05-09 06:27:28'),
(77, 0, 0, 'Menu sản phẩm ( dạng bài viết sole )', NULL, '_category_group_sole', '/category', 1, 'Y', 'N', '2019-07-26 21:21:47', '2019-07-26 22:06:53');

-- --------------------------------------------------------

--
-- Table structure for table `module_item`
--

CREATE TABLE `module_item` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `module_group_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'Y',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8
PARTITION BY RANGE COLUMNS(`subdomain_id`)
(
PARTITION p0 VALUES LESS THAN (1000) ENGINE=InnoDB,
PARTITION p1 VALUES LESS THAN (2000) ENGINE=InnoDB,
PARTITION p2 VALUES LESS THAN (3000) ENGINE=InnoDB,
PARTITION p3 VALUES LESS THAN (4000) ENGINE=InnoDB,
PARTITION p4 VALUES LESS THAN (MAXVALUE) ENGINE=InnoDB
);

--
-- Dumping data for table `module_item`
--

INSERT INTO `module_item` (`id`, `subdomain_id`, `module_group_id`, `parent_id`, `level`, `name`, `photo`, `type`, `type_id`, `sort`, `active`, `deleted`, `create_at`, `modified_in`) VALUES
(281760, 3863, 1, 0, 0, 'Banner: Tên cty, hotline, email', NULL, '_header_top', NULL, 1, 'Y', 'N', '2018-08-14 18:45:52', '0000-00-00 00:00:00'),
(281761, 3863, 2, 0, 0, 'Banner: Logo - Tìm kiếm - Giỏ hàng', NULL, '_header_logo_search_cart', NULL, 2, 'Y', 'N', '2018-08-14 18:45:52', '0000-00-00 00:00:00'),
(281762, 3863, 5, 281761, 0, 'Logo', NULL, '_header_logo', NULL, 1, 'Y', 'N', '2018-11-15 06:07:05', '0000-00-00 00:00:00'),
(281763, 3863, 6, 281761, 0, 'Tìm kiếm', NULL, '_header_search', NULL, 2, 'Y', 'N', '2018-11-15 06:07:40', '0000-00-00 00:00:00'),
(281764, 3863, 7, 281761, 0, 'Giỏ hàng', NULL, '_header_cart', NULL, 3, 'Y', 'N', '2018-11-15 06:08:40', '0000-00-00 00:00:00'),
(281765, 3863, 8, 0, 0, 'Tin Tức: Nổi bật', NULL, '_home_news_hot_index', NULL, 1, 'Y', 'N', '2018-08-14 18:45:52', '0000-00-00 00:00:00'),
(281766, 3863, 9, 0, 0, 'Sản phẩm: Nổi bật (Trang chủ)', NULL, '_home_product_hot', NULL, 1, 'Y', 'N', '2018-08-14 18:45:52', '0000-00-00 00:00:00'),
(281767, 3863, 27, 0, 0, 'Sản phẩm: Mới (Trang chủ)', NULL, '_home_product_new', NULL, 1, 'Y', 'N', '2018-08-14 18:45:52', '0000-00-00 00:00:00'),
(281768, 3863, 29, 0, 0, 'Sản phẩm: Theo Menu', NULL, '_home_product_category', NULL, 1, 'Y', 'N', '2018-08-14 18:45:52', '0000-00-00 00:00:00'),
(281769, 3863, 10, 0, 0, 'Footer 1: Liên hệ, menu 1, menu 2', '', '_footer_top', NULL, 1, 'Y', 'N', '2018-08-14 18:45:52', '0000-00-00 00:00:00'),
(281770, 3863, 12, 281769, 0, 'Thông tin công ty', NULL, '_footer_company_info', NULL, 1, 'Y', 'N', '2018-08-26 18:42:40', '0000-00-00 00:00:00'),
(281771, 3863, 13, 281769, 0, 'Danh mục sản phẩm', NULL, '_footer_product_category', NULL, 2, 'Y', 'N', '2018-08-26 18:43:54', '0000-00-00 00:00:00'),
(281772, 3863, 14, 281769, 0, 'Tin tức footer', NULL, '_footer_policy', NULL, 3, 'Y', 'N', '2018-08-26 18:44:32', '0000-00-00 00:00:00'),
(281773, 3863, 11, 0, 0, 'Footer 2: Facebook, mạng xã hội, thống kê', NULL, '_footer_bottom', NULL, 2, 'Y', 'N', '2018-08-14 18:45:52', '0000-00-00 00:00:00'),
(281774, 3863, 15, 281773, 0, 'Fanpage - Copyright', NULL, '_footer_weblink_copyright', NULL, 1, 'Y', 'N', '2018-08-26 18:46:56', '0000-00-00 00:00:00'),
(281775, 3863, 16, 281773, 0, 'Logo Mạng xã hội', NULL, '_footer_social_industry_minister', NULL, 2, 'Y', 'N', '2018-08-26 18:47:04', '0000-00-00 00:00:00'),
(281776, 3863, 17, 281773, 0, 'Thống kê truy cập', NULL, '_footer_online_access', NULL, 3, 'Y', 'N', '2018-08-26 18:47:13', '0000-00-00 00:00:00'),
(281777, 3863, 24, 0, 0, 'Footer 3: Liên hệ, mạng xã hội, thống Kê', NULL, '_footer_3', NULL, 3, 'Y', 'N', '2018-08-14 18:45:52', '0000-00-00 00:00:00'),
(281778, 3863, 18, 0, 0, 'Menu Trái: Theo Menu Sản Phẩm', '', '_product_category_left_right', NULL, 1, 'Y', 'N', '2018-08-14 18:45:53', '0000-00-00 00:00:00'),
(281779, 3863, 19, 0, 0, 'Sản phẩm: Bán chạy (trái, phải)', NULL, '_product_selling_left_right', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281780, 3863, 22, 0, 0, 'Đăng nhập hệ thống', '', '_admin_login', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281781, 3863, 21, 0, 0, 'Thông tin công ty(trái, phải)', NULL, '_company_info_left_right', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281782, 3863, 20, 0, 0, 'Facebook: Fangage', NULL, '_fanpage_left_right', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281783, 3863, 23, 0, 0, 'Facebook: Bình Luận', '', '_comment_facebook', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281784, 3863, 25, 0, 0, 'Banner: Tự động', '', '_banner_top', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281785, 3863, 30, 0, 0, 'Đăng ký nhận mail', NULL, '_newsletter', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281786, 3863, 31, 0, 0, 'Đa ngôn ngữ google', NULL, '_multi_language_google', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281787, 3863, 32, 0, 0, 'Thống kê truy cập: Cột Trái', '', '_access_online', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281788, 3863, 33, 0, 0, 'Bài trang chủ', NULL, '_home_article', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281789, 3863, 0, 0, 0, 'Menu Top: Tùy Chỉnh', '', 'menu', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281790, 3863, 0, 0, 0, 'Menu Trái: Tùy Chỉnh', '', 'menu', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281791, 3863, 0, 0, 0, 'Slider', '', 'banner', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281792, 3863, 0, 0, 0, 'Đối tác', '', 'banner', NULL, 2, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281793, 3863, 0, 0, 0, 'Banner: Hình ảnh 1', '', 'banner', NULL, 3, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281794, 3863, 0, 0, 0, 'Modul đặt xe ( đang làm )', '', 'post', 11126, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281795, 3863, 0, 0, 0, 'Hỗ Trợ 2', '', 'post', 11127, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281796, 3863, 0, 0, 0, 'Hỗ Trợ', '', 'post', 11128, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281797, 3863, 34, 0, 0, 'Tìm kiếm', '', '_search', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281798, 3863, 35, 0, 0, 'Box: Gửi tin nhắn', NULL, '_customer_message', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281799, 3863, 36, 0, 0, 'Box: Yêu cầu báo giá', NULL, '_frm_ycbg', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281800, 3863, 37, 0, 0, 'Tin tức: Theo menu tin tức', NULL, '_home_news_menu', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281801, 3863, 38, 0, 0, 'Tin Tức: Xem nhiều', NULL, '_news_most_view', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281802, 3863, 39, 0, 0, 'Tin Tức: Mới', NULL, '_news_new', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281803, 3863, 40, 0, 0, 'Tin tức: Giới thiệu chung', NULL, '_module_news_introduct', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281804, 3863, 41, 0, 0, 'Menu Trái: Theo Menu Tin tức', '', '_md_lr_news_menu', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281805, 3863, 42, 0, 0, 'Vận chuyển thanh toán', NULL, '_md_policy_service', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281806, 3863, 43, 0, 0, 'Menu chung: Gộp (Menu sản phẩm, slider, menu top)', '', '_md_combo_menu_slider', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281807, 3863, 44, 0, 0, 'Tìm kiếm nâng cao', '', '_md_search_advanced', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281808, 3863, 45, 0, 0, 'Menu top 2: Tùy chỉnh', '', '_md_menu_top_2', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281809, 3863, 46, 0, 0, 'Menu ngang: Sản phẩm ( kèm hình )', NULL, '_md_category_hot', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281810, 3863, 47, 0, 0, 'Banner: Tên cty, hotline, email, đăng ký', NULL, '_md_register_login', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281811, 3863, 48, 0, 0, 'Sản phẩm: Nổi bật (Slide)', NULL, '_md_product_hot_slide', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281812, 3863, 49, 0, 0, 'Slide chạy chữ', NULL, '_text_marquee_horizontal', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281813, 3863, 50, 0, 0, 'Ý kiến khách hàng', NULL, '_md_customer_comment', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281814, 3863, 51, 0, 0, 'Liên hệ', NULL, '_md_contact', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281815, 3863, 52, 0, 0, 'Banner: Hình ảnh 2', NULL, '_md_banner_2', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281816, 3863, 53, 0, 0, 'Banner: Hình ảnh 3', NULL, '_md_banner_3', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281817, 3863, 54, 0, 0, 'Bản đồ', NULL, '_md_google_map', NULL, 1, 'Y', 'N', '2018-08-14 18:45:54', '0000-00-00 00:00:00'),
(281818, 3863, 55, 0, 0, 'Footer tổng hợp', NULL, '_footer_total', NULL, 1, 'Y', 'N', '2018-08-26 20:32:30', '0000-00-00 00:00:00'),
(281819, 3863, 56, 281818, 0, 'Thông tin công ty', NULL, '_footer_total_company_info', NULL, 1, 'Y', 'N', '2018-08-26 20:36:58', '0000-00-00 00:00:00'),
(281820, 3863, 57, 281818, 0, 'Danh mục sản phẩm', NULL, '_footer_total_category', NULL, 2, 'Y', 'N', '2018-08-26 20:38:29', '0000-00-00 00:00:00'),
(281821, 3863, 58, 281818, 0, 'Tin tức footer', NULL, '_footer_total_news', NULL, 3, 'Y', 'N', '2018-08-26 20:41:16', '0000-00-00 00:00:00'),
(281822, 3863, 59, 281818, 0, 'Fanpage - Copyright', NULL, '_footer_total_fanpage_copyright', NULL, 4, 'Y', 'N', '2018-08-26 20:55:57', '0000-00-00 00:00:00'),
(281823, 3863, 60, 281818, 0, 'Logo mạng xã hội', NULL, '_footer_total_social_logo', NULL, 5, 'Y', 'N', '2018-08-26 20:57:18', '0000-00-00 00:00:00'),
(281824, 3863, 61, 281818, 0, 'Thống kê truy cập', NULL, '_footer_total_access_online', NULL, 6, 'Y', 'N', '2018-08-26 20:58:23', '0000-00-00 00:00:00'),
(281825, 3863, 62, 281818, 0, 'Bản đồ', NULL, '_footer_total_map', NULL, 7, 'Y', 'N', '2018-08-27 18:06:41', '0000-00-00 00:00:00'),
(281826, 3863, 63, 281818, 0, 'Hỗ Trợ', NULL, 'post', 12073, 8, 'Y', 'N', '2018-08-28 18:12:22', '2019-09-04 19:36:09'),
(281827, 3863, 63, 281818, 0, 'Hỗ Trợ 2', NULL, 'post', 12072, 9, 'Y', 'N', '2018-08-28 18:12:22', '2019-09-04 19:36:09'),
(281828, 3863, 63, 281818, 0, 'Modul đặt xe ( đang làm )', NULL, 'post', 12071, 10, 'Y', 'N', '2018-08-28 18:12:22', '2019-09-04 19:36:09'),
(281829, 3863, 76, 281818, 0, 'Bài liên hệ', NULL, '_footer_total_contact', NULL, 9, 'Y', 'N', '2019-05-09 06:27:15', '0000-00-00 00:00:00'),
(281830, 3863, 64, 0, 0, 'Đa ngôn ngữ tự nhập', NULL, '_multi_language_database', NULL, 1, 'Y', 'N', '2018-10-12 00:59:06', '0000-00-00 00:00:00'),
(281831, 3863, 66, 0, 0, 'Hỏi đáp', NULL, '_usually_question', NULL, 1, 'Y', 'N', '2018-10-24 06:35:49', '0000-00-00 00:00:00'),
(281832, 3863, 67, 0, 0, 'Module ngày giờ', NULL, '_md_date_time', NULL, 1, 'Y', 'N', '2018-11-04 09:05:29', '0000-00-00 00:00:00'),
(281833, 3863, 68, 0, 0, 'Dự án đã thực hiện', NULL, '_subdomain_implement', NULL, 1, 'Y', 'N', '2018-12-14 19:58:42', '0000-00-00 00:00:00'),
(281834, 3863, 69, 0, 0, 'Module tạo web', NULL, '_module_create_website', NULL, 1, 'Y', 'N', '2018-12-15 01:20:48', '0000-00-00 00:00:00'),
(281835, 3863, 70, 0, 0, 'Slider tin tức', NULL, '_slider_news', NULL, 1, 'Y', 'N', '2019-01-11 05:44:26', '0000-00-00 00:00:00'),
(281836, 3863, 71, 0, 0, 'Tin tức: Hiệu ứng nổi bật', NULL, '_news_hot_effect', NULL, 1, 'Y', 'N', '2019-01-24 18:18:25', '0000-00-00 00:00:00'),
(281837, 3863, 72, 0, 0, 'Sản phẩm: Nổi bật (Giới thiệu)', NULL, '_product_hot_about', NULL, 1, 'Y', 'N', '2019-03-03 18:17:39', '0000-00-00 00:00:00'),
(281838, 3863, 73, 0, 0, 'Tin tức: Thống kê', NULL, '_news_statistical', NULL, 1, 'Y', 'N', '2019-03-03 18:20:50', '0000-00-00 00:00:00'),
(281839, 3863, 74, 0, 0, 'Slider + Tin tức', NULL, '_md_slider_news', NULL, 1, 'Y', 'N', '2019-03-20 07:03:47', '0000-00-00 00:00:00'),
(281840, 3863, 75, 0, 0, 'Banner: Slider dọc', NULL, '_banner_slider_vertical', NULL, 1, 'Y', 'N', '2019-04-19 20:51:01', '0000-00-00 00:00:00'),
(281841, 3863, 77, 0, 0, 'Menu sản phẩm ( dạng bài viết sole )', NULL, '_category_group_sole', NULL, 1, 'Y', 'N', '2019-07-26 22:08:34', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) UNSIGNED NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` smallint(6) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `row_id` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slogan` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `summary` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `folder` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumb` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `head_content` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `body_content` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `hits` int(11) NOT NULL,
  `sort` int(11) DEFAULT 1,
  `active` char(1) COLLATE utf8_unicode_ci DEFAULT 'Y',
  `deleted` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `hot` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `new` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `most_view` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `introduct` char(1) COLLATE utf8_unicode_ci DEFAULT 'N',
  `slider` char(1) COLLATE utf8_unicode_ci DEFAULT 'N',
  `hot_effect` char(1) COLLATE utf8_unicode_ci DEFAULT 'N',
  `statistical` char(1) COLLATE utf8_unicode_ci DEFAULT 'N',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `type_id`, `subdomain_id`, `language_id`, `depend_id`, `row_id`, `name`, `slug`, `slogan`, `summary`, `content`, `folder`, `photo`, `thumb`, `title`, `keywords`, `description`, `head_content`, `body_content`, `hits`, `sort`, `active`, `deleted`, `hot`, `new`, `most_view`, `introduct`, `slider`, `hot_effect`, `statistical`, `created_at`, `modified_in`) VALUES
(37075, 0, 3863, 1, 0, 'ff4e13acf985869cc232', '8 tác dụng tuyệt vời của cao hồng sâm Hàn Quốc', '8-tac-dung-tuyet-voi-cua-cao-hong-sam-han-quoc', 'Mỗi khách hàng là một người bạn !', 'Cao hồng sâm Hàn Quốc là sản phẩm có nhiều công dụng tốt cho sức khỏe con người và là sản phẩm đang được ưa chuộng nhất trên thị trường hiện nay. Vậy bạn đã biết hết các tác dụng tuyệt vời của cao hồng sâm Hàn Quốc đối với sức khỏe chưa? Nếu chưa thì hãy cùng chúng tôi tìm hiểu về tác dụng của cao hồng sâm Hàn Quốc để có thêm sự hiểu biết nhất định về dòng sản phẩm chăm sóc sức khỏe cao cấp này.', '<p><strong>Cao hồng s&acirc;m H&agrave;n Quốc l&agrave; g&igrave;?</strong></p>\r\n\r\n<p><a href=\"https://kgchongsam.vn/wp-content/uploads/2015/05/hongsam-linh-vuc-hoat-dong.png\" rel=\"attachment wp-att-1253\"><img alt=\"hongsam-linh-vuc-hoat-dong\" height=\"250\" sizes=\"(max-width: 250px) 100vw, 250px\" src=\"https://kgchongsam.vn/wp-content/uploads/2015/05/hongsam-linh-vuc-hoat-dong.png\" srcset=\"https://kgchongsam.vn/wp-content/uploads/2015/05/hongsam-linh-vuc-hoat-dong-150x150.png 150w, https://kgchongsam.vn/wp-content/uploads/2015/05/hongsam-linh-vuc-hoat-dong-768x768.png 768w, https://kgchongsam.vn/wp-content/uploads/2015/05/hongsam-linh-vuc-hoat-dong-570x570.png 570w, https://kgchongsam.vn/wp-content/uploads/2015/05/hongsam-linh-vuc-hoat-dong-165x165.png 165w, https://kgchongsam.vn/wp-content/uploads/2015/05/hongsam-linh-vuc-hoat-dong-60x60.png 60w, https://kgchongsam.vn/wp-content/uploads/2015/05/hongsam-linh-vuc-hoat-dong.png 250w\" width=\"250\" /></a></p>\r\n\r\n<p>Cao hồng s&acirc;m H&agrave;n Quốc l&agrave; sản phẩm được chiết xuất v&agrave; c&ocirc; đặc chủ yếu từ những củ nh&acirc;n s&acirc;m l&acirc;u năm c&oacute; chất lượng cao nhất, k&iacute;ch cỡ đạt chuẩn. Trải qua qu&aacute; tr&igrave;nh tinh chế theo d&acirc;y chuyền hiện đại của H&agrave;n Quốc, c&aacute;c th&agrave;nh phần dưỡng chất c&oacute; lợi trong cao hồng s&acirc;m H&agrave;n Quốc, bao gồm hơn 34 loại saponin, ginsenosides, polyphenol, maltols, &hellip; được k&iacute;ch th&iacute;ch tăng cao hơn 230% so với mức trung b&igrave;nh của nh&acirc;n s&acirc;m. Ngo&agrave;i ra, c&aacute;c sản phẩm cao hồng s&acirc;m H&agrave;n Quốc c&ograve;n được kết hợp với nhiều thảo dược thi&ecirc;n nhi&ecirc;n qu&yacute; chứa hơn 15 yếu tố vi lượng cần thiết cho sức khỏe con người. Đ&acirc;y l&agrave; l&yacute; do tại sao&nbsp;<em>t&aacute;c dụng của cao hồng s&acirc;m H&agrave;n Quốc&nbsp;</em>&nbsp;tốt hơn gấp nhiều lần so với nh&acirc;n s&acirc;m tươi H&agrave;n Quốc th&ocirc;ng thường.</p>\r\n\r\n<p><strong>T&aacute;c dụng của cao hồng s&acirc;m H&agrave;n Quốc</strong></p>\r\n\r\n<p>C&aacute;c nh&agrave; khoa học cho biết, cao hồng s&acirc;m H&agrave;n Quốc l&agrave; d&ograve;ng sản phẩm cao cấp c&oacute; nhiều t&aacute;c dụng trong việc hỗ trợ điều trị bệnh v&agrave; bồi bổ sức khỏe.</p>\r\n\r\n<ul>\r\n	<li><em>K&iacute;ch th&iacute;ch hoạt động n&atilde;o bộ:</em>&nbsp;Đối với những người lao động tr&iacute; &oacute;c chịu nhiều &aacute;p lực th&igrave; việc d&ugrave;ng cao hồng s&acirc;m H&agrave;n Quốc thường xuy&ecirc;n với liều lượng ổn định sẽ gi&uacute;p tăng khả năng tập trung, cải thiện tr&iacute; nhớ, đồng thời k&iacute;ch th&iacute;ch hoạt động của tr&iacute; n&atilde;o, tăng năng suất l&agrave;m việc.</li>\r\n</ul>\r\n\r\n<ul>\r\n	<li><em>Giảm căng thẳng, stress:</em>&nbsp;Cuộc sống hiện đại với nhịp sống hối hả, &aacute;p lực c&ocirc;ng việc cao, d&ugrave;ng cao hồng s&acirc;m H&agrave;n Quốc c&oacute; t&aacute;c dụng điều tiết hormone trong cơ thể, gi&uacute;p giải tỏa mệt mỏi cả về tinh thần v&agrave; thể lực.</li>\r\n</ul>\r\n\r\n<ul>\r\n	<li><em>Điều h&ograve;a huyết &aacute;p, ổn định tim mạch:</em>&nbsp;D&ugrave;ng cao hồng s&acirc;m H&agrave;n Quốc kh&ocirc;ng chỉ gi&uacute;p lưu th&ocirc;ng m&aacute;u tốt m&agrave; c&ograve;n c&oacute; t&aacute;c dụng điều h&ograve;a lượng cholesterol trong m&aacute;u, &hellip; từ đ&oacute; ngăn ngừa v&agrave; ph&ograve;ng chống c&aacute;c bệnh li&ecirc;n quan đến tim mạch, huyết &aacute;p.</li>\r\n</ul>\r\n\r\n<ul>\r\n	<li><em>Bảo vệ gan khỏi c&aacute;c yếu tố độc hại:</em>&nbsp;Với h&agrave;m lượng saponin cao, cao hồng s&acirc;m H&agrave;n Quốc c&oacute; khả năng k&iacute;ch th&iacute;ch hoạt động của c&aacute;c enzyme li&ecirc;n quan tới sự tho&aacute;i h&oacute;a ethanol v&agrave; acetaldehyde, do đ&oacute; c&oacute; t&aacute;c dụng hỗ trợ giải độc gan, phục hồi chức năng gan, ngăn ngừa nguy cơ gan nhiễm mỡ do chế độ ăn uống v&agrave; vận động h&agrave;ng ng&agrave;y kh&ocirc;ng hợp l&yacute; g&acirc;y ra, đặc biệt l&agrave; ở những người uống nhiều rượu, bia.</li>\r\n</ul>\r\n\r\n<ul>\r\n	<li><em>Tăng cường chức năng sinh l&yacute;:</em>&nbsp;Nhiều nghi&ecirc;n cứu đ&atilde; chỉ ra rằng, cao hồng s&acirc;m H&agrave;n Quốc c&oacute; t&aacute;c dụng tổng hợp đạm v&agrave; DNA trong tế b&agrave;o tinh ho&agrave;n, do đ&oacute; hỗ trợ rất tốt cho những nam giới mắc bệnh yếu sinh l&yacute;, muốn n&acirc;ng cao sinh lực đ&agrave;n &ocirc;ng. Hơn nữa, d&ograve;ng sản phẩm cao cấp n&agrave;y c&ograve;n c&oacute; t&aacute;c dụng bổ thận, tr&aacute;ng dương, bồi bổ g&acirc;n cốt, lưu th&ocirc;ng kh&iacute; huyết trong cơ thể, hỗ trợ điều trị v&ocirc; sinh ở nam giới.</li>\r\n</ul>\r\n\r\n<ul>\r\n	<li><em>Ph&ograve;ng chống bệnh lo&atilde;ng xương:</em>&nbsp;<strong>T&aacute;c dụng của cao hồng s&acirc;m H&agrave;n Quốc&nbsp;</strong>&nbsp;l&agrave; gi&uacute;p điều tiết hormone estrogen, tăng khả năng hấp thụ canxi v&agrave; kho&aacute;ng chất của cơ thể, l&agrave;m cứng xương khớp, bải vệ hệ xương khớp chắc khỏe v&agrave; ph&ograve;ng chống bệnh lo&atilde;ng xương hiệu quả.</li>\r\n</ul>\r\n\r\n<ul>\r\n	<li><em>Hỗ trợ ph&ograve;ng chống ung thư:</em>&nbsp;Trải qua qu&aacute; tr&igrave;nh tinh luyện, c&aacute;c th&agrave;nh phần Gensenosides chứa trong cao hồng s&acirc;m H&agrave;n Quốc được k&iacute;ch th&iacute;ch tăng sinh ra nhiều hơn. Đồng thời, khi ở nhiệt độ cao, nh&acirc;n s&acirc;m sẽ bị ph&acirc;n hủy một số chất ảnh hưởng đến qu&aacute; tr&igrave;nh l&atilde;o h&oacute;a v&agrave; sản sinh th&ecirc;m c&aacute;c chất chống ung thư như Ginsenoside Rh2, Ginsenoside Rg3, &hellip; Nhờ vậy, d&ugrave;ng cao hồng s&acirc;m H&agrave;n Quốc c&oacute; thể ức chế sự sinh trưởng v&agrave; ph&aacute;t triển của tế b&agrave;o ung thư, gi&uacute;p hỗ trợ ph&ograve;ng chống ung thư hiệu quả.</li>\r\n</ul>\r\n\r\n<ul>\r\n	<li><em>Chống l&atilde;o h&oacute;a v&agrave; l&agrave;m đẹp:</em>&nbsp;Khoa học đ&atilde; chứng minh trong cao hồng s&acirc;m H&agrave;n Quốc c&oacute; chứa rất nhiều th&agrave;nh phần dưỡng chất cực kỳ qu&yacute; hiếm, đặc biệt phải kể đến th&agrave;nh phần Ginsenosides c&oacute; t&aacute;c dụng chống l&atilde;o h&oacute;a hiệu quả, giảm c&aacute;c vết nhăn, vết n&aacute;m, gi&uacute;p t&aacute;i tạo l&agrave;n da căng hồng, tươi trẻ, tr&agrave;n đầy sức sống, từ đ&oacute; duy tr&igrave; n&eacute;t thanh xu&acirc;n cho chị em phụ nữ.</li>\r\n</ul>\r\n\r\n<p><em>Cao hồng s&acirc;m H&agrave;n Quốc l&agrave; d&ograve;ng sản phẩm chăm s&oacute;c sức khỏe cao cấp</em></p>\r\n\r\n<p>Cao hồng s&acirc;m H&agrave;n Quốc kh&ocirc;ng chỉ được ưa chuộng bởi c&oacute; nhiều t&aacute;c dụng tốt cho sức khỏe con người m&agrave; n&oacute; c&ograve;n hấp dẫn người d&ugrave;ng bởi c&aacute;ch sử dụng cực kỳ đơn giản. Bạn chỉ cần lấy một lượng cao hồng s&acirc;m H&agrave;n Quốc đ&uacute;ng theo chỉ định pha v&agrave;o nước ấm v&agrave; uống mỗi ng&agrave;y 1 &ndash; 2 lần trước bữa ăn sẽ đem lại hiệu quả t&iacute;ch cực cho sức khỏe. Do cao hồng s&acirc;m H&agrave;n Quốc thuộc d&ograve;ng sản phẩm chức năng, v&igrave; thế bạn n&ecirc;n d&ugrave;ng đều đặn thường xuy&ecirc;n để đạt hiệu quả tốt nhất.</p>\r\n\r\n<p>Với những&nbsp;<strong>t&aacute;c dụng của cao hồng s&acirc;m H&agrave;n Quốc</strong>&nbsp; n&ecirc;u tr&ecirc;n xứng đ&aacute;ng trở th&agrave;nh bạn đồng h&agrave;nh đ&aacute;ng tin cậy cho sức khỏe của mọi người.</p>\r\n', '24-07-2019', 'hongsambabyhanquoc_OFasVUmX.jpg', NULL, 'Thiết kế website ', 'Website Nga Việt Hotel hoặc NgaViet hote;', '', NULL, NULL, 29, 1, 'Y', 'N', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2019-07-23 12:58:58', '2020-03-31 20:34:30'),
(37077, 0, 3863, 1, 0, '46927f5425aba57932df', 'CHUYÊN CUNG CẤP HÀNG CHẤT LƯỢNG XÁCH TAY TỪ NHẬT BẢN', 'chuyen-cung-cap-hang-chat-luong-xach-tay-tu-nhat-ban', 'Mỗi khách hàng là một người bạn !', '', '<p>ĐANG CẬP NHẬT....</p>\r\n', '24-07-2019', 'suatamcaocaphappybathhanquoc900ml32994_M3QhoxGp.jpg', NULL, 'Đối tác: Công ty LEHOMES (TNHH)', 'LEHOMES BACNINH', 'BACNINH LEHOMES // SMART HOME', NULL, NULL, 24, 1, 'Y', 'N', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2019-07-23 12:37:58', '2020-03-31 16:16:09'),
(37080, 0, 3863, 1, 0, '908cd306adcb3a8ac539', 'HÀNG TỐI NHẬT BẢN ', 'hang-toi-nhat-ban', '', '', '<article>\r\n<h1 class=\"entry-title\">ĐANG CẬP NHẬT....</h1>\r\n</article>\r\n', '17-12-2018', 'kbwatch02min_CD6ucV8k.jpg', NULL, '', '', '', NULL, NULL, 45, 1, 'Y', 'N', 'Y', 'Y', 'Y', 'N', 'N', 'N', 'N', '2018-12-17 04:03:58', '2020-03-30 15:48:56'),
(37081, 0, 3863, 1, 0, '7ff33a189e0e4e4a1504', 'XAY TAY NHẬT BÃN', 'xay-tay-nhat-ban', '', '', '<header class=\"entry-header\">\r\n<div class=\"entry-header-text entry-header-text-top text-center\">\r\n<header class=\"entry-header\">\r\n<div class=\"entry-header-text entry-header-text-top text-center\">\r\n<h1 class=\"entry-title\">ĐANG CẬP NHẬT....</h1>\r\n</div>\r\n</header>\r\n</div>\r\n</header>\r\n', '22-05-2019', '1c1514538733_SP8iww4C.jpg', NULL, '', '', '', NULL, NULL, 27, 1, 'Y', 'N', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', '2019-05-21 11:50:55', '2020-03-30 15:48:58');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

CREATE TABLE `newsletter` (
  `id` int(10) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `deleted` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `news_category`
--

CREATE TABLE `news_category` (
  `id` int(3) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `row_id` varchar(75) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `parent_id` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `content` text DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `hits` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'Y',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `menu` char(1) DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `news_menu`
--

CREATE TABLE `news_menu` (
  `id` int(3) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` smallint(6) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `parent_id` int(11) NOT NULL,
  `row_id` varchar(75) DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `hits` int(11) NOT NULL,
  `font_class` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `icon_type` smallint(6) DEFAULT 1,
  `sort` int(11) NOT NULL,
  `active` char(1) DEFAULT 'Y',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `menu` char(1) DEFAULT 'N',
  `footer` char(1) NOT NULL DEFAULT 'N',
  `home` char(1) NOT NULL DEFAULT 'N',
  `policy` char(1) DEFAULT 'N',
  `popup` char(1) DEFAULT 'Y',
  `reg_form` char(1) DEFAULT 'N',
  `messenger_form` char(1) DEFAULT 'N',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `news_menu`
--

INSERT INTO `news_menu` (`id`, `subdomain_id`, `language_id`, `depend_id`, `parent_id`, `row_id`, `level`, `name`, `slug`, `summary`, `content`, `title`, `keywords`, `description`, `hits`, `font_class`, `icon`, `icon_type`, `sort`, `active`, `deleted`, `menu`, `footer`, `home`, `policy`, `popup`, `reg_form`, `messenger_form`, `create_at`, `modified_in`) VALUES
(37471, 3863, 1, 0, 0, '1215', 0, 'Tin tức - Bài Viết', 'tin-tuc-bai-viet', '', '', '', '', '', 0, 'snowflake-o', NULL, 1, 1, 'Y', 'N', 'Y', 'Y', 'Y', 'N', 'N', 'N', 'N', '2018-09-29 00:08:32', '2019-05-27 19:29:53'),
(37472, 3863, 1, 0, 0, '2030', 0, 'Đăng Ký', 'dang-ky1', NULL, NULL, NULL, NULL, NULL, 0, 'user-o', NULL, 1, 1, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'Y', 'N', '2018-09-29 00:08:32', '2019-05-27 19:29:53'),
(37473, 3863, 1, 0, 0, '9c036ce029ef5cec3675', 0, 'Giới Thiệu', 'gioi-thieu', '', '', '', '', '', 0, '', NULL, 1, 1, 'Y', 'N', 'Y', 'N', 'Y', 'N', 'N', 'N', 'N', '2018-09-29 02:23:46', '2019-05-27 19:29:53'),
(37474, 3863, 1, 0, 0, '29161', 0, 'THIẾT KẾ WEBSITE', 'thiet-ke-website', '', '', '', '', '', 0, '', NULL, 1, 1, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'N', 'N', '2018-11-21 23:48:37', '2019-09-04 21:09:54'),
(37475, 3863, 1, 0, 0, '34c1b98695185982b898', 0, 'DỊCH VỤ - SỮA CHỮA', 'dich-vu---sua-chua', '', '', '', '', '', 0, '', 'screwdriverandwrenchcrossed1uqmmcoj_7PCyGIjY.png', 2, 1, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'N', 'Y', '2019-05-14 09:03:47', '2019-09-04 21:09:57'),
(37476, 3863, 1, 0, 37475, '50f3e5fec0d816c8043a', 1, 'Sữa Chữa Máy Chiếu', 'sua-chua-may-chieu', '', '', '', '', '', 0, '', NULL, 1, 5, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'N', 'N', '2019-05-27 10:20:27', '2019-05-27 19:29:53'),
(37477, 3863, 1, 0, 37475, '99159be83664ebb73ba0', 1, 'Sữa Chữa Laptop - PC', 'sua-chua-laptop---pc', '', '<p style=\"text-align: center;\"><span style=\"font-size:20px;\"><strong>TOPBACNINH.COM - DỊCH VỤ SỬA CHỮA LAPTOP LẤY NGAY</strong></span></p>\r\n\r\n<p><span style=\"text-align: justify;\">&nbsp;</span><span style=\"font-family: Arial; font-size: 12pt;\"><span style=\"font-size: 12pt;\">Như c&aacute;c bạn đ&atilde; biết, khi search từ kh&oacute;a &ldquo;</span><strong style=\"color: #000000;\">Dịch vụ sửa laptop</strong><span style=\"font-size: 12pt;\">&rdquo; h&agrave;ng triệu kết quả trả về kh&ocirc;ng đến 1 gi&acirc;y. H&agrave;ng ng&agrave;n trung t&acirc;m sửa chữa laptop mọc l&ecirc;n như nấm, chắc chắn bạn sẽ &ldquo;như đứng đống lửa như ngồi đống than&rdquo; khi giao ph&oacute; laptop, macbook, PC của m&igrave;nh cho những trung t&acirc;m tr&ocirc;i nổi. &ldquo;Tiền mất, tật mang&rdquo; - dĩ nhi&ecirc;n chẳng ai mong muốn điều đ&oacute;. Đừng lo lắng, trải nghiệm dịch vụ sửa chữa m&aacute;y t&iacute;nh tại Sửa chữa laptop tại TopBacNinh.com, bạn sẽ thấy sự kh&aacute;c biệt.</span></span></p>\r\n\r\n<div>&nbsp;</div>\r\n\r\n<div style=\"text-align: justify; \">&nbsp;</div>\r\n\r\n<div style=\"text-align: center; \"><img alt=\"sua_laptop_cong_nghe_4_0\" src=\"https://vitinhvominh.com/upload/images/Bai_viet/DV_lam_vo_laptop/lam-vo-bi-be-do-gay-ban-le.jpg\" title=\"Sửa laptop công nghệ 4.0\" /></div>\r\n\r\n<div style=\"text-align: center; \">\r\n<h3 style=\"text-align: center;\"><span style=\"font-family:times new roman,times,serif;\"><span style=\"font-size:18px;\"><em>H&igrave;nh: L&agrave;m vỏ laptop bị bể, g&atilde;y, nứt</em></span></span></h3>\r\n<span style=\"font-family: Arial; font-size: 12pt;\"><span style=\"font-size: 12pt;\">TopBacNinh.com - Dịch vụ số 1 Bắc Ninh</span></span></div>\r\n\r\n<h2 style=\"text-align: justify; \"><span style=\"font-family: Arial; font-size: 14pt;\"><strong>&ldquo;Tản mạn&rdquo; về dịch vụ sửa laptop tại Sửa chữa </strong></span><span style=\"font-size:20px;\"><strong><span style=\"font-family: Arial;\">TopBacNinh.com</span></strong></span></h2>\r\n\r\n<div style=\"text-align: justify; \"><span style=\"font-size:20px;\"><strong><span style=\"font-family: Arial;\">TopBacNinh.com</span></strong></span><span style=\"font-family: Arial; font-size: 12pt;\"><span style=\"font-family: Arial; font-size: 12pt;\"> l&agrave; một địa chỉ sửa laptop uy t&iacute;n tại Bắc Ninh v&agrave; Bắc Giang, với đội ngũ kỹ thuật&nbsp;gi&agrave;u&nbsp;kinh nghiệm sửa chữa.</span></span></div>\r\n\r\n<div style=\"text-align: justify; \"><span style=\"font-size:20px;\"><strong><span style=\"font-family: Arial;\">TopBacNinh.com</span></strong></span><span style=\"font-family: Arial; font-size: 12pt;\"><span style=\"font-family: Arial; font-size: 12pt;\"> sẽ gi&uacute;p bạn khắc nhanh phục sự cố&nbsp;m&aacute;y t&iacute;nh, laptop, macbook.</span></span></div>\r\n\r\n<div style=\"text-align: justify; \">&nbsp;</div>\r\n\r\n<div style=\"text-align: center; \"><img alt=\"dịch vụ sửa laptop tận tâm uy tín\" height=\"400\" longdesc=\"khách hàng được trực tiếp kiểm tra quá trình sửa laptop\" src=\"https://www.suachualaptop24h.com/uploads/dich-vu-sua-laptop-uy-tin.jpg\" width=\"600\" /></div>\r\n\r\n<div style=\"text-align: center;\"><span style=\"font-family: Arial; font-size: 12pt;\"><em>Kh&aacute;ch h&agrave;ng trực tiếp quan s&aacute;t v&agrave; nghe giải đ&aacute;p thắc mắc</em></span></div>\r\n\r\n<div style=\"text-align: justify; \">&nbsp;</div>\r\n\r\n<div style=\"text-align: center; \"><img alt=\"dịch vụ sửa laptop, bảo dưỡng laptop\" height=\"394\" longdesc=\"dịch vụ sửa laptop, bảo dưỡng laptop uy tín mà bạn hoàn toàn tin tưởng\" src=\"https://www.suachualaptop24h.com/uploads/bao-duong-laptop.jpg\" width=\"600\" /></div>\r\n\r\n<div style=\"text-align: center;\"><span style=\"font-family: Arial; font-size: 12pt;\"><em>Dịch vụ sửa laptop tại </em></span><span style=\"font-size:20px;\"><strong><span style=\"font-family: Arial;\">TopBacNinh.com</span></strong></span><span style=\"font-family: Arial; font-size: 12pt;\"><em> l&agrave; sự lựa chọn số 1&nbsp;của bạn</em></span></div>\r\n\r\n<h2 style=\"text-align: justify; \"><span style=\"font-family: Arial; font-size: 14pt;\"><strong>Dịch vụ sửa laptop lấy ngay, chất lượng v&agrave; uy t&iacute;n tại </strong></span><span style=\"font-size:20px;\"><strong><span style=\"font-family: Arial;\">TopBacNinh.com</span></strong></span></h2>\r\n\r\n<h3 style=\"text-align: justify; \"><span style=\"font-family: Arial; font-size: 12pt;\"><strong><em>- Sửa c&aacute;c pan bệnh phần cứng laptop</em></strong></span></h3>\r\n\r\n<div style=\"text-align: justify; \"><span style=\"font-family: Arial; font-size: 12pt;\">+ Sửa m&agrave;n h&igrave;nh m&aacute;y t&iacute;nh c&aacute;c lỗi m&agrave;n h&igrave;nh laptop bị kẻ ngang kẻ dọc, m&agrave;n h&igrave;nh laptop c&oacute; điểm chết, m&agrave;n h&igrave;nh laptop bị nh&ograve;e mờ, giật h&igrave;nh...</span></div>\r\n\r\n<div><span style=\"font-family: Arial; font-size: 12pt;\">+ Sửa b&agrave;n ph&iacute;m laptop c&aacute;c lỗi <strong style=\"color: #000000;\">b&agrave;n ph&iacute;m laptop bị liệt</strong></span><span style=\"font-family: Arial; font-size: 12pt;\">, b&agrave;n ph&iacute;m laptop bị treo đơ, b&agrave;n ph&iacute;m laptop bị chạm, bung mạch...</span></div>\r\n\r\n<div><span style=\"font-family: Arial; font-size: 12pt;\">+ Sửa main m&aacute;y t&iacute;nh, khắc phục mainboard laptop c&aacute;c lỗi chập ch&aacute;y tr&ecirc;n main, lỗi Bios, lỗi VGA&hellip;</span></div>\r\n\r\n<div><span style=\"font-family: Arial; font-size: 12pt;\">+ Sửa Chip laptop</span></div>\r\n\r\n<div><span style=\"font-family: Arial; font-size: 12pt;\">+ Sửa chuột m&aacute;y t&iacute;nh, sửa pin m&aacute;y t&iacute;nh nhanh ch&oacute;ng</span></div>\r\n\r\n<div><span style=\"font-family: Arial; font-size: 12pt;\">+ Sửa nguồn m&aacute;y t&iacute;nh</span></div>\r\n\r\n<div><span style=\"font-family: Arial; font-size: 12pt;\">+ Sửa c&aacute;c loại card laptop: card m&agrave;n h&igrave;nh laptop, card wifi laptop, card sound</span></div>\r\n\r\n<h3 style=\"text-align: justify; \"><span style=\"font-family: Arial; font-size: 12pt;\"><strong><em>- Sửa c&aacute;c pan bệnh phần mềm laptop</em></strong></span></h3>\r\n\r\n<div><span style=\"font-family: Arial; font-size: 12pt;\">+ Cập nhật Windows, c&agrave;i Windows/MacOS</span></div>\r\n\r\n<div><span style=\"font-family: Arial; font-size: 12pt;\">+ Cập nhật phần mềm đồ họa, tin học văn ph&ograve;ng, c&aacute;c chương tr&igrave;nh diệt virus</span></div>\r\n\r\n<h3 style=\"text-align: justify; \"><span style=\"font-family: Arial; font-size: 12pt;\"><strong><em>- Thay thế c&aacute;c linh kiện laptop :&nbsp;</em></strong></span><span style=\"font-family: Arial; font-size: 12pt;\"><strong style=\"color: #000000;\">m&agrave;n h&igrave;nh,&nbsp;</strong></span><span style=\"font-family: Arial; font-size: 12pt; color: #000000;\"><strong><span style=\"color: #000000;\">b&agrave;n ph&iacute;m,&nbsp;</span></strong></span><span style=\"font-family: Arial; font-size: 12pt;\"><strong style=\"color: #000000;\">sạc, pin,&nbsp;</strong>mainboard hoặc n&acirc;ng cấp RAM laptop, Main, CPU laptop</span></h3>\r\n\r\n<div style=\"text-align: justify; \"><span style=\"font-family: Arial; font-size: 12pt;\">+ <strong style=\"color: #000000;\">Sửa laptop lấy ngay</strong></span></div>\r\n\r\n<div><span style=\"font-family: Arial; font-size: 12pt;\">+ <strong style=\"color: #000000;\">Vệ sinh laptop, bảo dưỡng laptop chuy&ecirc;n nghiệp</strong></span></div>\r\n\r\n<div><span style=\"font-family: Arial; font-size: 12pt;\"><strong><em>- Bảo tr&igrave;, bảo dưỡng laptop chuy&ecirc;n nghiệp</em></strong></span></div>\r\n\r\n<div><span style=\"font-family: Arial; font-size: 12pt;\"><strong><em>- Mua b&aacute;n, thanh l&yacute; laptop gi&aacute; tốt&nbsp;&nbsp;</em></strong></span></div>\r\n\r\n<div style=\"text-align: justify; \"><span style=\"font-family: Arial; font-size: 12pt;\"><span style=\"font-family: Arial; font-size: 12pt;\">Th&ecirc;m v&agrave;o đ&oacute;, khi mang laptop, macbook, m&aacute;y t&iacute;nh đi kh&aacute;m định kỳ 6 th&aacute;ng một lần tại sửa chữa </span></span><span style=\"font-size:20px;\"><strong><span style=\"font-family: Arial;\">TopBacNinh.com</span></strong></span><span style=\"font-family: Arial; font-size: 12pt;\"><span style=\"font-family: Arial; font-size: 12pt;\">, bạn sẽ được </span><strong style=\"color: #000000;\">vệ sinh laptop</strong><span style=\"font-family: Arial; font-size: 12pt;\"> miễn ph&iacute;, bao gồm vệ sinh b&agrave;n ph&iacute;m, quạt tản nhiệt, ổ cứng, CPU&hellip;.v&agrave; được tra kem tản nhiệt nếu như m&aacute;y t&iacute;nh của bạn c&oacute; hiện tượng kh&ocirc; keo hoặc qu&aacute; n&oacute;ng khi sử dụng với mức gi&aacute; hợp l&yacute; nhất.</span></span></div>\r\n\r\n<div style=\"text-align: justify; \">&nbsp;</div>\r\n\r\n<div style=\"text-align: center; \"><img alt=\"\" src=\"https://www.suachualaptop24h.com/uploads/Anh-bai-viet-2019/bao_duong_laptop.jpg\" /></div>\r\n\r\n<div style=\"text-align: center;\"><span style=\"font-family: Arial; font-size: 12pt;\"><em>Kh&aacute;ch h&agrave;ng đến sử dụng dịch vụ sửa laptop tại </em></span><span style=\"font-size:20px;\"><strong><span style=\"font-family: Arial;\">TopBacNinh.com</span></strong></span></div>\r\n\r\n<div style=\"text-align: center; \">&nbsp;</div>\r\n\r\n<div><span style=\"font-family: Arial; font-size: 12pt;\">Xem th&ecirc;m:</span></div>\r\n\r\n<div style=\"text-align: justify; \"><span style=\"font-family: Arial; font-size: 12pt;\"><a href=\"http://www.suachualaptop24h.com/Goc-chia-se/Huong-dan-cach-bao-duong-ve-sinh-laptop-tai-nha-dung-cach-d4717.html\" target=\"_blank\"><em>Hướng dẫn vệ sinh bảo dưỡng laptop tại nh&agrave;</em></a></span></div>\r\n\r\n<div style=\"text-align: justify; \"><a href=\"http://www.suachualaptop24h.com/Thu-thuat-Laptop/Muoi-van-cau-hoi-vi-sao-ve-cac-loi-pin-laptop-va-cach-khac-phuc-P1--d6244.html\" target=\"_blank\"><em><span style=\"font-family: Arial; font-size: 12pt;\">C&aacute;c lỗi pin laptop thường gặp</span></em></a></div>\r\n\r\n<div style=\"text-align: justify; \"><a href=\"http://www.suachualaptop24h.com/Linh-kien-Laptop-1/Tong-hop-cac-loi-ban-phim-laptop-pho-bien-va-cach-khac-phuc-P1--d6223.html\" target=\"_blank\"><em><span style=\"font-family: Arial; font-size: 12pt;\">Tổng hợp c&aacute;c lỗi b&agrave;n ph&iacute;m phổ biến v&agrave; c&aacute;ch khắc phục</span></em></a></div>\r\n\r\n<div style=\"text-align: justify; \">&nbsp;</div>\r\n\r\n<div><span style=\"font-family: Arial; font-size: 12pt;\">Tất cả c&aacute;c dịch vụ của </span><span style=\"font-size:20px;\"><strong><span style=\"font-family: Arial;\">TopBacNinh.com</span></strong></span><span style=\"font-family: Arial; font-size: 12pt;\"> lu&ocirc;n được kh&aacute;ch h&agrave;ng đ&aacute;nh gi&aacute; cao sau lần đầu sử dụng.</span></div>\r\n\r\n<h2 style=\"text-align: justify; \"><span style=\"font-family: Arial; font-size: 14pt;\"><strong>Khuyến c&aacute;o sử dụng laptop đ&uacute;ng c&aacute;ch của trung t&acirc;m</strong></span></h2>\r\n\r\n<div style=\"text-align: justify; \"><span style=\"font-family: Arial; font-size: 12pt;\">Trong h&agrave;ng trăm ca bệnh m&agrave; trung t&acirc;m chữa trị mỗi ng&agrave;y c&oacute; kh&ocirc;ng &iacute;t những trường hợp m&aacute;y t&iacute;nh, laptop bị hỏng do những bất cẩn của kh&aacute;ch h&agrave;ng trong qu&aacute; tr&igrave;nh sử dụng. Chỉ cần ch&uacute; &yacute; hơn tới th&oacute;i quen sử dụng của m&igrave;nh th&ocirc;i th&igrave; người d&ugrave;ng đ&atilde; c&oacute; thể tr&aacute;nh được rất nhiều trường hợp hỏng m&aacute;y kh&ocirc;ng đ&aacute;ng c&oacute; rồi. V&igrave; vậy, khi l&agrave;m việc hay học tập với m&aacute;y t&iacute;nh, mỗi người d&ugrave;ng m&aacute;y t&iacute;nh cần lưu &yacute;:</span></div>\r\n\r\n<div style=\"font-family: Arial; font-size: 12pt;\"><span style=\"font-family: Arial; font-size: 12pt;\">- Nhẹ nh&agrave;ng khi mang v&aacute;c, di chuyển laptop.</span></div>\r\n\r\n<div style=\"font-family: Arial; font-size: 12pt;\"><span style=\"font-family: Arial; font-size: 12pt;\">- Kh&ocirc;ng để m&aacute;y qu&aacute; n&oacute;ng, tr&aacute;nh bắt m&aacute;y l&agrave;m việc nặng trong thời gian d&agrave;i, kh&ocirc;ng vừa cắm vừa sạc, cũng kh&ocirc;ng n&ecirc;n l&agrave;m b&iacute;t c&aacute;c luồng tho&aacute;t kh&iacute; của m&aacute;y do th&oacute;i quen thường xuy&ecirc;n đặt m&aacute;y l&ecirc;n đệm khi sử dụng.</span></div>\r\n\r\n<div style=\"font-family: Arial; font-size: 12pt;\"><span style=\"font-family: Arial; font-size: 12pt;\">- Giữ m&aacute;y t&iacute;nh lu&ocirc;n sạch sẽ, ch&uacute; &yacute; khi ăn uống b&ecirc;n cạnh laptop v&igrave; c&oacute; rất nhiều trường hợp m&aacute;y hỏng do vụn bẩn t&iacute;ch tụ l&acirc;u ng&agrave;y, đặc biệt l&agrave; khi người d&ugrave;ng kh&ocirc;ng cẩn thận l&agrave;m đổ nước v&agrave;o m&aacute;y t&iacute;nh g&acirc;y chập ch&aacute;y c&aacute;c linh kiện b&ecirc;n trong m&aacute;y.</span></div>\r\n\r\n<div><span style=\"font-family: Arial; font-size: 12pt;\">- Định kỳ vệ sinh bảo dưỡng m&aacute;y, n&acirc;ng cấp laptop nếu cần để m&aacute;y t&iacute;nh của bạn c&oacute; thể hoạt động với hiệu suất tốt nhất m&agrave; kh&ocirc;ng lo bị hỏng h&oacute;c.</span></div>\r\n\r\n<div>&nbsp;</div>\r\n\r\n<div style=\"text-align: justify; \">&nbsp;</div>\r\n\r\n<div style=\"text-align: center; \"><img alt=\"sử dụng laptop đúng cách\" height=\"400\" longdesc=\"sử dụng laptop đúng cách để tránh laptop bị hỏng phải đi sửa\" src=\"https://www.suachualaptop24h.com/uploads/su-dung-laptop.png\" width=\"600\" /></div>\r\n\r\n<div style=\"text-align: center;\"><span style=\"font-family: Arial; font-size: 12pt;\"><em>Kh&ocirc;ng đặt laptop l&ecirc;n gối, nệm, đ&ugrave;i khi sang sử dụng</em></span></div>\r\n\r\n<div style=\"text-align: center;\">&nbsp;</div>\r\n\r\n<div><span style=\"font-family: Arial; font-size: 12pt;\">Trường hợp m&aacute;y t&iacute;nh, laptop, Macbook của bạn kh&ocirc;ng may bị hỏng, bị lỗi m&agrave; kh&ocirc;ng biết c&aacute;ch <strong style=\"color: #000000;\">sửa m&aacute;y t&iacute;nh tại nh&agrave;</strong> th&igrave; h&atilde;y đem đến </span><span style=\"font-size:20px;\"><strong><span style=\"font-family: Arial;\">TopBacNinh.com</span></strong></span><span style=\"font-family: Arial; font-size: 12pt;\"> để được c&aacute;c kỹ thuật vi&ecirc;n l&agrave;nh nghề giải quyết nhanh gọn gi&uacute;p bạn nh&eacute;!</span></div>\r\n', '', '', '', 0, '', NULL, 1, 4, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'N', 'N', '2019-05-27 10:31:36', '2019-07-21 05:46:07'),
(37478, 3863, 1, 0, 37475, '121476344bc265b22cd8', 1, 'Cho Thuê Máy Chiếu', 'cho-thue-may-chieu', 'ctmc', '', '', '', '', 0, '', NULL, 1, 3, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'N', 'N', '2019-05-27 10:39:08', '2019-05-27 19:29:53'),
(37479, 3863, 1, 0, 37475, '17d2699ad7c836ea16af', 1, 'Phần Mềm Virut Bản Quyền', 'phan-mem-virut-ban-quyen', '', '', '', '', '', 0, '', NULL, 1, 2, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'N', 'N', '2019-05-27 10:42:04', '2019-05-27 19:29:53'),
(37480, 3863, 1, 0, 37475, '2a83b0c19040c2a4cc6e', 1, 'Phần mềm quản lý bán hàng', 'phan-mem-quan-ly-ban-hang-344', '', '', '', '', '', 0, '', NULL, 1, 1, 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'N', 'N', '2019-05-27 10:43:31', '2019-05-27 19:29:53'),
(37481, 3863, 1, 0, 0, 'e5c2745c9ec4f3e69802', 0, 'ĐỐI TÁC', 'doi-tac', '', '', '', '', '', 0, '', NULL, 1, 1, 'Y', 'N', 'N', 'Y', 'N', 'N', 'N', 'N', 'N', '2019-05-16 23:00:27', '2019-05-27 19:29:53');

-- --------------------------------------------------------

--
-- Table structure for table `news_type`
--

CREATE TABLE `news_type` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `row_id` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `summary` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `folder` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumb` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `active` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `menu` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `static` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `member_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `ip_address` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_info` text COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `total` int(11) NOT NULL,
  `currency` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_status` int(11) NOT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method` smallint(6) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`id`, `name`) VALUES
(1, 'Mới đặt'),
(2, 'Đã xác nhận'),
(3, 'Đang giao ship'),
(4, 'Đã giao'),
(5, 'Đã hủy');

-- --------------------------------------------------------

--
-- Table structure for table `password_changes`
--

CREATE TABLE `password_changes` (
  `id` int(10) UNSIGNED NOT NULL,
  `usersId` int(10) UNSIGNED NOT NULL,
  `ipAddress` char(15) NOT NULL,
  `userAgent` varchar(48) NOT NULL,
  `createdAt` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `profilesId` int(10) UNSIGNED NOT NULL,
  `resource` varchar(16) NOT NULL,
  `action` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT=' users access resource';

-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'Y',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `position`
--

INSERT INTO `position` (`id`, `name`, `code`, `sort`, `active`, `deleted`, `create_at`, `modified_in`) VALUES
(5, 'Header', 'header', 1, 'Y', 'N', '2017-09-27 04:44:15', '0000-00-00 00:00:00'),
(6, 'Cột trái', 'left', 2, 'Y', 'N', '2017-09-27 04:44:15', '0000-00-00 00:00:00'),
(7, 'Cột phải', 'right', 3, 'Y', 'N', '2017-09-27 04:44:15', '0000-00-00 00:00:00'),
(8, 'Cột giữa', 'center', 4, 'Y', 'N', '2017-09-27 04:44:15', '0000-00-00 00:00:00'),
(9, 'Footer', 'footer', 5, 'Y', 'N', '2017-09-27 04:44:15', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` smallint(6) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `module_item_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `row_id` varchar(255) DEFAULT NULL,
  `messenger_form` char(1) DEFAULT 'N',
  `mic_support_head` char(1) DEFAULT 'N',
  `mic_support_foot` char(1) DEFAULT 'N',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL,
  `deleted` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `subdomain_id`, `language_id`, `depend_id`, `module_item_id`, `name`, `content`, `position`, `row_id`, `messenger_form`, `mic_support_head`, `mic_support_foot`, `created_at`, `modified_in`, `sort`, `active`, `deleted`) VALUES
(12071, 3863, 1, 0, 281794, 'Modul đặt xe ( đang làm )', '<p>H&atilde;y c&ugrave;ng nhau chia sẻ để tạo ra cơ hội mới v&agrave; lợi nhuận chia đều !</p>\r\n\r\n<p>Li&ecirc;n hệ: 091 628 3628 ; Email: topbacninh@gmail.com&nbsp;</p>\r\n', NULL, 'post3598', 'Y', 'N', 'Y', '2019-09-04 19:36:09', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(12072, 3863, 1, 0, 281795, 'Hỗ Trợ 2', '<div class=\"boxhotrotructuyen\"><img alt=\"\" height=\"67\" src=\"https://cssgoc.110.vn/uploads/201/post/post224/sp.jpg\" width=\"49\" />\r\n<div class=\"support_info support\">\r\n<div class=\"support_name support\">Hỗ Trợ Trực Tuyến</div>\r\n<strong class=\"phone-number\"><a href=\"tel:091 628 3628\">091 628 3628</a></strong>&nbsp;;&nbsp;<a class=\"bg-support-menuright call-facebook\" href=\"https://www.facebook.com/Topbacninh-923313188005001/?modal=admin_todo_tour\">FACEBOOK</a> <a class=\"bg-support-menuright call-zalo\" href=\"https://zalo.me/0916283628\">ZALO</a> <a class=\"bg-support-menuright call-mail\" href=\"mail:topbacninh.com\">EMAIL</a></div>\r\n</div>\r\n', NULL, 'post3598', 'N', 'N', 'N', '2019-09-04 19:36:09', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(12073, 3863, 1, 0, 281796, 'Hỗ Trợ', '', NULL, 'post3598', 'Y', 'Y', 'N', '2019-09-04 19:36:09', '0000-00-00 00:00:00', 1, 'Y', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `price_range`
--

CREATE TABLE `price_range` (
  `id` int(11) NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `from_price` bigint(20) NOT NULL,
  `to_price` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NULL DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT 1,
  `active` char(1) COLLATE utf8_unicode_ci DEFAULT 'Y',
  `deleted` char(1) COLLATE utf8_unicode_ci DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` smallint(6) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `row_id` varchar(75) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `cart_link` varchar(255) DEFAULT NULL,
  `enable_link` tinyint(1) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `cost` bigint(20) DEFAULT 0,
  `price` bigint(20) DEFAULT 0,
  `cost_usd` int(11) DEFAULT NULL,
  `price_usd` int(11) DEFAULT NULL,
  `in_stock` int(11) DEFAULT 0,
  `folder` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `photo_secondary` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL,
  `purchase_number` int(11) DEFAULT 0,
  `sort` int(11) DEFAULT 1,
  `home` char(1) DEFAULT 'N',
  `hot` char(1) DEFAULT 'N',
  `selling` char(1) NOT NULL DEFAULT 'N',
  `promotion` char(1) NOT NULL DEFAULT 'N',
  `new` char(1) NOT NULL DEFAULT 'N',
  `gift` char(1) NOT NULL DEFAULT 'N',
  `out_stock` char(1) DEFAULT 'N',
  `active` char(1) NOT NULL DEFAULT 'Y',
  `deleted` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8
PARTITION BY RANGE COLUMNS(`subdomain_id`)
(
PARTITION p0 VALUES LESS THAN (500) ENGINE=InnoDB,
PARTITION p1 VALUES LESS THAN (1000) ENGINE=InnoDB,
PARTITION p2 VALUES LESS THAN (1500) ENGINE=InnoDB,
PARTITION p3 VALUES LESS THAN (2000) ENGINE=InnoDB,
PARTITION p4 VALUES LESS THAN (2500) ENGINE=InnoDB,
PARTITION p5 VALUES LESS THAN (3000) ENGINE=InnoDB,
PARTITION p6 VALUES LESS THAN (3500) ENGINE=InnoDB,
PARTITION p7 VALUES LESS THAN (4000) ENGINE=InnoDB,
PARTITION p8 VALUES LESS THAN (MAXVALUE) ENGINE=InnoDB
);

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `subdomain_id`, `language_id`, `depend_id`, `row_id`, `name`, `slug`, `link`, `cart_link`, `enable_link`, `summary`, `code`, `cost`, `price`, `cost_usd`, `price_usd`, `in_stock`, `folder`, `photo`, `photo_secondary`, `title`, `keywords`, `description`, `created_at`, `modified_in`, `hits`, `purchase_number`, `sort`, `home`, `hot`, `selling`, `promotion`, `new`, `gift`, `out_stock`, `active`, `deleted`) VALUES
(101133, 3863, 1, 0, 'fd39c66b6a5c6bba72ab', 'Trà sâm Hàn Quốc cao cấp 300g', 'tra-sam-han-quoc-cao-cap-300g', '', '', NULL, '', '', 0, 0, NULL, NULL, 0, '05-09-2019', '44ab117dac45794eda90068ce30c4b8cd6large_60nWqfZT.jpg', NULL, '', '', '', '2019-09-04 20:27:05', '2020-03-30 15:49:03', 27, 0, 1, 'N', 'Y', 'Y', 'N', 'Y', 'N', 'N', 'Y', 'N'),
(101135, 3863, 1, 0, '10a649476019644143dd', 'Hồng sâm baby', 'hong-sam-baby', '', '', NULL, '<p><strong>Th&agrave;nh phần:</strong></p>\r\n\r\n<ul>\r\n	<li><strong>Tinh chất hồng s&acirc;m 6 năm tuổi</strong>&nbsp;( RB1 &amp; RG1 8mg/g_</li>\r\n	<li>C&aacute;c loại thảo dược thi&ecirc;n nhi&ecirc;n.</li>\r\n	<li>Th&agrave;nh phần rau củ tổng hợp: dưa leo, bơ, cải xoăn...</li>\r\n	<li>Hỗn hợp axit amin: L-phenyl alanin, L-valin, L-leucine...</li>\r\n	<li>C&aacute;c vitamin v&agrave; kho&aacute;ng chất tổng hợp: kẽm glucat tổng hợp, vitamin B, B6, Vitamin C, vitamin D...</li>\r\n</ul>\r\n\r\n<p><strong>Quy c&aacute;ch sản phẩm :</strong></p>\r\n\r\n<p>1 hộp x 30 g&oacute;i x 20ml</p>\r\n\r\n<p><strong>H&atilde;ng sản xuất :</strong></p>\r\n\r\n<p>Cheon Ji Yang</p>\r\n\r\n<p><strong>Xuất xứ&nbsp;</strong>:</p>\r\n\r\n<p>H&agrave;n Quốc</p>\r\n', '', 0, 0, NULL, NULL, 0, '05-09-2019', 'hongsambabyhanquoc_LbWMYDGJ.jpg', NULL, '', '', '', '2019-09-04 20:33:29', '2020-03-30 15:49:05', 24, 0, 1, 'N', 'Y', 'Y', 'N', 'Y', 'N', 'N', 'Y', 'N'),
(101136, 3863, 1, 0, '523c82082df4a8392808', 'Sữa Tắm Cao Cấp Happy Bath Hàn Quốc 900ml', 'sua-tam-cao-cap-happy-bath-han-quoc-900ml', '', '', NULL, '<p>L&agrave; sữa tắm cực được ưa chuộng tại H&agrave;n Quốc, mang m&ugrave;i thơm nhẹ nh&agrave;ng của hoa thục quỳ, hoa hồng, oải hương, gạo&hellip; Trong th&agrave;nh phần của sữa tắm c&oacute; chứa nhiều chất kho&aacute;ng, c&aacute;c loại hoa tươi v&agrave; tảo biển, mang lại cảm gi&aacute;c thoải m&aacute;i, sảng kho&aacute;i, lưu lại m&ugrave;i hương quyến rũ sau khi tắm</p>\r\n', '', 0, 0, NULL, NULL, 0, '05-09-2019', 'suatamcaocaphappybathhanquoc900ml32994_Z6zqFNK1.jpg', NULL, '', '', '', '2019-09-04 20:37:29', '2020-03-31 20:32:45', 36, 0, 1, 'N', 'Y', 'Y', 'N', 'Y', 'N', 'N', 'Y', 'N'),
(101137, 3863, 1, 0, '123edf917cfcad5183bf', 'SỮA CÔNG THỨC WITH MOM - HÀN QUỐC SỐ 1 ', 'sua-cong-thuc-with-mom-han-quoc-so-1', '', '', NULL, '', '', 0, 0, NULL, NULL, 0, '05-09-2019', 'suawithmomhanquocso1900x1000compact_ntEXRCdQ.jpg', NULL, '', '', '', '2019-09-04 20:39:54', '2020-03-30 15:49:08', 31, 0, 1, 'N', 'Y', 'Y', 'N', 'Y', 'N', 'N', 'Y', 'N'),
(101138, 3863, 1, 0, '2e653bb0a53a1f4c5b50', 'HỒNG DẺO HÀN QUỐC', 'hong-deo-han-quoc', '', '', NULL, '<p>Hồng dẻo h&agrave;n quốc qu&agrave; biếu tặng sang trọng, sỉ hồng dẻo từ 10h với gi&aacute; cực k&igrave; hấp dẫn</p>\r\n', '', 0, 0, NULL, NULL, 0, '05-09-2019', '217682827319277736705596895295111327034352n_WDVGb9nc.jpg', NULL, '', '', '', '2019-09-04 20:42:40', '2020-03-30 15:49:11', 43, 0, 1, 'N', 'Y', 'Y', 'N', 'Y', 'N', 'N', 'Y', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `product_content`
--

CREATE TABLE `product_content` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` smallint(6) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `product_id` int(11) NOT NULL,
  `product_detail_id` int(11) NOT NULL,
  `row_id` varchar(75) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sort` int(11) NOT NULL DEFAULT 1,
  `active` char(1) NOT NULL,
  `deleted` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8
PARTITION BY RANGE COLUMNS(`subdomain_id`)
(
PARTITION p0 VALUES LESS THAN (500) ENGINE=InnoDB,
PARTITION p1 VALUES LESS THAN (1000) ENGINE=InnoDB,
PARTITION p2 VALUES LESS THAN (1500) ENGINE=InnoDB,
PARTITION p3 VALUES LESS THAN (2000) ENGINE=InnoDB,
PARTITION p4 VALUES LESS THAN (2500) ENGINE=InnoDB,
PARTITION p5 VALUES LESS THAN (3000) ENGINE=InnoDB,
PARTITION p6 VALUES LESS THAN (3500) ENGINE=InnoDB,
PARTITION p7 VALUES LESS THAN (4000) ENGINE=InnoDB,
PARTITION p8 VALUES LESS THAN (MAXVALUE) ENGINE=InnoDB
);

--
-- Dumping data for table `product_content`
--

INSERT INTO `product_content` (`id`, `subdomain_id`, `language_id`, `depend_id`, `product_id`, `product_detail_id`, `row_id`, `content`, `created_at`, `modified_in`, `sort`, `active`, `deleted`) VALUES
(438690, 3863, 1, 0, 101133, 10438, NULL, '<p><span style=\"font-size:16px;\">Tr&agrave; s&acirc;m H&agrave;n Quốc cao cấp 300g/동원천지인홍삼차 300g</span></p>\r\n\r\n<p><span style=\"font-size:16px;\"><strong>Hồng s&acirc;m H&agrave;n Quốc</strong>&nbsp;l&agrave; sản phẩm được l&agrave;m từ rễ của c&acirc;y nh&acirc;n s&acirc;m H&agrave;n Quốc 6 năm tuổi. Hồng s&acirc;m c&oacute; chứa c&aacute;c vitamin v&agrave; kho&aacute;ng chất c&ugrave;ng một số loại tinh dầu v&agrave; c&aacute;c enzyme tự nhi&ecirc;n.&nbsp;<br />\r\n<br />\r\n<strong>Tr&agrave; hồng s&acirc;m cao cấp H&agrave;n Quốc 300g (100 g&oacute;i x 3g)&nbsp;</strong>l&agrave; sản phẩm được nghi&ecirc;n cứu v&agrave; sản xuất tr&ecirc;n d&acirc;y chuyền hiện đại, lấy th&agrave;nh ch&iacute;nh l&agrave;&nbsp;<strong>hồng s&acirc;m H&agrave;n Quốc</strong>. Sản phẩm n&agrave;y đ&atilde; được chứng minh l&agrave; c&oacute; t&aacute;c dụng l&agrave;m giảm vi&ecirc;m, l&agrave; chất chống oxy h&oacute;a tự nhi&ecirc;n, hỗ trợ tăng cường sức chịu đựng của cơ thể. Đồng thời, sản phẩm n&agrave;y c&ograve;n c&oacute; khả năng l&agrave;m tăng sức chịu đựng, tăng cường hệ miễn dịch v&agrave; l&agrave;m giảm nguy cơ mắc &nbsp;tiểu đường đối với người bị tiểu đường</span></p>\r\n\r\n<p><span style=\"font-size:16px;\"><strong>&agrave;nh phần của sản phẩm:</strong><br />\r\n<br />\r\nSản phẩm lấy 100% th&agrave;nh phần ch&iacute;nh l&agrave; hồng s&acirc;m 6 năm tuổi H&agrave;n Quốc nghiền ở dạng bột v&agrave; đ&oacute;ng g&oacute;i th&agrave;nh loại tr&agrave;.&nbsp;<strong>Hồng s&acirc;m 6 năm tuổi H&agrave;n Quốc&nbsp;</strong>nổi tiếng với c&aacute;c loại saponin, đ&oacute;ng vai tr&ograve; như một chất để hỗ trợ tăng cường sức đề kh&aacute;ng.</span></p>\r\n\r\n<p><span style=\"font-size:16px;\"><strong>T&aacute;c dụng ch&iacute;nh của sản phẩm:</strong><br />\r\n<br />\r\nTh&agrave;nh phần c&oacute; &iacute;ch trong&nbsp;<strong>tr&agrave; nh&acirc;n s&acirc;m H&agrave;n Quốc</strong>&nbsp;c&oacute; thể gi&uacute;p tăng cường sức mạnh chiến đấu của c&aacute;c bạch cầu, c&aacute;c tế b&agrave;o m&aacute;u trắng m&agrave; ti&ecirc;u diệt vi khuẩn v&agrave; vi r&uacute;t x&acirc;m nhập v&agrave;o cơ thể của bạn. Đồng thời,&nbsp;<strong>tr&agrave; hồng s&acirc;m&nbsp;</strong>c&ograve;n c&oacute; khả năng tạo ra tế b&agrave;o hồng cầu chống lại c&aacute;c virus g&acirc;y hại.&nbsp;&nbsp;Tăng cường năng lượng,&nbsp;Hỗ trợ tr&aacute;i tim khỏe mạnh</span><br />\r\n&nbsp;</p>\r\n\r\n<p><span style=\"font-size:16px;\">c&oacute; khả năng ổn định nồng độ cholesterol trong m&aacute;u của bạn. N&oacute; c&oacute; thể l&agrave;m giảm mật độ lipoprotein (cholesterol xấu). Đồng thời, điều tiết v&agrave; sản xuất c&aacute;c loại cholesterol c&oacute; hại để loại bỏ v&agrave; chống lại c&aacute;c oxy h&oacute;a tự do g&acirc;y tổn hại cho c&aacute;c m&ocirc; tim v&agrave; động mạch, tiếp tục giữ cho tr&aacute;i tim của bạn khỏe mạnh.</span></p>\r\n\r\n<p><span style=\"font-size:16px;\">- Đối với nam giới, tr&agrave; thảo dược n&agrave;y c&oacute; thể cải thiện chức năng t&igrave;nh dục, giảm thiểu c&aacute;c trường hợp rối loạn chức năng cương dương.</span></p>\r\n\r\n<p><span style=\"font-size:16px;\">- Ngo&agrave;i ra, sản phẩm c&ograve;n c&oacute; t&aacute;c dụng tăng cường sinh lực, tăng cường sức đề kh&aacute;ng cho cơ thể con người.</span></p>\r\n', '2019-09-04 20:27:05', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(438691, 3863, 1, 0, 101133, 10439, NULL, '', '2019-09-04 20:27:05', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(438692, 3863, 1, 0, 101133, 10440, NULL, '', '2019-09-04 20:27:05', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(438694, 3863, 1, 0, 101135, 10438, NULL, '<h2>T&Aacute;C DỤNG CỦA HỒNG S&Acirc;M BABY&nbsp;</h2>\r\n\r\n<p>Người H&agrave;n Quốc coi Hồng s&acirc;m như thực phẩm bổ dưỡng, n&ecirc;n d&ugrave;ng h&agrave;ng ng&agrave;y để tăng cường sức đề kh&aacute;ng, cải thiện hệ miễn dịch&hellip;Sản phẩm&nbsp;<strong>Hồng s&acirc;m baby</strong>&nbsp;(<strong>Hồng s&acirc;m cho trẻ em</strong>) đ&atilde; được điều chỉnh h&agrave;m lượng nguy&ecirc;n chất theo đ&uacute;ng thể trạng lứa tuổi của trẻ.*</p>\r\n\r\n<p>Với những<strong>&nbsp;trẻ biếng ăn</strong>&nbsp;th&igrave; hồng s&acirc;m H&agrave;n Quốc cho b&eacute;<strong>&nbsp;</strong>(<strong>Hồng s&acirc;m trẻ em</strong>) l&agrave; sự kết hợp giữa tinh chất Hồng s&acirc;m c&ocirc; đặc với c&aacute;c th&agrave;nh phần dược thảo đ&ocirc;ng y bổ dưỡng kh&aacute;c như nhung, vitamin để tăng cường hệ miễn dịch, sức đề kh&aacute;ng cho c&aacute;c b&eacute;.*</p>\r\n\r\n<p><strong>C&aacute;c b&eacute; n&ecirc;n d&ugrave;ng Hồng s&acirc;m baby</strong>: b&eacute; c&oacute; hệ miễn dịch k&eacute;m, hay bị ốm, cơ thể suy nhược, thể lực yếu. C&aacute;c b&eacute; bị suy dinh dưỡng,&nbsp;<strong>b&eacute; lười ăn</strong>, chậm ph&aacute;t triển. C&aacute;c b&eacute; hoạt động nhiều, hay bị &aacute;p lực do cường độ học tập cao&hellip;*</p>\r\n\r\n<p><strong>Ph&aacute;t triển tr&iacute; n&atilde;o, tăng tr&iacute; nhớ</strong>: D&ugrave;ng&nbsp;<strong>s&acirc;m H&agrave;n Quốc cho b&eacute;</strong>&nbsp;h&agrave;ng ng&agrave;y sẽ được bổ sung c&aacute;c chất bổ dưỡng để k&iacute;ch th&iacute;ch tr&iacute; n&atilde;o hoạt động dẫn đến tăng cường ph&aacute;t triển tr&iacute; nhớ v&agrave; n&atilde;o cho b&eacute;.*</p>\r\n\r\n<p><strong>Giải độc gan:</strong>&nbsp;Saponin (hoạt chất ch&iacute;nh tạo n&ecirc;n những c&ocirc;ng dụng của Nh&acirc;n s&acirc;m) trong&nbsp;<strong>hong sam baby</strong>&nbsp;l&agrave;m tăng hoạt động enzym li&ecirc;n quan tới sự tho&aacute;i h&oacute;a ethanol v&agrave; acetaldehyd, do đ&oacute; gi&uacute;p gan của b&eacute; c&oacute; thể thanh lọc, giải độc.*</p>\r\n\r\n<p><strong>Cải thiện t&igrave;nh trạng biếng ăn, c&ograve;i xương, suy dinh dưỡng</strong>: trong Hồng s&acirc;m trẻ em c&oacute; bổ sung lượng canxi, vitamin v&agrave; một số dược thảo đ&ocirc;ng y kh&aacute;c v&igrave; vậy c&oacute; thể cải thiện được đ&aacute;ng kể t&igrave;nh trạng biếng ăn ở trẻ, gi&uacute;p trẻ ăn ngon miệng hơn, ngủ ngon hơn v&agrave; ph&aacute;t triển xương.*</p>\r\n\r\n<h2>C&Aacute;CH SỬ DỤNG HỒNG S&Acirc;M BABY&nbsp;</h2>\r\n\r\n<p>L&agrave; dạng lỏng n&ecirc;n c&oacute; thể cho b&eacute; uống trực tiếp 1 ng&agrave;y 1 lần, mỗi lần 1/2 g&oacute;i đến 1 g&oacute;i. &nbsp;N&ecirc;n uống v&agrave;o buổi s&aacute;ng, sau ăn 1h.&nbsp;</p>\r\n\r\n<p>Hoặc bạn cũng c&oacute; thể chia nhỏ 1 g&oacute;i cho b&eacute; uống l&agrave;m 2 ng&agrave;y m&agrave; kh&ocirc;ng ảnh hưởng tới chất lượng cũng như hiệu quả của hồng s&acirc;m.</p>\r\n\r\n<p>Đối với b&eacute; tr&ecirc;n 3 tuổi th&igrave; c&oacute; thể uống 1 g&oacute;i 1 ng&agrave;y.&nbsp;</p>\r\n\r\n<p><br />\r\nC&ograve;n c&aacute;c b&eacute; từ 8 tuổi trở l&ecirc;n uống h&agrave;ng ng&agrave;y, mỗi ng&agrave;y 1-2 g&oacute;i. Rất tốt cho b&eacute; m&agrave; kh&ocirc;ng c&oacute; bất cứ t&aacute;c dụng phụ n&agrave;o.*</p>\r\n\r\n<p><em>* Lưu &yacute;: C&ocirc;ng dụng của sản phẩm c&oacute; thể kh&aacute;c nhau t&ugrave;y thuộc v&agrave;o thể trạng v&agrave; cơ địa của mỗi b&eacute;.</em></p>\r\n\r\n<h4>Bảo quản:</h4>\r\n\r\n<p>Bảo quản nơi tho&aacute;ng m&aacute;t, tr&aacute;nh &aacute;nh nắng mặt trời v&agrave; tia bức xạ. Sản phẩm sau khi mở nắp n&ecirc;n bảo quản trong ngăn m&aacute;t tủ lạnh.</p>\r\n\r\n<p style=\"text-align:center\"><img alt=\"nhà máy sản xuất\" src=\"http://hongsamhanquoc.net/Upload/CKFinder/images/nha-may-san-xuat(1).jpg\" /></p>\r\n\r\n<h2>QUY TR&Igrave;NH SẢN XUẤT HỒNG S&Acirc;M</h2>\r\n\r\n<p>Mọi quy tr&igrave;nh sản xuất Hồng S&acirc;m&nbsp; đều trải qua c&aacute;c b&agrave;i kiểm tra, thử nghiệm nghi&ecirc;m ngặt từ gieo trồng, thu hoạch, đến sản xuất. Theo truyền thống, người H&agrave;n Quốc bắt đầu thu hoạch nh&acirc;n s&acirc;m từ bốn đến s&aacute;u năm tuổi.&nbsp;&nbsp;</p>\r\n\r\n<p>Nh&acirc;n s&acirc;m sau khi sau khi thu hoạch sẽ được rửa hai lần dưới s&oacute;ng si&ecirc;u &acirc;m. Những củ nh&acirc;n s&acirc;m được rửa sạch sẽ sau đ&oacute; sẽ được hấp một ng&agrave;y trước khi trải qua v&agrave;i tuần l&agrave;m kh&ocirc; trong &aacute;nh s&aacute;ng mặt trời để trờ th&agrave;nh Hồng S&acirc;m.</p>\r\n\r\n<p style=\"text-align:center\"><img alt=\"quy trình sản xuất nhân sâm\" src=\"http://hongsamhanquoc.net/Upload/CKFinder/images/quy-trinh-san-xuat-nhan-sam(2).jpg\" /></p>\r\n', '2019-09-04 20:33:29', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(438695, 3863, 1, 0, 101135, 10439, NULL, '', '2019-09-04 20:33:29', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(438696, 3863, 1, 0, 101135, 10440, NULL, '', '2019-09-04 20:33:29', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(438700, 3863, 1, 0, 101136, 10438, NULL, '<p><strong>Sữa Tắm Cao Cấp Happy Bath H&agrave;n Quốc 900ml</strong></p>\r\n\r\n<p><strong>Xuất xứ</strong>:<strong>&nbsp;H&agrave;n Quốc</strong></p>\r\n\r\n<p><strong>Dung t&iacute;ch</strong>: 900ml</p>\r\n\r\n<p><strong>C&oacute; 4 m&ugrave;i:</strong>&nbsp;Oải hương , gạo, hoa hồng, tr&aacute;i c&acirc;y</p>\r\n\r\n<table align=\"Center\" cellpadding=\"5\" cellspacing=\"5\" id=\"ContentPlaceHolder1_DataList1\">\r\n	<tbody>\r\n		<tr>\r\n			<td align=\"left\" valign=\"top\">\r\n			<table align=\"center\">\r\n				<tbody>\r\n					<tr>\r\n						<td>\r\n						<p>&nbsp;</p>\r\n\r\n						<p><strong>C&ocirc;ng dụng</strong>&nbsp;của sữa tắm Happy Bath H&agrave;n Quốc</p>\r\n\r\n						<p>Cung cấp nhiều loại axit amin l&agrave;m cho da mềm mại v&agrave; đ&agrave;n hồi tốt</p>\r\n\r\n						<p>Bọt sữa tắm nhỏ mịn với c&ocirc;ng thức đặc biệt l&agrave;m sạch da.</p>\r\n\r\n						<p>Chất chống &ocirc; xy h&oacute;a l&agrave;m trẻ h&oacute;a l&agrave;n da, chứa nhiều vitamin</p>\r\n\r\n						<p>&nbsp;</p>\r\n\r\n						<p>Chiết xuất thục quỳ (mashmallow) hữu cơ l&agrave;m sạch v&agrave; dễ chịu l&agrave;n da mệt mỏi do stress li&ecirc;n tục</p>\r\n\r\n						<p>Chiết xuất l&ocirc; hội hữu cơ giữ ẩm cho da mịn m&agrave;ng.</p>\r\n\r\n						<p>Hương thơm dịu nhẹ c&ugrave;ng với c&aacute;c tinh chất chăm s&oacute;c l&agrave;n da, mang đến cho bạn cảm gi&aacute;c dễ chịu v&agrave; giảm stress.</p>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td>\r\n						<p style=\"text-align:center\"><img alt=\"Sữa Tắm Cao Cấp Happy Bath Hàn Quốc 900ml-418\" src=\"https://myphamxinhdep.com/admincp/San-Pham/HinhAnh/-28456.jpg\" /></p>\r\n\r\n						<h2>&nbsp;</h2>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td align=\"left\" valign=\"top\">\r\n			<table align=\"center\">\r\n				<tbody>\r\n					<tr>\r\n						<td>\r\n						<p>&nbsp;</p>\r\n\r\n						<p><strong>Rose Essence - Hoa Hồng</strong>&nbsp;với 95% tinh chất được chiết xuất từ hoa hồng sẽ mang lại l&agrave;n da s&aacute;ng mịn, đủ độ ẩm, v&agrave; cảm gi&aacute;c thư gi&atilde;n, dễ chịu, ho&agrave;n to&agrave;n sảng kho&aacute;i sau khi tắm</p>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td>\r\n						<p style=\"text-align:center\"><img alt=\"Sữa Tắm Cao Cấp Happy Bath Hàn Quốc 900ml-419\" src=\"https://myphamxinhdep.com/admincp/San-Pham/HinhAnh/sua-tam-cao-cap-happy-bath-han-quoc-900ml-37443.jpg\" /></p>\r\n\r\n						<h2>&nbsp;</h2>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td align=\"left\" valign=\"top\">\r\n			<table align=\"center\">\r\n				<tbody>\r\n					<tr>\r\n						<td>\r\n						<p>&nbsp;</p>\r\n\r\n						<p>&nbsp;</p>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td>\r\n						<p style=\"text-align:center\"><img alt=\"Sữa Tắm Cao Cấp Happy Bath Hàn Quốc 900ml-420\" src=\"https://myphamxinhdep.com/admincp/San-Pham/HinhAnh/sua-tam-cao-cap-happy-bath-han-quoc-900ml-25764.jpg\" /></p>\r\n\r\n						<h2>&nbsp;</h2>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td align=\"left\" valign=\"top\">\r\n			<table align=\"center\">\r\n				<tbody>\r\n					<tr>\r\n						<td>\r\n						<p>&nbsp;</p>\r\n\r\n						<p><strong>Lavender Essence</strong>&nbsp;- chiết xuất 95% từ Hoa Oải Hương thực vật mang lại l&agrave;n da đủ độ ẩm, s&aacute;ng mịn, đem đến cảm gi&aacute;c thư gi&atilde;n, ho&agrave;n to&agrave;n sảng kho&aacute;i dễ chịu v&agrave; thư gi&atilde;n sau khi tắm.</p>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td>\r\n						<p style=\"text-align:center\"><img alt=\"Sữa Tắm Cao Cấp Happy Bath Hàn Quốc 900ml-421\" src=\"https://myphamxinhdep.com/admincp/San-Pham/HinhAnh/sua-tam-cao-cap-happy-bath-han-quoc-900ml-14161.jpg\" /></p>\r\n\r\n						<h2>&nbsp;</h2>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td align=\"left\" valign=\"top\">\r\n			<table align=\"center\">\r\n				<tbody>\r\n					<tr>\r\n						<td>\r\n						<p>&nbsp;</p>\r\n\r\n						<p><strong>Sữa Tắm Happy Bath Natural Real Mild Body Wash&nbsp;</strong>l&agrave; d&ograve;ng sản phẩm tự nhi&ecirc;n với th&agrave;nh phần chiết xuất 95% từ thực vật gi&uacute;p loại bỏ chất thải da v&agrave; c&aacute;c tế b&agrave;o da chết 1 c&aacute;ch nhẹ nh&agrave;ng,duy tr&igrave; độ ẩm tr&ecirc;n da trong 1 thời gian d&agrave;i.</p>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td>\r\n						<p style=\"text-align:center\"><img alt=\"Sữa Tắm Cao Cấp Happy Bath Hàn Quốc 900ml-422\" src=\"https://myphamxinhdep.com/admincp/San-Pham/HinhAnh/sua-tam-cao-cap-happy-bath-han-quoc-900ml-12853.jpg\" /></p>\r\n\r\n						<h2>&nbsp;</h2>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td align=\"left\" valign=\"top\">\r\n			<table align=\"center\">\r\n				<tbody>\r\n					<tr>\r\n						<td>\r\n						<p>&nbsp;</p>\r\n\r\n						<p><strong>Sữa Tắm Happy Bath Natural Real Moisture</strong>&nbsp;- chiết xuất tr&aacute;i c&acirc;y, từ chuối v&agrave; t&aacute;o gi&agrave;u Vitamin, protein, đường, c&aacute;c chất dưỡng ẩm tự nhi&ecirc;n từ l&ocirc; hội, gi&uacute;p dưỡng ẩm da kh&ocirc; tự nhi&ecirc;n với hiệu quả l&agrave;m sạch hương thơm nhẹ nh&agrave;ng</p>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td>\r\n						<p style=\"text-align:center\"><img alt=\"Sữa Tắm Cao Cấp Happy Bath Hàn Quốc 900ml-423\" src=\"https://myphamxinhdep.com/admincp/San-Pham/HinhAnh/sua-tam-cao-cap-happy-bath-han-quoc-900ml-52822.jpg\" /></p>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n', '2019-09-04 20:37:42', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(438701, 3863, 1, 0, 101136, 10439, NULL, '', '2019-09-04 20:37:42', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(438702, 3863, 1, 0, 101136, 10440, NULL, '', '2019-09-04 20:37:42', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(438706, 3863, 1, 0, 101137, 10438, NULL, '<p>Sữa c&ocirc;ng thức tr&ecirc;n thị trường v&ocirc; v&agrave;n chủng loại v&agrave; thương hiệu, với gi&aacute; trị năng lượng cung cấp c&oacute; thể giống nhau nhưng nếu chứa h&agrave;m lượng đạm với th&agrave;nh phần c&aacute;c acid amin thiết yếu v&agrave; b&aacute;n thiết yếu (tương tự như sữa mẹ) kh&aacute;c nhau th&igrave; t&aacute;c động l&ecirc;n cơ thể cung ho&agrave;n to&agrave;n kh&aacute;c nhau.</p>\r\n\r\n<p>Khi sữa mẹ kh&ocirc;ng phải l&agrave; nguồn cung cấp đạm ch&iacute;nh, tức mẹ kh&ocirc;ng cung cấp đủ sữa mẹ cho b&eacute; b&uacute; do t&aacute;c động của nhi&ecirc;u nguy&ecirc;n nh&acirc;n kh&aacute;c nhau ( mẹ đi l&agrave;m, mẹ &iacute;t sữa, mẹ đang điều trị bằng kh&aacute;ng sinh&hellip;)&nbsp; th&igrave; việc bổ sung sữa c&ocirc;ng thức c&oacute; sinh khả dụng v&agrave; chất lượng đạm tương tự sữa mẹ l&agrave; cực k&igrave; quan trọng.</p>\r\n\r\n<p>Đ&oacute; l&agrave; l&yacute; do v&igrave; sao With Mom ra đời với sứ mệnh l&agrave; d&ograve;ng sữa cao cấp nhất hiện nay theo dự &aacute;n sữa cao cấp của tập đo&agrave;n danh tiếng Lotte Foods H&agrave;n Quốc, gi&uacute;p h&agrave;ng triệu trẻ em kh&ocirc;ng chỉ ri&ecirc;ng H&agrave;n Quốc m&agrave; tr&ecirc;n Thế giới tiếp cận được d&ograve;ng sữa tuyệt vời như d&ograve;ng sữa Mẹ.</p>\r\n\r\n<p data-mce-style=\"text-align: center;\">&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<ol>\r\n	<li><strong>Sữa c&ocirc;ng thức cao cấp With Mom ch&uacute; trọng đến th&agrave;nh phần đạm trong sữa, gi&uacute;p giảm thiểu nguy cơ dị ứng sữa, hỗ trợ tăng c&acirc;n tốt hơn</strong></li>\r\n</ol>\r\n\r\n<p>Đạm (Protein) rất cần thiết trong sản xuất kh&aacute;ng thể để bảo vệ cơ thể, do đ&oacute;, đ&aacute;p ứng nhu cầu về đạm l&agrave; v&ocirc; c&ugrave;ng quan trọng. Thiếu hụt đạm sẽ dẫn đến cơ thể chậm tăng trưởng suy dinh dưỡng, rối loạn chức năng nhiều tuyến nội tiết, suy giảm miễn dịch, tăng tần suất nhiễm tr&ugrave;ng.</p>\r\n\r\n<p>Sữa c&ocirc;ng thức cao cấp With Mom hỗ trợ trẻ tăng c&acirc;n tốt theo nguy&ecirc;n tắc cung cấp đầy đủ dinh dưỡng thiết yếu, đặc biệt ch&uacute; trọng đến việc giảm nguy cơ dị ứng sữa b&ograve; v&agrave; tăng cường khả năng hấp cho hệ ti&ecirc;u h&oacute;a c&ograve;n non nớt ở trẻ nhờ ứng dụng đưa đạm Whey Protein thủy ph&acirc;n bổ sung v&agrave;o th&agrave;nh phần của sữa, thay v&igrave; để tỉ lệ đạm casein cao như c&aacute;c loại sữa th&ocirc;ng thường.</p>\r\n\r\n<p style=\"text-align:center\"><img alt=\"\" data-mce-src=\"//file.hstatic.net/1000317759/file/sua-withmom-han-quoc-7.jpg\" data-mce-style=\"display: block; margin-left: auto; margin-right: auto;\" src=\"https://file.hstatic.net/1000317759/file/sua-withmom-han-quoc-7.jpg\" /></p>\r\n\r\n<p><em>Vai tr&ograve; của Đạm ( Protein) đối với cơ thể</em></p>\r\n\r\n<p><em>V&igrave; sao Sữa c&ocirc;ng thức cao cấp With Mom bổ sung đạm Whey Protein thủy ph&acirc;n thay v&igrave; chọn sữa chứa nhiều đạm Casein th&ocirc;ng thường?</em></p>\r\n\r\n<p>Đạm casein c&oacute; đặc t&iacute;nh v&oacute;n cục n&ecirc;n giải ph&oacute;ng acid amin cho cơ thể chậm, gi&uacute;p tr&aacute;nh được sự ti&ecirc;u huỷ cơ để giữ cho nồng độ acid amin trong m&aacute;u ổn định ngay cả thời điểm c&aacute;ch xa bữa ăn, đảm bảo việc cung cấp nitrogen ổn định cho cơ thể. Tuy nhi&ecirc;n Casein hay c&ograve;n gọi l&agrave; đạm ban đ&ecirc;m mất tới 7 tiếng mới c&oacute; thể chuyển h&oacute;a v&agrave; hấp thu ho&agrave;n to&agrave;n, do đ&oacute; nếu d&ugrave;ng sữa c&oacute; Protein casein cao tr&ecirc;n 40% sẽ g&acirc;y n&ecirc;n kh&oacute; ti&ecirc;u, t&aacute;o b&oacute;n ,ngo&agrave;i ra c&ograve;n g&acirc;y bất dung nạp lactose dẫn đến c&aacute;c phản ứng dị ứng như mẩn ngứa, mề đay, vi&ecirc;m da cơ địa&hellip;</p>\r\n\r\n<p>Trong khi đ&oacute; đạm Whey hay c&ograve;n gọi l&agrave; đạm ban ng&agrave;y c&oacute; vai tr&ograve; l&agrave;m tăng tổng hợp protein, tăng acid amin trong m&aacute;u, c&oacute; nhiều leucin k&iacute;ch th&iacute;ch cho tổng hợp protein. Đạm whey dễ ti&ecirc;u ho&aacute; v&agrave; hấp thu nhanh n&ecirc;n thời gian trống dạ d&agrave;y nhanh, gi&uacute;p trẻ kh&ocirc;ng bị đầy bụng, kh&oacute; ti&ecirc;u v&agrave; tăng c&acirc;n tốt</p>\r\n\r\n<p>Vấn đề lớn nhất khiến trẻ dị ứng sữa b&ograve; ngay cả khi d&ugrave;ng đạm Whey l&agrave; đạm Whey trong sữa mẹ kh&ocirc;ng giống đạm Whey trong sữa b&ograve;.</p>\r\n\r\n<p style=\"text-align:center\"><img alt=\"\" data-mce-src=\"//file.hstatic.net/1000317759/file/sua-withmom-han-quoc-8.jpg\" data-mce-style=\"display: block; margin-left: auto; margin-right: auto;\" src=\"https://file.hstatic.net/1000317759/file/sua-withmom-han-quoc-8.jpg\" /></p>\r\n\r\n<p data-mce-style=\"text-align: center;\"><em>Sự kh&aacute;c nhau giữa đạm Whey v&agrave; Casein trong sữa</em></p>\r\n\r\n<p>Trong sữa mẹ c&oacute; nhiều Tryptophan gi&uacute;p chuyển ho&aacute; th&agrave;nh Vitamin B3, tiền chất của serotonin n&ecirc;n c&oacute; vai tr&ograve; điều ho&agrave; ngon miệng, giấc ngủ, t&acirc;m trạng đ&acirc;y l&agrave; điều rất quan trọng của trẻ nhỏ. Ngo&agrave;i ra whey trong sữa mẹ c&ograve;n c&oacute; nhiều yếu tố kh&aacute;ng thể như immunoglobulin, Lactoferrin.</p>\r\n\r\n<p>&nbsp;C&ograve;n trong sữa b&ograve; Whey chứa nhiều &szlig; lactoglobulin, li&ecirc;n kết kh&aacute; chặt chẽ, do đ&oacute; để cơ thể hấp thu tốt whey sữa b&ograve; như sữa mẹ th&igrave; c&aacute;c chuy&ecirc;n gia dinh dưỡng đ&atilde; nghi&ecirc;n cứu v&agrave; thực hiện phương ph&aacute;p thủy ph&acirc;n một phần đạm whey ( một qu&aacute; tr&igrave;nh phức tạp nhưng hiểu đơn giản l&agrave; t&aacute;c động nhiệt độ để th&aacute;o xoắn chuỗi peptid, sau đ&oacute; thủy ph&acirc;n bằng men chọn lọc gi&uacute;p chuỗi polypeptide được chia cắt&nbsp;th&agrave;nh những chuỗi nhỏ hơn l&agrave; oligopeptid c&oacute; trọng lượng 2-10.000 dalton n&ecirc;n giảm được nguy cơ dị ứng 300-1000 lần, tăng tỉ lệ hấp thu của cơ thể l&ecirc;n đến 95% so với chưa thủy ph&acirc;n.</p>\r\n\r\n<p>Như vậy ta đ&atilde; thấy trẻ sử dụng&nbsp;sữa c&oacute; đạm Whey Protein thuỷ ph&acirc;n c&oacute; thời gian chuyển h&oacute;a nhanh hơn so với sữa c&oacute; casein v&agrave; khả năng hấp thu tương tự như sữa mẹ. C&aacute;c đặc t&iacute;nh dinh dưỡng kh&ocirc;ng bị thay đổi trong sữa c&oacute; đạm thuỷ ph&acirc;n một phần. Mặt kh&aacute;c trẻ d&ugrave;ng sữa Protein thủy ph&acirc;n vượt trội th&igrave; c&oacute; ph&acirc;n mềm hơn so với trẻ d&ugrave;ng sữa casein vượt trội, điều n&agrave;y gi&uacute;p trẻ chống được t&aacute;o b&oacute;n một c&aacute;ch hiệu quả.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<ol start=\"2\">\r\n	<li><strong>Sữa C&ocirc;ng thức cao cấp With Mom l&agrave; sữa sản xuất theo c&ocirc;ng nghệ hữu cơ si&ecirc;u sạch</strong></li>\r\n</ol>\r\n\r\n<p>With Mom sử dụng 100% nguy&ecirc;n liệu hữu cơ, lấy nguy&ecirc;n liệu l&agrave; sữa tươi n&ocirc;ng trại<strong>&nbsp;</strong>b&ograve; sữa&nbsp;<strong>ho&agrave;n to&agrave;n kh&ocirc;ng sử dụng kh&aacute;ng sinh trong tất cả c&aacute;c kh&acirc;u</strong>&nbsp;từ chăn nu&ocirc;i b&ograve; đến qu&aacute; tr&igrave;nh thu gom, bảo quản v&agrave; sản xuất sữa.</p>\r\n\r\n<p>Thức ăn cho b&ograve; l&agrave; đồng cỏ tự nhi&ecirc;n, n&oacute;i kh&ocirc;ng với thuốc bảo vệ thực vật, kh&ocirc;ng c&oacute; sử dụng ph&acirc;n b&oacute;n, h&oacute;a chất để đảm bảo an to&agrave;n về cả nguồn đất v&agrave; nguồn nước</p>\r\n\r\n<p style=\"text-align:center\"><img alt=\"\" data-mce-src=\"//file.hstatic.net/1000317759/file/sua-withmom-han-quoc-so--5.jpg\" data-mce-style=\"display: block; margin-left: auto; margin-right: auto;\" src=\"https://file.hstatic.net/1000317759/file/sua-withmom-han-quoc-so--5.jpg\" /></p>\r\n\r\n<h3>Trang trại b&ograve; sữa ở tỉnh Gangwon H&agrave;n Quốc, đ&acirc;y l&agrave; tỉnh th&agrave;nh nằm ở Đ&ocirc;ng Bắc với diện t&iacute;ch 20.000 km&sup2; nhưng c&oacute; tới 82% diện t&iacute;ch l&agrave; đồi n&uacute;i. Do đ&oacute;&nbsp;Gangwon&nbsp;H&agrave;n Quốc&nbsp;sở hữu những khu rừng nguy&ecirc;n sinh, c&aacute;c ngọn n&uacute;i tr&ugrave;ng điệp đẹp đến m&ecirc; hồn. Kh&iacute; hậu tại đ&acirc;y ngo&agrave;i trừ m&ugrave;a đ&ocirc;ng l&agrave; nơi lạnh gi&aacute; nhất nh&igrave; tại xứ sở kim chi n&ecirc;n cấu th&agrave;nh lớp tuyết v&ocirc; c&ugrave;ng d&agrave;y th&igrave; c&aacute;c m&ugrave;a c&ograve;n lại trong năm rất m&aacute;t mẻ, kh&ocirc;ng kh&iacute; trong l&agrave;nh v&ocirc; c&ugrave;ng. Đ&acirc;y cũng l&agrave; yếu tố g&oacute;p phần quyết định sức khỏe của đ&agrave;n b&ograve; tại c&aacute;c trang trại b&ograve; sữa nơi đ&acirc;y lu&ocirc;n được duy tr&igrave; ở thể trạng tốt, ho&agrave;n to&agrave;n kh&ocirc;ng phải sử dụng đến kh&aacute;ng sinh hay hormon tăng trưởng.</h3>\r\n\r\n<ol start=\"3\">\r\n	<li><strong>Sữa c&ocirc;ng thức cao cấp With Mom chứa hệ lợi khuẩn tương đồng như sữa mẹ</strong></li>\r\n</ol>\r\n\r\n<p>Đ&oacute; l&agrave; l&yacute; do 100% b&eacute; b&uacute; With Mom v&agrave; b&uacute; mẹ đều kh&ocirc;ng gặp t&aacute;o b&oacute;n hay rối loạn ti&ecirc;u h&oacute;a.</p>\r\n\r\n<p><strong>Trong sữa c&ocirc;ng thức cao cấp With Mom chứa</strong></p>\r\n\r\n<p><strong>&nbsp;<em>Hệ Probiotics</em></strong>&nbsp;l&ecirc;n men từ thực vật gồm c&aacute;c lợi khuẩn sống như trong sữa mẹ ,bao gồm:<br />\r\n&nbsp;+ Lactobacillus ferrmentum (LC40)<br />\r\n&nbsp;+ Lactobacillus reuteri (DSM 17938)<br />\r\n<br />\r\n<strong><em>&nbsp;Hệ Prebiotics</em></strong>&nbsp; với 2 thể thực thụ GOS V&Agrave; FOS gi&uacute;p lợi khuẩn c&oacute; thể ph&aacute;t triển tốt trong đường ruột, duy tr&igrave; tỉ lệ v&agrave;ng cho hệ vi sinh đường ruột l&agrave; 85% lợi khuẩn:15% hại khuẩn</p>\r\n\r\n<p><strong><em>Hệ Acid Lactic</em></strong>&nbsp;gi&uacute;p ti&ecirc;u h&oacute;a tốt,&nbsp; chống ti&ecirc;u chảy, t&aacute;o b&oacute;n ở trẻ sơ sinh đồng thời gi&uacute;p tăng cường hệ miễn dịch v&agrave; sức đề kh&aacute;ng cho trẻ.</p>\r\n\r\n<p style=\"text-align:center\"><img alt=\"\" data-mce-src=\"//file.hstatic.net/1000317759/file/sua-withmom-han-quoc-p1.jpg\" data-mce-style=\"display: block; margin-left: auto; margin-right: auto;\" height=\"497\" src=\"https://file.hstatic.net/1000317759/file/sua-withmom-han-quoc-p1.jpg\" width=\"693\" /></p>\r\n\r\n<ol start=\"4\">\r\n	<li><strong>Sữa c&ocirc;ng thức cao cấp With Mom chứa DHA thực vật, tỉ lệ chuẩn theo WHO v&agrave; FAO khuyến kh&iacute;ch</strong></li>\r\n</ol>\r\n\r\n<p><br />\r\nLotte Foods- đơn vị sản xuất sữa c&ocirc;ng thức cao cấp With Mom&nbsp;cũng l&agrave; đơn vị ti&ecirc;n phong trong việc chiết xuất DHA từ thực vật để đảm bảo sự an to&agrave;n đến từng nguy&ecirc;n liệu. Hiện tại đ&acirc;y cũng l&agrave;&nbsp;tập đo&agrave;n duy nhất chiết xuất th&agrave;nh c&ocirc;ng DHA từ thực vật tại H&agrave;n Quốc.</p>\r\n\r\n<p>H&atilde;ng đ&atilde; được cấp bằng s&aacute;ng chế số 10-1295390.&nbsp;</p>\r\n\r\n<p>Việc tăng cường DHA giữ vai tr&ograve; hỗ trợ ph&aacute;t triển tốt nhất tr&iacute; lực, thị lực v&agrave; ho&agrave;n thiện v&otilde;ng mạc mắt, tăng IQ ng&ocirc;n ngữ, gi&uacute;p trẻ nhạy b&eacute;n v&agrave; th&ocirc;ng minh vượt trội. Trong With Mom, DHA được c&acirc;n đối v&agrave; chia tỉ lệ với AA, theo đ&oacute; DHA : AA = 1:2, đ&acirc;y l&agrave; tỉ lệ chuẩn được cả 2 tổ chức Y tế thế giới WHO v&agrave; tổ chức lương thực Thế giới FAO khuyến kh&iacute;ch.</p>\r\n\r\n<p style=\"text-align:center\"><img alt=\"\" data-mce-src=\"//file.hstatic.net/1000317759/file/sua-withmom-han-quoc-p3.jpg\" data-mce-style=\"display: block; margin-left: auto; margin-right: auto;\" height=\"372\" src=\"https://file.hstatic.net/1000317759/file/sua-withmom-han-quoc-p3.jpg\" width=\"689\" /></p>\r\n\r\n<ol start=\"5\">\r\n	<li><strong>Sữa c&ocirc;ng thức cao cấp With Mom đạt ti&ecirc;u chuẩn chất lượng loại 1 theo kiểm nghiệm của viện Pasteur</strong></li>\r\n</ol>\r\n\r\n<p>Với việc đảm bảo ti&ecirc;u chuẩn từ nguồn sữa, gi&aacute; trị dinh dưỡng cũng như t&aacute;c dụng thực tế qua kiểm nghiệm v&agrave; chứng minh l&acirc;m s&agrave;ng, Sữa c&ocirc;ng thức cao cấp With Mom đa đạt được chứng nhận ti&ecirc;u chuẩn chất lượng A1 của Viện kiểm nghiệm Pasteur. Rất hiếm d&ograve;ng sữa tr&ecirc;n thị trường hiện nay c&oacute; được sản phẩm chất lượng tuyệt hảo như With Mom.</p>\r\n\r\n<p><br />\r\nĐến được tay người ti&ecirc;u d&ugrave;ng,With Mom phải trải qua rất nhiều thử th&aacute;ch, lớn nhất v&agrave; khắt khe nhất phải kể đến việc đảm bảo quy chuẩn về mặt vệ sinh an to&agrave;n thực phẩm v&agrave; gi&aacute; trị thực mang lại cho những đứa trẻ sử dụng With Mom trong suốt chặng đường d&agrave;i. Thừa hưởng những trang thiết bị, nguồn nh&acirc;n lực v&agrave; c&ocirc;ng tr&igrave;nh kĩ thuật c&ugrave;ng đội ngũ c&aacute;c chuy&ecirc;n gia h&agrave;ng đầu Thế giới về sản xuất sữa v&agrave; thực phẩm d&agrave;nh ri&ecirc;ng cho trẻ từ&nbsp;<strong>Lotte Foods</strong>, With Mom thực sự trở th&agrave;nh sản phẩm dinh dưỡng tuyệt hảo s&aacute;nh ngang c&ugrave;ng sữa mẹ.</p>\r\n\r\n<ol start=\"6\">\r\n	<li><strong>Tỉ lệ canxi:phospho đạt chuẩn giới hạn khuyến c&aacute;o của WHO/FAO</strong></li>\r\n</ol>\r\n\r\n<p>Thực tế để xem x&eacute;t 1 loại sữa n&agrave;o đ&oacute; gi&uacute;p ph&aacute;t triển chiều cao hay kh&ocirc;ng th&igrave; cần quan t&acirc;m tới tỷ lệ Canxi: Phospho (&nbsp; Ca/P) &nbsp;trong sữa đ&oacute; l&agrave; bao nhi&ecirc;u, v&igrave; tỷ lệ n&agrave;y g&oacute;p phần tạo n&ecirc;n cấu tr&uacute;c xương. Canxi nếu c&oacute; hấp thụ m&agrave; kh&ocirc;ng theo tỷ lệ tốt th&igrave; cũng kh&ocirc;ng c&oacute; t&aacute;c dụng nhiều, nhất l&agrave; giai đoạn 0 - 12 th&aacute;ng tuổi.</p>\r\n\r\n<p>Theo ti&ecirc;u chuẩn của WHO/FAO th&igrave;&nbsp;<strong>tỷ lệ Ca/P tốt cho trẻ l&agrave; từ 1.2 - 2</strong>. Trong sữa c&ocirc;ng thức cao cấp With Mom H&agrave;n Quốc tỉ lệ n&agrave;y theo từng số như sau:</p>\r\n\r\n<p><strong>With Mom số 1: Ca/P = 81/47 =</strong>&nbsp;<strong>1.723</strong></p>\r\n\r\n<p><strong>With Mom số 2: : Ca/P = 51/30 =</strong>&nbsp;<strong>1.7</strong></p>\r\n\r\n<p><strong>With Mom số 3: Ca/P = 100/58 = 1.724</strong></p>\r\n\r\n<p style=\"text-align:center\"><img alt=\"\" data-mce-src=\"//file.hstatic.net/1000317759/file/sua-withmom-han-quoc-p5.jpg\" data-mce-style=\"display: block; margin-left: auto; margin-right: auto;\" height=\"376\" src=\"https://file.hstatic.net/1000317759/file/sua-withmom-han-quoc-p5.jpg\" width=\"663\" /></p>\r\n\r\n<p>C&oacute; thể thấy rằng, tất cả c&aacute;c số của With Mom đều đạt được tỉ lệ chuẩn theo khuyến c&aacute;o. Đ&acirc;y l&agrave; yếu tố ti&ecirc;n quyết gi&uacute;p trẻ c&oacute; sự tăng trưởng khỏe mạnh v&agrave; vượt trội về thể chất sau n&agrave;y. Nh&igrave;n v&agrave;o c&ocirc;ng thức sữa H&agrave;n Quốc c&oacute; thể t&igrave;m được l&yacute; do giải th&iacute;ch v&igrave; sao người H&agrave;n Quốc đạt chiều cao tốt nhất ch&acirc;u &Aacute; v&agrave; s&aacute;nh ngang với ti&ecirc;u chuẩn chiều cao Thế giới.</p>\r\n\r\n<p style=\"text-align:center\"><img alt=\"\" data-mce-src=\"//file.hstatic.net/1000317759/file/chieu-cao.jpg\" data-mce-style=\"display: block; margin-left: auto; margin-right: auto;\" src=\"https://file.hstatic.net/1000317759/file/chieu-cao.jpg\" /></p>\r\n\r\n<ol start=\"7\">\r\n	<li><strong>Sữa c&ocirc;ng thức cao cấp With Mom sản xuất tr&ecirc;n c&ocirc;ng nghệ d&acirc;y chuyền hiện đại, d&agrave;nh ri&ecirc;ng cho trẻ em</strong></li>\r\n</ol>\r\n\r\n<p>Với quy tr&igrave;nh sản xuất d&agrave;nh ri&ecirc;ng cho trẻ em (One line System), được&nbsp;<strong><u>sấy kh&ocirc; bằng phương ph&aacute;p MSD c&oacute; khả năng giảm thiểu sự thất tho&aacute;t dinh dưỡng</u></strong><strong>&nbsp;</strong>trong qu&aacute; tr&igrave;nh chế biến, quá trình sản xu&acirc;́t sữa bao g&ocirc;̀m các c&ocirc;ng đoạn gia nhi&ecirc;̣t xử lý ti&ecirc;̣t trùng, c&ocirc; đặc, s&acirc;́y kh&ocirc;, v.v.. Trong quá trình này, n&ecirc;́u c&ocirc;ng đoạn gia nhi&ecirc;̣t quá mức có th&ecirc;̉ làm hỏng các thành ph&acirc;̀n dinh dưỡng.</p>\r\n\r\n<p>MSD là phương pháp xử lý ti&ecirc;n ti&ecirc;́n được áp dụng khi&ecirc;́n khả năng bi&ecirc;́n ch&acirc;́t do nhi&ecirc;̣t của thành ph&acirc;̀n dinh dưỡng trong quá trình sản xu&acirc;́t được hạn ch&ecirc;́ đ&ecirc;́n mức th&acirc;́p nh&acirc;́t. Hạt sưa c&oacute; độ tơi xốp hơn, khả năng h&ograve;a tan tốt hơn ( đặc trưng của sữa H&agrave;n Quốc) v&agrave; việc bắn c&aacute;c hạt vi chất v&agrave;o sữa sẽ hiệu quả hơn.</p>\r\n\r\n<p style=\"text-align:center\"><img alt=\"\" data-mce-src=\"//file.hstatic.net/1000317759/file/sua-withmom-han-quoc-p7.jpg\" src=\"https://file.hstatic.net/1000317759/file/sua-withmom-han-quoc-p7.jpg\" /></p>\r\n\r\n<p><strong>Hướng dẫn sử dụng sữa c&ocirc;ng thức cao cấp With Mom H&agrave;n Quốc</strong></p>\r\n\r\n<p><u>C&ocirc;ng thức pha sữa:</u></p>\r\n\r\n<p>1 muỗng sữa bột gạt ngang pha với 40ml nước</p>\r\n\r\n<p>Nhiệt độ nước pha sữa l&agrave; nước đun s&ocirc;i để nguội xuống 40-50 độ C</p>\r\n\r\n<p><u>C&aacute;ch pha tham khảo</u><br />\r\n<br />\r\nĐong 2/3 lượng nước cần đong v&agrave;o b&igrave;nh sữa hoặc cốc pha sữa c&oacute; vạch chia định mức, d&ugrave;ng th&igrave;a trong hộp sữa đong số th&igrave;a bột tương ứng, h&ograve;a tan ho&agrave;n to&agrave;n, sau đ&oacute; cho th&ecirc;m lượng nước vừa đủ đến vạch cần pha.</p>\r\n\r\n<p data-mce-style=\"text-align: center;\">&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><em>&nbsp;</em></p>\r\n\r\n<p><strong>Th&ocirc;ng tin chi tiết sản phẩm sữa c&ocirc;ng thức cao cấp With Mom</strong></p>\r\n\r\n<p>- Sữa c&ocirc;ng thức d&agrave;nh cho trẻ em</p>\r\n\r\n<p>- Quy c&aacute;ch đ&oacute;ng : lon 750gr</p>\r\n\r\n<p>- Nh&agrave; sản xuất:&nbsp; Lottefoods</p>\r\n\r\n<p>- Xuất xứ: H&agrave;n Quốc</p>\r\n\r\n<p data-mce-style=\"text-align: center;\">&nbsp;</p>\r\n', '2019-09-04 20:40:36', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(438707, 3863, 1, 0, 101137, 10439, NULL, '', '2019-09-04 20:40:36', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(438708, 3863, 1, 0, 101137, 10440, NULL, '', '2019-09-04 20:40:36', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(438709, 3863, 1, 0, 101138, 10438, NULL, '<p>Hồng dẻo H&agrave;n Quốc sấy dẻo l&agrave;m qu&agrave; biếu trung thu sang trọng</p>\r\n\r\n<p>Xuất xứ: H&agrave;n Quốc</p>\r\n\r\n<p>Bảo quản, để tủ đ&ocirc;ng đ&aacute;, khi bỏ ra vẫn c&ograve;n dai sần sật, ngọt dẻo</p>\r\n\r\n<p>Một set gồm 24 tr&aacute;i, net 1.15kg</p>\r\n\r\n<p>Hộp c&oacute; đầy đủ t&uacute;i v&agrave; hộp</p>\r\n\r\n<p style=\"text-align:center\"><img alt=\"\" src=\"https://oanhtraicay.com/kcfinder/upload/images/21768282_731927773670559_6895295111327034352_n.jpg\" /></p>\r\n\r\n<p><em>Hộp gồm 24 tr&aacute;i&nbsp;</em></p>\r\n\r\n<p style=\"text-align:center\"><img alt=\"\" src=\"https://oanhtraicay.com/kcfinder/upload/images/21768369_731927787003891_316267296703796046_n.jpg\" /></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><em>o</em></p>\r\n\r\n<p style=\"text-align:center\"><img alt=\"\" src=\"https://oanhtraicay.com/kcfinder/upload/images/21766445_731935597003110_4221277550167200268_n%281%29.jpg\" /></p>\r\n\r\n<p><i>Hồng dẻo l&ecirc;n phấn c&oacute; độ ngọt cao hơn so với chưa l&ecirc;n phấn</i></p>\r\n', '2019-09-04 20:42:40', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(438710, 3863, 1, 0, 101138, 10439, NULL, '', '2019-09-04 20:42:40', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(438711, 3863, 1, 0, 101138, 10440, NULL, '', '2019-09-04 20:42:40', '0000-00-00 00:00:00', 1, 'Y', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `product_detail`
--

CREATE TABLE `product_detail` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` smallint(6) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sort` int(11) NOT NULL,
  `enable_delete` char(1) DEFAULT 'N',
  `active` char(1) DEFAULT 'Y',
  `deleted` char(1) DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_detail`
--

INSERT INTO `product_detail` (`id`, `subdomain_id`, `language_id`, `depend_id`, `name`, `slug`, `created_at`, `modified_in`, `sort`, `enable_delete`, `active`, `deleted`) VALUES
(10438, 3863, 1, 0, 'Chi tiết sản phẩm', 'chi-tiet-san-pham', '2018-09-29 00:08:33', '0000-00-00 00:00:00', 1, 'N', 'Y', 'N'),
(10439, 3863, 1, 0, 'Cấu hình chi tiết', 'cau-hinh-chi-tiet', '2018-09-29 00:08:33', '2019-07-20 06:36:54', 2, 'N', 'Y', 'N'),
(10440, 3863, 1, 0, 'Bình Luận', 'binh-luan', '2018-09-29 00:08:33', '0000-00-00 00:00:00', 9, 'N', 'Y', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `product_element`
--

CREATE TABLE `product_element` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` smallint(6) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `name` varchar(45) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sort` int(11) NOT NULL,
  `search` char(1) DEFAULT 'Y',
  `show_price` char(1) DEFAULT 'N',
  `combo` char(1) DEFAULT 'N',
  `is_color` char(1) DEFAULT 'N',
  `is_product_photo` char(1) DEFAULT 'N',
  `active` char(1) NOT NULL,
  `deleted` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_element_detail`
--

CREATE TABLE `product_element_detail` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` smallint(6) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `product_element_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL,
  `deleted` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_photo`
--

CREATE TABLE `product_photo` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` smallint(6) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `product_id` int(11) NOT NULL,
  `product_element_detail_id` int(11) DEFAULT NULL,
  `folder` varchar(255) DEFAULT NULL,
  `photo` varchar(255) NOT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL,
  `deleted` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `active` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='defined permision';

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `name`, `active`, `sort`) VALUES
(1, 'Administrators', 'Y', 1),
(5, 'Quản trị tin tức', 'Y', 1),
(6, 'Quản trị video', 'Y', 3),
(7, 'Quản trị tin tức và video', 'Y', 1);

-- --------------------------------------------------------

--
-- Table structure for table `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `usersId` int(10) UNSIGNED NOT NULL,
  `token` char(32) NOT NULL,
  `userAgent` varchar(120) NOT NULL,
  `createdAt` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reset_passwords`
--

CREATE TABLE `reset_passwords` (
  `id` int(10) UNSIGNED NOT NULL,
  `usersId` int(10) UNSIGNED NOT NULL,
  `code` varchar(48) NOT NULL,
  `createdAt` int(10) UNSIGNED NOT NULL,
  `modifiedAt` int(10) UNSIGNED DEFAULT NULL,
  `reset` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `layout_id` int(11) NOT NULL,
  `layout_subdomain_id` int(11) DEFAULT 0,
  `banner_html_id` int(11) DEFAULT 0,
  `row_id` varchar(75) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slogan` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banner_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banner_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banner_3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banner_4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_order` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hotline` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `business_license` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `copyright` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `google` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `youtube` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `yahoo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zalo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_meta` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_menu_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enable_image_menu_2` tinyint(1) NOT NULL DEFAULT 0,
  `title` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `article_home` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `footer` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `enable_contact_default` tinyint(1) DEFAULT 1,
  `enable_footer_default` tinyint(1) DEFAULT 1,
  `enable_form_contact` tinyint(1) DEFAULT 1,
  `enable_video_article_home` tinyint(1) DEFAULT 0,
  `enable_image_article_home` tinyint(1) DEFAULT 0,
  `enable_form_reg_article_home` tinyint(1) DEFAULT 0,
  `enable_search_advance_article_home` tinyint(1) DEFAULT 0,
  `enable_map` tinyint(1) DEFAULT 1,
  `map_code` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `youtube_code` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_article_home` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text_logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `enable_logo_text` tinyint(1) DEFAULT 0,
  `favicon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bgr_ycbg` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `analytics` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `note_payment_method_2` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `head_content` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `body_content` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `order_admin_note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `subdomain_id`, `language_id`, `depend_id`, `layout_id`, `layout_subdomain_id`, `banner_html_id`, `row_id`, `name`, `website`, `slogan`, `banner_1`, `banner_2`, `banner_3`, `banner_4`, `address`, `email`, `email_order`, `phone`, `hotline`, `fax`, `tax_code`, `business_license`, `copyright`, `facebook`, `google`, `youtube`, `yahoo`, `zalo`, `twitter`, `image_meta`, `image_menu_2`, `enable_image_menu_2`, `title`, `keywords`, `description`, `article_home`, `contact`, `footer`, `enable_contact_default`, `enable_footer_default`, `enable_form_contact`, `enable_video_article_home`, `enable_image_article_home`, `enable_form_reg_article_home`, `enable_search_advance_article_home`, `enable_map`, `map_code`, `youtube_code`, `image_article_home`, `logo`, `text_logo`, `enable_logo_text`, `favicon`, `bgr_ycbg`, `analytics`, `note_payment_method_2`, `head_content`, `body_content`, `order_admin_note`, `created_at`, `modified_in`) VALUES
(3870, 3863, 1, 0, 2, 0, 0, '3870', 'HÀNG XÁCH TAY KOREA CHÍNH HÃNG', '', 'chuyên cung cấp hàng xách tay nhật bản với giá thành phải chăng nhất', NULL, NULL, NULL, NULL, 'Khóm 3 Thị Trấn Trà Cú Huyện Trà Cú, Trà Vinh', 'topbacninh@gmail.com', NULL, '', '0975 516 751 - 0938 998 866', '0946 105 105 - 0919 423 579', '', '', 'Copyright © 2018. All Rights Reserved Thanhdatweb.info', '', '', '', NULL, NULL, '', '', '', 0, '', '', '', '', '<p><strong>C&ocirc;ng ty TNHH&nbsp;Lehomes (TOPBACNINH.COM)</strong></p>\r\n\r\n<p>Hotline: 091 628 3628 ; E-mail:&nbsp;<a href=\"mailto:topbacninh@gmail.com\">topbacninh@gmail.com</a>&nbsp;;&nbsp;<a href=\"http://www.topbacninh.com/\">www.topbacninh.com</a></p>\r\n', '<p>Copyright &copy; 2018. All Rights Reserved &gt; www.topbacninh.com&nbsp;(Designed by Mr Thanh, VuTien)</p>\r\n', 1, 1, 1, 1, 1, 1, 1, 1, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d7439.983081591437!2d106.0798013!3d21.192495!3m2!1i1024!2i768!4f13.1!5e0!3m2!1svi!2s!4v1563705046285!5m2!1svi!2s\" width=\"600\" height=\"450\" frameborder=\"0\" style=\"border:0\" allowfullscreen></iframe>', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/uKib1W19HC4\" frameborder=\"0\" allow=\"autoplay; encrypted-media\" allowfullscreen></iframe>', 'o_Hxck3wax.jpg', '222222_Wl2ZUcj1.png', '[\"TOPBACNINH\",\"Mỗi khách hàng là một người bạn !\"]', 0, '', '', NULL, '<p>Ng&acirc;n h&agrave;ng Vietcombank - Chi nh&aacute;nh Bắc Ninh</p>\r\n\r\n<p>T&agrave;i khoản: Vũ Tiến Th&agrave;nh</p>\r\n\r\n<p>Số TK: (li&ecirc;n hệ)</p>\r\n', '', '', '', '2018-09-29 00:08:30', '2019-09-04 23:23:41');

-- --------------------------------------------------------

--
-- Table structure for table `subdomain`
--

CREATE TABLE `subdomain` (
  `id` int(11) UNSIGNED NOT NULL,
  `create_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `folder_sort` int(11) DEFAULT NULL,
  `folder` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `share_price` int(10) UNSIGNED DEFAULT 50000,
  `copyright_name` varchar(255) DEFAULT NULL,
  `copyright_link` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `active_date` datetime NOT NULL,
  `expired_date` datetime NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'N',
  `hot` char(1) NOT NULL DEFAULT 'N',
  `suspended` char(1) NOT NULL DEFAULT 'N',
  `closed` char(1) NOT NULL DEFAULT 'N',
  `new` char(1) NOT NULL DEFAULT 'N',
  `special` char(1) DEFAULT 'N',
  `add_to_server` char(1) DEFAULT 'N',
  `not_thumb` char(1) DEFAULT 'N',
  `display` char(1) DEFAULT 'Y',
  `duplicate` char(1) DEFAULT 'N',
  `copyright` char(1) DEFAULT 'N',
  `other_interface` char(1) DEFAULT 'N',
  `is_ssl` char(1) DEFAULT 'N',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subdomain`
--

INSERT INTO `subdomain` (`id`, `create_id`, `name`, `folder_sort`, `folder`, `note`, `share_price`, `copyright_name`, `copyright_link`, `sort`, `active_date`, `expired_date`, `active`, `hot`, `suspended`, `closed`, `new`, `special`, `add_to_server`, `not_thumb`, `display`, `duplicate`, `copyright`, `other_interface`, `is_ssl`, `deleted`, `create_at`, `modified_in`) VALUES
(3863, 81, 'hangxachtaykorea', 3519, '3519', NULL, 50000, NULL, NULL, 1, '2019-09-19 16:11:06', '2020-09-13 16:11:06', 'Y', 'N', 'N', 'N', 'Y', 'N', 'N', 'N', 'Y', 'Y', 'N', 'N', 'N', 'N', '2019-09-04 19:36:07', '2019-09-19 02:11:06');

-- --------------------------------------------------------

--
-- Table structure for table `subdomain_rating`
--

CREATE TABLE `subdomain_rating` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `rate` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `success_logins`
--

CREATE TABLE `success_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `usersId` int(10) UNSIGNED NOT NULL,
  `ipAddress` char(15) NOT NULL,
  `userAgent` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `support`
--

CREATE TABLE `support` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `skype` varchar(255) DEFAULT NULL,
  `messenger` varchar(255) DEFAULT NULL,
  `zalo` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL DEFAULT 'Y',
  `deleted` char(1) NOT NULL DEFAULT 'N',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `active` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `title`, `keywords`, `description`, `sort`, `active`, `create_at`, `modified_in`) VALUES
(1, 'Liverpool', 'liverpool', 'Liverpool', 'Liverpool', 'Liverpool', 1, 'Y', '2016-05-21 16:38:54', '2016-05-21 16:44:42'),
(2, 'MU', 'mu', 'MU', 'MU', 'MU', 1, 'Y', '2016-05-21 16:43:30', '2016-05-21 16:44:33'),
(3, 'Arsenal', 'arsenal', 'Arsenal', 'Arsenal', 'Arsenal', 1, 'Y', '2016-05-21 16:45:02', '0000-00-00 00:00:00'),
(4, 'V-league', 'vleague', 'V-league', 'V-league', 'V-league', 1, 'Y', '2016-05-21 16:45:10', '0000-00-00 00:00:00'),
(5, 'Man City', 'man-city', '', '', '', 1, 'Y', '2016-05-31 18:48:32', '0000-00-00 00:00:00'),
(6, 'Chelsea', 'chelsea', 'Chelsea', 'Chelsea', 'Chelsea', 1, 'Y', '2016-05-31 18:49:25', '0000-00-00 00:00:00'),
(7, 'Barcelona', 'barcelona', 'Barcelona', 'Barcelona', 'Barcelona', 1, 'Y', '2016-05-31 18:49:59', '0000-00-00 00:00:00'),
(8, 'Real Madrid', 'real-madrid', 'Real Madrid', 'Real Madrid', 'Real Madrid', 1, 'Y', '2016-05-31 18:50:13', '0000-00-00 00:00:00'),
(9, 'Atletico Madrid', 'atletico-madrid', 'Atletico Madrid', 'Atletico Madrid', 'Atletico Madrid', 1, 'Y', '2016-05-31 18:50:30', '0000-00-00 00:00:00'),
(10, 'LALIGA', 'laliga', 'LALIGA', 'LALIGA', 'LALIGA', 1, 'Y', '2016-05-31 18:51:16', '0000-00-00 00:00:00'),
(11, 'BUNDESLIGA', 'bundesliga', 'BUNDESLIGA', 'BUNDESLIGA', 'BUNDESLIGA', 1, 'Y', '2016-05-31 18:51:50', '0000-00-00 00:00:00'),
(12, 'B.Dortmund', 'bdortmund', 'B.Dortmund', 'B.Dortmund', 'B.Dortmund', 1, 'Y', '2016-05-31 18:52:06', '0000-00-00 00:00:00'),
(13, 'SERIA A', 'seria-a', 'SERIA A', 'SERIA A', 'SERIA A', 1, 'Y', '2016-05-31 18:52:41', '0000-00-00 00:00:00'),
(14, 'Juventus', 'juventus', 'Juventus', 'Juventus', 'Juventus', 1, 'Y', '2016-05-31 18:52:58', '0000-00-00 00:00:00'),
(15, 'Inter Milan', 'inter-milan', 'Inter Milan', 'Inter Milan', 'Inter Milan', 1, 'Y', '2016-05-31 18:53:14', '0000-00-00 00:00:00'),
(16, 'Copa America', 'copa-america', 'Giải đấu copa america tranh hung chau my  ', 'Giải đấu copa america tranh hung chau my  ', 'Giải đấu copa america tranh hung chau my  ', 1, 'Y', '2016-06-11 05:50:13', '0000-00-00 00:00:00'),
(17, 'Euro', 'euro', 'Euro 2016 .Pháp Đăng cai  giải bóng đá châu âu', 'Euro 2016 .Pháp Đăng cai  giải bóng đá châu âu', 'Pháp Đăng cai  giải bóng đá châu âu Euro 2016 .Nơi hội tụ nhiều ngôi sao , cuôc đấu trí  tranh tai cua cac HLV .', 1, 'Y', '2016-06-11 10:34:12', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tmp_banner_banner_type`
--

CREATE TABLE `tmp_banner_banner_type` (
  `subdomain_id` int(11) NOT NULL,
  `banner_id` int(11) NOT NULL,
  `banner_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tmp_banner_banner_type`
--

INSERT INTO `tmp_banner_banner_type` (`subdomain_id`, `banner_id`, `banner_type_id`) VALUES
(3863, 49126, 11070),
(3863, 49128, 11070),
(3863, 49127, 11070),
(3863, 49125, 11070),
(3863, 49129, 11070),
(3863, 49124, 11070),
(3863, 49123, 11070),
(3863, 49115, 11071),
(3863, 49137, 11069),
(3863, 49133, 11069);

-- --------------------------------------------------------

--
-- Table structure for table `tmp_landing_module`
--

CREATE TABLE `tmp_landing_module` (
  `id` int(11) NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `landing_page_id` int(11) NOT NULL,
  `module_item_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 1,
  `active` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_layout_module`
--

CREATE TABLE `tmp_layout_module` (
  `id` int(11) NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL,
  `module_item_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `css` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT 1,
  `active` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `active_inner` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tmp_layout_module`
--

INSERT INTO `tmp_layout_module` (`id`, `subdomain_id`, `layout_id`, `module_item_id`, `position_id`, `css`, `sort`, `active`, `active_inner`) VALUES
(448655, 3863, 2, 281760, 5, NULL, 1, 'Y', 'N'),
(448656, 3863, 2, 281761, 5, NULL, 2, 'Y', 'N'),
(448657, 3863, 2, 281762, 5, NULL, 1, 'Y', 'N'),
(448658, 3863, 2, 281763, 5, NULL, 2, 'Y', 'N'),
(448659, 3863, 2, 281764, 5, NULL, 3, 'Y', 'N'),
(448660, 3863, 2, 281765, 8, NULL, 17, 'Y', 'N'),
(448661, 3863, 2, 281766, 8, NULL, 13, 'Y', 'N'),
(448662, 3863, 2, 281767, 7, NULL, 1, 'Y', 'N'),
(448663, 3863, 2, 281767, 8, NULL, 14, 'Y', 'N'),
(448664, 3863, 2, 281768, 8, NULL, 16, 'Y', 'N'),
(448665, 3863, 2, 281778, 6, NULL, 1, 'Y', 'N'),
(448666, 3863, 2, 281779, 6, NULL, 2, 'Y', 'N'),
(448667, 3863, 2, 281782, 6, NULL, 8, 'Y', 'N'),
(448668, 3863, 2, 281785, 9, NULL, 1, 'Y', 'N'),
(448669, 3863, 2, 281787, 7, NULL, 1, 'Y', 'N'),
(448670, 3863, 2, 281792, 9, NULL, 2, 'Y', 'N'),
(448671, 3863, 2, 281793, 6, NULL, 5, 'Y', 'N'),
(448672, 3863, 2, 281796, 6, NULL, 3, 'Y', 'N'),
(448673, 3863, 2, 281799, 6, NULL, 4, 'Y', 'N'),
(448674, 3863, 2, 281802, 6, NULL, 6, 'Y', 'N'),
(448675, 3863, 2, 281806, 5, NULL, 3, 'Y', 'N'),
(448676, 3863, 2, 281812, 8, NULL, 1, 'Y', 'N'),
(448677, 3863, 2, 281818, 9, NULL, 2, 'Y', 'N'),
(448678, 3863, 2, 281819, 9, NULL, 1, 'Y', 'N'),
(448679, 3863, 2, 281820, 9, NULL, 2, 'Y', 'N'),
(448680, 3863, 2, 281821, 9, NULL, 4, 'Y', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `tmp_module_group_layout`
--

CREATE TABLE `tmp_module_group_layout` (
  `module_group_id` int(11) NOT NULL,
  `layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tmp_module_group_layout`
--

INSERT INTO `tmp_module_group_layout` (`module_group_id`, `layout_id`) VALUES
(25, 1),
(25, 2),
(25, 3),
(25, 4),
(23, 1),
(23, 2),
(23, 3),
(23, 4),
(18, 1),
(18, 2),
(18, 3),
(18, 4),
(20, 1),
(20, 2),
(20, 3),
(20, 4),
(19, 1),
(19, 2),
(19, 3),
(19, 4),
(27, 1),
(27, 2),
(27, 3),
(27, 4),
(9, 1),
(9, 2),
(9, 3),
(9, 4),
(21, 1),
(21, 2),
(21, 3),
(21, 4),
(8, 1),
(8, 2),
(8, 3),
(8, 4),
(10, 1),
(10, 2),
(10, 3),
(10, 4),
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(22, 1),
(22, 2),
(22, 3),
(22, 4),
(11, 1),
(11, 2),
(11, 3),
(11, 4),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(24, 1),
(24, 2),
(24, 3),
(24, 4),
(29, 1),
(29, 2),
(29, 3),
(29, 4),
(30, 1),
(30, 2),
(30, 3),
(30, 4),
(31, 1),
(31, 2),
(31, 3),
(31, 4),
(32, 1),
(32, 2),
(32, 3),
(32, 4),
(33, 1),
(33, 2),
(33, 3),
(33, 4),
(34, 1),
(34, 2),
(34, 3),
(34, 4),
(35, 1),
(35, 2),
(35, 3),
(35, 4),
(36, 1),
(36, 2),
(36, 3),
(36, 4),
(37, 1),
(37, 2),
(37, 3),
(37, 4),
(38, 1),
(38, 2),
(38, 3),
(38, 4),
(39, 1),
(39, 2),
(39, 3),
(39, 4),
(40, 1),
(40, 2),
(40, 3),
(40, 4),
(41, 1),
(41, 2),
(41, 3),
(41, 4),
(42, 1),
(42, 2),
(42, 3),
(42, 4),
(43, 1),
(43, 2),
(43, 3),
(43, 4),
(44, 1),
(44, 2),
(44, 3),
(44, 4),
(45, 1),
(45, 2),
(45, 3),
(45, 4),
(46, 1),
(46, 2),
(46, 3),
(46, 4),
(47, 1),
(47, 2),
(47, 3),
(47, 4),
(48, 1),
(48, 2),
(48, 3),
(48, 4),
(49, 1),
(49, 2),
(49, 3),
(49, 4),
(50, 1),
(50, 2),
(50, 3),
(50, 4),
(51, 1),
(51, 2),
(51, 3),
(51, 4),
(52, 1),
(52, 2),
(52, 3),
(52, 4),
(53, 1),
(53, 2),
(53, 3),
(53, 4),
(54, 1),
(54, 2),
(54, 3),
(54, 4),
(55, 1),
(55, 2),
(55, 3),
(55, 4),
(56, 1),
(56, 2),
(56, 3),
(56, 4),
(57, 1),
(57, 2),
(57, 3),
(57, 4),
(58, 1),
(58, 2),
(58, 3),
(58, 4),
(59, 1),
(59, 2),
(59, 3),
(59, 4),
(60, 1),
(60, 2),
(60, 3),
(60, 4),
(61, 1),
(61, 2),
(61, 3),
(61, 4),
(62, 1),
(62, 2),
(62, 3),
(62, 4),
(63, 1),
(63, 2),
(63, 3),
(63, 4),
(64, 1),
(64, 2),
(64, 3),
(64, 4),
(65, 1),
(65, 2),
(65, 3),
(65, 4),
(66, 1),
(66, 2),
(66, 3),
(66, 4),
(67, 1),
(67, 2),
(67, 3),
(67, 4),
(68, 1),
(68, 2),
(68, 3),
(68, 4),
(69, 1),
(69, 2),
(69, 3),
(69, 4),
(70, 1),
(70, 2),
(70, 3),
(70, 4),
(71, 1),
(71, 2),
(71, 3),
(71, 4),
(72, 1),
(72, 2),
(72, 3),
(72, 4),
(73, 1),
(73, 2),
(73, 3),
(73, 4),
(74, 1),
(74, 2),
(74, 3),
(74, 4),
(75, 1),
(75, 2),
(75, 3),
(75, 4),
(76, 1),
(76, 2),
(76, 3),
(76, 4),
(77, 1),
(77, 2),
(77, 3),
(77, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tmp_news_news_category`
--

CREATE TABLE `tmp_news_news_category` (
  `subdomain_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `news_category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_news_news_menu`
--

CREATE TABLE `tmp_news_news_menu` (
  `subdomain_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `news_menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tmp_news_news_menu`
--

INSERT INTO `tmp_news_news_menu` (`subdomain_id`, `news_id`, `news_menu_id`) VALUES
(3863, 37075, 37481),
(3863, 37075, 37474),
(3863, 37081, 37471),
(3863, 37080, 37473),
(3863, 37080, 37471),
(3863, 37077, 37481),
(3863, 37077, 37471);

-- --------------------------------------------------------

--
-- Table structure for table `tmp_news_tags`
--

CREATE TABLE `tmp_news_tags` (
  `news_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_position_module_group`
--

CREATE TABLE `tmp_position_module_group` (
  `position_id` int(11) NOT NULL,
  `module_group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tmp_position_module_group`
--

INSERT INTO `tmp_position_module_group` (`position_id`, `module_group_id`) VALUES
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(5, 5),
(5, 6),
(6, 7),
(8, 8),
(9, 8),
(8, 9),
(9, 10),
(9, 11),
(9, 12),
(9, 13),
(9, 14),
(9, 15),
(9, 16),
(9, 17),
(6, 19),
(7, 19),
(6, 21),
(7, 21),
(6, 20),
(7, 20),
(6, 22),
(7, 22),
(8, 23),
(9, 24),
(5, 25),
(7, 26),
(8, 27),
(6, 18),
(7, 18);

-- --------------------------------------------------------

--
-- Table structure for table `tmp_position_module_item`
--

CREATE TABLE `tmp_position_module_item` (
  `subdomain_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL,
  `module_item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_product_category`
--

CREATE TABLE `tmp_product_category` (
  `id` int(11) NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
PARTITION BY RANGE COLUMNS(`subdomain_id`)
(
PARTITION p0 VALUES LESS THAN (500) ENGINE=InnoDB,
PARTITION p1 VALUES LESS THAN (1000) ENGINE=InnoDB,
PARTITION p2 VALUES LESS THAN (1500) ENGINE=InnoDB,
PARTITION p3 VALUES LESS THAN (2000) ENGINE=InnoDB,
PARTITION p4 VALUES LESS THAN (2500) ENGINE=InnoDB,
PARTITION p5 VALUES LESS THAN (3000) ENGINE=InnoDB,
PARTITION p6 VALUES LESS THAN (3500) ENGINE=InnoDB,
PARTITION p7 VALUES LESS THAN (4000) ENGINE=InnoDB,
PARTITION p8 VALUES LESS THAN (MAXVALUE) ENGINE=InnoDB
);

--
-- Dumping data for table `tmp_product_category`
--

INSERT INTO `tmp_product_category` (`id`, `subdomain_id`, `product_id`, `category_id`) VALUES
(317267, 3863, 101133, 63271),
(317268, 3863, 101133, 63268),
(317269, 3863, 101133, 63270),
(317270, 3863, 101133, 63269),
(317271, 3863, 101133, 63267),
(317272, 3863, 101133, 63266),
(317273, 3863, 101133, 63263),
(317274, 3863, 101133, 63265),
(317275, 3863, 101133, 63264),
(317278, 3863, 101135, 63271),
(317279, 3863, 101135, 63268),
(317280, 3863, 101135, 63270),
(317281, 3863, 101135, 63269),
(317282, 3863, 101135, 63267),
(317283, 3863, 101135, 63266),
(317284, 3863, 101135, 63263),
(317285, 3863, 101135, 63265),
(317286, 3863, 101135, 63264),
(317287, 3863, 101136, 63271),
(317288, 3863, 101136, 63268),
(317289, 3863, 101136, 63270),
(317290, 3863, 101136, 63269),
(317291, 3863, 101136, 63267),
(317292, 3863, 101136, 63266),
(317293, 3863, 101136, 63263),
(317294, 3863, 101136, 63265),
(317295, 3863, 101136, 63264),
(317296, 3863, 101137, 63271),
(317297, 3863, 101137, 63268),
(317298, 3863, 101137, 63270),
(317299, 3863, 101137, 63269),
(317300, 3863, 101137, 63267),
(317301, 3863, 101137, 63266),
(317302, 3863, 101137, 63263),
(317303, 3863, 101137, 63265),
(317304, 3863, 101137, 63264),
(317305, 3863, 101138, 63271),
(317306, 3863, 101138, 63268),
(317307, 3863, 101138, 63270),
(317308, 3863, 101138, 63269),
(317309, 3863, 101138, 63267),
(317310, 3863, 101138, 63266),
(317311, 3863, 101138, 63263),
(317312, 3863, 101138, 63265),
(317313, 3863, 101138, 63264);

-- --------------------------------------------------------

--
-- Table structure for table `tmp_product_form_item`
--

CREATE TABLE `tmp_product_form_item` (
  `subdomain_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `form_item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_product_product_element_detail`
--

CREATE TABLE `tmp_product_product_element_detail` (
  `id` int(11) NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_element_detail_id` int(11) DEFAULT NULL,
  `combo_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cost` bigint(20) DEFAULT NULL,
  `price` bigint(20) DEFAULT NULL,
  `cost_usd` int(11) DEFAULT NULL,
  `price_usd` int(11) DEFAULT NULL,
  `selected` tinyint(1) NOT NULL DEFAULT 0,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_subdomain_language`
--

CREATE TABLE `tmp_subdomain_language` (
  `subdomain_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_subdomain_user`
--

CREATE TABLE `tmp_subdomain_user` (
  `subdomain_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_type_module`
--

CREATE TABLE `tmp_type_module` (
  `id` int(11) NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `module_item_id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 1,
  `active` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `email` varchar(40) DEFAULT NULL,
  `password` char(60) NOT NULL,
  `mustChangePassword` char(1) DEFAULT NULL,
  `profilesId` int(10) UNSIGNED DEFAULT NULL,
  `role` int(11) NOT NULL DEFAULT 1,
  `fullName` varchar(50) DEFAULT NULL,
  `sex` char(1) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `creditCard` varchar(25) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `balance` int(11) DEFAULT 0,
  `address` varchar(100) DEFAULT NULL,
  `cityRegion` varchar(25) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `signup` char(1) DEFAULT 'N',
  `banned` char(1) DEFAULT NULL,
  `suspended` char(1) DEFAULT NULL,
  `deleted` char(1) DEFAULT 'N',
  `sort` int(11) DEFAULT 1,
  `active` char(1) DEFAULT 'Y',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `subdomain_id`, `username`, `email`, `password`, `mustChangePassword`, `profilesId`, `role`, `fullName`, `sex`, `birthday`, `creditCard`, `phone`, `facebook`, `balance`, `address`, `cityRegion`, `token`, `signup`, `banned`, `suspended`, `deleted`, `sort`, `active`, `created_at`, `modified_in`) VALUES
(3734, 3863, 'admin', NULL, '$2y$12$UogmFOghi1QXXeQeIj8qCe8NSOhmZ40qb6whhbD5/2BQSck5xvv.G', NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'N', NULL, NULL, 'N', 1, 'Y', '2019-09-04 19:36:07', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_history`
--

CREATE TABLE `user_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `subdomain_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `action` int(11) NOT NULL,
  `summary` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT 1,
  `active` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `deleted` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_history`
--

INSERT INTO `user_history` (`id`, `user_id`, `subdomain_id`, `subdomain_name`, `amount`, `action`, `summary`, `sort`, `active`, `deleted`, `create_at`, `modified_in`) VALUES
(8079, 81, 3863, 'hangxachtaykorea', -4500, 1, 'tạo website', 1, 'Y', 'N', '2019-09-04 19:36:07', '0000-00-00 00:00:00'),
(8080, 3734, 3863, NULL, 0, 4, 'số tiền được cộng sẵn', 1, 'Y', 'N', '2019-09-04 19:36:07', '0000-00-00 00:00:00'),
(8233, 81, 3863, 'hangxachtaykorea', -95000, 2, 'kích hoạt website', 1, 'Y', 'N', '2019-09-19 02:10:54', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_history_transfer`
--

CREATE TABLE `user_history_transfer` (
  `id` int(11) NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `online_payment_type` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `error_text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secure_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token_nl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT 1,
  `active` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `deleted` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usually_question`
--

CREATE TABLE `usually_question` (
  `id` int(11) NOT NULL,
  `subdomain_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL DEFAULT 1,
  `depend_id` int(11) DEFAULT 0,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slogan` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` int(11) DEFAULT 1,
  `active` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted` char(1) COLLATE utf8_unicode_ci DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `word_core`
--

CREATE TABLE `word_core` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `word_key` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `word_translate` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sort` int(11) NOT NULL,
  `active` char(1) NOT NULL,
  `deleted` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `word_core`
--

INSERT INTO `word_core` (`id`, `name`, `word_key`, `word_translate`, `created_at`, `modified_in`, `sort`, `active`, `deleted`) VALUES
(1, '_danh_muc_san_pham', 'Danh mục sản phẩm', 'Danh mục sản phẩm', '2018-03-15 22:43:05', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(2, '_san_pham_ban_chay', 'Sản phẩm bán chạy', 'Sản phẩm bán chạy', '2018-03-15 22:43:35', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(3, '_san_pham_moi', 'Sản phẩm mới', 'Sản phẩm mới', '2018-03-15 22:44:07', '2018-04-10 09:41:39', 1, 'Y', 'N'),
(4, '_thong_tin_doanh_nghiep', 'Thông tin doanh nghiệp', 'Thông tin doanh nghiệp', '2018-03-15 22:45:13', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(5, '_hotline', 'Hotline', 'Hotline', '2018-03-15 22:46:02', '2018-08-20 02:26:55', 1, 'Y', 'N'),
(6, '_chinh_sach_chung', 'Chính sách chung', 'Chính sách chung', '2018-03-15 22:46:56', '2018-04-10 09:42:25', 1, 'Y', 'N'),
(7, '_danh_muc', 'Danh mục', 'Danh mục', '2018-03-15 22:47:47', '2018-04-10 09:42:47', 1, 'Y', 'N'),
(8, '_thong_ke_truy_cap', 'Thống kê truy cập', 'Thống kê truy cập', '2018-03-15 22:48:15', '2018-04-10 09:43:02', 1, 'Y', 'N'),
(9, '_tin_noi_bat', 'Tin nổi bật', 'Tin nổi bật', '2018-03-15 22:49:11', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(10, '_doi_tac', 'Đối tác', 'Đối tác', '2018-03-15 22:50:22', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(11, '_hom_nay', 'Hôm nay', 'Hôm nay', '2018-03-15 22:52:15', '2018-04-10 09:43:33', 1, 'Y', 'N'),
(12, '_dang_online', 'Đang online', 'Đang online', '2018-03-15 22:52:42', '2018-04-10 09:43:22', 1, 'Y', 'N'),
(13, '_menu', 'Menu', 'Menu', '2018-03-15 22:53:20', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(14, '_san_pham_noi_bat', 'Sản phẩm nổi bật', 'Sản phẩm nổi bật', '2018-03-16 00:30:53', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(15, '_san_pham_khuyen_mai', 'Sản phẩm khuyến mãi', 'Sản phẩm khuyến mãi', '2018-03-16 00:31:22', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(16, '_chi_tiet_san_pham', 'Chi tiết sản phẩm', 'Chi tiết sản phẩm', '2018-03-17 09:21:04', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(17, '_san_pham_khac', 'Sản phẩm khác', 'Sản phẩm khác', '2018-03-17 09:21:19', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(18, '_hom_qua', 'Hôm qua', 'Hôm qua', '2018-03-17 22:11:44', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(19, '_tuan_nay', 'Tuần này', 'Tuần này', '2018-03-17 22:11:59', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(20, '_thang_nay', 'Tháng này', 'Tháng này', '2018-03-17 22:12:15', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(21, '_nam_nay', 'Năm nay', 'Năm nay', '2018-03-17 22:12:31', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(22, '_dien_thoai', 'Điện thoại', 'Điện thoại', '2018-03-17 22:12:53', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(23, '_lien_ket_website', 'Liên kết website', 'Liên kết website', '2018-03-17 22:13:07', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(24, '_email', 'Email', 'Email', '2018-03-17 22:13:19', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(25, '_tong_truy_cap', 'Tổng truy cập', 'Tổng truy cập', '2018-03-17 22:13:29', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(26, '_mua_ngay', 'Mua ngay', 'Mua ngay', '2018-03-23 00:43:20', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(28, '_chi_tiet', 'Chi tiết', 'Chi tiết', '2018-04-05 23:08:08', '2018-04-12 02:20:06', 1, 'Y', 'N'),
(29, '_dang_ky_nhan_mail', 'Đăng ký nhận mail', 'Đăng ký nhận mail', '2018-04-12 23:36:51', '2018-04-13 01:44:55', 1, 'Y', 'N'),
(30, '_dang_ky', 'Đăng ký', 'Đăng ký', '2018-04-12 23:37:31', '2018-04-13 01:43:44', 1, 'Y', 'N'),
(31, '_cap_nhat_cac_thong_tin_khuyen_mai_moi_nhat', 'Cập nhật các thông tin khuyến mãi mới nhất', 'Cập nhật các thông tin khuyến mãi mới nhất', '2018-04-12 23:38:41', '2018-04-13 01:44:25', 1, 'Y', 'N'),
(32, '_bat_buoc', 'Bắt buộc', 'Bắt buộc', '2018-04-17 22:53:24', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(33, '_khong_bat_buoc', 'Không bắt buộc', 'Không bắt buộc', '2018-04-17 22:53:41', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(34, '_ho_ten', 'Họ tên', 'Họ tên', '2018-04-17 22:54:01', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(35, '_tieu_de', 'Tiêu đề', 'Tiêu đề', '2018-04-17 22:54:51', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(36, '_yeu_cau_khac', 'Yêu cầu khác', 'Yêu cầu khác', '2018-04-17 22:55:08', '2018-04-17 23:01:02', 1, 'Y', 'N'),
(37, '_gui_yeu_cau_bao_gia_ngay', 'Gửi yêu cầu báo giá ngay', 'Gửi yêu cầu báo giá ngay', '2018-04-17 23:01:22', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(38, '_gui_yeu_cau_bao_gia', 'Gửi yêu cầu báo giá', 'Gửi yêu cầu báo giá', '2018-04-17 23:03:36', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(39, '_de_lai_tin_nhan_cho_chung_toi', 'Để lại tin nhắn cho chúng tôi', 'Để lại tin nhắn cho chúng tôi', '2018-04-18 06:09:40', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(40, '_vui_long_de_lai_loi_nhan', 'Vui lòng để lại lời nhắn, nhân viên tư vấn sẽ liên hệ hỗ trợ bạn', 'Vui lòng để lại lời nhắn, nhân viên tư vấn sẽ liên hệ hỗ trợ bạn', '2018-04-18 06:11:01', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(41, '_xin_moi_nhap_ho_ten', 'Xin mời nhập họ tên', 'Xin mời nhập họ tên', '2018-04-18 06:12:22', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(42, '_vui_long_nhap_so_dien_thoai_cua_ban', 'Vui lòng nhập số điện thoại của bạn', 'Vui lòng nhập số điện thoại của bạn', '2018-04-18 06:15:07', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(43, '_hay_gui_tin_nhan_cho_chung_toi_van_de_cua_ban', 'Hãy gửi tin nhắn cho chúng tôi về vấn đề của bạn', 'Hãy gửi tin nhắn cho chúng tôi về vấn đề của bạn', '2018-04-18 06:16:45', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(44, '_gui_tin_nhan', 'Gửi tin nhắn', 'Gửi tin nhắn', '2018-04-18 06:17:03', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(45, '_tin_moi', 'Tin mới', 'Tin mới', '2018-04-28 19:47:51', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(46, '_tin_xem_nhieu', 'Tim xem nhiều', 'Tim xem nhiều', '2018-04-28 19:49:07', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(47, '_yeu_cau_mic_ho_tro', 'Yêu Cầu Hỗ Trợ', 'Yêu Cầu Hỗ Trợ', '2018-05-05 06:23:56', '2018-05-05 07:26:16', 1, 'Y', 'N'),
(48, '_yeu_cau_tu_van_qua_dien_thoai', 'Yêu cầu tư vấn qua điện thoại', 'Yêu cầu tư vấn qua điện thoại', '2018-05-05 06:25:13', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(49, '_gui_yeu_cau', 'Gửi yêu cầu', 'Gửi yêu cầu', '2018-05-05 06:26:10', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(50, '_gioi_thieu_chung', 'Giới thiệu chung', 'Giới thiệu chung', '2018-05-12 03:06:58', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(51, '_noi_can_don', 'Nơi cần đón', 'Nơi cần đón', '2018-05-12 20:23:01', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(52, '_noi_can_den', 'Nơi cần đến', 'Nơi cần đến', '2018-05-12 20:23:19', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(53, '_loai_xe', 'Loại xe', 'Loại xe', '2018-05-12 20:24:07', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(54, '_ngay_don', 'Ngày đón', 'Ngày đón', '2018-05-12 20:24:31', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(55, '_xem_them', 'Xem thêm', 'Xem thêm', '2018-05-28 18:22:32', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(56, '_danh_muc_tin_tuc', 'Danh mục tin tức', 'Danh mục tin tức', '2018-06-02 20:25:46', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(57, '_xem_tiep', 'Xem tiếp', 'Xem tiếp', '2018-06-18 20:22:43', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(58, '_danh_muc_san_pham_noi_bat', 'Danh mục sản phẩm nổi bật', 'Danh mục sản phẩm nổi bật', '2018-06-29 21:54:01', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(59, '_dang_nhap', 'Đăng nhập', 'Đăng nhập', '2018-06-30 02:06:22', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(60, '_tai_khoan', 'Tài khoản', 'Tài khoản', '2018-06-30 19:34:32', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(61, '_dang_xuat', 'Đăng xuất', 'Đăng xuất', '2018-06-30 19:38:11', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(62, '_thong_tin_khach_hang', 'Thông tin khách hàng', 'Thông tin khách hàng', '2018-07-11 20:42:37', '2018-07-12 07:14:22', 1, 'Y', 'N'),
(63, '_mail_note_link', 'Chú ý: Để quản lý tốt hơn, vui lòng truy cập vào trong link sau', 'Chú ý: Để quản lý tốt hơn, vui lòng truy cập vào trong link sau', '2018-07-11 20:44:32', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(64, '_lop', 'Lớp', 'Lớp', '2018-07-20 18:36:13', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(65, '_mon_hoc', 'Môn học', 'Môn học', '2018-07-20 18:37:01', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(66, '_so_luong_hoc_sinh', 'Số lượng học sinh', 'Số lượng học sinh', '2018-07-20 18:37:27', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(67, '_hoc_luc_hien_tai', 'Học lực hiện tại', 'Học lực hiện tại', '2018-07-20 18:37:50', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(68, '_so_buoi', 'Số buổi', 'Số buổi', '2018-07-20 18:38:42', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(69, '_thoi_gian_hoc', 'Thời gian học', 'Thời gian học', '2018-07-20 18:39:16', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(70, '_yeu_cau', 'Yêu cầu', 'Yêu cầu', '2018-07-20 18:40:49', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(71, '_ma_so_gia_su_da_chon', 'Mã số gia sư đã chọn', 'Mã số gia sư đã chọn', '2018-07-20 18:41:33', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(72, '_lop_1', 'Lớp 1', 'Lớp 1', '2018-07-20 18:43:50', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(73, '_lop_2', 'Lớp 2', 'Lớp 2', '2018-07-20 18:46:34', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(74, '_lop_3', 'Lớp 3', 'Lớp 3', '2018-07-20 18:46:59', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(75, '_lop_4', 'Lớp 4', 'Lớp 4', '2018-07-20 18:47:16', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(76, '_lop_5', 'Lớp 5', 'Lớp 5', '2018-07-20 18:48:04', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(77, '_lop_6', 'Lớp 6', 'Lớp 6', '2018-07-20 18:48:28', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(78, '_lop_8', 'Lớp 8', 'Lớp 8', '2018-07-20 18:49:04', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(79, '_lop_9', 'Lớp 9', 'Lớp 9', '2018-07-20 18:50:08', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(80, '_lop_10', 'Lớp 10', 'Lớp 10', '2018-07-20 18:51:53', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(81, '_lop_11', 'Lớp 11', 'Lớp 11', '2018-07-20 18:53:01', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(82, '_on_dai_hoc', 'Ôn Đại Học', 'Ôn Đại Học', '2018-07-20 18:54:02', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(83, '_lop_nang_khieu', 'Lớp năng khiếu', 'Lớp năng khiếu', '2018-07-20 18:54:41', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(84, '_lop_ngoai_ngu', 'Lớp ngoại ngữ', 'Lớp ngoại ngữ', '2018-07-20 18:55:14', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(85, '_lop_khac', 'Lớp khác', 'Lớp khác', '2018-07-20 18:55:49', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(86, '_lop_la', 'Lớp lá', 'Lớp lá', '2018-07-20 19:00:01', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(87, '_he_dai_hoc', 'Hệ đại học', 'Hệ đại học', '2018-07-20 19:00:30', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(88, '_vi_du_toan_ly_hoa', 'Ví dụ: toán, lý, hóa,...', 'Ví dụ: toán, lý, hóa,...', '2018-07-20 19:01:30', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(89, '_1_buoi', '1 buổi', '1 buổi', '2018-07-20 19:02:01', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(90, '_2_buoi', '2 buổi', '2 buổi', '2018-07-20 19:02:38', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(91, '_3_buoi', '3 buổi', '3 buổi', '2018-07-20 19:03:13', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(92, '_4_buoi', '4 buổi', '4 buổi', '2018-07-20 19:03:33', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(93, '_5_buoi', '5 buổi', '5 buổi', '2018-07-20 19:04:10', '2018-07-20 19:04:33', 1, 'Y', 'N'),
(94, '_6_buoi', '6 buổi', '6 buổi', '2018-07-20 19:04:52', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(95, '_7_buoi', '7 buổi', '7 buổi', '2018-07-20 19:05:15', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(96, '_tren_tuan', '/tuần', '/tuần', '2018-07-20 19:06:14', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(97, '_vi_du_t2_t4', 'Ví dụ: T2 - T4 - T6; 17h - 19h', 'Ví dụ: T2 - T4 - T6; 17h - 19h', '2018-07-20 19:06:55', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(98, '_sinh_vien_nam', 'Sinh viên nam', 'Sinh viên nam', '2018-07-20 19:15:50', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(99, '_sinh_vien_nu', 'Sinh viên nữ', 'Sinh viên nữ', '2018-07-20 19:16:11', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(100, '_sinh_vien', 'Sinh viên', 'Sinh viên', '2018-07-20 19:16:40', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(101, '_giao_vien_nam', 'Giáo viên nam', 'Giáo viên nam', '2018-07-20 19:17:12', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(102, '_giao_vien_nu', 'Giáo viên nữ', 'Giáo viên nữ', '2018-07-20 19:17:43', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(103, '_giao_vien', 'Giáo viên', 'Giáo viên', '2018-07-20 19:18:10', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(104, '_cu_nhan', 'Cử nhân', 'Cử nhân', '2018-07-20 19:18:31', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(105, '_cu_nhan_nam', 'Cử nhân nam', 'Cử nhân nam', '2018-07-20 19:18:55', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(106, '_cu_nhan_nu', 'Cử nhân nữ', 'Cử nhân nữ', '2018-07-20 19:19:17', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(107, '_thac_sy', 'Thạc sỹ', 'Thạc sỹ', '2018-07-20 19:19:37', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(108, '_thac_sy_nam', 'Thạc sỹ nam', 'Thạc sỹ nam', '2018-07-20 19:20:01', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(109, '_thac_sy_nu', 'Thạc sỹ nữ', 'Thạc sỹ nữ', '2018-07-20 19:20:16', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(110, '_tien_sy', 'Tiến sỹ', 'Tiến sỹ', '2018-07-20 19:20:39', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(111, '_tien_sy_nam', 'Tiến sỹ nam', 'Tiến sỹ nam', '2018-07-20 19:21:04', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(112, '_tien_sy_nu', 'Tiến sỹ nữ', 'Tiến sỹ nữ', '2018-07-20 19:21:31', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(113, '_vi_du_ma_so', 'Ví dụ: Mã số 2880, 2982,...', 'Ví dụ: Mã số 2880, 2982,...', '2018-07-20 19:21:56', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(114, '_loai_xe_4_cho_ngoi', 'Loại xe 4 chỗ ngồi', 'Loại xe 4 chỗ ngồi', '2018-07-21 01:04:32', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(115, '_loai_xe_7_cho_ngoi', 'Loại xe 7 chỗ ngồi', 'Loại xe 7 chỗ ngồi', '2018-07-21 01:04:56', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(116, '_loai_xe_16_cho_ngoi', 'Loại xe 16 chỗ ngồi', 'Loại xe 16 chỗ ngồi', '2018-07-21 01:05:30', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(117, '_chon_lop', 'Chọn lớp', 'Chọn lớp', '2018-07-21 20:15:19', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(118, '_chon_so_buoi', 'Chọn số buổi', 'Chọn số buổi', '2018-07-21 20:15:44', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(119, '_chon_yeu_cau', 'Chọn yêu cầu', 'Chọn yêu cầu', '2018-07-21 20:16:54', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(120, '_dia_chi', 'Địa chỉ', 'Địa chỉ', '2018-07-22 02:34:05', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(121, '_lop_7', 'Lớp 7', 'Lớp 7', '2018-07-22 21:50:32', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(122, '_lop_12', 'Lớp 12', 'Lớp 12', '2018-07-22 21:50:46', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(123, '_anh_the', 'Ảnh thẻ', 'Ảnh thẻ', '2018-07-23 04:02:20', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(124, '_anh_bang_cap', 'Ảnh bằng cấp', 'Ảnh bằng cấp', '2018-07-23 04:02:59', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(125, '_sinh_vien_giao_vien_truong', 'Sinh viên(giáo viên) trường', 'Sinh viên(giáo viên) trường', '2018-07-23 04:03:49', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(126, '_nganh_hoc', 'Ngành học', 'Ngành học', '2018-07-23 04:04:37', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(127, '_nam_tot_nghiep', 'Năm tốt nghiệp', 'Năm tốt nghiệp', '2018-07-23 04:05:34', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(128, '_hien_la', 'Hiện là', 'Hiện là', '2018-07-23 04:06:47', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(129, '_uu_diem', 'Ưu điểm', 'Ưu điểm', '2018-07-23 04:08:27', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(130, '_vi_du_dai_hoc_su_pham', 'Ví dụ: Đại học Sư Phạm ...', 'Ví dụ: Đại học Sư Phạm ...', '2018-07-23 04:18:10', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(131, '_vi_du_kinh_nghiem', 'Ví dụ: Có 3 năm kinh nghiệm dạy kèm, nhiệt tình...', 'Ví dụ: Có 3 năm kinh nghiệm dạy kèm, nhiệt tình...', '2018-07-23 04:21:32', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(132, '_trinh_do', 'Trình độ', 'Trình độ', '2018-07-23 04:25:07', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(133, '_gioi_tinh', 'Giới tính', 'Giới tính', '2018-07-23 08:53:46', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(134, '_mon_day', 'Môn dạy', 'Môn dạy', '2018-07-23 08:54:41', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(135, '_toan', 'Toán', 'Toán', '2018-07-23 08:55:36', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(136, '_ly', 'Lý', 'Lý', '2018-07-23 08:56:03', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(137, '_hoa', 'Hóa', 'Hóa', '2018-07-23 08:56:39', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(138, '_van', 'Văn', 'Văn', '2018-07-23 08:56:53', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(139, '_tieng_anh', 'Tiếng Anh', 'Tiếng Anh', '2018-07-23 08:57:29', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(140, '_sinh', 'Sinh', 'Sinh', '2018-07-23 08:58:12', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(141, '_bao_bai', 'Báo bài', 'Báo bài', '2018-07-23 08:58:33', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(142, '_su', 'Sử', 'Sử', '2018-07-23 08:59:16', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(143, '_tieng_viet', 'Tiếng Việt', 'Tiếng Việt', '2018-07-23 08:59:41', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(144, '_dia', 'Địa', 'Địa', '2018-07-23 09:00:12', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(145, '_ve', 'Vẽ', 'Vẽ', '2018-07-23 09:00:32', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(146, 'dan_nhac', 'Đàn nhạc', 'Đàn nhạc', '2018-07-23 09:01:05', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(147, '_tin_hoc', 'Tin học', 'Tin học', '2018-07-23 09:01:41', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(148, '_luyen_chu_dep', 'Luyện chữ đẹp', 'Luyện chữ đẹp', '2018-07-23 09:02:26', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(149, '_tieng_trung', 'Tiếng Trung', 'Tiếng Trung', '2018-07-23 09:02:47', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(150, '_tieng_nhat', 'Tiếng Nhật', 'Tiếng Nhật', '2018-07-23 09:03:05', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(151, '_anh_van_giao_tiep', 'Anh văn giao tiếp', 'Anh văn giao tiếp', '2018-07-23 09:03:25', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(152, '_tieng_han', 'Tiếng Hàn', 'Tiếng Hàn', '2018-07-23 09:05:30', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(153, '_ke_toan', 'Kế toán', 'Kế toán', '2018-07-23 09:06:05', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(154, '_tieng_nga', 'Tiếng Nga', 'Tiếng Nga', '2018-07-23 09:06:19', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(155, '_tieng_phap', 'Tiếng Pháp', 'Tiếng Pháp', '2018-07-23 09:06:58', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(156, '_tieng_duc', 'Tiếng Đức', 'Tiếng Đức', '2018-07-23 09:07:19', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(157, '_tieng_campuchia', 'Tiếng Campuchia', 'Tiếng Campuchia', '2018-07-23 09:07:35', '2018-07-23 09:07:56', 1, 'Y', 'N'),
(158, '_tieng_thai', 'Tiếng Thái', 'Tiếng Thái', '2018-07-23 09:08:20', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(159, '_tieng_y', 'Tiếng Ý', 'Tiếng Ý', '2018-07-23 09:08:44', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(160, '_mon_khac', 'Môn khác', 'Môn khác', '2018-07-23 09:21:54', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(161, '_thu_2_b_sang', 'Thứ 2 - B.Sáng', 'Thứ 2 - B.Sáng', '2018-07-23 09:44:43', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(162, '_thu_2_b_chieu', 'Thứ 2 - B.Chiều', 'Thứ 2 - B.Chiều', '2018-07-23 09:45:13', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(163, '_thu_2_b_toi', 'Thứ 2 - B.Tối', 'Thứ 2 - B.Tối', '2018-07-23 09:45:34', '2018-07-23 10:02:41', 1, 'Y', 'N'),
(164, '_thu_3_b_sang', 'Thứ 3 - B.Sáng', 'Thứ 3 - B.Sáng', '2018-07-23 09:45:56', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(165, '_thu_3_b_chieu', 'Thứ 3 - B.Chiều', 'Thứ 3 - B.Chiều', '2018-07-23 09:46:13', '2018-07-23 18:36:52', 1, 'Y', 'N'),
(166, '_thu_3_b_toi', 'Thứ 3 - B.Tối', 'Thứ 3 - B.Tối', '2018-07-23 09:46:33', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(167, '_thu_4_b_sang', 'Thứ 4 - B.Sáng', 'Thứ 4 - B.Sáng', '2018-07-23 09:47:00', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(168, '_thu_4_b_chieu', 'Thứ 4 - B.Chiều', 'Thứ 4 - B.Chiều', '2018-07-23 09:47:27', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(169, '_thu_4_b_toi', 'Thứ 4 - B.Tối', 'Thứ 4 - B.Tối', '2018-07-23 09:47:48', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(170, '_thu_5_b_sang', 'Thứ 5 - B.Sáng', 'Thứ 5 - B.Sáng', '2018-07-23 09:56:47', '2018-07-23 10:02:09', 1, 'Y', 'N'),
(171, '_thu_5_b_chieu', 'Thứ 5 - B.Chiều', 'Thứ 5 - B.Chiều', '2018-07-23 09:57:02', '2018-07-23 10:01:55', 1, 'Y', 'N'),
(172, '_thu_5_b_toi', 'Thứ 5 - B.Tối', 'Thứ 5 - B.Tối', '2018-07-23 09:57:25', '2018-07-23 10:01:43', 1, 'Y', 'N'),
(173, '_thu_6_b_sang', 'Thứ 6 - B.Sáng', 'Thứ 6 - B.Sáng', '2018-07-23 09:57:47', '2018-07-23 10:01:32', 1, 'Y', 'N'),
(174, '_thu_6_b_chieu', 'Thứ 6 - B.Chiều', 'Thứ 6 - B.Chiều', '2018-07-23 09:58:05', '2018-07-23 10:01:21', 1, 'Y', 'N'),
(175, '_thu_6_b_toi', 'Thứ 6 - B.Tối', 'Thứ 6 - B.Tối', '2018-07-23 09:58:41', '2018-07-23 10:01:08', 1, 'Y', 'N'),
(176, '_thu_7_b_sang', 'Thứ 7 - B.Sáng', 'Thứ 7 - B.Sáng', '2018-07-23 10:04:01', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(177, '_thu_7_b_chieu', 'Thứ 7 - B.Chiều', 'Thứ 7 - B.Chiều', '2018-07-23 10:04:33', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(178, '_thu_7_b_toi', 'Thứ 7 - B.Tối', 'Thứ 7 - B.Tối', '2018-07-23 10:04:58', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(179, '_cn_b_sang', 'CN - B.Sáng', 'CN - B.Sáng', '2018-07-23 10:05:19', '2018-07-23 18:36:40', 1, 'Y', 'N'),
(180, '_cn_b_chieu', 'CN - B.Chiều', 'CN - B.Chiều', '2018-07-23 10:05:33', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(181, '_cn_b_toi', 'CN - B.Tối', 'CN - B.Tối', '2018-07-23 10:05:56', '2018-07-23 18:36:30', 1, 'Y', 'N'),
(182, '_yeu_cau_luong_toi_thieu', 'Yêu cầu lương tối thiểu', 'Yêu cầu lương tối thiểu', '2018-07-23 10:06:22', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(183, '_tren_buoi', '/1 buổi', '/1 buổi', '2018-07-23 10:06:43', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(184, '_1_buoi_cua_giao_vien', '1 buổi của Giáo viên là: 90 phút, 1 buổi Sinh Viên là: 120 phút', '1 buổi của Giáo viên là: 90 phút, 1 buổi Sinh Viên là: 120 phút', '2018-07-23 10:07:36', '2018-07-23 18:36:19', 1, 'Y', 'N'),
(185, '_chon_giong_noi', 'Chọn giọng nói', 'Chọn giọng nói', '2018-07-23 10:08:21', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(186, '_cu_nhan_su_pham', 'Cử nhân sư phạm', 'Cử nhân sư phạm', '2018-07-23 20:23:47', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(187, '_sinh_vien_su_pham', 'Sinh viên sư phạm', 'Sinh viên sư phạm', '2018-07-23 20:24:16', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(188, '_ky_su', 'Kỹ sư', 'Kỹ sư', '2018-07-23 20:25:34', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(189, '_bang_khac', 'Bằng khác', 'Bằng khác', '2018-07-23 20:25:56', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(190, '_nam', 'Nam', 'Nam', '2018-07-23 20:26:40', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(191, '_nu', 'Nữ', 'Nữ', '2018-07-23 20:26:52', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(192, '_mien_bac', 'Miền Bắc', 'Miền Bắc', '2018-07-23 20:29:22', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(193, '_mien_trung', 'Miền Trung', 'Miền Trung', '2018-07-23 20:29:50', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(194, '_mien_nam', 'Miền Nam', 'Miền Nam', '2018-07-23 20:30:11', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(195, '_chon_tinh_thanh', 'Chọn Tỉnh/Thành', 'Chọn Tỉnh/Thành', '2018-07-24 09:17:39', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(196, '_tinh_thanh_tren_cmnd', 'Tỉnh/Thành trên CMND', 'Tỉnh/Thành trên CMND', '2018-07-24 09:18:11', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(197, '_tinh_thanh_day', 'Tỉnh/Thành dạy', 'Tỉnh/Thành dạy', '2018-07-24 09:45:20', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(198, '_ngay_sinh', 'Ngày sinh', 'Ngày sinh', '2018-07-24 18:21:02', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(199, '_nguyen_quan', 'Nguyên quán', 'Nguyên quán', '2018-07-24 18:21:17', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(200, '_dan_nhac', 'Đàn nhạc', 'Đàn nhạc', '2018-07-24 20:43:48', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(201, '_lop_day', 'Lớp dạy', 'Lớp dạy', '2018-07-24 21:46:19', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(202, '_thoi_gian_day', 'Thời gian dạy', 'Thời gian dạy', '2018-07-24 21:48:55', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(203, '_giong_noi', 'Giọng nói', 'Giọng nói', '2018-07-26 01:09:17', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(204, '_chon_hinh_thuc_nhan_lop', 'Chọn hình thức nhận lớp', 'Chọn hình thức nhận lớp', '2018-07-27 01:39:43', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(205, '_hinh_thuc_nhan_lop_1', 'Chuyển khoản (Lệ phí 30%= 288,000₫)', 'Chuyển khoản (Lệ phí 30%= 288,000₫)', '2018-07-27 01:40:22', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(206, '_hinh_thuc_nhan_lop_2', 'Tới trung tâm (Lệ phí 35%= 336,000₫)', 'Tới trung tâm (Lệ phí 35%= 336,000₫)', '2018-07-27 01:40:41', '2018-07-27 02:13:10', 1, 'Y', 'N'),
(207, '_chon_gio', 'Chọn giờ', 'Chọn giờ', '2018-07-27 02:13:42', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(208, '_nhan_lop', 'Nhận lớp', 'Nhận lớp', '2018-07-27 02:15:17', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(209, '_thoi_gian_nhan_lop', 'Thời gian nhận lớp', 'Thời gian nhận lớp', '2018-07-27 02:15:59', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(210, '_luc_khac', 'Lúc khác', 'Lúc khác', '2018-07-27 02:24:30', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(211, '_dang_ky_nhanh', 'Đăng ký nhanh', 'Đăng ký nhanh', '2018-07-27 02:24:49', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(212, '_gio', 'giờ', 'giờ', '2018-07-27 02:28:54', '2018-07-27 02:29:09', 1, 'Y', 'N'),
(213, '_yeu_cau_them', 'Yêu cầu thêm', 'Yêu cầu thêm', '2018-07-27 04:06:56', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(214, '_ngay_nhan_lop', 'Ngày nhận lớp', 'Ngày nhận lớp', '2018-07-27 07:35:06', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(215, '_ngay', 'Ngày', 'Ngày', '2018-07-27 19:16:54', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(216, '_gui_y_kien', 'Gửi ý kiến', 'Gửi ý kiến', '2018-07-28 18:03:54', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(217, '_y_kien_khach_hang', 'Ý kiến khách hàng', 'Ý kiến khách hàng', '2018-07-28 18:19:09', '2018-07-28 18:36:55', 1, 'Y', 'N'),
(218, '_noi_dung', 'Nội dung', 'Nội dung', '2018-07-28 18:37:14', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(219, '_gui', 'Gửi', 'Gửi', '2018-07-28 18:39:50', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(220, '_lien_he', 'Liên hệ', 'Liên hệ', '2018-07-31 17:24:37', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(221, '_ngay_gio_khoi_hanh', 'Ngày/ giờ khởi hành', 'Ngày/ giờ khởi hành', '2018-08-01 18:26:30', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(222, '_ngay_gio_ve', 'Ngày/ giờ về', 'Ngày/ giờ về', '2018-08-01 18:26:48', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(223, '_so_luong_ve', 'Số lượng vé', 'Số lượng vé', '2018-08-01 18:27:15', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(224, '_ban_do', 'Bản đồ', 'Bản đồ', '2018-08-06 21:38:12', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(225, '_du_an_da_thuc_hien', 'Dự án đã thực hiện', 'Dự án đã thực hiện', '2018-08-08 07:53:08', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(226, '_message_messenger_facebook', 'Xin chào! Chúng tôi luôn sẵn sàng trả lời mọi câu hỏi của bạn.', 'Xin chào! Chúng tôi luôn sẵn sàng trả lời mọi câu hỏi của bạn.', '2018-08-11 08:02:21', '2018-08-11 08:06:45', 1, 'Y', 'N'),
(227, '_san_pham', 'Sản phẩm', 'Sản phẩm', '2018-08-16 18:11:20', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(228, '_tim_kiem', 'Tìm kiếm', 'Tìm kiếm', '2018-09-12 03:02:29', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(229, '_nhap_tu_khoa', 'Nhập từ khóa', 'Nhập từ khóa', '2018-09-12 03:08:17', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(230, '_khoang_gia', 'Khoảng giá', 'Khoảng giá', '2018-09-12 03:13:55', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(231, '_hinh_anh', 'Hình ảnh', 'Hình ảnh', '2018-10-02 07:59:07', '2018-10-02 08:04:38', 1, 'Y', 'N'),
(232, '_binh_luan', 'Bình luận', 'Bình luận', '2018-10-13 09:03:13', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(233, '_gio_hang', 'Giỏ hàng', 'Giỏ hàng', '2018-10-13 09:04:32', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(234, '_nha', 'Nhà', 'Nhà', '2018-10-13 09:05:10', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(235, '_du_an', 'Dự án', 'Dự án', '2018-10-13 09:06:03', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(236, '_video', 'Video', 'Video', '2018-10-13 09:06:59', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(237, '_trang_chu', 'Trang chủ', 'Trang chủ', '2018-10-13 09:07:38', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(238, '_da_kich_hoat', 'Đã kích hoạt', 'Đã kích hoạt', '2018-10-13 09:08:24', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(239, '_thu_tu', 'Thứ tự', 'Thứ tự', '2018-10-13 09:09:25', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(240, '_ten', 'Tên', 'Tên', '2018-10-13 09:10:05', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(241, '_moi_tao', 'Mới tạo', 'Mới tạo', '2018-10-13 09:10:47', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(242, '_tat_ca', 'Tất cả', 'Tất cả', '2018-10-13 09:11:10', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(243, '_ket_qua_tim_kiem', 'Kết quả tìm kiếm', 'Kết quả tìm kiếm', '2018-10-13 09:11:39', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(244, '_tin_tuc_khac', 'Tin tức khác', 'Tin tức khác', '2018-10-13 09:12:24', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(245, '_ma_san_pham', 'Mã sản phẩm', 'Mã sản phẩm', '2018-10-13 09:13:06', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(246, '_so_luong', 'Số lượng', 'Số lượng', '2018-10-13 09:13:44', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(247, '_gia', 'Giá', 'Giá', '2018-10-13 09:14:19', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(248, '_thanh_tien', 'Thành tiền', 'Thành tiền', '2018-10-13 09:15:12', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(249, '_xoa', 'Xóa', 'Xóa', '2018-10-13 09:15:58', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(250, '_tiep_tuc_mua_sam', 'Tiếp tục mua sắm', 'Tiếp tục mua sắm', '2018-10-13 09:16:58', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(251, '_thanh_toan', 'Thanh Toán', 'Thanh Toán', '2018-10-13 09:17:29', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(252, '_hien_chua_co_san_pham_nao_trong_gio_hang', 'Hiện chưa có sản phẩm nào trong giỏ hàng', 'Hiện chưa có sản phẩm nào trong giỏ hàng', '2018-10-13 09:18:01', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(253, '_tong_tien', 'Tổng tiền', 'Tổng tiền', '2018-10-13 09:18:31', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(254, '_thong_tin_don_hang', 'Thông tin đơn hàng', 'Thông tin đơn hàng', '2018-10-13 09:18:52', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(255, '_gui_don_hang', 'Gửi đơn hàng', 'Gửi đơn hàng', '2018-10-13 09:19:15', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(256, '_ghi_chu', 'Ghi chú', 'Ghi chú', '2018-10-13 09:19:40', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(257, '_dang_ky_thanh_vien', 'Đăng ký thành viên', 'Đăng ký thành viên', '2018-10-16 06:24:33', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(258, '_ban_da_la_thanh_vien', 'Bạn đã là thành viên?', 'Bạn đã là thành viên?', '2018-10-16 06:25:19', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(259, '_tai_day', 'tại đây', 'tại đây', '2018-10-16 06:26:46', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(260, '_ten_dang_nhap', 'Tên đăng nhập', 'Tên đăng nhập', '2018-10-16 06:27:37', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(261, '_mat_khau', 'Mật khẩu', 'Mật khẩu', '2018-10-16 06:28:08', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(262, '_nhap_lai_mat_khau', 'Nhập lại mật khẩu', 'Nhập lại mật khẩu', '2018-10-16 06:30:24', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(263, '_ban_chua_co_tai_khoan', 'Bạn chưa có tài khoản?', 'Bạn chưa có tài khoản?', '2018-10-16 06:32:33', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(264, '_ghi_nho', 'Nhớ lại', 'Ghi nhớ', '2018-10-16 06:45:03', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(265, '_thong_tin_tai_khoan', 'Thông tin tài khoản', 'Thông tin tài khoản', '2018-10-16 06:47:33', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(266, '_doi_mat_khau', 'Đổi mật khẩu', 'Đổi mật khẩu', '2018-10-16 06:48:19', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(267, '_lich_su_don_hang', 'Lịch sử đơn hàng', 'Lịch sử đơn hàng', '2018-10-16 06:49:20', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(268, '_cap_nhat', 'Cập nhật', 'Cập nhật', '2018-10-16 06:49:51', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(269, '_hien_tai_ban_chua_co_don_hang', 'Hiện tại bạn chưa có đơn hàng nào', 'Hiện tại bạn chưa có đơn hàng nào', '2018-10-16 06:51:31', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(270, '_cap_nhat_thong_tin_ca_nhan', 'Cập nhật thông tin cá nhân', 'Cập nhật thông tin cá nhân', '2018-10-16 06:52:12', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(271, '_ban_da_dang_nhap_thanh_cong', 'Bạn đã đăng nhập thành công', 'Bạn đã đăng nhập thành công', '2018-10-16 06:52:52', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(272, '_cap_nhat_thong_tin_tai_khoan_thanh_cong', 'Cập nhật thông tin tài khoản thành công', 'Cập nhật thông tin tài khoản thành công', '2018-10-16 07:03:34', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(273, '_ban_da_dang_ky_tai_khoan_thanh_cong', 'Bạn đã đăng ký tài khoản thành công', 'Bạn đã đăng ký tài khoản thành công', '2018-10-16 07:05:35', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(274, '_ban_da_doi_mat_khau_thanh_cong', 'Bạn đã đổi mật khẩu thành công', 'Bạn đã đổi mật khẩu thành công', '2018-10-16 07:07:22', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(275, '_don_hang_khong_ton_tai', 'Đơn hàng không tồn tại', 'Đơn hàng không tồn tại', '2018-10-16 07:10:22', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(276, '_don_hang_ma_so', 'Đơn hàng mã số', 'Đơn hàng mã số', '2018-10-16 07:10:54', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(277, '_ma_don_hang', 'Mã đơn hàng', 'Mã đơn hàng', '2018-10-16 09:18:46', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(278, '_danh_sach_san_pham', 'Danh sách sản phẩm', 'Danh sách sản phẩm', '2018-10-16 09:19:15', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(279, '_xem_chi_tiet', 'Xem chi tiết', 'Xem chi tiết', '2018-10-16 09:20:57', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(280, '_tinh_trang', 'Tình trạng', 'Tình trạng', '2018-10-16 09:22:10', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(281, '_thoi_gian_dat_hang', 'Thời gian đặt hàng', 'Thời gian đặt hàng', '2018-10-16 09:25:03', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(282, '_dat_hang_thanh_cong', 'Đặt hàng thành công', 'Đặt hàng thành công', '2018-10-16 18:00:09', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(283, '_message_dat_hang_thanh_cong', 'Bạn đã gửi đơn hàng thành công. Chúng tôi sẽ liên hệ bạn trong thời gian sớm nhất!', 'Bạn đã gửi đơn hàng thành công. Chúng tôi sẽ liên hệ bạn trong thời gian sớm nhất!', '2018-10-16 18:01:25', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(284, '_khong_co_ket_qua_nao', 'Không có kết quả nào. Vui lòng thử lại!', 'Không có kết quả nào. Vui lòng thử lại!', '2018-10-27 02:31:36', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(285, '_chon_ngon_ngu', 'Chọn ngôn ngữ', 'Chọn ngôn ngữ', '2018-11-03 02:40:45', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(286, '_het_hang', 'Hết hàng', 'Hết hàng', '2018-11-04 08:32:16', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(287, '_cap_nhat_hang_ngay_chuong_trinh_bat_dau_tu', 'Cập nhật hàng ngày, chương trình bắt đầu từ lúc', 'Cập nhật hàng ngày, chương trình bắt đầu từ', '2018-11-04 21:06:09', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(288, '_dang_nhap_quan_tri', 'Đăng nhập quản trị', 'Đăng nhập quản trị', '2018-11-07 07:15:41', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(289, '_huong_dan_quan_tri', 'Hướng dẫn quản trị', 'Hướng dẫn quản trị', '2018-11-07 07:16:20', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(290, '_ngay_het_han', 'Ngày hết hạn', 'Ngày hết hạn', '2018-11-07 07:16:55', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(291, '_con_lai', 'Số ngày còn lại', 'Số ngày còn lại', '2018-11-07 07:17:37', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(293, '_dang_luc', 'Đăng lúc', 'Đăng lúc', '2018-12-13 06:46:34', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(294, '_luot_mua', 'Lượt mua', 'Lượt mua', '2018-12-21 06:11:11', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(295, '_luot_xem', 'Lượt xem', 'Lượt xem', '2018-12-21 06:12:21', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(296, '_box_tuy_chon', 'Box tùy chọn', 'Box tùy chọn', '2018-12-21 18:49:02', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(297, '_lua_chon_1', 'Lựa chọn 1', 'Lựa chọn 1', '2018-12-21 18:51:21', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(298, '_lua_chon_2', 'Lựa chọn 2', 'Lựa chọn 2', '2018-12-21 18:51:54', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(299, '_lua_chon_3', 'Lựa chọn 3', 'Lựa chọn 3', '2018-12-21 18:52:45', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(300, '_lua_chon_4', 'Lựa chọn 4', 'Lựa chọn 4', '2018-12-21 18:53:13', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(301, '_lua_chon_5', 'Lựa chọn 5', 'Lựa chọn 5', '2018-12-21 18:53:38', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(302, '_lua_chon_6', 'Lựa chọn 6', 'Lựa chọn 6', '2018-12-21 18:54:30', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(303, '_lua_chon_7', 'Lựa chọn 7', 'Lựa chọn 7', '2018-12-21 18:54:57', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(304, '_lua_chon_8', 'Lựa chọn 8', 'Lựa chọn 8', '2018-12-21 18:55:51', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(305, '_lua_chon_9', 'Lựa chọn 9', 'Lựa chọn 9', '2018-12-21 18:56:14', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(306, '_lua_chon_10', 'Lựa chọn 10', 'Lựa chọn 10', '2018-12-21 18:56:53', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(307, '_lua_chon_11', 'Lựa chọn 11', 'Lựa chọn 11', '2018-12-21 18:57:18', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(308, '_lua_chon_12', 'Lựa chọn 12', 'Lựa chọn 12', '2018-12-21 18:58:03', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(309, '_lua_chon_13', 'Lựa chọn 13', 'Lựa chọn 13', '2018-12-21 18:58:35', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(310, '_lua_chon_14', 'Lựa chọn 14', 'Lựa chọn 14', '2018-12-21 18:58:54', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(311, '_lua_chon_15', 'Lựa chọn 15', 'Lựa chọn 15', '2018-12-21 18:59:15', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(312, '_thiet_ke_web', 'Thiết kế web', 'Thiết kế web', '2018-12-29 19:14:13', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(313, '_tu_khoa_tim_ten_mien', 'Nhập từ khóa | Ví dụ: Tìm web ô tô thì nhập: auto, toyota, ford, xe bán tải,...', 'Nhập từ khóa | Vd: Tìm web ô tô thì nhập: auto, toyota, ford, xe bán tải,...', '2019-01-07 17:37:05', '2019-01-07 17:57:17', 1, 'Y', 'N'),
(314, '_we_are_here_for_you', 'We’re Here for You', 'We’re Here for You', '2019-01-11 15:27:22', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(315, '_what_we_offer', 'What We Offer', 'What We Offer', '2019-01-11 15:28:01', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(316, '_get_in_touch', 'Get in Touch', 'Get in Touch', '2019-01-11 15:29:09', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(317, '_thanh_toan_khi_nhan_hang', 'Thanh toán khi nhận hàng', 'Thanh toán khi nhận hàng', '2019-01-25 19:17:31', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(318, '_thanh_toan_chuyen_khoan_qua_ngan_hang', 'Thanh toán chuyển khoản qua ngân hàng', 'Thanh toán chuyển khoản qua ngân hàng', '2019-01-25 19:19:00', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(319, '_ghi_chu_nhan_hang_tai_nha', 'Nhận sản phẩm tại nhà, khách hàng sẽ được kiểm tra sản phẩm trước khi thanh toán', 'Nhận hàng tại nhà, được kiểm tra hàng trước khi thanh toán', '2019-01-25 19:34:59', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(320, '_san_pham_con_lai', 'There are', 'Còn', '2019-01-25 21:15:38', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(321, '_them_vao_gio_hang', 'Thêm vào giỏ hàng', 'Thêm vào giỏ hàng', '2019-01-28 06:18:15', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(322, '_gui_cv', 'Gửi CV', 'Gửi CV', '2019-03-08 22:07:49', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(323, '_chap_nhan_file', 'Chấp nhận file: word, excel, pdf', 'Chấp nhận file: word, excel, pdf', '2019-03-08 22:10:16', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(324, '_hien_chua_co_phan_hoi_nao', 'Hiện chưa có bình luận nào', 'Hiện chưa có phản hồi nào', '2019-03-25 17:48:16', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(325, '_chon_hinh', 'Chọn hình', 'Chọn hình', '2019-03-25 17:50:28', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(326, '_dang_cap_nhat', 'Đang cập nhật', 'Đang cập nhật', '2019-07-19 21:09:50', '0000-00-00 00:00:00', 1, 'Y', 'N'),
(327, '_xem_ngay', 'Xem ngay', 'Xem ngay', '2019-07-27 02:04:09', '0000-00-00 00:00:00', 1, 'Y', 'N');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suddomain_id` (`subdomain_id`);

--
-- Indexes for table `background`
--
ALTER TABLE `background`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`),
  ADD KEY `layout_id` (`layout_config_id`);

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`),
  ADD KEY `photo` (`photo`),
  ADD KEY `language_id` (`language_id`),
  ADD KEY `depend_id` (`depend_id`);

--
-- Indexes for table `banner_html`
--
ALTER TABLE `banner_html`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `banner_type`
--
ALTER TABLE `banner_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`),
  ADD KEY `module_item_id` (`module_item_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`,`subdomain_id`),
  ADD KEY `slug` (`slug`),
  ADD KEY `depend_id` (`depend_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `clip`
--
ALTER TABLE `clip`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `config_core`
--
ALTER TABLE `config_core`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `field` (`field`),
  ADD KEY `config_group_id` (`config_group_id`);

--
-- Indexes for table `config_group`
--
ALTER TABLE `config_group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `module` (`module`);

--
-- Indexes for table `config_item`
--
ALTER TABLE `config_item`
  ADD PRIMARY KEY (`id`,`subdomain_id`),
  ADD KEY `config_group_id` (`config_group_id`),
  ADD KEY `config_core_id` (`config_core_id`),
  ADD KEY `field` (`field`);

--
-- Indexes for table `config_kernel`
--
ALTER TABLE `config_kernel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `customer_comment`
--
ALTER TABLE `customer_comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `customer_message`
--
ALTER TABLE `customer_message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `domain`
--
ALTER TABLE `domain`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `form_group`
--
ALTER TABLE `form_group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `module` (`module`);

--
-- Indexes for table `form_item`
--
ALTER TABLE `form_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `ip_note`
--
ALTER TABLE `ip_note`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `landing_page`
--
ALTER TABLE `landing_page`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `layout`
--
ALTER TABLE `layout`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `layout_config`
--
ALTER TABLE `layout_config`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`),
  ADD KEY `layout_id` (`layout_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`),
  ADD KEY `module_item_id` (`module_item_id`);

--
-- Indexes for table `menu_item`
--
ALTER TABLE `menu_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`),
  ADD KEY `menu_id` (`menu_id`),
  ADD KEY `module_id` (`module_id`);
ALTER TABLE `menu_item` ADD FULLTEXT KEY `name` (`name`);
ALTER TABLE `menu_item` ADD FULLTEXT KEY `url` (`url`);

--
-- Indexes for table `module_group`
--
ALTER TABLE `module_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `module_item`
--
ALTER TABLE `module_item`
  ADD PRIMARY KEY (`id`,`subdomain_id`),
  ADD KEY `module_group_id` (`module_group_id`),
  ADD KEY `type` (`type`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `name` (`name`),
  ADD KEY `slug` (`slug`),
  ADD KEY `folder` (`folder`),
  ADD KEY `photo` (`photo`),
  ADD KEY `depend_id` (`depend_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `news_category`
--
ALTER TABLE `news_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `news_menu`
--
ALTER TABLE `news_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`),
  ADD KEY `name` (`name`),
  ADD KEY `slug` (`slug`),
  ADD KEY `depend_id` (`depend_id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `news_type`
--
ALTER TABLE `news_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_changes`
--
ALTER TABLE `password_changes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usersId` (`profilesId`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`),
  ADD KEY `module_item_id` (`module_item_id`);

--
-- Indexes for table `price_range`
--
ALTER TABLE `price_range`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`,`subdomain_id`),
  ADD KEY `name` (`name`),
  ADD KEY `slug` (`slug`),
  ADD KEY `folder` (`folder`),
  ADD KEY `photo` (`photo`),
  ADD KEY `depend_id` (`depend_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `product_content`
--
ALTER TABLE `product_content`
  ADD PRIMARY KEY (`id`,`subdomain_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `product_detail_id` (`product_detail_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `product_detail`
--
ALTER TABLE `product_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `product_element`
--
ALTER TABLE `product_element`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `product_element_detail`
--
ALTER TABLE `product_element_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_element_id` (`product_element_id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `product_photo`
--
ALTER TABLE `product_photo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `subdomain_id` (`subdomain_id`),
  ADD KEY `photo` (`photo`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active` (`active`);

--
-- Indexes for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `token` (`token`);

--
-- Indexes for table `reset_passwords`
--
ALTER TABLE `reset_passwords`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usersId` (`usersId`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `subdomain`
--
ALTER TABLE `subdomain`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE` (`name`),
  ADD KEY `display` (`display`);
ALTER TABLE `subdomain` ADD FULLTEXT KEY `name` (`name`);

--
-- Indexes for table `subdomain_rating`
--
ALTER TABLE `subdomain_rating`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `success_logins`
--
ALTER TABLE `success_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usersId` (`usersId`);

--
-- Indexes for table `support`
--
ALTER TABLE `support`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tmp_banner_banner_type`
--
ALTER TABLE `tmp_banner_banner_type`
  ADD KEY `subdomain_id` (`subdomain_id`),
  ADD KEY `banner_id` (`banner_id`),
  ADD KEY `banner_type_id` (`banner_type_id`);

--
-- Indexes for table `tmp_landing_module`
--
ALTER TABLE `tmp_landing_module`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tmp_layout_module`
--
ALTER TABLE `tmp_layout_module`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_item_id` (`module_item_id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `tmp_news_news_menu`
--
ALTER TABLE `tmp_news_news_menu`
  ADD KEY `news_id` (`news_id`),
  ADD KEY `news_menu_id` (`news_menu_id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `tmp_product_category`
--
ALTER TABLE `tmp_product_category`
  ADD PRIMARY KEY (`id`,`subdomain_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `tmp_product_product_element_detail`
--
ALTER TABLE `tmp_product_product_element_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `product_element_detail_id` (`product_element_detail_id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `tmp_subdomain_language`
--
ALTER TABLE `tmp_subdomain_language`
  ADD KEY `subdomain_id` (`subdomain_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `tmp_subdomain_user`
--
ALTER TABLE `tmp_subdomain_user`
  ADD KEY `subdomain_id` (`subdomain_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tmp_type_module`
--
ALTER TABLE `tmp_type_module`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `user_history`
--
ALTER TABLE `user_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `user_history_transfer`
--
ALTER TABLE `user_history_transfer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subdomain_id` (`subdomain_id`);

--
-- Indexes for table `usually_question`
--
ALTER TABLE `usually_question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suddomain_id` (`subdomain_id`);

--
-- Indexes for table `word_core`
--
ALTER TABLE `word_core`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `background`
--
ALTER TABLE `background`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2892;

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60294;

--
-- AUTO_INCREMENT for table `banner_html`
--
ALTER TABLE `banner_html`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=459;

--
-- AUTO_INCREMENT for table `banner_type`
--
ALTER TABLE `banner_type`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13261;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74493;

--
-- AUTO_INCREMENT for table `clip`
--
ALTER TABLE `clip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13925;

--
-- AUTO_INCREMENT for table `config_core`
--
ALTER TABLE `config_core`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `config_group`
--
ALTER TABLE `config_group`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `config_item`
--
ALTER TABLE `config_item`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=665995;

--
-- AUTO_INCREMENT for table `config_kernel`
--
ALTER TABLE `config_kernel`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7371;

--
-- AUTO_INCREMENT for table `customer_comment`
--
ALTER TABLE `customer_comment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6628;

--
-- AUTO_INCREMENT for table `customer_message`
--
ALTER TABLE `customer_message`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18014;

--
-- AUTO_INCREMENT for table `domain`
--
ALTER TABLE `domain`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2518;

--
-- AUTO_INCREMENT for table `form_group`
--
ALTER TABLE `form_group`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `form_item`
--
ALTER TABLE `form_item`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29040;

--
-- AUTO_INCREMENT for table `ip_note`
--
ALTER TABLE `ip_note`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `landing_page`
--
ALTER TABLE `landing_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `layout`
--
ALTER TABLE `layout`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `layout_config`
--
ALTER TABLE `layout_config`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4935;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=429;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9723;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77210;

--
-- AUTO_INCREMENT for table `module_group`
--
ALTER TABLE `module_group`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `module_item`
--
ALTER TABLE `module_item`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=341138;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61061;

--
-- AUTO_INCREMENT for table `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17065;

--
-- AUTO_INCREMENT for table `news_category`
--
ALTER TABLE `news_category`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `news_menu`
--
ALTER TABLE `news_menu`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47319;

--
-- AUTO_INCREMENT for table `news_type`
--
ALTER TABLE `news_type`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1091;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9552;

--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `password_changes`
--
ALTER TABLE `password_changes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `position`
--
ALTER TABLE `position`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14678;

--
-- AUTO_INCREMENT for table `price_range`
--
ALTER TABLE `price_range`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124149;

--
-- AUTO_INCREMENT for table `product_content`
--
ALTER TABLE `product_content`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=572867;

--
-- AUTO_INCREMENT for table `product_detail`
--
ALTER TABLE `product_detail`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13026;

--
-- AUTO_INCREMENT for table `product_element`
--
ALTER TABLE `product_element`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1211;

--
-- AUTO_INCREMENT for table `product_element_detail`
--
ALTER TABLE `product_element_detail`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10280;

--
-- AUTO_INCREMENT for table `product_photo`
--
ALTER TABLE `product_photo`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63930;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reset_passwords`
--
ALTER TABLE `reset_passwords`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4623;

--
-- AUTO_INCREMENT for table `subdomain`
--
ALTER TABLE `subdomain`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4615;

--
-- AUTO_INCREMENT for table `subdomain_rating`
--
ALTER TABLE `subdomain_rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=494;

--
-- AUTO_INCREMENT for table `success_logins`
--
ALTER TABLE `success_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support`
--
ALTER TABLE `support`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tmp_landing_module`
--
ALTER TABLE `tmp_landing_module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=568;

--
-- AUTO_INCREMENT for table `tmp_layout_module`
--
ALTER TABLE `tmp_layout_module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=548123;

--
-- AUTO_INCREMENT for table `tmp_product_category`
--
ALTER TABLE `tmp_product_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=375347;

--
-- AUTO_INCREMENT for table `tmp_product_product_element_detail`
--
ALTER TABLE `tmp_product_product_element_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71181;

--
-- AUTO_INCREMENT for table `tmp_type_module`
--
ALTER TABLE `tmp_type_module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4230;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4467;

--
-- AUTO_INCREMENT for table `user_history`
--
ALTER TABLE `user_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10227;

--
-- AUTO_INCREMENT for table `user_history_transfer`
--
ALTER TABLE `user_history_transfer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `usually_question`
--
ALTER TABLE `usually_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=605;

--
-- AUTO_INCREMENT for table `word_core`
--
ALTER TABLE `word_core`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=328;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
