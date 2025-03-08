-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: alwaysflower.sldb.iwinv.net
-- 생성 시간: 25-03-07 05:19
-- 서버 버전: 10.9.4-MariaDB
-- PHP 버전: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `sj`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `product`
--

CREATE TABLE `product` (
  `product_idx` int(11) NOT NULL,
  `category_idx` int(11) DEFAULT NULL,
  `t_category_name` varchar(50) DEFAULT NULL,
  `base_storage_idx` int(11) DEFAULT NULL,
  `t_base_storage_name` varchar(50) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `consulting_idx` int(11) DEFAULT NULL,
  `display` enum('on','off') NOT NULL DEFAULT 'on' COMMENT '계약종료여부, 구매자화면 노출여부',
  `display_order` int(11) NOT NULL DEFAULT 50,
  `product_price` int(11) DEFAULT 0,
  `delivery_unit` varchar(50) DEFAULT NULL COMMENT '배송단위',
  `delivery_fee` varchar(150) DEFAULT NULL COMMENT '배송비',
  `memo` text DEFAULT NULL COMMENT '메모',
  `product_img_url` varchar(500) DEFAULT NULL,
  `regist_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='상품목록';

--
-- 테이블의 덤프 데이터 `product`
--

INSERT INTO `product` (`product_idx`, `category_idx`, `t_category_name`, `base_storage_idx`, `t_base_storage_name`, `product_name`, `consulting_idx`, `display`, `display_order`, `product_price`, `delivery_unit`, `delivery_fee`, `memo`, `product_img_url`, `regist_datetime`, `update_datetime`) VALUES
(3, 4, NULL, 1, '본사 ', 'DHL a', 3, 'on', 40, 0, NULL, NULL, NULL, NULL, '2025-01-13 15:32:09', '2025-02-04 15:56:28'),
(2, 4, NULL, 1, '본사 ', 'DHL b', 3, 'on', 32, 0, NULL, NULL, NULL, NULL, '2025-01-13 15:30:07', '2025-02-04 15:56:31'),
(6, 4, NULL, 1, '본사', '콜마', 4, 'on', 50, 0, NULL, NULL, NULL, NULL, '2025-01-14 11:42:46', '2025-01-15 17:09:34'),
(7, 4, NULL, 1, '본사', '악사', 17, 'on', 50, 0, NULL, NULL, NULL, NULL, '2025-01-14 11:46:24', '2025-01-14 11:46:24'),
(8, 4, NULL, 1, '본사', '대방건설A', 12, 'on', 50, 0, NULL, NULL, NULL, NULL, '2025-01-14 11:47:18', '2025-01-14 11:47:28'),
(9, 4, NULL, 1, '본사', '대방건설B', 12, 'on', 50, 0, NULL, NULL, NULL, NULL, '2025-01-14 11:48:02', '2025-01-14 11:48:12'),
(10, 4, NULL, 1, '본사', '신성ST', 19, 'on', 50, 0, NULL, NULL, NULL, NULL, '2025-01-14 11:49:07', '2025-01-14 11:49:07'),
(11, 4, NULL, 1, '본사', '동성모터스', 6, 'on', 50, 0, NULL, NULL, NULL, NULL, '2025-01-14 11:49:34', '2025-01-14 11:49:34'),
(14, 4, NULL, 1, '본사', 'DHL 근조기', 3, 'on', 50, 0, NULL, NULL, NULL, NULL, '2025-01-14 17:28:38', '2025-01-14 17:28:38'),
(15, 4, NULL, 16, '강원 지사 (빠삐용)', '조광폐인트', 20, 'on', 50, 0, '1', NULL, NULL, NULL, '2025-02-25 13:11:00', '2025-02-25 13:11:00'),
(16, 4, NULL, 1, '본사 ', '제우스', 2, 'on', 50, 0, '1', NULL, NULL, NULL, '2025-02-25 13:12:17', '2025-02-25 13:12:17');

--
-- 트리거 `product`
--
DELIMITER $$
CREATE TRIGGER `product_insert_before` BEFORE INSERT ON `product` FOR EACH ROW BEGIN

set NEW.t_category_name = (select category_name from category where category_idx=NEW.category_idx),
NEW.t_base_storage_name = (select storage_name from storage where storage_idx=NEW.base_storage_idx);


END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `product_update_before` BEFORE UPDATE ON `product` FOR EACH ROW BEGIN

set NEW.t_category_name = (select category_name from category where category_idx=NEW.category_idx),
NEW.t_base_storage_name = (select storage_name from storage where storage_idx=NEW.base_storage_idx);


END
$$
DELIMITER ;

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_idx`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `product`
--
ALTER TABLE `product`
  MODIFY `product_idx` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
