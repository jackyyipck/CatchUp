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

if($action == "get-vote-status")
{
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
}
?>