<?php
include 'catchup-lib.php';
init_db();

$action = $_REQUEST["action"];

if($action == "get-verify-code")
{
	echo get_verify_code($_SESSION["db_conn"],
								 $_REQUEST["user_mobile"]);
}
?>