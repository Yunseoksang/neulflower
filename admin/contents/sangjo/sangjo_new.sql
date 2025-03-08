-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: alwaysflower.sldb.iwinv.net
-- 생성 시간: 25-03-07 07:16
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
-- 테이블 구조 `attachment`
--

CREATE TABLE `attachment` (
  `attachment_idx` int(11) NOT NULL,
  `out_order_idx` int(11) DEFAULT NULL,
  `filename` varchar(200) DEFAULT NULL,
  `regist_datetime` datetime DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_idx` int(11) DEFAULT NULL,
  `admin_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='첨부파일관리';

-- --------------------------------------------------------

--
-- 테이블 구조 `category`
--

CREATE TABLE `category` (
  `category_idx` int(11) NOT NULL,
  `category_name` varchar(50) DEFAULT NULL,
  `regist_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='제품 카테고리';

-- --------------------------------------------------------

--
-- 테이블 구조 `category1`
--

CREATE TABLE `category1` (
  `category1_idx` int(11) NOT NULL,
  `category1_name` varchar(50) DEFAULT NULL,
  `regist_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='대카테고리';

--
-- 트리거 `category1`
--
DELIMITER $$
CREATE TRIGGER `category1_update_after` AFTER UPDATE ON `category1` FOR EACH ROW BEGIN

if NEW.category1_name <> OLD.category1_name THEN

   update category2 set t_category1_name=NEW.category1_name where category1_idx=NEW.category1_idx;
   
  update product set t_category1_name=NEW.category1_name where category1_idx=NEW.category1_idx;

   
  update out_order_client_product set t_category1_name=NEW.category1_name where category1_idx=NEW.category1_idx;
  
  
     
  update consulting.client_bill set t_category1_name=NEW.category1_name where category1_idx=NEW.category1_idx;
  

end if;



END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- 테이블 구조 `category2`
--

CREATE TABLE `category2` (
  `category2_idx` int(11) NOT NULL,
  `category1_idx` int(11) DEFAULT NULL,
  `t_category1_name` varchar(50) DEFAULT NULL,
  `category2_name` varchar(50) DEFAULT NULL,
  `base_price` int(11) NOT NULL DEFAULT 0,
  `regist_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='서브카테고리';

--
-- 트리거 `category2`
--
DELIMITER $$
CREATE TRIGGER `category2_insert_before` BEFORE INSERT ON `category2` FOR EACH ROW BEGIN

set NEW.t_category1_name=(select category1_name from category1 where category1_idx=NEW.category1_idx);

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `category2_update_after` AFTER UPDATE ON `category2` FOR EACH ROW BEGIN

if NEW.category2_name <> OLD.category2_name THEN

      update product set t_category2_name=NEW.category2_name where category2_idx=NEW.category2_idx;

end if;



END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `category2_update_before` BEFORE UPDATE ON `category2` FOR EACH ROW BEGIN 

if NEW.category1_idx <> OLD.category1_idx THEN

      set NEW.t_category1_name = (select category1_name from category1 where category1_idx=NEW.category1_idx);

end if;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- 테이블 구조 `client_product`
--

CREATE TABLE `client_product` (
  `client_product_idx` int(11) NOT NULL,
  `consulting_idx` int(11) DEFAULT NULL,
  `product_idx` int(11) DEFAULT NULL,
  `admin_idx` int(11) DEFAULT NULL,
  `admin_name` varchar(30) DEFAULT NULL,
  `client_price` int(11) DEFAULT 0,
  `client_price_tax` int(11) DEFAULT 0,
  `client_price_sum` int(11) DEFAULT 0,
  `display` enum('on','off') NOT NULL DEFAULT 'on',
  `regist_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 테이블 구조 `in_out`
--

CREATE TABLE `in_out` (
  `io_idx` int(11) NOT NULL,
  `storage_idx` int(11) DEFAULT NULL,
  `t_storage_name` varchar(50) DEFAULT NULL,
  `bill_idx` int(11) DEFAULT NULL,
  `bill_yyyymm` int(11) DEFAULT NULL COMMENT '계산서소속년월_정산종료일이속하는달',
  `bill_num` varchar(100) DEFAULT NULL,
  `bill_yyyymm_except` int(11) DEFAULT NULL,
  `out_order_idx` int(11) DEFAULT NULL,
  `oocp_idx` int(11) DEFAULT NULL,
  `consulting_idx` int(11) DEFAULT NULL,
  `category1_idx` int(11) DEFAULT NULL,
  `client_product_idx` int(11) DEFAULT NULL,
  `product_idx` int(11) DEFAULT NULL,
  `t_product_name` varchar(200) NOT NULL,
  `client_price_sum` int(11) DEFAULT 0 COMMENT '단가(부가세합계)',
  `total_client_price_sum` int(11) DEFAULT 0 COMMENT '상품별합계',
  `part` enum('생산','입고','이동출고','이동입고','출고','수량조정') NOT NULL,
  `io_status` enum('미확인','이동전','이동출고완료','미입고','이동입고완료','조정완료','입고완료','미출고','출고완료','주문취소','출고취소','이동출고취소','배송완료','출고발송완료','출고수령완료') NOT NULL DEFAULT '미확인',
  `from_io_idx` int(11) DEFAULT NULL COMMENT '이동수령완료한 부모idx',
  `in_count` int(11) DEFAULT NULL,
  `zero_count` varchar(10) DEFAULT '0',
  `from_storage_idx` int(11) DEFAULT NULL,
  `t_from_storage_name` varchar(50) DEFAULT NULL,
  `out_count` int(11) DEFAULT NULL,
  `to_storage_idx` int(11) DEFAULT NULL,
  `t_to_storage_name` varchar(50) DEFAULT NULL,
  `delivery_type` enum('택배','직배송') DEFAULT NULL,
  `out_date` date DEFAULT NULL COMMENT '출고,이동출고날짜',
  `receive_date` date DEFAULT NULL COMMENT '이동도착날짜,출고후배송완료날짜',
  `current_count` int(11) DEFAULT NULL,
  `memo` varchar(250) DEFAULT NULL COMMENT '관리자 메모',
  `admin_notice` varchar(500) DEFAULT NULL COMMENT '본사요청사항',
  `head_officer` varchar(50) DEFAULT NULL COMMENT '본사담당자',
  `agency_order_price` int(11) NOT NULL DEFAULT 0 COMMENT '협력사발주금액',
  `move_check` tinyint(1) DEFAULT NULL,
  `regist_datetime` datetime DEFAULT current_timestamp(),
  `write_admin_idx` int(11) DEFAULT NULL,
  `t_write_admin_name` varchar(100) DEFAULT NULL,
  `update_datetime` datetime DEFAULT current_timestamp(),
  `update_admin_idx` int(11) DEFAULT NULL,
  `t_update_admin_name` varchar(30) DEFAULT NULL,
  `status_change_datetime` datetime DEFAULT current_timestamp(),
  `input_datetime` datetime DEFAULT NULL,
  `in_manager_idx` int(11) DEFAULT NULL,
  `t_in_manager_name` varchar(30) DEFAULT NULL,
  `output_datetime` datetime DEFAULT NULL,
  `out_manager_idx` int(11) DEFAULT NULL,
  `t_out_manager_name` varchar(30) DEFAULT NULL,
  `moveout_datetime` datetime DEFAULT NULL,
  `moveout_manager_idx` int(11) DEFAULT NULL,
  `t_moveout_manager_name` varchar(30) DEFAULT NULL,
  `movein_datetime` datetime DEFAULT NULL,
  `movein_manager_idx` int(11) DEFAULT NULL,
  `t_movein_manager_name` varchar(30) DEFAULT NULL,
  `adjust_datetime` datetime DEFAULT NULL,
  `adjust_manager_idx` int(11) DEFAULT NULL,
  `t_adjust_manager_name` varchar(30) DEFAULT NULL,
  `cancel_datetime` datetime DEFAULT NULL,
  `cancel_manager_idx` int(11) DEFAULT NULL,
  `t_cancel_manager_name` varchar(50) DEFAULT NULL,
  `qr_input_datetime` datetime DEFAULT NULL COMMENT 'qr생산입고',
  `qr_output_datetime` datetime DEFAULT NULL COMMENT 'qr출고',
  `qr_moveout_datetime` datetime DEFAULT NULL COMMENT 'qr이동출고',
  `qr_movein_datetime` datetime DEFAULT NULL COMMENT 'qr이동입고'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='입출고내역';

--
-- 트리거 `in_out`
--
DELIMITER $$
CREATE TRIGGER `in_out_insert_before` BEFORE INSERT ON `in_out` FOR EACH ROW BEGIN
/*
set 
NEW.category1_idx=(select category1_idx from product where product_idx=NEW.product_idx),
NEW.t_storage_name=(select storage_name from storage where storage_idx=NEW.storage_idx),
NEW.t_product_name = (select product_name from product where product_idx=NEW.product_idx),
NEW.t_to_storage_name=(select storage_name from storage where storage_idx=NEW.to_storage_idx),
NEW.t_from_storage_name=(select storage_name from storage where storage_idx=NEW.from_storage_idx),

NEW.t_out_manager_name=(select admin_name from admin.admin_list where admin_idx=NEW.out_manager_idx),
NEW.t_in_manager_name=(select admin_name from admin.admin_list where admin_idx=NEW.in_manager_idx),
NEW.t_write_admin_name=(select admin_name from admin.admin_list where admin_idx=NEW.write_admin_idx),
NEW.t_update_admin_name=(select admin_name from admin.admin_list where admin_idx=NEW.update_admin_idx);

if NEW.current_count < 0 THEN
  set NEW.current_count = 0;
end if;

if NEW.io_status='입고완료' THEN
  set NEW.input_datetime=now();
end if;


if NEW.out_order_idx is not null THEN
	set NEW.consulting_idx = (select consulting_idx from out_order where out_order_idx=NEW.out_order_idx);
end if;


*/
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `in_out_update_before1` BEFORE UPDATE ON `in_out` FOR EACH ROW BEGIN
/*

if NEW.storage_idx <> OLD.storage_idx THEN
   set NEW.t_storage_name=(select storage_name from storage where storage_idx=NEW.storage_idx);
end if;
if NEW.product_idx <> OLD.product_idx THEN
   set NEW.t_product_name=(select product_name from product where product_idx=NEW.product_idx);
end if;
if NEW.to_storage_idx <> OLD.to_storage_idx THEN
   set NEW.t_to_storage_name=(select storage_name from storage where storage_idx=NEW.to_storage_idx);
end if;
if NEW.from_storage_idx <> OLD.from_storage_idx THEN
   set NEW.t_from_storage_name=(select storage_name from storage where storage_idx=NEW.from_storage_idx);
end if;


if NEW.out_manager_idx <> OLD.out_manager_idx THEN
   set NEW.t_out_manager_name=(select admin_name from admin.admin_list where admin_idx=NEW.out_manager_idx);
end if;

if NEW.in_manager_idx <> OLD.in_manager_idx THEN
   set NEW.t_in_manager_name=(select admin_name from admin.admin_list where admin_idx=NEW.in_manager_idx);
end if;

if NEW.write_admin_idx <> OLD.write_admin_idx THEN
   set NEW.t_write_admin_name=(select admin_name from admin.admin_list where admin_idx=NEW.write_admin_idx);
end if;

if NEW.update_admin_idx <> OLD.update_admin_idx THEN
   set NEW.t_update_admin_name=(select admin_name from admin.admin_list where admin_idx=NEW.update_admin_idx);
end if;


if NEW.cancel_manager_idx <> OLD.cancel_manager_idx THEN
   set NEW.t_cancel_manager_name=(select admin_name from admin.admin_list where admin_idx=NEW.cancel_manager_idx);
end if;


if NEW.current_count < 0 THEN
  set NEW.current_count = 0;
end if;


if NEW.io_status <> OLD.io_status THEN
   set NEW.status_change_datetime=now();
  
  if NEW.io_status='출고완료' THEN
     set NEW.output_datetime=now(),
     NEW.out_date=date(now());
  elseif NEW.io_status='이동출고완료' THEN
     set NEW.moveout_datetime=now(),NEW.out_date=date(now());
  elseif NEW.io_status='입고완료' THEN
     set NEW.input_datetime=now();
  elseif NEW.io_status='이동입고완료' THEN
     set NEW.movein_datetime=now();
  elseif NEW.io_status='이동출고취소' or NEW.io_status='출고취소' THEN
     set NEW.cancel_datetime=now();
  elseif NEW.io_status='조정완료' THEN
     set NEW.adjust_datetime=now();      
     
  end if;
  
end if;

*/

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- 테이블 구조 `out_order`
--

CREATE TABLE `out_order` (
  `out_order_idx` int(11) NOT NULL,
  `oocp_idx` int(11) DEFAULT NULL,
  `consulting_idx` int(11) DEFAULT NULL,
  `t_company_name` varchar(50) DEFAULT NULL,
  `manager_idx` int(11) DEFAULT NULL,
  `storage_idx` int(11) DEFAULT NULL,
  `t_storage_name` varchar(50) DEFAULT NULL,
  `to_place_name` varchar(100) DEFAULT NULL,
  `to_address` varchar(250) DEFAULT NULL,
  `to_name` varchar(100) DEFAULT NULL,
  `to_hp` varchar(100) DEFAULT NULL,
  `to_phone` varchar(100) DEFAULT NULL,
  `receiver_name` varchar(50) DEFAULT NULL,
  `delivery_memo` varchar(500) DEFAULT NULL,
  `order_date` varchar(50) DEFAULT NULL COMMENT '발주일',
  `total_client_price` int(11) DEFAULT 0,
  `total_client_price_tax` int(11) DEFAULT 0,
  `total_client_price_sum` int(11) DEFAULT 0,
  `admin_idx` int(11) DEFAULT NULL,
  `admin_name` varchar(30) DEFAULT NULL,
  `regist_datetime` datetime DEFAULT current_timestamp(),
  `update_datetime` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='주문출고상세';

--
-- 트리거 `out_order`
--
DELIMITER $$
CREATE TRIGGER `out_order_insert_before` BEFORE INSERT ON `out_order` FOR EACH ROW BEGIN
/*
if NEW.storage_idx is not null then
SET NEW.t_storage_name = (select storage_name from storage where storage_idx=NEW.storage_idx);
end if;

if NEW.consulting_idx is not null THEN
SET NEW.t_company_name = (select company_name from consulting.consulting where consulting_idx=NEW.consulting_idx);
end if;
*/
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `out_order_update_before` BEFORE UPDATE ON `out_order` FOR EACH ROW BEGIN
/*
if NEW.storage_idx is not null then
SET NEW.t_storage_name = (select storage_name from storage where storage_idx=NEW.storage_idx);
end if;

if NEW.consulting_idx is not null THEN
SET NEW.t_company_name = (select company_name from consulting.consulting where consulting_idx=NEW.consulting_idx);
end if;
*/
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- 테이블 구조 `out_order_client_product`
--

CREATE TABLE `out_order_client_product` (
  `oocp_idx` int(11) NOT NULL,
  `flower_out_order_idx` int(11) DEFAULT NULL,
  `sangjo_out_order_idx` int(11) DEFAULT NULL,
  `out_order_idx_deprecated` int(11) DEFAULT NULL,
  `consulting_idx` int(11) NOT NULL,
  `category1_idx` int(11) DEFAULT NULL,
  `t_category1_name` varchar(50) DEFAULT NULL,
  `client_product_idx` int(11) NOT NULL,
  `order_date` date DEFAULT NULL COMMENT '발주일',
  `bill_idx` int(11) DEFAULT NULL,
  `bill_yyyymm` int(11) DEFAULT NULL COMMENT '거래명세서 발행했거나 발행예정월, -1:발행제외',
  `oocp_status` enum('주문접수','출고지시','주문취소') DEFAULT '주문접수',
  `product_name` varchar(100) DEFAULT NULL,
  `client_price` int(11) DEFAULT NULL COMMENT '공급단가',
  `client_price_tax` int(11) NOT NULL DEFAULT 0 COMMENT '부가세',
  `client_price_sum` int(11) NOT NULL DEFAULT 0,
  `order_count` int(11) NOT NULL,
  `total_client_price` int(11) NOT NULL DEFAULT 0,
  `total_client_price_tax` int(11) NOT NULL DEFAULT 0,
  `total_client_price_sum` int(11) NOT NULL DEFAULT 0,
  `unit_price` int(11) DEFAULT NULL,
  `price_calcu` int(11) DEFAULT NULL COMMENT '상품별합계',
  `bigo` varchar(100) DEFAULT NULL COMMENT '거래명세서 비고',
  `admin_idx` int(11) NOT NULL,
  `admin_name` varchar(30) NOT NULL,
  `manager_idx` int(11) DEFAULT NULL,
  `regist_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='고객사별출고상품';

--
-- 트리거 `out_order_client_product`
--
DELIMITER $$
CREATE TRIGGER `oocp_insert_before` BEFORE INSERT ON `out_order_client_product` FOR EACH ROW BEGIN 
/*
set NEW.client_price_sum = NEW.client_price + NEW.client_price_tax;
*/
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `oocp_update_before` BEFORE UPDATE ON `out_order_client_product` FOR EACH ROW BEGIN 
/*
set NEW.client_price_sum = NEW.client_price + NEW.client_price_tax;
*/
END
$$
DELIMITER ;

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
DELIMITER $$
CREATE TRIGGER `product_update_after` AFTER UPDATE ON `product` FOR EACH ROW BEGIN


if OLD.product_name <> NEW.product_name THEN

update in_out set t_product_name=NEW.product_name 
where product_idx=NEW.product_idx;

update out_order_client_product a , client_product b set a.product_name=NEW.product_name
 where a.client_product_idx=b.client_product_idx and b.product_idx=NEW.product_idx;


end if;


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

-- --------------------------------------------------------

--
-- 테이블 구조 `settlement_manager`
--

CREATE TABLE `settlement_manager` (
  `sm_idx` int(11) NOT NULL,
  `manager_idx` int(11) DEFAULT NULL COMMENT 'consulting.manager',
  `category1_idx` int(11) DEFAULT NULL,
  `consulting_idx` int(11) DEFAULT NULL,
  `regist_admin_idx` int(11) DEFAULT NULL,
  `update_admin_idx` int(11) DEFAULT NULL,
  `regist_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 테이블 구조 `settlement_sdate`
--

CREATE TABLE `settlement_sdate` (
  `fs_idx` int(11) NOT NULL,
  `consulting_idx` int(11) DEFAULT NULL,
  `category1_idx` int(11) DEFAULT NULL,
  `settlement_split` enum('통합','개별') DEFAULT '통합',
  `payment_method` enum('카드','계산서','계산서역발행','미지정') DEFAULT '계산서',
  `sdate` varchar(20) DEFAULT NULL COMMENT '정산마감일',
  `regist_admin_idx` int(11) DEFAULT NULL,
  `update_admin_idx` int(11) DEFAULT NULL,
  `regist_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='종합물류 회사별 대카테고리별 정산마감일';

-- --------------------------------------------------------

--
-- 테이블 구조 `statistics_product`
--

CREATE TABLE `statistics_product` (
  `st_idx` int(11) NOT NULL,
  `product_idx` int(11) DEFAULT NULL,
  `t_product_name` varchar(100) DEFAULT NULL,
  `part` varchar(50) DEFAULT NULL,
  `total` int(11) NOT NULL,
  `today` int(11) NOT NULL,
  `yesterday` int(11) NOT NULL,
  `this_month` int(11) NOT NULL,
  `last_month` int(11) NOT NULL,
  `this_year` int(11) NOT NULL,
  `last_year` int(11) NOT NULL,
  `recent_30d` int(11) NOT NULL,
  `avg_30d` int(11) NOT NULL,
  `avg_90d` int(11) NOT NULL,
  `regist_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 테이블 구조 `storage`
--

CREATE TABLE `storage` (
  `storage_idx` int(11) NOT NULL,
  `storage_name` varchar(50) DEFAULT NULL,
  `manager` varchar(50) DEFAULT NULL,
  `hp` varchar(20) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `regist_datetime` datetime DEFAULT current_timestamp(),
  `update_datetime` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='창고관리';

--
-- 트리거 `storage`
--
DELIMITER $$
CREATE TRIGGER `storage_update_after` AFTER UPDATE ON `storage` FOR EACH ROW BEGIN

IF NEW.storage_name <> OLD.storage_name THEN
   update admin.admin_permission set t_storage_super=NEW.storage_name where storage_super=NEW.storage_idx;
   update admin.admin_permission set t_storage_fullfillment=NEW.storage_name where storage_fullfillment=NEW.storage_idx;   
   update admin.admin_permission set t_storage_consulting=NEW.storage_name where storage_consulting=NEW.storage_idx;
   update admin.admin_permission set t_storage_statistics=NEW.storage_name where storage_statistics=NEW.storage_idx;   
   
END IF;


END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- 테이블 구조 `storage_safe`
--

CREATE TABLE `storage_safe` (
  `st_safe_idx` int(11) NOT NULL,
  `storage_idx` int(11) DEFAULT NULL,
  `t_storage_name` varchar(100) DEFAULT NULL,
  `product_idx` int(11) DEFAULT NULL,
  `t_product_name` varchar(100) DEFAULT NULL,
  `current_count` int(11) DEFAULT 0,
  `in_waiting_count` int(11) NOT NULL DEFAULT 0 COMMENT '입고대기수량',
  `safe_count` int(11) DEFAULT 0,
  `regist_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='안전재고관리';

--
-- 트리거 `storage_safe`
--
DELIMITER $$
CREATE TRIGGER `insert_storage_safe_insert_before` BEFORE INSERT ON `storage_safe` FOR EACH ROW BEGIN

set NEW.t_storage_name=(select storage_name from storage where storage_idx=NEW.storage_idx),
NEW.t_product_name = (select product_name from product where product_idx=NEW.product_idx);

if NEW.current_count < 0 THEN
  set NEW.current_count = 0;
end if;

END
$$
DELIMITER ;

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `attachment`
--
ALTER TABLE `attachment`
  ADD PRIMARY KEY (`attachment_idx`);

--
-- 테이블의 인덱스 `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_idx`);

--
-- 테이블의 인덱스 `category1`
--
ALTER TABLE `category1`
  ADD PRIMARY KEY (`category1_idx`);

--
-- 테이블의 인덱스 `category2`
--
ALTER TABLE `category2`
  ADD PRIMARY KEY (`category2_idx`);

--
-- 테이블의 인덱스 `client_product`
--
ALTER TABLE `client_product`
  ADD PRIMARY KEY (`client_product_idx`),
  ADD KEY `client_idx` (`consulting_idx`),
  ADD KEY `client_idx_2` (`consulting_idx`,`display`);

--
-- 테이블의 인덱스 `in_out`
--
ALTER TABLE `in_out`
  ADD PRIMARY KEY (`io_idx`),
  ADD KEY `storage_idx` (`part`,`storage_idx`,`product_idx`) USING BTREE,
  ADD KEY `part_2` (`part`,`from_storage_idx`),
  ADD KEY `part_3` (`part`,`to_storage_idx`),
  ADD KEY `from_io_idx` (`from_io_idx`);

--
-- 테이블의 인덱스 `out_order`
--
ALTER TABLE `out_order`
  ADD PRIMARY KEY (`out_order_idx`),
  ADD KEY `client_idx` (`consulting_idx`);

--
-- 테이블의 인덱스 `out_order_client_product`
--
ALTER TABLE `out_order_client_product`
  ADD PRIMARY KEY (`oocp_idx`),
  ADD KEY `out_order_idx` (`out_order_idx_deprecated`),
  ADD KEY `client_idx` (`consulting_idx`);

--
-- 테이블의 인덱스 `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_idx`),
  ADD KEY `display` (`display`);

--
-- 테이블의 인덱스 `settlement_manager`
--
ALTER TABLE `settlement_manager`
  ADD PRIMARY KEY (`sm_idx`),
  ADD UNIQUE KEY `manager_idx` (`consulting_idx`,`category1_idx`,`manager_idx`) USING BTREE;

--
-- 테이블의 인덱스 `settlement_sdate`
--
ALTER TABLE `settlement_sdate`
  ADD PRIMARY KEY (`fs_idx`);

--
-- 테이블의 인덱스 `statistics_product`
--
ALTER TABLE `statistics_product`
  ADD PRIMARY KEY (`st_idx`);

--
-- 테이블의 인덱스 `storage`
--
ALTER TABLE `storage`
  ADD PRIMARY KEY (`storage_idx`);

--
-- 테이블의 인덱스 `storage_safe`
--
ALTER TABLE `storage_safe`
  ADD PRIMARY KEY (`st_safe_idx`),
  ADD UNIQUE KEY `storage_idx` (`storage_idx`,`product_idx`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `attachment`
--
ALTER TABLE `attachment`
  MODIFY `attachment_idx` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `category`
--
ALTER TABLE `category`
  MODIFY `category_idx` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `category1`
--
ALTER TABLE `category1`
  MODIFY `category1_idx` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `category2`
--
ALTER TABLE `category2`
  MODIFY `category2_idx` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `client_product`
--
ALTER TABLE `client_product`
  MODIFY `client_product_idx` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `in_out`
--
ALTER TABLE `in_out`
  MODIFY `io_idx` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `out_order`
--
ALTER TABLE `out_order`
  MODIFY `out_order_idx` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `out_order_client_product`
--
ALTER TABLE `out_order_client_product`
  MODIFY `oocp_idx` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `product`
--
ALTER TABLE `product`
  MODIFY `product_idx` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `settlement_manager`
--
ALTER TABLE `settlement_manager`
  MODIFY `sm_idx` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `settlement_sdate`
--
ALTER TABLE `settlement_sdate`
  MODIFY `fs_idx` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `statistics_product`
--
ALTER TABLE `statistics_product`
  MODIFY `st_idx` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `storage`
--
ALTER TABLE `storage`
  MODIFY `storage_idx` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `storage_safe`
--
ALTER TABLE `storage_safe`
  MODIFY `st_safe_idx` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
