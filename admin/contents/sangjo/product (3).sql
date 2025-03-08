-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: alwaysflower.sldb.iwinv.net
-- 생성 시간: 25-03-07 05:20
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
-- 데이터베이스: `sangjo_new`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `product`
--

CREATE TABLE `product` (
  `product_idx` int(11) NOT NULL,
  `category1_idx` int(11) DEFAULT NULL,
  `t_category1_name` varchar(50) DEFAULT NULL,
  `category2_idx` int(11) DEFAULT NULL,
  `t_category2_name` varchar(50) DEFAULT NULL,
  `base_storage_idx` int(11) DEFAULT NULL,
  `t_base_storage_name` varchar(50) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `unit` varchar(20) DEFAULT NULL COMMENT '단위',
  `product_price` int(11) DEFAULT 0 COMMENT '납품단가',
  `cost` int(11) NOT NULL DEFAULT 0 COMMENT '제조원가',
  `specifications` varchar(50) DEFAULT NULL COMMENT '규격',
  `delivery_unit` varchar(50) DEFAULT NULL COMMENT '배송단위',
  `delivery_fee` varchar(150) DEFAULT NULL COMMENT '배송비',
  `memo` text DEFAULT NULL,
  `product_img_url` varchar(500) DEFAULT NULL,
  `display` enum('on','off') NOT NULL DEFAULT 'on',
  `display_order` varchar(50) NOT NULL DEFAULT '50' COMMENT '낮을수록 앞에 위치',
  `display_group` varchar(50) DEFAULT NULL,
  `regist_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='기본상품관리';

--
-- 트리거 `product`
--
DELIMITER $$
CREATE TRIGGER `product_insert_before` BEFORE INSERT ON `product` FOR EACH ROW BEGIN
/*
declare CN1 varchar(50);
declare CN2 varchar(50);

if NEW.category2_idx is not null and NEW.category2_idx > 0 THEN
select t_category1_name,category2_name into CN1,CN2 from category2 where category2_idx=NEW.category2_idx;

set 
NEW.t_category1_name=CN1,
NEW.t_category2_name=CN2;
elseif NEW.category1_idx is not null and NEW.category1_idx > 0 THEN
select category1_name into CN1 from category1 where category1_idx=NEW.category1_idx;

set 
NEW.t_category1_name=CN1,
NEW.category2_idx=null,
NEW.t_category2_name=null;

end if;

set 
NEW.t_base_storage_name = (select storage_name from storage where storage_idx=NEW.base_storage_idx);

*/

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `product_update_after` AFTER UPDATE ON `product` FOR EACH ROW BEGIN

/*

if OLD.product_name <> NEW.product_name THEN

update in_out set t_product_name=NEW.product_name 
where product_idx=NEW.product_idx;

update out_order_client_product a , client_product b set a.product_name=NEW.product_name
 where a.client_product_idx=b.client_product_idx and b.product_idx=NEW.product_idx;


end if;


*/
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `product_update_before` BEFORE UPDATE ON `product` FOR EACH ROW BEGIN

declare CN1 varchar(50);
declare CN2 varchar(50);

if NEW.category2_idx is not null and NEW.category2_idx > 0 THEN
select t_category1_name,category2_name into CN1,CN2 from category2 where category2_idx=NEW.category2_idx;

set 
NEW.t_category1_name=CN1,
NEW.t_category2_name=CN2;
elseif NEW.category1_idx is not null and NEW.category1_idx > 0 THEN
select category1_name into CN1 from category1 where category1_idx=NEW.category1_idx;

set 
NEW.t_category1_name=CN1,
NEW.category2_idx=null,
NEW.t_category2_name=null;

end if;

set 
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
  ADD PRIMARY KEY (`product_idx`),
  ADD KEY `display` (`display`);

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
