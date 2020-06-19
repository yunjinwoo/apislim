<?php
require_once '../../../_define.php';
require_once '../kakao.c.php';
/**
 * Created by Netbeans.
 * User: yjw3647@gmail.com
 * Date: 2019. 11. 27 
 */
function generate_state() {
    $mt = microtime();
    $rand = mt_rand();
    return md5($mt . $rand);
}

function naver_login_token(){
	// 상태 토큰으로 사용할 랜덤 문자열을 생성
	$state = generate_state();
	// 세션 또는 별도의 저장 공간에 상태 토큰을 저장
	$_SESSION['state'] = $state;
	return $state;
}

$Session = new Session();

if( $_GET['code'] == '' ){
	if( $Session->getSession('code') == '' ){
		exitJs('로그인정보가 없습니다', 'history.back()');
	}
}else{
	$Session->setSession('code', $_GET['code']);
}


require_once '../kakao.c.php';
$kakao = new KAKAO_LOGIN(array(
		"CLIENT_ID" => __KAKAO_API_KEY__,		// (*필수)클라이언트 ID  
		"RETURN_URL" => "https://cha-go.com/engine/module/sns/test/kakaocallback.php",		// (*필수)콜백 URL
		"AUTO_CLOSE" => false,				// 인증 완료후 팝업 자동으로 닫힘 여부 설정 (추가 정보 기재등 추가행동 필요시 false 설정 후 추가)
		"SHOW_LOGOUT" => false				// 인증 후에 네이버 로그아웃 버튼 표시/ 또는 표시안함
		)
	);



pre($kakao->getConnectState());
pre($_SESSION);
$kakao->test($_SESSION['code'], $_SESSION['state']);
$ddd = $kakao->getUserProfile('JSON');
pre($kakao->error);
//	pre('eee');
//	pre($ddd);
$data = json_decode($ddd);
pre($ddd);
pre($data);