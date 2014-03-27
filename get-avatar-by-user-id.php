<?php
include 'catchup-lib.php';
init_db();
header("Content-Type: text/xml; charset=utf-8");
mysql_set_charset('utf8');

//XML compilation*****************
$response_row_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');

$avatar_sql = get_userdetails_sql($_GET["user_id"]);
$avatar_query_result = mysql_query($avatar_sql);

while($avatar_query_row = mysql_fetch_assoc($avatar_query_result)) 
{
	
	$avatar_name_node = $response_row_node->addChild('avatar', $avatar_query_row['user_avatar_filename']);
	$avatar_name_node->addAttribute('user_id',$avatar_query_row['user_id']);
	
}

mysql_free_result($avatar_query_result);

echo $response_row_node->asXML();

?>