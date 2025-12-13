-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2025 at 06:52 PM
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
-- Database: `royal_express_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE `area` (
  `area_id` int(11) NOT NULL,
  `area_name` varchar(255) NOT NULL,
  `is_deleted` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `area`
--

INSERT INTO `area` (`area_id`, `area_name`, `is_deleted`) VALUES
(7, 'Galle', 0),
(22, 'Matara', 0),
(23, 'Colombo', 0),
(24, 'Hambantota', 0),
(25, 'Kalutara', 0),
(26, 'Colombo 6', 0),
(27, 'Colombo 8', 0),
(28, 'Colombo 5', 0),
(29, 'Colombo 4', 1),
(30, 'Johor', 0),
(31, 'KL', 0);

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `branch_id` int(11) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `is_deleted` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`branch_id`, `branch_name`, `is_deleted`) VALUES
(6, 'Tangalle', 0),
(7, 'Matara', 0),
(8, 'Galle', 0),
(9, 'Hambantota', 0),
(10, 'Kalutara', 1),
(11, 'Colombo', 0);

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `contact_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`contact_id`, `name`, `email`, `subject`, `message`, `date_updated`) VALUES
(16, 'Pathum Wijesekara', 'pathumwijesekara@gmail.com', 'Testing Purpose', 'Hi There!', '2022-11-28 10:54:08');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `nic` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `street` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `additional_address` varchar(255) DEFAULT NULL,
  `gender` int(2) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_deleted` int(2) NOT NULL,
  `active` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `name`, `email`, `phone`, `nic`, `address`, `street`, `city`, `state`, `zip_code`, `additional_address`, `gender`, `password`, `is_deleted`, `active`) VALUES
(12, 'Test User 1', 'testuser1@royalexpress.com', '0700000000', '971212125V', 'No 232, Matara Road, Southern Province, Sri Lanka', NULL, NULL, NULL, NULL, NULL, 1, 'testuser1', 0, 1),
(13, 'Test USer 2', 'testuser2@royalexpress.com', '0701111111', '971212123V', 'No 780, Galle Road, Southern Province, Sri Lanka', NULL, NULL, NULL, NULL, NULL, 1, 'testuser2', 1, 1),
(19, 'hasan', 'hsn@gmail.com', '0701111111', '10156972', 'test address', NULL, NULL, NULL, NULL, NULL, 1, '$2y$10$10Qvcejdm97mRTagRTAtiO5gd/8UDtgSoRv7nNbayYfP83tihjTuy', 0, 1),
(23, 'Muadh ali', 'hsnforstudy@gmail.com', '0123456789', '10156972', 'test address', '', '', '', '', '', 1, '$2y$10$6AOOtWelOTgKhF58U5AmRO5C0k7B8LaokY9QGWkub3kDUfstxWJY2', 0, 1),
(24, 'hasan', 'hsnforstudy@gmail.com', '0123456789', '10156972', 'addhsn, 11, hsncity, hsnstate 123456', '11', 'hsncity', 'hsnstate', '123456', 'addhsn', 1, '$2y$10$V1SIB4UkJaFrn3EL5A.OTeGsVgrEteKU9.HVSPnTFflKNc1aSlTCC', 0, 1),
(31, 'hasan', 'hubaishih@gmail.com', '0123456789', '10156972', 'hsnadd, 1, hscity, hsnstate 123456', '1', 'hscity', 'hsnstate', '123456', 'hsnadd', 1, '$2y$10$17U6MlMuFQM/8lVEcefCQekbJqSl8xCjHp4sK0JbNWX0/008h1h0.', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `custom_statuses`
--

CREATE TABLE `custom_statuses` (
  `status_id` int(11) NOT NULL,
  `status_name_en` varchar(255) NOT NULL,
  `status_name_ar` varchar(255) NOT NULL,
  `status_icon` varchar(50) DEFAULT 'fa-circle',
  `status_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_statuses`
--

INSERT INTO `custom_statuses` (`status_id`, `status_name_en`, `status_name_ar`, `status_icon`, `status_order`, `is_active`, `created_at`) VALUES
(1, 'Order is placed', 'تم وضع الطلب', 'fa-shopping-cart', 1, 1, '2025-11-25 23:51:28'),
(2, 'Prepare Order', 'تحضير الطلب', 'fa-box', 2, 1, '2025-11-25 23:51:28'),
(3, 'Seller has dropped off your parcel and it will be picked up by our logistics partner', 'قام البائع بإسقاط طردك وسيتم استلامه من قبل شريكنا اللوجستي', 'fa-hand-holding-box', 3, 1, '2025-11-25 23:51:28'),
(4, 'Your parcel has been picked up by our logistics partner', 'تم استلام طردك من قبل شريكنا اللوجستي', 'fa-truck-pickup', 4, 1, '2025-11-25 23:51:28'),
(5, 'Your parcel has arrived at the sorting facility', 'وصل طردك إلى منشأة الفرز', 'fa-warehouse', 5, 1, '2025-11-25 23:51:28'),
(6, 'Your parcel has departed from the sorting facility', 'غادر طردك منشأة الفرز', 'fa-truck', 6, 1, '2025-11-25 23:51:28'),
(7, 'Your parcel has arrived at the delivery hub', 'وصل طردك إلى مركز التوصيل', 'fa-building', 7, 1, '2025-11-25 23:51:28'),
(8, 'Your parcel is out for delivery', 'طردك في الطريق للتوصيل', 'fa-truck-fast', 8, 1, '2025-11-25 23:51:28'),
(9, 'Delivery attempt was unsuccessful', 'فشلت محاولة التوصيل', 'fa-exclamation-triangle', 9, 1, '2025-11-25 23:51:28'),
(10, 'Your parcel is ready for collection at the collection point', 'طردك جاهز للاستلام من نقطة الاستلام', 'fa-store', 10, 1, '2025-11-25 23:51:28'),
(11, 'Parcel has been delivered', 'تم التسليم', 'fa-circle-check', 11, 1, '2025-11-25 23:51:28'),
(12, 'Canceled', 'ملغي', 'fa-times-circle', 12, 1, '2025-11-25 23:51:28'),
(13, 'fuck', 'فاك', 'fa-exclamation-triangle', 13, 1, '2025-12-12 14:09:30');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `nic` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `gender` int(2) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_deleted` int(2) NOT NULL,
  `branch_id` int(255) NOT NULL,
  `active` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_id`, `name`, `email`, `phone`, `nic`, `address`, `gender`, `password`, `is_deleted`, `branch_id`, `active`) VALUES
(1, 'admin', 'admin', '', '', '', 0, '12345', 0, 0, 0),
(12, 'EMP1', 'emp1@royalexpress.com', '0770000000', '975001820V', 'No 800, Matara Road, Southern Province, Sri Lanka', 1, 'emp1', 0, 6, 0),
(13, 'EMP2', 'emp2@royalexpress.com', '0771111111', '977854562V', 'No 750, Matara Road, Southern Province, Sri Lanka', 1, 'emp2', 1, 9, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `gallery_id` int(11) NOT NULL,
  `gallery_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`gallery_id`, `gallery_image`) VALUES
(28, 'Gallery_01.jpg'),
(29, 'Gallery_02.jpg'),
(30, 'Gallery_03.jpg'),
(32, 'Gallery_05.jpg'),
(33, 'Gallery_06.jpg'),
(34, 'Gallery_04.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `price_table`
--

CREATE TABLE `price_table` (
  `price_id` int(11) NOT NULL,
  `start_area` varchar(255) NOT NULL,
  `end_area` varchar(255) NOT NULL,
  `price` int(255) NOT NULL,
  `is_deleted` int(255) NOT NULL,
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `price_table`
--

INSERT INTO `price_table` (`price_id`, `start_area`, `end_area`, `price`, `is_deleted`, `date_updated`) VALUES
(8, '7', '23', 100, 0, '2022-12-04 12:16:56'),
(9, '7', '26', 120, 0, '2022-12-04 12:17:15'),
(10, '22', '7', 50, 0, '2022-12-04 12:17:42'),
(11, '25', '22', 200, 0, '2022-12-04 12:18:06'),
(12, '22', '25', 1704564, 1, '2022-12-04 12:22:42'),
(14, '30', '31', 150, 0, '2025-11-02 22:42:18');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `request_id` int(11) NOT NULL,
  `tracking_code` varchar(20) NOT NULL,
  `qr_code_path` varchar(255) DEFAULT NULL,
  `customer_id` int(255) NOT NULL,
  `sender_phone` int(255) NOT NULL,
  `weight` int(255) NOT NULL,
  `send_location` int(255) NOT NULL,
  `end_location` int(255) NOT NULL,
  `total_fee` int(255) NOT NULL,
  `payment_method` enum('paypal','cod') NOT NULL DEFAULT 'cod',
  `payment_status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending',
  `paypal_transaction_id` varchar(255) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `res_phone` int(255) NOT NULL,
  `red_address` varchar(255) NOT NULL,
  `is_deleted` int(2) NOT NULL,
  `date_updated` datetime NOT NULL,
  `tracking_status` int(10) NOT NULL,
  `res_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`request_id`, `tracking_code`, `qr_code_path`, `customer_id`, `sender_phone`, `weight`, `send_location`, `end_location`, `total_fee`, `payment_method`, `payment_status`, `paypal_transaction_id`, `payment_date`, `res_phone`, `red_address`, `is_deleted`, `date_updated`, `tracking_status`, `res_name`) VALUES
(18, 'TEMP18', NULL, 12, 770000000, 1, 22, 7, 50, 'cod', 'paid', NULL, NULL, 771111111, 'Receiver 1, \r\nNo 235,\r\nGalle Road,\r\nMatara', 0, '2023-01-15 11:14:09', 1, 'Receiver 1'),
(29, 'FOJ-20251111-1650', 'server/uploads/qr_codes/QR_FOJ-20251111-1650_29.png', 24, 123456789, 1, 7, 23, 100, 'paypal', 'paid', '6960856342492031X', '2025-11-12 21:22:42', 123456789, 'hfghfrty ', 0, '2025-11-12 03:14:16', 1, 'new'),
(49, 'FOJ-20251112-6299', NULL, 24, 123456789, 15, 7, 23, 1500, 'paypal', 'paid', '2VD23389XL874445X', '2025-11-12 20:23:46', 123456789, 'jejejeje', 0, '2025-11-12 20:20:54', 2, 'jaja'),
(50, 'FOJ-20251112-2538', 'server/uploads/qr_codes/QR_FOJ-20251112-2538_50.png', 24, 123456789, 13, 7, 23, 1300, 'paypal', 'paid', '6YM94096KS555163B', '2025-11-13 00:28:16', 123456789, 'I am testing the QR', 0, '2025-11-13 00:27:48', 1, 'Qr fuck'),
(51, 'FOJ-20251113-5099', 'server/uploads/qr_codes/QR_FOJ-20251113-5099_51.png', 25, 123456789, 5, 7, 23, 500, 'paypal', 'paid', '9C020047XM023315Y', '2025-11-13 18:52:21', 123456789, 'Aldiksfjqkw;ei', 0, '2025-11-13 18:51:09', 1, 'Ali'),
(52, 'FOJ-20251113-7061', 'server/uploads/qr_codes/QR_FOJ-20251113-7061_52.png', 23, 123456789, 12, 7, 23, 1200, 'paypal', 'paid', '45037149NA510045H', '2025-11-13 18:54:03', 123456789, 'reywretfhywerth', 0, '2025-11-13 18:53:21', 11, 'Ali Hamoad'),
(53, 'FOJ-20251113-3893', 'server/uploads/qr_codes/QR_FOJ-20251113-3893_53.png', 23, 123456789, 3, 7, 23, 300, 'cod', 'paid', NULL, NULL, 123456789, 'sfjhsdgf,jvbgdfrrgbv', 0, '2025-11-13 18:54:56', 11, 'Salah'),
(55, 'FOJ-20251118-7884', 'server/uploads/qr_codes/QR_FOJ-20251118-7884_55.png', 29, 123456789, 2, 7, 23, 200, 'cod', 'pending', NULL, NULL, 123456789, 'khutygfkyhtfkyj', 0, '2025-11-19 04:24:16', 1, '0123456789'),
(56, 'FOJ-20251125-5060', NULL, 23, 123456789, 2, 7, 23, 200, 'cod', 'pending', NULL, NULL, 123456789, 'a5yrtys 5s5y45ywb ', 0, '2025-11-25 17:10:41', 107, 'hello'),
(57, 'FOJ-20251212-0045', 'server/uploads/qr_codes/QR_FOJ-20251212-0045_57.png', 30, 123456789, 2, 7, 23, 200, 'paypal', 'pending', NULL, NULL, 123456789, 'fjkhio aoseh ufjkie ', 0, '2025-12-12 21:54:56', 1, 'HASAN'),
(58, 'FOJ-20251212-7985', 'server/uploads/qr_codes/QR_FOJ-20251212-7985_58.png', 30, 123456789, 2, 7, 23, 200, 'cod', 'pending', NULL, NULL, 123456789, 'kfhujk tyjkdtyjtyjdtyttdy ty ', 0, '2025-12-12 21:57:38', 113, 'hello');

-- --------------------------------------------------------

--
-- Table structure for table `request_status_history`
--

CREATE TABLE `request_status_history` (
  `history_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `status` int(10) NOT NULL,
  `status_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_status_history`
--

INSERT INTO `request_status_history` (`history_id`, `request_id`, `status`, `status_date`, `created_at`) VALUES
(1, 52, 2, '2025-11-25 07:31:44', '2025-11-24 23:31:44'),
(2, 52, 3, '2025-11-25 07:31:54', '2025-11-24 23:31:54'),
(3, 52, 4, '2025-11-25 07:32:04', '2025-11-24 23:32:04'),
(4, 52, 6, '2025-11-25 07:32:09', '2025-11-24 23:32:09'),
(5, 52, 5, '2025-11-25 07:43:18', '2025-11-24 23:43:18'),
(6, 52, 7, '2025-11-25 07:43:25', '2025-11-24 23:43:25'),
(7, 52, 8, '2025-11-25 07:43:27', '2025-11-24 23:43:27'),
(8, 52, 11, '2025-11-25 07:43:35', '2025-11-24 23:43:35'),
(9, 53, 2, '2025-11-25 07:47:32', '2025-11-24 23:47:32'),
(10, 53, 3, '2025-11-25 07:47:41', '2025-11-24 23:47:41'),
(11, 53, 4, '2025-11-25 07:47:53', '2025-11-24 23:47:53'),
(12, 53, 5, '2025-11-25 16:54:20', '2025-11-25 08:54:20'),
(13, 53, 6, '2025-11-25 17:03:27', '2025-11-25 09:03:27'),
(14, 53, 7, '2025-11-25 17:03:37', '2025-11-25 09:03:37'),
(15, 53, 8, '2025-11-25 17:03:47', '2025-11-25 09:03:47'),
(16, 53, 10, '2025-11-25 17:04:05', '2025-11-25 09:04:05'),
(17, 53, 11, '2025-11-25 17:04:16', '2025-11-25 09:04:16'),
(18, 56, 1, '2025-11-25 17:10:52', '2025-11-25 09:10:52'),
(19, 56, 103, '2025-11-26 07:51:57', '2025-11-25 23:51:57'),
(20, 56, 106, '2025-11-26 07:52:05', '2025-11-25 23:52:05'),
(21, 56, 111, '2025-11-26 07:52:15', '2025-11-25 23:52:15'),
(22, 56, 107, '2025-11-26 07:55:44', '2025-11-25 23:55:44'),
(23, 57, 1, '2025-12-12 21:54:57', '2025-12-12 13:54:57'),
(24, 58, 1, '2025-12-12 21:57:39', '2025-12-12 13:57:39'),
(25, 58, 109, '2025-12-12 22:08:18', '2025-12-12 14:08:18'),
(26, 58, 104, '2025-12-12 22:08:25', '2025-12-12 14:08:25'),
(27, 58, 111, '2025-12-12 22:08:51', '2025-12-12 14:08:51'),
(28, 58, 113, '2025-12-12 22:09:30', '2025-12-12 14:09:30');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `header_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `header_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `header_desc` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `about_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `about_desc` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `company_phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `company_email` varchar(255) NOT NULL,
  `company_address` varchar(255) NOT NULL,
  `sub_image` varchar(255) NOT NULL,
  `about_image` varchar(255) NOT NULL,
  `link_facebook` varchar(255) NOT NULL,
  `link_twiiter` varchar(255) NOT NULL,
  `link_instragram` varchar(255) NOT NULL,
  `background_image` varchar(255) NOT NULL,
  `paypal_mode` enum('sandbox','live') DEFAULT 'sandbox',
  `paypal_client_id` varchar(255) DEFAULT NULL,
  `paypal_client_secret` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`header_image`, `header_title`, `header_desc`, `about_title`, `about_desc`, `company_phone`, `company_email`, `company_address`, `sub_image`, `about_image`, `link_facebook`, `link_twiiter`, `link_instragram`, `background_image`, `paypal_mode`, `paypal_client_id`, `paypal_client_secret`) VALUES
('Truck Transport.jpg', 'Welcome to Royal Express', 'Your Premier Domestic Courier Service Provider', 'About Us', 'Royal Express is a Sri Lankan premier domestic courier service provider. With the strength of an experienced and talented team, Royal Express functions with the utmost confidence in the broadest coverage, security, and timely delivery of your important documents and packages. Royal Express also has a strong background in logistics management. As a result of the trust we have built with our corporate clients, Royal Express is now the official courier for a number of entities in Sri Lanka. Royal Express upholds high service standards and quality levels to ensure that your packages and documents are handled by professionals.', '+9477-1233254', 'connect@royalexpress.com', 'jtygjuftgyjjg', 'Sub_Header.jpg', 'Tea.jpeg', 'https://www.facebook.com/', 'https://www.twitter.com/', 'https://www.instagram.com/', 'Truck Transport.jpg', 'sandbox', 'ATF3NgqnXDgojMU7vjwdjYMENojiNMUdKDJb2npC8J6H0QThG8yfNUJUx8QTz9ILnf-7f57ys82pQssS', 'EAlVy0TnJ3TcWYvMKZSxw_NyiwmLVKONMGuflnXP_g7z3JSaPNngyxShnxdRSwn8AamJ_pHGKLHAEpN9');

-- --------------------------------------------------------

--
-- Table structure for table `tracking_history`
--

CREATE TABLE `tracking_history` (
  `history_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `status` int(10) NOT NULL,
  `status_name` varchar(50) NOT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`area_id`);

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`branch_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `custom_statuses`
--
ALTER TABLE `custom_statuses`
  ADD PRIMARY KEY (`status_id`),
  ADD KEY `is_active` (`is_active`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`gallery_id`);

--
-- Indexes for table `price_table`
--
ALTER TABLE `price_table`
  ADD PRIMARY KEY (`price_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`request_id`),
  ADD UNIQUE KEY `tracking_code` (`tracking_code`);

--
-- Indexes for table `request_status_history`
--
ALTER TABLE `request_status_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `tracking_history`
--
ALTER TABLE `tracking_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `request_id` (`request_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `area`
--
ALTER TABLE `area`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `custom_statuses`
--
ALTER TABLE `custom_statuses`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `gallery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `price_table`
--
ALTER TABLE `price_table`
  MODIFY `price_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `request_status_history`
--
ALTER TABLE `request_status_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tracking_history`
--
ALTER TABLE `tracking_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tracking_history`
--
ALTER TABLE `tracking_history`
  ADD CONSTRAINT `tracking_history_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `request` (`request_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
