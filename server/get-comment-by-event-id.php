<?php
include 'catchup-lib.php';
init_db();
header("Content-Type: text/xml; charset=utf-8");
mysql_set_charset('utf8');

//XML compilation*****************
$response_row_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');
$last_comment_id = isset($_REQUEST["last_comment_id"])?$_REQUEST["last_comment_id"]:0;
$comment_sql = get_comment_by_event_id_sql($_REQUEST["event_id"], $last_comment_id);
$comment_query_result = mysql_query($comment_sql);

while($comment_query_row = mysql_fetch_assoc($comment_query_result)) 
{
	$userdetail_sql = get_userdetails_sql($comment_query_row['create_by']);
	$userdetail_query_result = mysql_query($userdetail_sql);
	$userdetail_query_row = mysql_fetch_assoc($userdetail_query_result);
	
	$comment_name_node = $response_row_node->addChild('comment');
	$comment_name_node->addAttribute('comment_id',$comment_query_row['comment_id']);
	
	$comment_string_node = $comment_name_node->addChild('comment_string', $comment_query_row['comment']);
	$create_by_node = $comment_name_node->addChild('commenter', $userdetail_query_row['user_name']);
	$create_by_node->addAttribute('create_by',$comment_query_row['create_by']);	
	$create_by_node->addAttribute('create_at',$comment_query_row['create_at']);
	
}

if ($userdetail_query_result != null) mysql_free_result($userdetail_query_result);
if ($comment_query_result != null) mysql_free_result($comment_query_result);

echo $response_row_node->asXML();

?>