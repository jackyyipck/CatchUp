<?php
include 'catchup-lib.php';

init_db();
// Append creator to be invitee
$_REQUEST["user_id"][] = $_REQUEST["create_by"];

/* Core execution */
if(@$_REQUEST["action"] == "delete"){
	delete_event_detail($_SESSION["db_conn"], $_REQUEST["event_id"]);
	header( 'Location: create-event-detail-test.php');
}else{
	$event_id = create_or_update_event_detail($_SESSION["db_conn"], 
						@$_REQUEST["event_id"],
						@$_REQUEST["event_name"],
						@$_REQUEST["event_desc"],
						@$_REQUEST["create_at"],
						@$_REQUEST["updated_at"],
						@$_REQUEST["start_at"],
						@$_REQUEST["expire_at"],
						@$_REQUEST["create_by"],
						@$_REQUEST["is_allday"],
						@$_REQUEST["is_public"],
						@$_REQUEST["option_id"],
						@$_REQUEST["option_name"],
						@$_REQUEST["option_desc"],
						@$_REQUEST["user_id"],
						@$_REQUEST["event_profile_pic_filename"],
						@$_FILES["event_profile_pic"]);
					
	header( 'Location: create-event-detail-test.php?event_id='.$event_id.'') ;
}
?>