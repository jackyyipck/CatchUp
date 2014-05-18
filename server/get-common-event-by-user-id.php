<?php
include 'catchup-lib.php';

init_db();

header("Content-Type: text/xml; charset=utf-8");

//XML compilation*****************
$event_list_query_row_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><events/>');

$friend_id = $_GET["friend_id"];
$event_sql = get_event_sql($_GET["user_id"]);

$event_list_query_result = mysql_query($event_sql);
while($event_list_query_row = mysql_fetch_assoc($event_list_query_result)) {
	
	$user_sql = get_user_sql($event_list_query_row['event_id']);
	$user_list_query_result = mysql_query($user_sql);
	while($user_list_query_row = mysql_fetch_assoc($user_list_query_result)) {
		
		if ($user_list_query_row['user_id'] == $friend_id)
		{
		
			$event_node = $event_list_query_row_node->addChild('event');
			$event_node->addAttribute('event_id',$event_list_query_row['event_id']);
			$event_node->addChild('event_name',$event_list_query_row['event_name']);
			$event_node->addChild('event_desc',$event_list_query_row['event_desc']);
			$event_node->addChild('event_invitees',mysql_num_rows($user_list_query_result));
			$event_node->addAttribute('event_create_at',$event_list_query_row['event_create_at']);
			$event_node->addAttribute('event_start_at',$event_list_query_row['event_start_at']);
			$event_node->addAttribute('event_expire_at',$event_list_query_row['event_expire_at']);	
		}
		
	}
	
}

mysql_free_result($event_list_query_result);

echo $event_list_query_row_node->asXML();
?>