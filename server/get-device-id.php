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

if($action == "get-device-id")
{
	$return_value = 0;
	if(isset($_GET["action"]))
	{
		$return_value = get_device_id($_SESSION["db_conn"],
								 $_GET["user_id"]);
	}
	else
	{
		$return_value = get_device_id($_SESSION["db_conn"],
								 $_POST["user_id"]);
	}	

	echo $return_value;
}
?>