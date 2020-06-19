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