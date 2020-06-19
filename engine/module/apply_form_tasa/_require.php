<?php
//require_once _PATH_lib_.'/page.f.php';
/*

CREATE TABLE `jin_user_form_gallery` (
	`form_idx` INT(11) NOT NULL AUTO_INCREMENT,
	`reg_type` VARCHAR(100) NULL DEFAULT NULL COMMENT '신청,어드민',
	`reg_admin_id` VARCHAR(50) NULL DEFAULT 'site',
	`is_use` CHAR(1) NULL DEFAULT NULL COMMENT 'Y,N,....',
	`apply_type` VARCHAR(200) NULL DEFAULT NULL,
	`apply_user_type` VARCHAR(200) NULL DEFAULT NULL,
	`user_name` VARCHAR(200) NULL DEFAULT NULL,
	`apply_phone` VARCHAR(200) NULL DEFAULT NULL,
	`field_car` VARCHAR(200) NULL DEFAULT NULL,
	`field_car_engine` VARCHAR(200) NULL DEFAULT NULL,
	`field_hope_range` VARCHAR(200) NULL DEFAULT NULL,
	`loan_user_name` VARCHAR(50) NULL DEFAULT NULL COMMENT '고객명',
	`apply_phone1` VARCHAR(20) NULL DEFAULT NULL COMMENT '고객전화번호',
	`apply_phone2` VARCHAR(20) NULL DEFAULT NULL,
	`apply_phone3` VARCHAR(20) NULL DEFAULT NULL,
	`loan_passwd_crypt` VARCHAR(200) NULL DEFAULT NULL COMMENT '비밀번호',
	`loan_gender` CHAR(1) NOT NULL DEFAULT '' COMMENT '성별',
	`loan_title` VARCHAR(200) NULL DEFAULT NULL COMMENT '제목',
	`loan_content` VARCHAR(2000) NULL DEFAULT NULL COMMENT '내용',
	`loan_area` VARCHAR(200) NULL DEFAULT NULL COMMENT '지역',
	`loan_job` VARCHAR(200) NULL DEFAULT NULL COMMENT '직업',
	`loan_age` VARCHAR(200) NULL DEFAULT NULL COMMENT '나이',
	`loan_amount` VARCHAR(200) NULL DEFAULT NULL COMMENT '희망금액',
	`loan_type` VARCHAR(200) NULL DEFAULT NULL COMMENT '대출구분',
	`read_cnt` INT(11) NULL DEFAULT 0,
	`reg_ip` VARCHAR(30) NULL DEFAULT NULL,
	`reg_admin_login_info` VARCHAR(30) NULL DEFAULT NULL,
	`reg_date` DATETIME NULL DEFAULT NULL,
	`update_admin_login_info` DATETIME NULL DEFAULT NULL,
	`update_date` TIMESTAMP NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
	`send_date` DATETIME NULL DEFAULT NULL,
	`user_idx` INT(11) NULL DEFAULT NULL,
	`car_seq` INT(11) NULL DEFAULT NULL,
	`car_name` VARCHAR(100) NULL DEFAULT NULL,
	PRIMARY KEY (`form_idx`),
	INDEX `media_seq` (`media`),
	INDEX `user_name` (`loan_user_name`),
	INDEX `loan_phone3` (`apply_phone3`) USING BTREE
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=50
;

 *  */