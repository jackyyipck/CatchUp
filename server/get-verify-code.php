<?php
include 'catchup-lib.php';
init_db();

header("Content-Type: text/xml; charset=utf-8");

$action = $_REQUEST["action"];

if($action == "get-verify-code")
{
	echo get_verify_code($_SESSION["db_conn"],
								 $_REQUEST["user_mobile"]);
}
?>