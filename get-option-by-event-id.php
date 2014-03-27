<?php
include 'catchup-lib.php';
init_db();
header("Content-Type: text/xml; charset=utf-8");



//XML compilation*****************
$response_row_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');

$option_sql = get_option_sql($_GET["event_id"]);
$option_query_result = mysql_query($option_sql);

while($option_query_row = mysql_fetch_assoc($option_query_result)) 
{
	$option_name_node = $response_row_node->addChild('option_name', $option_query_row['option_name']);
	$option_name_node->addAttribute('option_id',$option_query_row['option_id']);
	$option_name_node->addAttribute('option_desc',$option_query_row['option_desc']);
	
	$voter_sql = get_voter_sql($option_query_row['option_id']);
	$voter_query_result = mysql_query($voter_sql);
	$option_name_node->addAttribute('voters_num',mysql_num_rows($voter_query_result));
	mysql_free_result($voter_query_result);	
}
mysql_free_result($option_query_result);

$respondent_sql = get_distinct_respondent_sql($_GET["event_id"]);
$invitee_sql = get_user_sql($_GET["event_id"]);

$respondent_query_result = mysql_query($respondent_sql);
$invitee_query_result = mysql_query($invitee_sql);
$rsvp_num = mysql_num_rows($invitee_query_result) - mysql_num_rows($respondent_query_result);

$rsvp_node = $response_row_node->addChild('option_name', "Pending RSVP");
$rsvp_node->addAttribute('option_id',"N");
$rsvp_node->addAttribute('option_desc',"");
$rsvp_node->addAttribute('voters_num', $rsvp_num);
	
mysql_free_result($respondent_query_result);
mysql_free_result($invitee_query_result);

echo $response_row_node->asXML();

?>