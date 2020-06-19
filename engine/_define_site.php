<?php
define('_error_msg_print_', 'true');

define('_HTML_TOP_', '../../design/inc/_top.php'); // exitJs 에 기본으로 
define('db_type', ''); // _mssql

//  goodncar / vlxjvos75!@
define('_db_fix_', 'jin_') ; // module db table prefix
define('_db_car_fix_', 'jin_') ; // module_car db table prefix

// 	uws64-181.cafe24.com/WebMysql
define('_DB_HOST_', '127.0.0.1') ;//10.10.10.42
define('_DB_USER_', '----') ;
define('_DB_PASS_', '-----') ;
define('_DB_NAME_', '----') ;
		

//define('_DB_HOST_', 'parse.vps.phps.kr') ;//10.10.10.42
//define('_DB_USER_', 'cargo') ;
//define('_DB_PASS_', 'cargopw') ;
//define('_DB_NAME_', 'car니go') ;

define('_SITE_INFO_TITLE_', 'slim api');

// uws7-033.cafe24.com/WebMysql
// 
define('_DOMAIN__', 'http://api.jinadd.com/');
define('_MOBILE_INDEX_', 'http://api.jinadd.com/m/');


define('_DB_USE_COMPANY_', 'CompanyLoan');

function cryptMake($pwd, $salt = ''){
	return crypt($pwd, $salt);
}

function cryptCheck($pwd, $pwd2){
	return strcmp($pwd2, cryptMake($pwd, $pwd2)) !== 0;
}
class MyInfo{
	static $row ;
	static function getField($field){
		return A::str(self::$row, $field);
	}
	static function getUserId(){
		return A::str(self::$row, 'user_id');
	}
	static function getName(){
		return A::str(self::$row, 'user_name');
	}
	static function getTeamName(){
		return A::str(self::$row, 'team_name');
	}
	static function getIdx(){
		return A::str(self::$row, 'admin_idx');
	}
	
	static function getIsTest(){
		return A::str(self::$row, 'user_id') == 'test2';
	}
	
	static function user_idx(){
		return A::str(self::$row, 'user_idx');
	}
	static function user_name(){
		return A::str(self::$row, 'user_name');
	}
	

		static function dealer_idx(){
			return A::str(self::$row, 'user_idx');
		}
		static function dealer_id(){
			return A::str(self::$row, 'user_id');
		}
		static function dealer_name(){
			return A::str(self::$row, 'user_name');
			//return A::str(self::$row, 'nickname');
		}
}


// 아이피 등 필요정보가 들어가 있어야 한다.
function getLoginInfo(){
	return '';
}

function get_car_other_list(){
	return [
		'벤츠' => '벤츠',
		'BMW' => 'BMW',
		'렉서스' => '렉서스',
		'볼보' => '볼보',
		'폭스바겐' => '폭스바겐',
		'아우디' => '아우디',
		'랜드로버' => '랜드로버',
		'재규어' => '재규어',
		'기타' => '기타',
	];
}

function getBannerAreaList(){
	return array(
		'전국' => '전국'
	,	'서울' => '서울특별시'
	,	'경기' => '경기도'
	,	'인천' => '인천광역시'
	,	'대전' => '대전광역시'
	,	'대구' => '대구광역시'
		
	,	'부산' => '부산광역시'
	,	'광주' => '광주광역시'
	,	'울산' => '울산광역시'
	,	'세종' => '세종특별자치시'
	,	'강원' => '강원도'
	,	'충북' => '충청북도'
		
	,	'충남' => '충청남도'
	,	'전북' => '전라북도'
	,	'전남' => '전라남도'
	,	'경북' => '경상북도'
	,	'경남' => '경상남도'
	,	'제주' => '제주도'
	);
}


function getBannerProductList(){
	return array(
		'전체' => '전체'
	,	'직장인' => '직장인대출'
	,	'무직자' => '무직자대출'
	,	'여성' => '여성대출'
	,	'개인돈' => '개인돈대출'
	,	'연체자' => '연체자대출'
		
	,	'소액' => '소액대출'
	,	'무방문' => '무방문대출'
	,	'일수' => '일수대출'
	,	'당일' => '당일대출'
	,	'사업자' => '사업자대출'
	,	'월변' => '월변대출'
		
	,	'저신용' => '저신용자대출'
	,	'신용' => '신용대출'
	,	'추가' => '추가대출'
	,	'자동차' => '자동차담보'
	,	'부동산' => '부동산담보'
	,	'기타' => '기타대출'
		
	,	'전월세' => '전월세대출'		
	,	'보증금' => '보증금대출'
	,	'회파복' => '회파복대출'
	);
}



/**
 * admin
 * company.php
 * company_product.php
 * 
 * user
 * join_2.php
 * mypage/
 * **/
function bannerUpdate($company_idx, $update_msg){
	$Banner = new BannerCompany();
	$BannerArea = new BannerAreaCompany();
	$BannerProduct = new BannerProductCompany();
		
	//업체 베너영역정보
	$aPostBanner = G::postArr('b');
	if( count($aPostBanner) >= 1 ){
		$aPostBanner['company_idx'] = $company_idx;
		$r = $Banner->getRow($company_idx);
		$isSave = true;// 날짜만 변경이 안된다... 181818
		foreach( getBannerList() as $k => $v ){
			if( A::str($aPostBanner, $k) == '' ){
				$aPostBanner[$k] = 'N';
			}
			if( A::str($r, $k) != A::str($aPostBanner, $k) ){
				$isSave = true;
			}
		}

		if( $isSave ){
			$aPostBanner['banner_idx'] = $Banner->save($aPostBanner);
			$aPostBanner['reg_info'] = $update_msg;
			$aPostBanner['reg_date'] = date('Y-m-d H:i:s');
			$Banner->saveLog($aPostBanner);
		}
	}
	
	//업체 지역정보
	$aPostArea = G::postArr('ba');
	if( count($aPostArea) >= 1 ){
		$aPostArea['company_idx'] = $company_idx;
		//pre($aPostArea);
		$r = $BannerArea->getRow($company_idx);
		$isSave = true;// 날짜만 변경이 안된다... 181818
		foreach(getBannerAreaFieldList() as $k => $v ){
			if( A::str($aPostArea, $k) == '' ){
				$aPostArea[$k] = 'N';
			}
			if( A::str($r, $k) != A::str($aPostArea, $k) ){
				$isSave = true;
			}
		}
		if( $isSave ){
			$aPostArea['area_idx'] = $BannerArea->save($aPostArea);
			$aPostArea['reg_info'] = $update_msg;
			$BannerArea->saveLog($aPostArea);
		}
	}

	//업체 상품정보
	$aPostProduct = G::postArr('bp');
	if( count($aPostProduct) >= 1 ){
		$aPostProduct['company_idx'] = $company_idx;
		//pre($aPostProduct);
		$r = $BannerProduct->getRow($company_idx);
		$isSave = true; // 날짜만 변경이 안된다... 181818
		foreach(getBannerProductFieldList() as $k => $v ){
			if( A::str($aPostProduct, $k) == '' ){
				$aPostProduct[$k] = 'N';
			}
			if( A::str($r, $k) != A::str($aPostProduct, $k) ){
				$isSave = true;
			}
		}
		if( $isSave ){
			$aPostProduct['product_idx'] = $BannerProduct->save($aPostProduct);
			$aPostProduct['reg_info'] = $update_msg;
			$BannerProduct->saveLog($aPostProduct);
		}
	}
}

