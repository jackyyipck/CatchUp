<?php
include 'catchup-lib.php';
init_db();
$response_row_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');
$action = "";
if(isset($_GET["action"]))
{
	$action = $_GET["action"];
}
else
{
	$action = $_POST["action"];
}


if($action == "create-user")
{
	$return_value = "";
	if(isset($_GET["action"]))
	{
		$return_value = create_user($_SESSION["db_conn"],
								 $_GET["user_name"],
								 $_GET["user_avatar_filename"],
								 $_GET["user_mobile"],
								 $_GET["user_email"]);
	}
	else
	{
		$return_value = create_user($_SESSION["db_conn"],
								 $_POST["user_name"],
								 $_POST["user_avatar_filename"],
								 $_POST["user_mobile"],
								 $_POST["user_email"]);
	}	

	//mail("jackyyipck@gmail.com", "Catchup! User Registration", "Verification code: ".$verification_code, "From: noreply@seayu.hk");
	$response_row_node->addChild('user_id', $return_value[0]);
	$response_row_node->addChild('verification_code',$return_value[1]);
}
elseif($action == "verify-user")
{
	if(isset($_GET["action"]))
	{
		mysql_query(verify_user_sql($_GET["user_id"], $_GET["verification_code"]), $_SESSION["db_conn"]);
	}
	else
	{
		mysql_query(verify_user_sql($_POST["user_id"], $_POST["verification_code"]), $_SESSION["db_conn"]);
	}
	
	if (mysql_affected_rows() == 1)
	{
		$return_val = 1;
	}
	else
	{
		$return_val = 0;
	}
	$response_row_node->addChild('verification_status',$return_val);
}
echo $response_row_node->asXML();
?>