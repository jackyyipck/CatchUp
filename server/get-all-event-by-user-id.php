<?php
include 'catchup-lib.php';
init_db();
header("Content-Type: text/xml; charset=utf-8");
mysql_set_charset('utf8');

//XML compilation*****************
$response_row_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');
$event_sql = get_all_event_sql($_REQUEST["user_id"]);
$event_query_result = mysql_query($event_sql);

while($event_query_row = mysql_fetch_assoc($event_query_result)) 
{
	
	$event_node = $response_row_node->addChild('all_events', $event_query_row['event_name']);
	$event_node->addAttribute('all_event_id',$event_query_row['event_id']);
	
}

if ($event_query_result != null) mysql_free_result($event_query_result);

echo $response_row_node->asXML();

?>