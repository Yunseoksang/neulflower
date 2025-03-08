ALTER TABLE flower.out_order ADD COLUMN move_check tinyint(1) DEFAULT NULL COMMENT '이동지시서 동시작성' AFTER admin_memo;
