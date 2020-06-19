<?php
//require_once _PATH_lib_.'/page.f.php';
load('loan');
load('company/loan');
define('_db_loan_form_loannloan_', _db_fix_.'loannloan_loan_form');
define('_db_loan_form_view_company_loannloan_', _db_fix_.'loannloan_loan_form_view_company');
define('_db_loan_form_view_company_log_loannloan_', _db_fix_.'loannloan_loan_form_view_company_log');




function printLogFormIdxViewCnt_loannloan($form_idx){
	$LoanFormView = new LoanFormViewCompanyLoannloan();
	$a = $LoanFormView->getFormIdxViewCnt($form_idx);
	return $a['view_cnt'].'/'.$a['total_cnt'];
}