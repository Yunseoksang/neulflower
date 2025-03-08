-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: alwaysflower.sldb.iwinv.net
-- 생성 시간: 25-03-07 17:44
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
-- 데이터베이스: `flower`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `addr`
--

CREATE TABLE `addr` (
  `addr_idx` int(11) NOT NULL,
  `dong_code` varchar(20) DEFAULT NULL,
  `sido` varchar(30) DEFAULT NULL,
  `sigungu` varchar(30) DEFAULT NULL,
  `dong` varchar(30) DEFAULT NULL,
  `create_date` varchar(30) DEFAULT NULL,
  `del_date` varchar(30) DEFAULT NULL,
  `regist_datetime` datetime DEFAULT NULL,
  `update_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 테이블 구조 `attachment`
--

CREATE TABLE `attachment` (
  `attachment_idx` int(11) NOT NULL,
  `out_order_idx` int(11) DEFAULT NULL,
  `attachType` enum('attachment','photo') DEFAULT 'attachment',
  `filename` varchar(200) DEFAULT NULL,
  `regist_datetime` datetime DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_idx` int(11) DEFAULT NULL,
  `admin_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='첨부파일관리';

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

-- --------------------------------------------------------

--
-- 테이블 구조 `client_flower_sender`
--

CREATE TABLE `client_flower_sender` (
  `client_flower_sender_idx` int(11) NOT NULL,
  `consulting_idx` int(11) NOT NULL,
  `sender_name` varchar(100) DEFAULT NULL,
  `admin_idx` int(11) DEFAULT NULL,
  `admin_name` varchar(30) DEFAULT NULL,
  `regist_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='화훼 보내는 분';

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
  `out_order_idx` int(11) DEFAULT NULL,
  `client_product_idx` int(11) DEFAULT NULL,
  `product_idx` int(11) DEFAULT NULL,
  `t_product_name` varchar(200) NOT NULL,
  `unit_price` int(11) DEFAULT NULL COMMENT '단가(부가세합계)',
  `price_sum` int(11) DEFAULT NULL COMMENT '상품별합계',
  `part` enum('출고') NOT NULL DEFAULT '출고',
  `io_status` enum('미확인','본부접수','주문접수','배송중','배송완료','주문취소') NOT NULL DEFAULT '미확인' COMMENT '발송완료시 발송창고 재고 차감,수령완료시 수령창고 재고추가',
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
  `receive_date` date DEFAULT NULL COMMENT '이동도착날짜',
  `admin_memo` varchar(250) DEFAULT NULL COMMENT '관리자 메모',
  `move_check` tinyint(1) DEFAULT NULL COMMENT '이동지시서 동시작성',
  `admin_notice` varchar(500) DEFAULT NULL COMMENT '본사요청사항',
  `head_officer` varchar(50) DEFAULT NULL COMMENT '본사담당자',
  `agency_order_price` int(11) NOT NULL DEFAULT 0 COMMENT '협력사발주금액',
  `write_admin_idx` int(11) DEFAULT NULL,
  `t_write_admin_name` varchar(100) DEFAULT NULL,
  `in_manager_idx` int(11) DEFAULT NULL,
  `t_in_manager_name` varchar(30) DEFAULT NULL,
  `out_manager_idx` int(11) DEFAULT NULL,
  `t_out_manager_name` varchar(30) DEFAULT NULL,
  `update_admin_idx` int(11) DEFAULT NULL,
  `t_update_admin_name` varchar(30) DEFAULT NULL,
  `cancel_manager_idx` int(11) DEFAULT NULL,
  `t_cancel_manager_name` varchar(50) DEFAULT NULL,
  `regist_datetime` datetime DEFAULT current_timestamp(),
  `update_datetime` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status_change_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `input_datetime` datetime DEFAULT NULL,
  `output_datetime` datetime DEFAULT NULL,
  `moveout_datetime` datetime DEFAULT NULL,
  `movein_datetime` datetime DEFAULT NULL,
  `adjust_datetime` datetime DEFAULT NULL,
  `cancel_datetime` datetime DEFAULT NULL,
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

set NEW.t_storage_name=(select storage_name from storage where storage_idx=NEW.storage_idx),
NEW.t_product_name = (select product_name from product where product_idx=NEW.product_idx),
NEW.t_to_storage_name=(select storage_name from storage where storage_idx=NEW.to_storage_idx),
NEW.t_from_storage_name=(select storage_name from storage where storage_idx=NEW.from_storage_idx),

NEW.t_out_manager_name=(select admin_name from admin.admin_list where admin_idx=NEW.out_manager_idx),
NEW.t_in_manager_name=(select admin_name from admin.admin_list where admin_idx=NEW.in_manager_idx),
NEW.t_write_admin_name=(select admin_name from admin.admin_list where admin_idx=NEW.write_admin_idx),
NEW.t_update_admin_name=(select admin_name from admin.admin_list where admin_idx=NEW.update_admin_idx);




END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `in_out_update_before` BEFORE UPDATE ON `in_out` FOR EACH ROW BEGIN


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


if NEW.io_status <> OLD.io_status THEN
   set NEW.status_change_datetime=now();
   
  if NEW.io_status='배송완료' THEN
     set NEW.output_datetime=now();
  elseif NEW.io_status='출고취소' THEN
     set NEW.cancel_datetime=now();
  end if;
  
end if;


END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- 테이블 구조 `out_order`
--

CREATE TABLE `out_order` (
  `out_order_idx` int(11) NOT NULL,
  `consulting_idx` int(11) NOT NULL,
  `t_company_name` varchar(50) DEFAULT NULL,
  `manager_idx` int(11) DEFAULT NULL,
  `storage_idx` int(11) DEFAULT NULL,
  `t_storage_name` varchar(50) DEFAULT NULL,
  `out_order_part` enum('상조','화훼') DEFAULT NULL,
  `out_order_status` enum('접수대기','배송요청','본부접수','주문접수','배송중','배송완료','주문취소') NOT NULL DEFAULT '접수대기',
  `order_product_title` varchar(100) DEFAULT NULL COMMENT '구매한 대표상품 명',
  `total_order_kinds` int(11) NOT NULL DEFAULT 0 COMMENT '주문품목수',
  `total_order_count` int(11) NOT NULL DEFAULT 0 COMMENT '주문통합수량',
  `order_date` varchar(20) DEFAULT NULL COMMENT '주문일',
  `order_name` varchar(50) DEFAULT NULL COMMENT '주문고객명',
  `order_tel` varchar(50) DEFAULT NULL COMMENT '주문고객전화',
  `order_company_tel` varchar(50) DEFAULT NULL COMMENT '주문부서/사번',
  `r_name` varchar(50) DEFAULT NULL COMMENT '받는고객명',
  `r_tel` varchar(50) DEFAULT NULL COMMENT '받는고객전화',
  `r_company_tel` varchar(50) DEFAULT NULL COMMENT '받는부서/사번',
  `r_date` date DEFAULT NULL COMMENT '배달날짜',
  `r_date_weekday` varchar(30) DEFAULT NULL COMMENT '배달요청날짜(요일)',
  `r_hour` varchar(100) DEFAULT NULL COMMENT '배달시간',
  `local_area` enum('서울','경기','인천','강원','충북','세종','충남','대전','경북','대구','울산','부산','경남','전북','전남','광주','제주','대기') DEFAULT '대기' COMMENT '지역',
  `address1` varchar(500) DEFAULT NULL COMMENT '주소1',
  `address2` varchar(500) DEFAULT NULL COMMENT '주소2',
  `zipNo` varchar(10) DEFAULT NULL COMMENT '우편번호',
  `messageType` enum('messageCard','messageRibbon') DEFAULT NULL COMMENT '메세지종류:카드만,리본만',
  `eType` varchar(100) DEFAULT NULL COMMENT '경조유형',
  `msgTitle` varchar(100) DEFAULT NULL COMMENT '경조사어1',
  `msgTitle2` varchar(100) DEFAULT NULL COMMENT '경조사어2',
  `msgTitle3` varchar(100) DEFAULT NULL COMMENT '경조사어3',
  `sender_name` varchar(50) DEFAULT NULL COMMENT '보내는분(표기용)',
  `delivery_memo` varchar(500) DEFAULT NULL COMMENT '주문자 요청사항',
  `paymentType` enum('paymentCard','paymentBill') DEFAULT NULL COMMENT '결제선택:월말(계산서),카드',
  `receiver_name` varchar(50) DEFAULT NULL COMMENT '인수자',
  `received_time` varchar(50) DEFAULT NULL COMMENT '인수시간',
  `total_client_price` int(11) DEFAULT 0,
  `total_client_price_tax` int(11) DEFAULT 0,
  `total_client_price_sum` int(11) DEFAULT 0,
  `admin_memo` text DEFAULT NULL COMMENT '관리자메모',
  `move_check` tinyint(1) DEFAULT NULL COMMENT '이동지시서 동시작성',
  `admin_notice` text DEFAULT NULL COMMENT '본사요청사항',
  `head_officer` varchar(50) DEFAULT NULL COMMENT '본사담당자',
  `agency_order_price` int(11) NOT NULL DEFAULT 0 COMMENT '협력사발주금액',
  `agency_order_price_tax` int(11) NOT NULL DEFAULT 0 COMMENT '협력사 부가세',
  `branch_storage_idx` int(11) DEFAULT NULL COMMENT '협력사 storage_idx',
  `t_branch_name` varchar(50) DEFAULT NULL COMMENT '협력사이름',
  `branch_price` int(11) DEFAULT 0 COMMENT '협력사발주금액',
  `sms_sent` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0:미발송, 1:발송',
  `admin_idx` int(11) DEFAULT NULL,
  `admin_name` varchar(30) DEFAULT NULL,
  `regist_datetime` datetime DEFAULT current_timestamp(),
  `update_datetime` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ask_datetime` datetime DEFAULT NULL COMMENT '배송요청일',
  `ask_admin_idx` int(11) DEFAULT NULL,
  `ask_admin_name` varchar(50) DEFAULT NULL,
  `confirm_datetime` datetime DEFAULT NULL COMMENT '본부접수일/주문접수일',
  `confirm_admin_idx` int(11) DEFAULT NULL,
  `confirm_admin_name` varchar(50) DEFAULT NULL,
  `going_datetime` datetime DEFAULT NULL COMMENT '배송중',
  `going_admin_idx` int(11) DEFAULT NULL,
  `going_admin_name` varchar(50) DEFAULT NULL,
  `complete_datetime` datetime DEFAULT NULL COMMENT '배송완료',
  `complete_admin_idx` int(11) DEFAULT NULL,
  `complete_admin_name` varchar(50) DEFAULT NULL,
  `cancel_datetime` datetime DEFAULT NULL COMMENT '주문취소일',
  `cancel_admin_idx` int(11) DEFAULT NULL,
  `cancel_admin_name` varchar(50) DEFAULT NULL,
  `return_datetime` datetime DEFAULT NULL COMMENT '지점(협력사)반려일',
  `return_admin_idx` int(11) DEFAULT NULL,
  `return_admin_name` varchar(50) DEFAULT NULL,
  `return_storage_name` varchar(50) DEFAULT NULL COMMENT '반려지점'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci COMMENT='주문출고상세';

--
-- 트리거 `out_order`
--
DELIMITER $$
CREATE TRIGGER `out_order_insert_before` BEFORE INSERT ON `out_order` FOR EACH ROW BEGIN

if NEW.storage_idx is not null then
SET NEW.t_storage_name = (select storage_name from storage where storage_idx=NEW.storage_idx);
end if;

if NEW.branch_storage_idx is not null then
SET NEW.t_branch_name = (select storage_name from storage where storage_idx=NEW.branch_storage_idx);
end if;

if NEW.consulting_idx is not null THEN
SET NEW.t_company_name = (select company_name from consulting.consulting where consulting_idx=NEW.consulting_idx);
end if;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `out_order_update_before` BEFORE UPDATE ON `out_order` FOR EACH ROW BEGIN

if NEW.storage_idx is not null then
SET NEW.t_storage_name = (select storage_name from storage where storage_idx=NEW.storage_idx);
end if;

if NEW.branch_storage_idx is not null then
SET NEW.t_branch_name = (select storage_name from storage where storage_idx=NEW.branch_storage_idx);
end if;

if NEW.consulting_idx is not null THEN
SET NEW.t_company_name = (select company_name from consulting.consulting where consulting_idx=NEW.consulting_idx);
end if;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- 테이블 구조 `out_order_client_product`
--

CREATE TABLE `out_order_client_product` (
  `oocp_idx` int(11) NOT NULL,
  `out_order_idx` int(11) NOT NULL,
  `consulting_idx` int(11) NOT NULL,
  `client_product_idx` int(11) DEFAULT NULL,
  `product_idx` int(11) DEFAULT NULL COMMENT 'product_idx 는 client_product_idx 값이 없을때 필수',
  `product_name` varchar(100) DEFAULT NULL,
  `option_name` varchar(200) DEFAULT NULL,
  `client_price` int(11) DEFAULT 0 COMMENT '공급단가',
  `client_price_tax` int(11) NOT NULL DEFAULT 0 COMMENT '부가세',
  `client_price_sum` int(11) NOT NULL DEFAULT 0 COMMENT '가격(공급단가+부가세)',
  `order_count` int(11) NOT NULL,
  `total_client_price` int(11) NOT NULL DEFAULT 0 COMMENT '공급가',
  `total_client_price_tax` int(11) NOT NULL DEFAULT 0 COMMENT '부가세',
  `total_client_price_sum` int(11) DEFAULT 0 COMMENT '상품별합계',
  `admin_idx` int(11) NOT NULL,
  `admin_name` varchar(30) NOT NULL,
  `regist_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='고객사별출고상품';

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
  `product_price` int(11) DEFAULT 0,
  `option_price` int(11) DEFAULT NULL,
  `is_vat` int(11) NOT NULL DEFAULT 0 COMMENT 'vat 포함여부 , 0: vat없음. 1: vat 포함됨',
  `delivery_unit` varchar(50) DEFAULT NULL COMMENT '배송단위',
  `delivery_fee` varchar(150) DEFAULT NULL COMMENT '배송비',
  `memo` text DEFAULT NULL,
  `options` text DEFAULT NULL,
  `options_count` int(11) NOT NULL DEFAULT 0,
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

select t_category1_name,category2_name into CN1,CN2 from category2 where category2_idx=NEW.category2_idx;

set 
NEW.t_category1_name=CN1,
NEW.t_category2_name=CN2,
NEW.t_base_storage_name = (select storage_name from storage where storage_idx=NEW.base_storage_idx);

if NEW.options is not null and NEW.options != '' THEN
   set NEW.options=TRIM(BOTH '/' FROM NEW.options),
    NEW.options_count = (LENGTH(NEW.options) - LENGTH(REPLACE(NEW.options, '/', '')))+1;
end if;


END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `product_update_before` BEFORE UPDATE ON `product` FOR EACH ROW BEGIN

declare CN1 varchar(50);
declare CN2 varchar(50);

select t_category1_name,category2_name into CN1,CN2 from category2 where category2_idx=NEW.category2_idx;

set 
NEW.t_category1_name=CN1,
NEW.t_category2_name=CN2,
NEW.t_base_storage_name = (select storage_name from storage where storage_idx=NEW.base_storage_idx);

if NEW.options is not null and NEW.options != '' THEN
   set NEW.options=TRIM(BOTH '/' FROM NEW.options),
    NEW.options_count = (LENGTH(NEW.options) - LENGTH(REPLACE(NEW.options, '/', '')))+1;
end if;


END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- 테이블 구조 `sms`
--

CREATE TABLE `sms` (
  `sms_idx` int(11) NOT NULL,
  `out_order_idx` int(11) DEFAULT NULL,
  `template_idx` int(11) DEFAULT NULL,
  `sms_subject` varchar(50) DEFAULT NULL COMMENT '제목',
  `sms_content` varchar(500) DEFAULT NULL COMMENT '메세지 본문',
  `sender_tel` varchar(50) DEFAULT NULL COMMENT '발신자 번호',
  `receiver_tel` varchar(50) DEFAULT NULL COMMENT '수신자 번호',
  `pic1` varchar(100) DEFAULT NULL,
  `pic2` varchar(100) DEFAULT NULL,
  `pic3` varchar(100) DEFAULT NULL,
  `regist_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_idx` int(11) DEFAULT NULL,
  `admin_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 테이블 구조 `sms_template`
--

CREATE TABLE `sms_template` (
  `template_idx` int(11) NOT NULL,
  `template_title` varchar(50) DEFAULT NULL COMMENT '템플릿타이틀',
  `template_subject` varchar(50) DEFAULT NULL COMMENT '제목',
  `template_content` varchar(500) DEFAULT NULL COMMENT '템플릿내용',
  `default_selected` tinyint(4) NOT NULL DEFAULT 0 COMMENT '기본선택 템플릿 1',
  `display` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1:사용, 0:삭제',
  `regist_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `update_datetime` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_idx` int(11) DEFAULT NULL,
  `admin_name` varchar(50) DEFAULT NULL,
  `up_admin_idx` int(11) DEFAULT NULL,
  `up_admin_name` varchar(50) DEFAULT NULL,
  `del_admin_idx` int(11) DEFAULT NULL,
  `del_admin_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='sms 템플릿';

-- --------------------------------------------------------

--
-- 테이블 구조 `storage`
--

CREATE TABLE `storage` (
  `storage_idx` int(11) NOT NULL,
  `storage_type` enum('지점','협력사') DEFAULT NULL,
  `storage_name` varchar(50) DEFAULT NULL,
  `manager` varchar(50) DEFAULT NULL,
  `manager_id` varchar(50) DEFAULT NULL,
  `manager_pw` varchar(100) DEFAULT NULL,
  `hp` varchar(20) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `company_name` varchar(50) DEFAULT NULL,
  `biz_num` varchar(20) DEFAULT NULL,
  `ceo_name` varchar(50) DEFAULT NULL,
  `biz_part` varchar(50) DEFAULT NULL,
  `biz_type` varchar(50) DEFAULT NULL,
  `biz_email` varchar(50) DEFAULT NULL,
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
-- 테이블의 인덱스 `addr`
--
ALTER TABLE `addr`
  ADD PRIMARY KEY (`addr_idx`);

--
-- 테이블의 인덱스 `attachment`
--
ALTER TABLE `attachment`
  ADD PRIMARY KEY (`attachment_idx`);

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
-- 테이블의 인덱스 `client_flower_sender`
--
ALTER TABLE `client_flower_sender`
  ADD PRIMARY KEY (`client_flower_sender_idx`),
  ADD UNIQUE KEY `consulting_idx` (`consulting_idx`,`sender_name`);

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
  ADD KEY `client_idx` (`consulting_idx`),
  ADD KEY `out_order_status` (`out_order_status`);

--
-- 테이블의 인덱스 `out_order_client_product`
--
ALTER TABLE `out_order_client_product`
  ADD PRIMARY KEY (`oocp_idx`),
  ADD KEY `out_order_idx` (`out_order_idx`),
  ADD KEY `client_idx` (`consulting_idx`);

--
-- 테이블의 인덱스 `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_idx`),
  ADD KEY `display` (`display`);

--
-- 테이블의 인덱스 `sms`
--
ALTER TABLE `sms`
  ADD PRIMARY KEY (`sms_idx`),
  ADD KEY `order_idx` (`out_order_idx`),
  ADD KEY `out_order_idx` (`out_order_idx`),
  ADD KEY `receiver_tel` (`receiver_tel`);

--
-- 테이블의 인덱스 `sms_template`
--
ALTER TABLE `sms_template`
  ADD PRIMARY KEY (`template_idx`);

--
-- 테이블의 인덱스 `storage`
--
ALTER TABLE `storage`
  ADD PRIMARY KEY (`storage_idx`),
  ADD KEY `storage_type` (`storage_type`),
  ADD KEY `storage_name` (`storage_name`);

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
-- 테이블의 AUTO_INCREMENT `addr`
--
ALTER TABLE `addr`
  MODIFY `addr_idx` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `attachment`
--
ALTER TABLE `attachment`
  MODIFY `attachment_idx` int(11) NOT NULL AUTO_INCREMENT;

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
-- 테이블의 AUTO_INCREMENT `client_flower_sender`
--
ALTER TABLE `client_flower_sender`
  MODIFY `client_flower_sender_idx` int(11) NOT NULL AUTO_INCREMENT;

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
-- 테이블의 AUTO_INCREMENT `sms`
--
ALTER TABLE `sms`
  MODIFY `sms_idx` int(11) NOT NULL AUTO_INCREMENT;

--
-- 테이블의 AUTO_INCREMENT `sms_template`
--
ALTER TABLE `sms_template`
  MODIFY `template_idx` int(11) NOT NULL AUTO_INCREMENT;

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
