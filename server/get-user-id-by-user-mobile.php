<?php
include 'catchup-lib.php';
init_db();
header("Content-Type: text/xml; charset=utf-8");

//XML compilation*****************
$response_row_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');
$user_sql = "";

if (isset($_GET["arr_user_mobile"]))
{
	$user_sql = get_user_sql_by_user_mobile($_GET["arr_user_mobile"]);
}
else
{
	$user_sql = get_user_sql_by_user_mobile($_POST["arr_user_mobile"]);
}

$user_query_result = mysql_query($user_sql);

while($user_query_row = mysql_fetch_assoc($user_query_result)) 
{
	$user_name_node = $response_row_node->addChild('user_name', $user_query_row['user_name']);
	$user_name_node->addAttribute('user_id',$user_query_row['user_id']);
	$user_name_node->addAttribute('user_mobile',$user_query_row['user_mobile']);
	$user_name_node->addAttribute('user_status',$user_query_row['user_status']);
}
mysql_free_result($user_query_result);

echo $response_row_node->asXML();

?>