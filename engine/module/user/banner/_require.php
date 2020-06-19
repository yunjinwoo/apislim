<?php
//require_once dirname(__FILE__).'/../_require.php';
load('company/loan');

define('_db_banner_company_', _db_fix_.'banner_company');
define('_db_banner_company_log_', _db_fix_.'banner_company_log');
define('_db_banner_company_area_', _db_fix_.'banner_company_area');
define('_db_banner_company_area_log_', _db_fix_.'banner_company_area_log');
define('_db_banner_company_product_', _db_fix_.'banner_company_product');
define('_db_banner_company_product_log_', _db_fix_.'banner_company_product_log');

require_once dirname(__FILE__).'/BannerCompany.c.php';

function __query_company_banner_area_all($isBanner = false){
	$q = '
		SELECT %s
		FROM '._db_company_loan_.' a 
		LEFT JOIN '._db_banner_company_area_.' b 
		ON a.company_idx = b.company_idx 
		WHERE
			read_company_loan_release_date_start <= \''._TODAY_.'\'
		AND read_company_loan_release_date_end > \''._TODAY_.'\'
		AND is_use = \'Y\'
	' ;
	if( $isBanner ){
		$q .= '
		AND b.start_date <= \''._TODAY_.'\'
		AND b.end_date > \''._TODAY_.'\'
			';
	}
		
	// $format = 'The %s contains %d monkeys';
	
	return $q;
}
function __query_company_banner_area($isBanner = false){
	$q = '
		SELECT %s
		FROM '._db_company_loan_.' a 
		LEFT JOIN '._db_banner_company_area_.' b 
		ON a.company_idx = b.company_idx 
		WHERE
			b.%s = \'Y\'
		AND	read_company_loan_release_date_start <= \''._TODAY_.'\'
		AND read_company_loan_release_date_end > \''._TODAY_.'\'
			AND is_use = \'Y\'
	' ;
	if( $isBanner ){
		$q .= '
		AND b.start_date <= \''._TODAY_.'\'
		AND b.end_date > \''._TODAY_.'\'
			';
	}
		
	// $format = 'The %s contains %d monkeys';
	
	return $q;
}

function get_company_banner_area($area, $search_cate, $search_str, $no_find = "", $top_search = ""){
	$Banner = new BannerAreaCompany();	
	$field = $Banner->getAreaToField($area);
	
	$db = $Banner->db();
	if( $no_find == 'yes'){
		$query = __query_company_banner_area_all();
	}else{
		$query = __query_company_banner_area();
	}
	
	$top_w = "";
	if( !empty($top_search) ){
		$top_search = stripslashes($top_search);
		$top_w = 'AND ( read_company_name LIKE \'%'.$top_search.'%\' OR read_company_loan_release_number LIKE \'%'.$top_search.'%\' OR company_info_product LIKE \'%'.$top_search.'%\' OR company_info2 LIKE \'%'.$top_search.'%\' ) ';
	}
	
	
	$stmt_cnt = $db->prepare(sprintf($query, 'COUNT(*) as cnt', $field).$top_w);	
	stmtExecute($stmt_cnt);
	$area_main_cnt = $stmt_cnt->fetch();
	$area_main_cnt = $area_main_cnt['cnt'];
	
	
	$totalCnt = $area_main_cnt;
	$list_size = $Banner->getInfoListSize();
	$page = F::number(G::request('page'), 1);
	$page_size = $Banner->getInfoPageSize();
	$page_count = ceil($totalCnt/$list_size);
	
	$Banner->setPageData($page, $page_size, $page_count);
	$Banner->setLimit($list_size , $page);
	$limit = $Banner->getLimit();
	if( $no_find != 'yes'){
		$limit = '';
	}
	
	setPageData($Banner->getPageData());
	$w = '';
	if( !empty($search_cate) && !empty($search_str) ){
		$search_str = stripslashes($search_str);
		if( $search_cate == 'company_name' ){
			$w = 'AND read_company_name LIKE \'%'.$search_str.'%\' ';
		}
		if( $search_cate == 'company_phone' ){
			$w = 'AND read_company_loan_release_number LIKE \'%'.$search_str.'%\' ';
		}
	}
	$q = sprintf($query, 'a.*', $field).$w.$top_w.$limit;
	$stmt = $db->prepare($q);
	

	$BannerArea = new BannerAreaCompany();	
	$area_main_list = array();
	stmtExecute($stmt);
	while($a = $stmt->fetch()){
		$aaa = explode(' ', $a['company_addr_1']);
		$a['addr_first'] = array_shift($aaa);
		$area_main_list[A::str($a, 'company_idx')] = $a;
		$area_main_list[A::str($a, 'company_idx')]['area'] =  $BannerArea->getRow(A::str($a, 'company_idx'));
	}
	
	return array(  'cnt' => $area_main_cnt, 'list' => $area_main_list);
}

function get_company_banner_area_money($area){
	$Banner = new BannerAreaCompany();	
	$field = $Banner->getAreaToField($area);
	$BannerArea = new BannerAreaCompany();
	
	$db = $Banner->db();
	$query = __query_company_banner_area(true);
	$stmt_cnt = $db->prepare(sprintf($query, 'COUNT(*) as cnt', $field));	
	stmtExecute($stmt_cnt);
	$banner_main_cnt = $stmt_cnt->fetch();
	$banner_main_cnt = $banner_main_cnt['cnt'];
	
	$q = sprintf($query, 'a.*', $field).' LIMIT 50 ';
	$stmt = $db->prepare($q);
	$banner_main_list = array();
	stmtExecute($stmt);
	while($a = $stmt->fetch()){
		$aaa = explode(' ', $a['company_addr_1']);
		$a['addr_first'] = array_shift($aaa);
		$banner_main_list[A::str($a, 'company_idx')] = $a;
		$banner_main_list[A::str($a, 'company_idx')]['area'] =  $BannerArea->getRow(A::str($a, 'company_idx'));
	}
	return array('banner_cnt' => $banner_main_cnt, 'banner_list' => $banner_main_list);
}



function __query_company_banner_product($isBanner = false){
	$q = '
		SELECT %s
		FROM '._db_company_loan_.' a 
		LEFT JOIN '._db_banner_company_product_.' b 
		ON a.company_idx = b.company_idx 
		WHERE
			b.%s = \'Y\'
		AND	read_company_loan_release_date_start <= \''._TODAY_.'\'
		AND read_company_loan_release_date_end > \''._TODAY_.'\'
	' ;
	if( $isBanner ){
		$q .= '
		AND b.start_date <= \''._TODAY_.'\'
		AND b.end_date > \''._TODAY_.'\'
			';
	}
		
	// $format = 'The %s contains %d monkeys';
	
	return $q;
}

function get_company_banner_product($product, $search_cate, $search_str){
	
	$Banner = new BannerProductCompany();	
	$field = $Banner->getAreaToField($product);
	$BannerArea = new BannerAreaCompany();
	
	$db = $Banner->db();
	$query = __query_company_banner_product();
	$stmt_cnt = $db->prepare(sprintf($query, 'COUNT(*) as cnt', $field));	
	stmtExecute($stmt_cnt);
	$area_main_cnt = $stmt_cnt->fetch();
	$area_main_cnt = $area_main_cnt['cnt'];
	
	
	$totalCnt = $area_main_cnt;
	$list_size = $Banner->getInfoListSize();
	$page = F::number(G::request('page'), 1);
	$page_size = $Banner->getInfoPageSize();
	$page_count = ceil($totalCnt/$list_size);
	
	$Banner->setPageData($page, $page_size, $page_count);
	$Banner->setLimit($list_size , $page);
	$limit = $Banner->getLimit();
	
	
	setPageData($Banner->getPageData());
	$w = '';
	if( !empty($search_cate) && !empty($search_str) ){
		$search_str = stripslashes($search_str);
		if( $search_cate == 'company_name' ){
			$w = 'AND read_company_name LIKE \'%'.$search_str.'%\' ';
		}
		if( $search_cate == 'company_phone' ){
			$w = 'AND read_company_loan_release_number LIKE \'%'.$search_str.'%\' ';
		}
	}
	$q = sprintf($query, 'a.*', $field).$w.$limit;
	$stmt = $db->prepare($q);
	$area_main_list = array();
	stmtExecute($stmt);
	while($a = $stmt->fetch()){
		$aaa = explode(' ', $a['company_addr_1']);
		$a['addr_first'] = array_shift($aaa);
		$area_main_list[A::str($a, 'company_idx')] = $a;
		$area_main_list[A::str($a, 'company_idx')]['area'] =  $BannerArea->getRow(A::str($a, 'company_idx'));
	}
	
	
	return array( 'cnt' => $area_main_cnt, 'list' => $area_main_list);
}

function get_company_banner_product_money($product){
	$Banner = new BannerProductCompany();	
	$field = $Banner->getAreaToField($product);
	$BannerArea = new BannerAreaCompany();
	
	$db = $Banner->db();
	$query = __query_company_banner_product(true);
	$stmt_cnt = $db->prepare(sprintf($query, 'COUNT(*) as cnt', $field));	
	stmtExecute($stmt_cnt);
	$banner_main_cnt = $stmt_cnt->fetch();
	$banner_main_cnt = $banner_main_cnt['cnt'];
	
	$q = sprintf($query, 'a.*', $field).' LIMIT 50 ';
	$stmt = $db->prepare($q);
	$banner_main_list = array();
	stmtExecute($stmt);
	while($a = $stmt->fetch()){
		$aaa = explode(' ', $a['company_addr_1']);
		$a['addr_first'] = array_shift($aaa);
		$banner_main_list[A::str($a, 'company_idx')] = $a;
		$banner_main_list[A::str($a, 'company_idx')]['area'] =  $BannerArea->getRow(A::str($a, 'company_idx'));
	}
	
	return array('banner_cnt' => $banner_main_cnt, 'banner_list' => $banner_main_list);
}


function __query_company_banner_group(){
	$q = '
		SELECT %s
		FROM '._db_company_loan_.' a 
		LEFT JOIN '._db_banner_company_.' b 
		ON a.company_idx = b.company_idx 
		WHERE
			b.%s = \'Y\'
		AND	read_company_loan_release_date_start <= \''._TODAY_.'\'
		AND read_company_loan_release_date_end > \''._TODAY_.'\'
	
		AND b.start_date <= \''._TODAY_.'\'
		AND b.end_date > \''._TODAY_.'\'
		ORDER BY rand()
			';
		
	// $format = 'The %s contains %d monkeys';
	
	return $q;
}

function get_list_banner_premium(){
	$Banner = new BannerAreaCompany();	
	$query = __query_company_banner_group();
	$q = sprintf($query, 'a.*', 'premium');
	$stmt = $Banner->db()->prepare($q);
	$banner_main_list = array();
	stmtExecute($stmt);
	while($a = $stmt->fetch()){
		$banner_main_list[A::str($a, 'company_idx')] = $a;
		$banner_main_list[A::str($a, 'company_idx')]['area'] =  $Banner->getRow(A::str($a, 'company_idx'));
	}
	return $banner_main_list;
}
function get_list_banner_main_top(){
	$Banner = new BannerAreaCompany();	
	$query = __query_company_banner_group();
	$q = sprintf($query, 'a.*', 'main_top');
	$stmt = $Banner->db()->prepare($q);
	$banner_main_list = array();
	stmtExecute($stmt);
	while($a = $stmt->fetch()){
		$banner_main_list[A::str($a, 'company_idx')] = $a;
		$banner_main_list[A::str($a, 'company_idx')]['area'] =  $Banner->getRow(A::str($a, 'company_idx'));
	}
	
	return $banner_main_list;
}
function get_list_banner_main_center(){
	$Banner = new BannerAreaCompany();	
	$query = __query_company_banner_group();
	$q = sprintf($query, 'a.*', 'main_center');
	$stmt = $Banner->db()->prepare($q);
	$banner_main_list = array();
	stmtExecute($stmt);
	while($a = $stmt->fetch()){
		$banner_main_list[A::str($a, 'company_idx')] = $a;
		$banner_main_list[A::str($a, 'company_idx')]['area'] =  $Banner->getRow(A::str($a, 'company_idx'));
	}
	
	return $banner_main_list;
}
function get_list_banner_sponsor(){
	$Banner = new BannerAreaCompany();	
	$query = __query_company_banner_group();
	$q = sprintf($query, 'a.*', 'sponsor');
	$stmt = $Banner->db()->prepare($q);
	$banner_main_list = array();
	stmtExecute($stmt);
	while($a = $stmt->fetch()){
		$banner_main_list[A::str($a, 'company_idx')] = $a;
		$banner_main_list[A::str($a, 'company_idx')]['area'] =  $Banner->getRow(A::str($a, 'company_idx'));
	}
	
	return $banner_main_list;
}
$__banner_main_list = array();
function get_list_banner_sponsor_row($k){
	global $__banner_main_list;
	if( count($__banner_main_list) == 0 ){
		$__banner_main_list = get_list_banner_sponsor();
	}
	
	if(!is_numeric($k)){
		$k = 1;
	}
	$num = 1;
	$ret = array();
	foreach( $__banner_main_list as $idx => $r ){
		if( $num == $k ){
			$ret = $r;
			break;
		}
		$num++;
	}
	return $ret;
}

function get_list_new_company($limit = 15){
	$Banner = new CompanyLoan();
	$Banner->addWhereStr('read_company_loan_release_date_start',' <= \''._TODAY_.'\' ');
	$Banner->addWhereStr('read_company_loan_release_date_end',' > \''._TODAY_.'\' ');
	$Banner->setLimit(15);
	
	return $Banner->getList();
}