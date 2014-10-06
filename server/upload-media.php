<?php
include 'catchup-lib.php';

init_db();

/* Core execution */
for($i=0; $i < count($_FILES["media_file"]["name"]); $i++)
{
	if(!empty($_FILES["media_file"]["name"][$i]))
	{
		upload_media($_SESSION["db_conn"], 
					@$_REQUEST["event_id"],
					@$_REQUEST["create_by"],			
					@$_REQUEST["updated_at"],
					@$_REQUEST["media_type"],
					$_FILES["media_file"]["name"][$i],
					$_FILES["media_file"]["tmp_name"][$i],
					$_FILES["media_file"]["error"][$i]);
	}
}
?>