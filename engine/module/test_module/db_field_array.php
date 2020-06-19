<?php
require_once '../../_define.php';


$pdo = new dbConnector('mysql:host='._DB_HOST_.';dbname='._DB_NAME_.';', _DB_USER_, _DB_PASS_
				,	array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
						,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
				)
			);
$t = G::get('t');
if( empty($t) ){
	exit;
}

$table_cnt = 0;
foreach ($pdo->query('SHOW FULL COLUMNS FROM '.$t) as $r) {
	if(strpos($r->Comment, 'NOVIEW') !== false ) continue; 
	echo '<br />
	,	\''.$r->Field.'\' => \''.$r->Comment.'\'
	';
}