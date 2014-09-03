<?php
include 'catchup-lib.php';
init_db();

$arr_user_id = $_REQUEST["user"];

for ($i = 0; $i < count($arr_user_id); $i++) 
{
	remind_user($_SESSION["db_conn"], $arr_user_id[$i], $_POST["event_id"]);
}
?>