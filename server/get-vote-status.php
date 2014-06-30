<?php
include 'catchup-lib.php';
init_db();

$action = $_REQUEST["action"];

if($action == "get-vote-status")
{
	echo get_vote_status($_SESSION["db_conn"], $_REQUEST["user_id"], $_REQUEST["option_id"]);
}
?>