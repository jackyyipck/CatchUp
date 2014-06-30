<?php
include 'catchup-lib.php';

init_db();

header("Content-Type: text/xml; charset=utf-8");

//XML compilation*****************
$response_node = new SimpleXMLElement('<response/>');

$rsvp_sql = get_rsvp_sql($_REQUEST['event_id']);
$rsvp_query_result = mysql_query($rsvp_sql);

while($rsvp_query_row = mysql_fetch_assoc($rsvp_query_result)) 
{	
	$option_name_node = $response_node->addChild('rsvp_name', $rsvp_query_row['user_name']);
	$option_name_node->addAttribute('rsvp_id',$rsvp_query_row['user_id']);
	
	$userdetails_sql = get_userdetails_sql($rsvp_query_row['user_id']);
	$userdetails_query_result = mysql_query($userdetails_sql);
	$userdetails_query_row = mysql_fetch_assoc($userdetails_query_result);
	$option_name_node->addAttribute('rsvp_mobile', $userdetails_query_row['user_mobile']);
	$option_name_node->addAttribute('rsvp_status', $userdetails_query_row['user_status']);
	
}
mysql_free_result($rsvp_query_result);

echo $response_node->asXML();

?>