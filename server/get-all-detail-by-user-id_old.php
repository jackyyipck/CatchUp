<?php
include 'catchup-lib.php';

header("Content-Type: text/xml; charset=utf-8");

mysql_connect('localhost', 'seayu_catchup', 'anios');
mysql_select_db('seayu_catchup');
mysql_set_charset('utf8');

//XML compilation*****************
$event_list_query_row_node = new SimpleXMLElement('<events/>');

$event_sql = get_event_sql($_GET["user_id"]);
$event_list_query_result = mysql_query($event_sql);
while($event_list_query_row = mysql_fetch_assoc($event_list_query_result)) {
	
	$event_node = $event_list_query_row_node->addChild('event');
	$event_node->addAttribute('event_id',$event_list_query_row['event_id']);
	$event_node->addChild('event_name',$event_list_query_row['event_name']);
	$event_node->addChild('event_desc',$event_list_query_row['event_desc']);
	
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
		$invitee_node->addChild('invitees_name', $user_list_query_row['user_name']);
	}
	mysql_free_result($user_list_query_result);
	
	//******<options>*********
	$option_sql = get_option_sql($event_list_query_row['event_id']);
	$option_list_query_result = mysql_query($option_sql);
	$option_list_num_rows = mysql_num_rows($option_list_query_result);
	
	$event_node->addChild('options_num',$option_list_num_rows);	
	$options_node = $event_node->addChild('options');
	
	while($options_query_row = mysql_fetch_assoc($option_list_query_result)) 
	{
		$option_node = $options_node->addChild('option');
		$option_node->addChild('option_name',$options_query_row['option_name']);
		$option_node->addChild('option_desc',$options_query_row['option_desc']);
		
		//******<users>*********
		$user_vote_sql = get_user_vote_sql($options_query_row['option_id']);
		$user_vote_list_query_result = mysql_query($user_vote_sql);
		$users_node = $options_node->addChild('users');
		while($user_votes_query_row = mysql_fetch_assoc($user_vote_list_query_result)) 
		{
			$users_node->addChild('user_name',$user_votes_query_row['user_name']);
		}
		mysql_free_result($user_vote_list_query_result);
	}
	mysql_free_result($option_list_query_result);
}
mysql_free_result($event_list_query_result);

echo $event_list_query_row_node->asXML();
?>