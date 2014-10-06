<?php
include 'catchup-lib.php';
init_db();
header("Content-Type: text/xml; charset=utf-8");
mysql_set_charset('utf8');

//XML compilation*****************
$response_row_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');
$media_sql = get_media_by_event_id_sql($_REQUEST["event_id"]);
$media_query_result = mysql_query($media_sql);

while($media_query_row = mysql_fetch_assoc($media_query_result)) 
{
	$media_node = $response_row_node->addChild('media');
	$media_node->addAttribute('media_id',$media_query_row['media_id']);
	$media_node->addAttribute('user_id',$media_query_row['user_id']);
	$media_node->addAttribute('create_at',$media_query_row['create_at']);
	$media_node->addAttribute('media_filename',$media_query_row['media_filename']);
}

mysql_free_result($media_query_result);

echo $response_row_node->asXML();

?>