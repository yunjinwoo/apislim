<?php
require_once _PATH_lib_.'/dbConnector.c.php';

define('_db_file_', _db_fix_.'file_manager') ; // db table prefix
//define('_db_admin_', _db_fix_.'admin_member') ;
define('_db_category_product_', _db_fix_.'category_product') ; 
define('_db_product_menu_', _db_fix_.'product_menu') ; 
define('_db_banner_', _db_fix_.'banner_manager') ; 
define('_db_board_', _db_fix_.'board_default') ; 
define('_db_board_file_', _db_fix_.'board_file') ; 
define('_db_product_item_', _db_fix_.'product_item') ;  

/* create.sql 에 없는 테이블들.... */
define('_db_page_make_', _db_fix_.'page_make') ; 
define('_db_contact_us_', _db_fix_.'contact_us') ; 
define('_db_product_item_mobeil_', _db_product_item_.'_mobile') ; 
define('_db_product_menu_mobile_', _db_product_menu_.'_mobile') ; 
define('_db_site_code_', _db_fix_.'site_code') ; 
define('_db_upload_list_', _db_fix_.'upload_list') ; 
define('_db_board_comment_', _db_fix_.'board_comment') ; 

define('_db_member_', _db_fix_.'member') ;
define('_db_member_out_', _db_fix_.'member_out') ;
/**
 * list_idx
 */
class CreateTables
{
	/**
	 */
	function queryFind($findStr)
	{
		$f = fopen(_PATH_module_.'/create.sql', 'r') ;
		$isAppend = false ;
		$q = '' ;
		while( ($d = fgets($f, 4096)) !== false )
		{
			if( !$isAppend && strpos($d, '@'.$findStr) === false )
				continue ;
			
			$isAppend = true ;
			$q .= $d.chr(13) ;
			
			if( strpos($d, '@'.$findStr.'End') !== false )
				break ;			
		}
		return $q ;
	}
	
	function log($str)
	{
		if( db()->errorCode() == '00000' )
			echo '<h3>'.$str.'</h3>' ;	
		else
			print_r( db()->errorInfo ()) ;
	}
	/**
	 * 
	 */
	function file()
	{
		$q = $this->queryFind('file') ;
		db()->exec_(preg_replace('/(EXISTS )([^ ]*)/','${1} '._db_file_,$q)) ;
		$this->log('create file') ;
	}
	/**
	 * 
	 */
	function admin()
	{
		$q = $this->queryFind('admin') ;
		db()->exec_(preg_replace('/(EXISTS )([^ ]*)/','${1} '._db_admin_,$q)) ;
		$this->log('create admin') ;
		
	}
	
	/**
	 * 
	 */
	function accessIp()
	{
		$q = $this->queryFind('access_ip') ;
		db()->exec_(preg_replace('/(EXISTS )([^ ]*)/','${1} '._db_access_ip_,$q)) ;
		$this->log('create accesIp') ;
	}
	
	/**
	 * 
	 */
	function category()
	{
		$q = $this->queryFind('category') ;
		db()->exec_(preg_replace('/(EXISTS )([^ ]*)/','${1} '._db_category_,$q)) ;
		$this->log('create category') ;
	}	
	
	function cateProd()
	{
		$q = $this->queryFind('cate_prod') ;
		db()->exec(preg_replace('/(EXISTS )([^ ]*)/','${1} '._db_category_product_,$q)) ;
		$this->log('create cate_prod') ;
	}
	
	
	
	function product_menu()
	{
		$q = $this->queryFind('product_menu') ;
		db()->exec(preg_replace('/(EXISTS )([^ ]*)/','${1} '._db_product_menu_,$q)) ;
		$this->log('create product_menu') ;
	}
	
	function banner()
	{
		$q = $this->queryFind('banner') ;
		db()->exec(preg_replace('/(EXISTS )([^ ]*)/','${1} '._db_banner_,$q)) ;
		$this->log('create banner') ;
	}
	
	function board($tableName='default')
	{
		$q = $this->queryFind('board') ;
		db()->exec(preg_replace('/(EXISTS )([^ ]*)/','${1} '.str_replace('default',$tableName,_db_board_),$q)) ;
		$this->log('create board') ;
	}
	
	function board_file()
	{
		$q = $this->queryFind('board_file') ;
		db()->exec(preg_replace('/(EXISTS )([^ ]*)/','${1} '._db_board_file_,$q)) ;
		$this->log('create board_file') ;
	}
	
	
	function product_item()
	{
		$q = $this->queryFind('product_item') ;
		db()->exec(preg_replace('/(EXISTS )([^ ]*)/','${1} '._db_product_item_,$q)) ;
		$this->log('create product_item') ;
	}
	
	
	///////////////// xxxx_default
	///////////////// xxxx_default
	///////////////// xxxx_default
	///////////////// xxxx_default
	
	function admin_default()
	{
		db()->exec_("
			INSERT INTO `my`.`my_admin_member` 
				(`admin_idx`, `admin_id`, `admin_pw`, `admin_name`, `admin_owner`, `reg_date`) 
			VALUES 
				(1, 'madison', 'madison', 'madison', 1, sysdate());") ;
		$this->log('insert admin - madison') ;
	}
	
	function category_default()
	{
		db()->exec("
			INSERT INTO `my`.`my_category` 
				(`category_idx`, `category_type`, `category_name`, `reg_date`) 
			VALUES 
				(1, 'product', 'Imaging System', '2013-08-13 17:16:17');") ;
		$this->log('insert category - Imaging System') ;
		
		db()->exec("
			INSERT INTO `my`.`my_category` 
				(`category_idx`, `category_type`, `category_name`, `reg_date`) 
			VALUES 
				(2, 'product', 'software', '2013-08-13 17:16:35');") ;
		$this->log('insert category  - software') ;
	}
	
	function product_menu_default()
	{
		db()->exec("
			INSERT INTO `my`.`my_product_menu` (`menu_group`, `menu_sort`, `cate_proc_idx`, `menu_name`)
			VALUES (1, 1, 0, 'overview');") ;
		$this->log('insert product_menu - 1 overview') ;
		echo '<h3>'.db()->errorCode().' insert category  - software </h3>' ;
		db()->exec("
			INSERT INTO `my`.`my_product_menu` (`menu_group`, `menu_sort`, `cate_proc_idx`, `menu_name`) 
			VALUES (2, 1, 0, 'overview');") ;
		$this->log('insert product_menu - 2 overview') ;
	}
	


}
