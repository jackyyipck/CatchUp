<?php
include 'catchup-lib.php';
init_db();
header("Content-Type: text/xml; charset=utf-8");

//XML compilation*****************
$response_row_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');

$option_sql = get_option_sql($_REQUEST["event_id"]);
$option_query_result = mysql_query($option_sql);

$return_value = get_event_detail($_SESSION["db_conn"], $_REQUEST["event_id"]);

$response_row_node->addChild('event_name', $return_value['event']['event_name']);
$response_row_node->addChild('event_desc', $return_value['event']['event_desc']);
$response_row_node->addChild('start_date', $return_value['event']['event_start_at']);
$response_row_node->addChild('expiry_date', $return_value['event']['event_expire_at']);
$response_row_node->addChild('is_allday', $return_value['event']['is_allday']);
$response_row_node->addChild('is_public', $return_value['event']['is_public']);

//******<message>*********

if ($_REQUEST["user_id"] != NULL)
{
	$message_sql = get_comment_sql_by_event_id($_REQUEST["event_id"], $_REQUEST["user_id"]);
	$message_list_query_result = mysql_query($message_sql);
	$response_row_node->addChild('event_messages',mysql_num_rows($message_list_query_result));
}
else
{
	$response_row_node->addChild('event_messages',0);
}

$event_profile_pic_ = "";
if($return_value['event']['event_profile_filename'] == '')
{
	$event_profile_pic = "default";
} 
else
{
	$event_profile_pic = $return_value['event']['event_profile_filename'];
}
$response_row_node->addChild('event_profile_pic', $event_profile_pic);

while($option_query_row = mysql_fetch_assoc($option_query_result)) 
{
	$option_node = $response_row_node->addChild('option');
	$option_name_node = $option_node->addChild('option_name', $option_query_row['option_name']);
	$option_name_node->addAttribute('option_id',$option_query_row['option_id']);
	$option_name_node->addAttribute('option_desc',$option_query_row['option_desc']);
	
	$voter_sql = get_voter_sql($option_query_row['option_id']);
	$voter_query_result = mysql_query($voter_sql);
	$option_name_node->addAttribute('voters_num',mysql_num_rows($voter_query_result));
	
	while($voter_query_row = mysql_fetch_assoc($voter_query_result))
	{
		$voter_name_node = $option_node->addChild('voter_name', $voter_query_row['user_name']);
	}
	mysql_free_result($voter_query_result);	
	
	$vote_status_sql = get_vote_status_sql($_REQUEST["user_id"], $option_query_row['option_id']);
	$vote_status_query_result = mysql_query($vote_status_sql);
	if (mysql_num_rows($vote_status_query_result) <> 0)
	{
		//Indicate if the current user has voted the option
		$option_name_node->addAttribute('vote_status', 1);
	}
	else
	{
		$option_name_node->addAttribute('vote_status', 0);
	}
	mysql_free_result($vote_status_query_result);
}
mysql_free_result($option_query_result);

$respondent_sql = get_distinct_respondent_sql($_REQUEST["event_id"]);
$invitee_sql = get_user_sql_by_event_id($_REQUEST["event_id"]);

$respondent_query_result = mysql_query($respondent_sql);
$invitee_query_result = mysql_query($invitee_sql);
$rsvp_num = mysql_num_rows($invitee_query_result) - mysql_num_rows($respondent_query_result);
$rsvp_sql = get_rsvp_sql($_REQUEST['event_id']);
$rsvp_query_result = mysql_query($rsvp_sql);

$option_node = $response_row_node->addChild('option');
$rsvp_node = $option_node->addChild('option_name', "Pending RSVP");
$rsvp_node->addAttribute('option_id',"N");
$rsvp_node->addAttribute('option_desc',"");
$rsvp_node->addAttribute('voters_num', $rsvp_num);
$rsvp_node->addAttribute('vote_status', 0);
while($rsvp_query_row = mysql_fetch_assoc($rsvp_query_result))
{
	$rsvp_name_node = $option_node->addChild('voter_name', $rsvp_query_row['user_name']);
}
	
mysql_free_result($respondent_query_result);
mysql_free_result($invitee_query_result);

echo $response_row_node->asXML();

?>