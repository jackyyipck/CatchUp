<?php
include 'catchup-lib.php';
init_db();

echo trigger_vote($_SESSION["db_conn"],
			$_POST["event_id"],
			$_POST["vote_status"]);
?>