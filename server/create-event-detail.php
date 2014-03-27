<?php
include 'catchup-lib.php';

init_db();
// Append creator to be invitee
$_POST["user_id"][] = $_POST["create_by"];

/* Core execution */
$event_id = create_event_detail($_SESSION["db_conn"], 
					$_POST["event_name"],
					$_POST["event_desc"],
					$_POST["expire_at"],
					$_POST["create_by"],
					$_POST["option_name"],
					$_POST["option_desc"],
					$_POST["user_id"]);
					
header( 'Location: get-all-detail-by-user-id.php?user_id='.$_POST["create_by"].'') ;

?>