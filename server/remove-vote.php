<?php
include 'catchup-lib.php';
init_db();

echo remove_vote($_SESSION["db_conn"],
			$_POST["user_id"],
			$_POST["option_id"]);
?>