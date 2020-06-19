<?php
//require_once _PATH_lib_.'/page.f.php';
define('_db_apply_form_', _db_fix_.'apply_form');
define('_db_apply_form_view_company_', _db_fix_.'apply_form_view_company');
define('_db_apply_form_view_company_log_', _db_fix_.'apply_form_view_company_log');




function printLogFormIdxViewCnt22222222222222($form_idx){
	$ApplyFormView = new ApplyFormViewCompany;
	$a = $ApplyFormView->getFormIdxViewCnt($form_idx);
	return $a['view_cnt'].'/'.$a['total_cnt'];
}