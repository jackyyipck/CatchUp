<?php
include 'catchup-lib.php';

init_db();

/* Core execution */
$event_id = create_comment_detail($_SESSION["db_conn"], 
					$_POST["user_comment"],
					$_POST["create_by"],
					$_POST["updated_at"],
					$_POST["event_id"]);
					
header( 'Location: get-comment-by-event-id.php?event_id='.$_POST["event_id"].'') ;

?>