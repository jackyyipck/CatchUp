<?php
include 'catchup-lib.php';
init_db();
header("Content-Type: text/xml; charset=utf-8");

//XML compilation*****************
$option_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><option/>');

$voter_sql = get_voter_sql($_GET['option_id']);
$voter_query_result = mysql_query($voter_sql);

while($voter_query_row = mysql_fetch_assoc($voter_query_result)) 
{	
	$option_name_node = $option_node->addChild('voter_name', $voter_query_row['user_name']);
	$option_name_node->addAttribute('voter_id',$voter_query_row['user_id']);	
	$option_name_node->addAttribute('create_at',$voter_query_row['create_at']);	
	
	$userdetails_sql = get_userdetails_sql($voter_query_row['user_id']);
	$userdetails_query_result = mysql_query($userdetails_sql);
	$userdetails_query_row = mysql_fetch_assoc($userdetails_query_result);
	$option_name_node->addAttribute('voter_mobile', 	$userdetails_query_row['user_mobile']);
}
mysql_free_result($voter_query_result);

echo $option_node->asXML();

?>