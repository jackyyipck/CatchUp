<?php
include 'catchup-lib.php';
init_db();
header("Content-Type: text/xml; charset=utf-8");

	$return_value = 0;
	if(isset($_GET["action"]))
	{
		$return_value = get_vote_status($_SESSION["db_conn"],
								 $_GET["user_id"], $_GET["option_id"]);
	}
	else
	{
		$return_value = get_vote_status($_SESSION["db_conn"],
								 $_POST["user_id"], $_POST["option_id"]);
	}	

	echo $return_value;
	
//XML compilation*****************
$response_row_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');

$vote_status_sql = get_vote_status_sql($_GET["user_id"], $_GET["option_id"]);


?>