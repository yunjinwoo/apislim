-- @file 
-- 파일관리
CREATE  TABLE IF NOT EXISTS `my`.`file_manager` (
  `file_idx` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `session_key` VARCHAR(60) NOT NULL ,
  `file_domain` VARCHAR(60) NULL ,
  `file_path` VARCHAR(200) NOT NULL ,
  `file_alt` VARCHAR(200) NULL ,
  `file_name` VARCHAR(100) NOT NULL ,
  `file_size` INT UNSIGNED NOT NULL ,
  `file_type` VARCHAR(45) NOT NULL ,
  `reg_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  PRIMARY KEY (`file_idx`) ,
  INDEX `index_table` (`session_key` ASC) )
ENGINE = InnoDB
-- @fileEnd

-- @admin
-- 관리자 정보 리스트
CREATE  TABLE IF NOT EXISTS `my`.`admin_member` (
  `admin_idx` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '관리자 번호' ,
  `admin_id` VARCHAR(45) NOT NULL COMMENT '관리자 아이디' ,
  `admin_pw` VARCHAR(60) NOT NULL COMMENT '관리자 비밀번호' ,
  `admin_name` VARCHAR(45) NOT NULL COMMENT '관리자 이름' ,
  `admin_phone` VARCHAR(20) NULL COMMENT '관리자 전화번호' ,
  `admin_level` INT UNSIGNED NULL COMMENT '관리자 레벨' ,
  `admin_owner` INT UNSIGNED NULL COMMENT '관리자 권한 - 임시로 만듬' ,
  `reg_date` DATETIME NOT NULL ,
  PRIMARY KEY (`admin_idx`) ,
  UNIQUE INDEX `admin_id_UNIQUE` (`admin_id` ASC) )
ENGINE = InnoDB

-- @adminEnd

-- @access_ip
-- 접근 ip 테이블
CREATE  TABLE IF NOT EXISTS `my`.`access_ip` (
  `ip` VARCHAR(40) NOT NULL ,
  `ip_info` VARCHAR(100) NULL ,
  `reg_date` DATETIME NULL ,
  PRIMARY KEY (`ip`) )
ENGINE = InnoDB
-- @access_ipEnd

-- @category
-- 최상위 카테고리 관리 
CREATE  TABLE IF NOT EXISTS `my`.`category` (
  `category_idx` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `category_type` VARCHAR(45) NOT NULL ,
  `category_name` VARCHAR(45) NOT NULL ,
  `reg_date` DATETIME NOT NULL ,
  PRIMARY KEY (`category_idx`) ,
  INDEX `un_category_type` (`category_type` ASC) )
ENGINE = InnoDB
-- @categoryEnd

-- @cate_prod
-- 상품 카테고리 관리 
CREATE  TABLE IF NOT EXISTS my.`my_category_product` (
  `cate_proc_idx` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `category_idx` INT UNSIGNED NOT NULL ,
  `cate_proc_parent_idx` INT UNSIGNED NOT NULL ,
  `cate_proc_name` VARCHAR(45) NOT NULL ,
  `use_y_n` ENUM('Y','N') NOT NULL ,
  `reg_date` DATETIME NOT NULL ,
  PRIMARY KEY (`cate_proc_idx`) ,
  INDEX `code` (`cate_proc_parent_idx` ASC) ,
  INDEX `category_idx_UNIQUE` (`category_idx` ASC) )
ENGINE = InnoDB
-- @cate_prodEnd
-- FOREIGN KEY 사용시 테이블 명 차이로 오류를 발생한다. 따로 날린다.
-- 사용은 안함 위에것을 사용
CREATE  TABLE IF NOT EXISTS `my`.`category_product` (
  `cate_proc_idx` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `category_idx` INT UNSIGNED NOT NULL COMMENT '최상위 카테고리 번호' ,
  `cate_proc_parent_idx` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '서브 메뉴용 자기자신의 키를 가진다.' ,
  `cate_proc_name` VARCHAR(45) NOT NULL ,
  `use_y_n` ENUM('Y','N') NOT NULL ,
  `reg_date` DATETIME NOT NULL ,
  PRIMARY KEY (`cate_proc_idx`) ,
  INDEX `code` (`cate_proc_parent_idx` ASC) ,
  UNIQUE INDEX `category_idx_UNIQUE` (`category_idx` ASC) ,
  CONSTRAINT `fk_category_product_category`
    FOREIGN KEY (`category_idx` )
    REFERENCES `my`.`category` (`category_idx` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB


-- @product_menu
-- 상품 카테고리 관리 
CREATE  TABLE IF NOT EXISTS `my`.`product_menu` (
  `menu_idx` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `menu_group` INT NOT NULL DEFAULT 0 ,
  `menu_sort` INT NOT NULL DEFAULT 0 ,
  `menu_sub_group` INT NOT NULL DEFAULT 0 ,
  `cate_proc_idx` INT NOT NULL DEFAULT 0 ,
  `menu_name` VARCHAR(45) NOT NULL ,
  `use_y_n` ENUM('Y','N') NOT NULL DEFAULT 'Y' ,
  UNIQUE INDEX `group` (`menu_group` ASC, `menu_sort` ASC, `menu_sub_group` ASC) ,
  INDEX `cate_proc_idx` (`cate_proc_idx` ASC) ,
  PRIMARY KEY (`menu_idx`) )
ENGINE = InnoDB
-- @product_menuEnd

-- @banner
-- 각종 베너 관리 
CREATE  TABLE IF NOT EXISTS `my`.`banner_manager` (
  `banner_idx` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `banner_type` VARCHAR(30) NOT NULL ,
  `banner_sort` INT NOT NULL DEFAULT 0 ,
  `banner_src` VARCHAR(200) NOT NULL ,
  `banner_alt` VARCHAR(200) NULL ,
  `banner_link` VARCHAR(255) NULL ,
  `link_type` VARCHAR(10) NOT NULL DEFAULT 'image' ,
  `reg_date` DATETIME NOT NULL ,
  PRIMARY KEY (`banner_idx`) ,
  INDEX `banner_type` (`banner_type` ASC) )
ENGINE = InnoDB
-- @bannerEnd

-- @board
-- 기본게시판 형태
CREATE  TABLE IF NOT EXISTS `my`.`board_default` (
  `board_idx` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `board_title` VARCHAR(200) NOT NULL ,
  `board_text` TEXT NOT NULL ,
  `reg_date` DATETIME NOT NULL ,
  `read_cnt` INT UNSIGNED NOT NULL DEFAULT 0 ,
  `is_notice` ENUM('Y','N') NOT NULL DEFAULT 'N' ,
  `board_date` CHAR(19) NOT NULL COMMENT '날짜 sorting 용',
  `board_type` CHAR(1) NOT NULL DEFAULT '' COMMENT 'testimonial 용',
  PRIMARY KEY (`board_idx`) )
ENGINE = InnoDB
-- @boardEnd
-- 에디터용 필드
ALTER TABLE `my`.`board_default` ADD COLUMN `editor_session_key` VARCHAR(60) NULL AFTER `board_date`;
-- 사용자용 필드
ALTER TABLE `my`.`board_default` ADD COLUMN `board_type` VARCHAR(50) NULL AFTER `board_date`;
ALTER TABLE `my`.`board_default` ADD COLUMN `board_youtube_url` VARCHAR(200) NULL AFTER `board_date`;


-- @board_file
-- 게시판 파일관리 테이블
CREATE  TABLE IF NOT EXISTS `my`.`board_files` (
	`file_idx` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`board_name` VARCHAR(45) NOT NULL,
	`board_idx` INT(11) NOT NULL,
	`board_sub_name` VARCHAR(50) NULL DEFAULT NULL,
	`file_path` VARCHAR(200) NOT NULL,
	`file_name` VARCHAR(50) NULL DEFAULT NULL,
	`file_alt` VARCHAR(200) NOT NULL,
	`file_upload_name` VARCHAR(100) NOT NULL,
	`file_size` INT(11) NOT NULL,
	`file_type` VARCHAR(45) NOT NULL,
	`reg_date` DATETIME NOT NULL,
	PRIMARY KEY (`file_idx`),
	INDEX `board_find` (`board_name`, `board_idx`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
-- @board_fileEnd


-- @product_item
-- 상품정보 테이블
CREATE  TABLE IF NOT EXISTS `my`.`product_item` (
  `item_idx` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `product_menu_idx` INT UNSIGNED NOT NULL ,
  `overview_img` VARCHAR(200) NULL ,
  `overview_alt` VARCHAR(200) NULL ,
  `overview_foot_img` VARCHAR(200) NULL ,
  `overview_foot_alt` VARCHAR(200) NULL ,
  `item_img` VARCHAR(200) NULL ,
  `item_alt` VARCHAR(200) NULL ,
  `item_over_img` VARCHAR(200) NULL ,
  `item_over_alt` VARCHAR(200) NULL ,
  `highlight_img_1` VARCHAR(200) NULL ,
  `highlight_alt_1` VARCHAR(200) NULL ,
  `highlight_img_2` VARCHAR(200) NULL ,
  `highlight_alt_2` VARCHAR(200) NULL ,
  `highlight_img_3` VARCHAR(200) NULL ,
  `highlight_alt_3` VARCHAR(300) NULL ,
  `highlight_img_4` VARCHAR(200) NULL ,
  `highlight_alt_4` VARCHAR(200) NULL ,
  `icon_index` TEXT NULL ,
  `tab_use_bit` INT NULL ,
  `tab_content_1` TEXT NULL ,
  `tab_content_2` TEXT NULL ,
  `tab_content_4` TEXT NULL ,
  `tab_content_8` TEXT NULL ,
  `tab_content_16` TEXT NULL ,
  `reg_date` DATETIME NULL ,
  PRIMARY KEY (`item_idx`) ,
  INDEX `product_menu_idx` (`product_menu_idx` ASC) )
ENGINE = InnoDB
-- @product_itemEnd

-- @contact_us
-- 그냥 폼...
CREATE TABLE `my_contact_us` (
	`us_idx` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`customer_bit` INT(11) NULL DEFAULT NULL,
	`country` VARCHAR(100) NULL DEFAULT NULL,
	`name` VARCHAR(200) NULL DEFAULT NULL,
	`phone` VARCHAR(200) NULL DEFAULT NULL,
	`email` VARCHAR(200) NULL DEFAULT NULL,
	`purpose_bit` INT(11) NULL DEFAULT NULL,
	`product_menu_idx` INT(11) NULL DEFAULT NULL,
	`title` VARCHAR(200) NULL DEFAULT NULL,
	`text` TEXT NULL,
	`reg_date` DATETIME NULL DEFAULT NULL,
	`file_path` VARCHAR(200) NULL DEFAULT NULL,
	`file_name` VARCHAR(200) NULL DEFAULT NULL,
	PRIMARY KEY (`us_idx`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
-- @contact_usEnd