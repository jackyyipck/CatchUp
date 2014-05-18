<?php
include 'catchup-lib.php';
init_db();
header("Content-Type: text/xml; charset=utf-8");
mysql_set_charset('utf8');

//XML compilation*****************
$response_row_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');

if (isset($_GET["last_comment_id"]))
{
	$comment_sql = get_comment_after_last_comment_id_sql($_GET["user_id"], $_GET["last_comment_id"]);
}
else
{
	$comment_sql = get_comment_after_last_comment_id_sql($_GET["user_id"], 0);
}
$comment_query_result = mysql_query($comment_sql);
$processing_event_id = '';

while($comment_query_row = mysql_fetch_assoc($comment_query_result)) 
{
	if ($processing_event_id <> $comment_query_row['event_id'])
	{
		$processing_event_id = $comment_query_row['event_id'];
		$event_node = $response_row_node->addChild('event');
		$event_node->addAttribute('event_id', $processing_event_id);
	}
	
	//TODO: improve by caching the username
	$userdetail_sql = get_userdetails_sql($comment_query_row['create_by']);
	$userdetail_query_result = mysql_query($userdetail_sql);
	$userdetail_query_row = mysql_fetch_assoc($userdetail_query_result);

	$comment_name_node = $event_node->addChild('comment', $comment_query_row['comment']);
	$comment_name_node->addAttribute('comment_id',$comment_query_row['comment_id']);
	$comment_name_node->addAttribute('commenter', $userdetail_query_row['user_name']);
	$comment_name_node->addAttribute('create_by',$comment_query_row['create_by']);	
	$comment_name_node->addAttribute('create_at',$comment_query_row['create_at']);	
}
mysql_free_result($comment_query_result);

echo $response_row_node->asXML();

?>