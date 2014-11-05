<?php
include 'catchup-lib.php';
init_db();

$action = $_REQUEST["action"];
if($action == "get-device-id")
{
	$device_id = get_device_id($_SESSION["db_conn"],$_REQUEST["user_id"]);
	$has_verified = get_verify_state($_SESSION["db_conn"],$_REQUEST["user_id"]);
	echo $has_verified.$device_id;
}
?>