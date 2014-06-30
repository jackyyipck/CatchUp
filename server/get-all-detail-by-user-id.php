<?php
include 'catchup-lib.php';

init_db();

header("Content-Type: text/xml; charset=utf-8");

//XML compilation*****************
$event_list_query_row_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><events/>');

$event_sql = get_event_sql($_REQUEST["user_id"]);
$event_list_query_result = mysql_query($event_sql);
while($event_list_query_row = mysql_fetch_assoc($event_list_query_result)) {
	
	$event_node = $event_list_query_row_node->addChild('event');
	$event_node->addAttribute('event_id',$event_list_query_row['event_id']);
	$event_node->addChild('event_name',$event_list_query_row['event_name']);
	$event_node->addChild('event_desc',$event_list_query_row['event_desc']);
	$event_node->addAttribute('event_create_at',$event_list_query_row['event_create_at']);
	$event_node->addAttribute('event_start_at',$event_list_query_row['event_start_at']);
	$event_node->addAttribute('event_expire_at',$event_list_query_row['event_expire_at']);
	$event_node->addAttribute('is_allday',$event_list_query_row['is_allday']);
	
	//******<invitor>*********
	$invitor_sql = get_userdetails_sql($event_list_query_row['event_create_by']);
	$invitor_list_query_result = mysql_query($invitor_sql);
	$invitor_node = $event_node->addChild('invitor');
	while($invitor_list_query_row = mysql_fetch_assoc($invitor_list_query_result))
	{
		$invitor_name_node = $invitor_node->addChild('event_create_by',$invitor_list_query_row['user_name']);
		$invitor_name_node->addAttribute('invitor_id', $event_list_query_row['event_create_by']);
	}
	mysql_free_result($invitor_list_query_result);
		
	//******<invitees>*********	
	$user_sql = get_user_sql($event_list_query_row['event_id']);
	$user_list_query_result = mysql_query($user_sql);
	$event_node->addChild('event_invitees',mysql_num_rows($user_list_query_result));	
	
	$distinct_respondent_sql = get_distinct_respondent_sql($event_list_query_row['event_id']);
	$distinct_respondent_query_result = mysql_query($distinct_respondent_sql);
	$event_node->addChild('event_respondents',mysql_num_rows($distinct_respondent_query_result));
	
	$invitee_node = $event_node->addChild('invitees');
	while($user_list_query_row = mysql_fetch_assoc($user_list_query_result)) 
	{		
		$invitee_name_node = $invitee_node->addChild('invitees_name', $user_list_query_row['user_name']);
		$invitee_name_node->addAttribute('invitees_id', $user_list_query_row['user_id']);
	}
	mysql_free_result($user_list_query_result);
	
	//******<options>*********
	$option_sql = get_option_sql($event_list_query_row['event_id']);
	$option_list_query_result = mysql_query($option_sql);
	$option_list_num_rows = mysql_num_rows($option_list_query_result);
	$option_latest_timestamp_sql = get_option_latest_timestamp_sql($event_list_query_row['event_id']);
	$option_latest_timestamp_row = mysql_fetch_assoc(mysql_query($option_latest_timestamp_sql));
	
	$event_node->addChild('options_num',$option_list_num_rows);	
	$options_node = $event_node->addChild('options');	
	$options_node->addAttribute('option_latest_timestamp', $option_latest_timestamp_row['latest_timestamp']);
	
	while($options_query_row = mysql_fetch_assoc($option_list_query_result)) 
	{
		$option_node = $options_node->addChild('option');
		$option_node->addAttribute('option_id',$options_query_row['option_id']);
		$option_node->addChild('option_name',$options_query_row['option_name']);
		$option_node->addChild('option_desc',$options_query_row['option_desc']);
		
		//******<users>*********
		$user_vote_sql = get_user_vote_sql($options_query_row['option_id']);
		$user_vote_list_query_result = mysql_query($user_vote_sql);
		$users_node = $option_node->addChild('users');
		while($user_votes_query_row = mysql_fetch_assoc($user_vote_list_query_result)) 
		{
			$user_node = $users_node->addChild('user_name',$user_votes_query_row['user_name']);
			$user_node->addAttribute('create_at',$user_votes_query_row['create_at']);
			$user_node->addAttribute('user_id',$user_votes_query_row['user_id']);
		}
		mysql_free_result($user_vote_list_query_result);
	}
	mysql_free_result($option_list_query_result);
}
mysql_free_result($event_list_query_result);

echo $event_list_query_row_node->asXML();
?>