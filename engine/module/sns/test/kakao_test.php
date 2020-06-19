<?php
require_once '../../../_define.php';
require_once '../kakao.c.php';


$kakao = new KAKAO_LOGIN(array(
		"CLIENT_ID" => __KAKAO_API_KEY__,		// (*필수)클라이언트 ID  
		"RETURN_URL" => "https://cha-go.com/engine/module/sns/test/kakaocallback.php",		// (*필수)콜백 URL
		"AUTO_CLOSE" => false,				// 인증 완료후 팝업 자동으로 닫힘 여부 설정 (추가 정보 기재등 추가행동 필요시 false 설정 후 추가)
		"SHOW_LOGOUT" => false				// 인증 후에 네이버 로그아웃 버튼 표시/ 또는 표시안함
		)
	);

$k = $kakao->getConnectState();
if( !$k ){
	pre('empty');
}else{
	pre(['print', $k]);
}
pre($kakao);
pre($kakao->getConnectState());
pre($_SESSION);
$ddd = $kakao->getUserProfile('JSON');
pre($ddd);
if( $_GET['out'] == 'on' ){
	echo $kakao->logout();
	exitJs('','?');
}

?>
<a href="?out=on">[logout]</>
@@
<div class="login_box">
 <?=$kakao->login()?>
</div>@@
