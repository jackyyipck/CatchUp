<?php
include 'catchup-lib.php';
init_db();
header("Content-Type: text/xml; charset=utf-8");
mysql_set_charset('utf8');

//XML compilation*****************
$response_row_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');

$user_sql = get_userdetails_sql($_GET["user_id"]);
$user_query_result = mysql_query($user_sql);

while($user_query_row = mysql_fetch_assoc($user_query_result)) 
{
	
	$user_name_node = $response_row_node->addChild('user', $user_query_row['user_name']);
	$user_name_node->addAttribute('email',$user_query_row['user_email']);
	$user_name_node->addAttribute('status',$user_query_row['user_status']);

}

mysql_free_result($user_query_result);

echo $response_row_node->asXML();

?>