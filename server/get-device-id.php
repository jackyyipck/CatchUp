<?php
include 'catchup-lib.php';
init_db();

$action = $_REQUEST["action"];
if($action == "get-device-id")
{
	echo get_device_id($_SESSION["db_conn"],$_REQUEST["user_id"]);
}
?>