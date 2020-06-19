<?php
require_once '../../_define.php';


$pdo = new dbConnector('mysql:host='._DB_HOST_.';dbname='._DB_NAME_.';', _DB_USER_, _DB_PASS_
				,	array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
						,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
				)
			);

echo '<h1>schema</h1>' ;
echo '<strong>table 수 : <span id="table_cnt"></span></strong>';

$table_cnt = 0;
foreach ($pdo->query('SHOW TABLE STATUS') as $row) {
	
	if(strpos($row->Comment, 'NOVIEW') !== false ) continue; 
		
	$table_cnt++;
	echo '
		<table>
		<caption>'.$row->Name.' <a href="db_field_array.php?t='.$row->Name.'" target="_blank">[P]</a></caption>
		<thead>
		<tr class="info">
			<th class="tl" colspan="4">'.$row->Comment.'</th>
			<th>'.$row->Engine.'</th>
			<th>'.$row->Collation.'</th>
		</tr>
		<tr>
			<th>Field</th>
			<th>Type</th>
			<th>Collation</th>
			<th>Null[Default]</th>
			<th>Key</th>
			<th>Comment</th>
		</tr>
		</thead>
		<tbody>
	' ;
	foreach( $pdo->query('SHOW FULL COLUMNS FROM '.$row->Name) as $r )
	{
		if(strpos($r->Comment, 'NOVIEW') !== false ) continue; 
		if(!empty($r->Default))
			$r->Default = '['.$r->Default.']';
		echo '
		<tr>
			<td>'.$r->Field.'</td>
			<td>'.$r->Type.'</td>
			<td>'.$r->Collation.'</td>
			<td>'.$r->Null.$r->Default.'</td>
			<td>'.$r->Key.'</td>
			<td class="tl">'.$r->Comment.'</td>
		</tr>
		';
	}
	echo '
		</tbody>
		</table>' ;
}
?>
<script type="text/javascript">
	document.getElementById('table_cnt').innerHTML="<?=$table_cnt?>";
	
	document.querySelector(".field_view").onclick = function(){
		console.log(this.getAttribute("href").replace("#",""), this.getAttribute("href"));
		var s = document.getElementById(this.getAttribute("href").replace("#","")).style;
		if( s.display == "none" ){
			s.display = "block";
		}else{
			s.display = "none";
		}
	}
</script>
<style type="text/css">
	body{ text-align:center; }
	table{margin:0 auto; margin-top: 20px;margin-bottom: 20px;width:90%;text-align:center;border:1px solid #a5a5a5;border-collapse: collapse;}
	table th { background: #cdbcb1;padding:4px;}
	table td { padding:4px;border-bottom:1px solid #a5a5a5;}
	table tr.info th { padding:4px; background: #e6cfb7;}
	table tr.info td { padding:4px; border-bottom:0px;}
	table caption{font-size: 15pt; font-weight: bold;}
	.tl{text-align:left;}
</style>