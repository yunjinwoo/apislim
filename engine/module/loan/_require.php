<?php
//require_once _PATH_lib_.'/page.f.php';
define('_db_loan_form_', _db_fix_.'loan_form');
define('_db_loan_form_view_company_', _db_fix_.'loan_form_view_company');
define('_db_loan_form_view_company_log_', _db_fix_.'loan_form_view_company_log');




function printLogFormIdxViewCnt($form_idx){
	$LoanFormView = new LoanFormViewCompany;
	$a = $LoanFormView->getFormIdxViewCnt($form_idx);
	return $a['view_cnt'].'/'.$a['total_cnt'];
}