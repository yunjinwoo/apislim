<?php
require_once _PATH_lib_.'/page.f.php';
require_once dirname(__FILE__).'/CarList.c.php';

function print_price($v){
	if(is_numeric($v)){
		$v = number_format($v);
	}
	return $v;
}
function get_car_list($country = '', $company = '', $keyword = '', $orderBy = ''){
	load('car');

	if(empty($country)){$country = G::get('cou');}
	if(empty($company)){$company = G::get('com');}
	if(empty($keyword)){$keyword = G::get('keyword');}
	if(empty($orderBy)){$orderBy = ' ORDER BY rand() ';}
	
	$carList = new CarList;
	$carList->setOrder($orderBy);
	
	$field = '';
	if( G::get('is_main') == 'Y' ){
		$field = 'is_main';
	}elseif( G::get('is_main_bottom') == 'Y' ){
		$field = 'is_main_bottom';
	}

	if( $field != ''){			
		return $carList->getMainList($field);		
	}else{		
		return $carList->getList($company, $country, $keyword, 'Y');
	}
}

function __car__row($a){
	$a['car_table1'] = unserialize($a['car_table1']);
	$a['car_table2'] = unserialize($a['car_table2']);
	$a['car_table3'] = unserialize($a['car_table3']);

	$a['company_han'] = companyEngToHan($a['company']);
	$a['car_list_img_main'] = empty($a['car_list_img2'])?$a['car_list_img']:$a['car_list_img2'];
	
	
	return $a;
}
function companyEngToHan($company){
	$s = $company;
	switch( $company ){
		case 'hyundai'		: $s = '현대자동차';break;
		case 'kia'			: $s= '기아자동차';break;
		case 'samsung'		: $s= '르노삼성';break;
		case 'ssangyong'	: $s= '쌍용자동차';break;
		case 'chevrolet'	: $s= '쉐보레';break;
		case 'genesis'		: $s= '제네시스';break;

		case 'bmw'			: $s= 'BMW';break;
		case 'benz'			: $s= 'BENZ';break;
		case 'audi'			: $s= '아우디';break;
		case 'volks'		: $s= '폭스바겐';break;
		case 'toyota'		: $s= '렉서스';break;
		case 'other'		: $s= '기타수입차';break;
	}
	return $s;
}

function getCarList($country = '', $company=''){
	if(empty($company)){
		$company = G::get('com');
	}
	if(empty($country)){
		$country = G::get('cou');
	}

	$keyword = G::get('keyword');
	$is_all = G::get('is_all');

	$is_main = G::get('is_main');

	$carList = new CarList;
	
	
	$aPage = array();
	$page = F::number(G::get('page'), 1) ;
	$list_size = 12;
	$aPage['page'] = $page ;
	$aPage['page_size'] = 10 ;
	$aPage['total_page_count'] = ceil($carList->getCount($company, $country, $keyword, $is_all)/$list_size) ;
	$carList->setLimit(' LIMIT '.(($page-1)*$list_size).','.$list_size);
	
	if( $is_main == 'Y' ){
		$__car_list = $carList->getMainList();
	}else{
		$__car_list = $carList->getList($company, $country, $keyword, $is_all);
	}
	
	
	$__car_list['page_data'] = $aPage;
	
	return $__car_list;
}
function nextCarListIteam(){
	
	
	return $ret;
}
function hasCarListIteam(){
	return false;
}
			  
/* 
CREATE TABLE "car_list" (
	"car_seq" INT NOT NULL,
	"country" VARCHAR(100) NOT NULL,
	"company" VARCHAR(100) NOT NULL,
	"car_name" VARCHAR(100) NOT NULL,
	"car_price" VARCHAR(100) NOT NULL,
	"car_table1" VARCHAR(1000) NOT NULL,
	"car_table2" VARCHAR(1000) NOT NULL,
	"car_table3" VARCHAR(1000) NOT NULL,
	"car_list_con" VARCHAR(100) NOT NULL,
	"car_img" VARCHAR(100) NOT NULL,
	"car_list_img" VARCHAR(100) NOT NULL,
	"car_detail_img" VARCHAR(100) NOT NULL,
	"orderby" INT NOT NULL,
	"is_hide" CHAR(1) NOT NULL,
	"reg_date" DATETIME NOT NULL,
	"price_36" VARCHAR(200) NOT NULL,
	"price_48" VARCHAR(200) NOT NULL,
	"car_table_img" VARCHAR(100) NOT NULL,
	PRIMARY KEY ("car_seq")
);

 */

$__main_car = null;
function getMainRow($field = 'is_main', $orderBy = '', $num = ''){
	global $__main_car;
	if( A::str($__main_car, $field) == "" ){
		$carList = new CarList;
		$__main_car[$field] = $carList->getMainList($field,$orderBy);
	}
	
	return A::arr($__main_car[$field], $num);
}


function listItemRow_main($row){
	if( !isset( $row['car_name'] ) ){ return ; }
	
	include '_car_item.html';
}

function listItemRow($row){
	if( !isset( $row['car_name'] ) ){ return ; }
	
	include $_SERVER['DOCUMENT_ROOT'].'/design/inc/_car_item.html';
}

function listItemRow_mobile($row){
	if( !isset( $row['car_name'] ) ){ return ; }
	
	include $_SERVER['DOCUMENT_ROOT'].'/mobile/inc/_car_item.html';
}


function listItemRow_admin($row){
	if( !isset( $row['car_name'] ) ){ return ; }
	
	include $_SERVER['DOCUMENT_ROOT'].'/admin/car/_car_item.html';
}

