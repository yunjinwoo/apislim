<?php
//require_once dirname(__FILE__).'/../_require.php';
load('company');

define('_db_company_loan_', _db_fix_.'member_company_loan');

define('_db_company_loan_log_', _db_fix_.'member_company_loan_log');

function getPointTypeList(){
	return array(
		'view' => '고객확인' // CPV
	,	'admin' => '관리자변경'
	,	'pay' => '결제'
	,	'msg' => '문자'
	);
}


$company_loan_row_view_by_idx_list = array();
function company_loan_row_view_by_idx($company_idx, $field, $sub = "")
{
	global $company_loan_row_view_by_idx_list;
	if( $company_loan_row_view_by_idx_list == null || !isset($company_loan_row_view_by_idx_list[$company_idx]) )
	{
		$C = new CompanyLoan;		
		$company_loan_row_view_by_idx_list[$company_idx] = $C->getRowCompanyIdx($company_idx);
	}
	return A::str($company_loan_row_view_by_idx_list[$company_idx], $field);
}