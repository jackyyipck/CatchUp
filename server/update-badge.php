<?php
include 'catchup-lib.php';
init_db();

echo update_badge_count($_SESSION["db_conn"],
			$_POST["badge_count"],
			$_POST["user_id"]);
?>