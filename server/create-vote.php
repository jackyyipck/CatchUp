<?php
include 'catchup-lib.php';

echo create_vote($_SESSION["db_conn"],
			$_POST["user_id"],
			$_POST["option_id"]);
?>