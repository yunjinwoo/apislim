<?php

require_once _PATH_lib_.'/page.f.php';



$memberRow = null;
function member_row_view($field, $sub = "")
{
	$Session = new Session;
	$user_id = $Session->getUserId();
	return member_row_view_select($user_id, $field, $sub);
}

$memberRowList = null;
function member_row_view_select($user_id, $field, $sub = "")
{
	global $memberRowList;
	if( $memberRowList == null || !isset($memberRowList[$user_id]) )
	{
		load('member');
		$memberRowList[$user_id] = new memberRow;
		$memberRowList[$user_id]->setRow($user_id);
	}
	return $memberRowList[$user_id]->data($field, $sub);
}
