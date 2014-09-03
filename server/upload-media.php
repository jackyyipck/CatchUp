<?php
include 'catchup-lib.php';

init_db();

/* Core execution */
upload_media($_SESSION["db_conn"], 
			@$_REQUEST["event_id"],
			@$_REQUEST["create_by"],			
			@$_REQUEST["updated_at"],
			@$_REQUEST["media_type"],
			@$_FILES["media_file"]);
?>