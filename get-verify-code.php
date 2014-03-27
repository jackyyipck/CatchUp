<?php
include 'catchup-lib.php';
init_db();

$action = "";
if(isset($_GET["action"]))
{
	$action = $_GET["action"];
}
else
{
	$action = $_POST["action"];
}

if($action == "get-verify-code")
{
	$return_value = 0;
	if(isset($_GET["action"]))
	{
		$return_value = get_verify_code($_SESSION["db_conn"],
								 $_GET["user_mobile"]);
	}
	else
	{
		$return_value = get_verify_code($_SESSION["db_conn"],
								 $_POST["user_mobile"]);
	}	

	echo $return_value;
}
?>