<?php 
include 'catchup-sql.php';

function init_db()
{
	if ($_SERVER["HTTP_HOST"] == "www.seayu.hk")
	{
		$_SESSION["db_conn"] = mysql_connect('localhost', 'seayu_catchup', 'anios');
	}else
	{
		$_SESSION["db_conn"] = mysql_connect('localhost', 'root', '');
	}
	mysql_select_db('seayu_catchup');
	mysql_set_charset('utf8');
}
function create_event_detail($db_conn, 
							$event_name, 
							$event_desc, 
							$start_at,
							$expire_at, 
							$create_by, 
							$arr_option_name, 
							$arr_option_desc, 
							$arr_user_id)
{
	$event_id = create_event($db_conn, $event_name, $event_desc, $start_at, $expire_at, $create_by);
	if ($event_id > 0)
	{
		for($i = 0; $i<count($arr_option_name); $i++)
		{			
			$option_id = create_option($db_conn, $arr_option_name[$i], $arr_option_desc[$i]);
			
			if($option_id > 0)
			{
				create_event_option_pair($db_conn, $event_id, $option_id);
			}
		}
		for($i = 0; $i<count($arr_user_id); $i++)
		{		
			create_event_user_pair($db_conn, $event_id, $arr_user_id[$i]);
		}
	}
	return $event_id;
}
function delete_event_detail($db_conn, $event_id)
{
	$arr_sql = delete_event_sql($event_id);
	for($i=0; $i<count($arr_sql); $i++)
	{
		mysql_query($arr_sql[$i]);
	}
}
function create_or_update_event_detail($db_conn, 
							$event_id,
							$event_name, 
							$event_desc, 
							$create_at,
							$start_at,							
							$expire_at, 
							$create_by, 
							$is_allday,
							$arr_option_id,
							$arr_option_name, 
							$arr_option_desc, 
							$arr_invitee_id,
							$event_profile_filename,
							$event_profile_pic_object
							)
{
	
	$target_filename = "";
	if (!empty($event_profile_pic_object["tmp_name"]) && is_uploaded_file($event_profile_pic_object["tmp_name"]))
	{
		echo "isset!";
		switch ($_FILES['event_profile_pic']['error']) 
		{
			case UPLOAD_ERR_OK:
				break;
			case UPLOAD_ERR_NO_FILE:
				throw new RuntimeException('No file sent.');
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				throw new RuntimeException('Exceeded filesize limit.');
			default:
				throw new RuntimeException('Unknown errors.');
		}	
		$target_filename = "avatars/event_".time().$event_profile_pic_object["name"];
		move_uploaded_file($event_profile_pic_object["tmp_name"], $target_filename);
	}
	else
	{	
		// If no file object, attempt to get filename
		$target_filename = $event_profile_filename;
	}
	if (mysql_query(create_or_update_event_sql($event_id, $event_name, $event_desc, $create_at, $start_at, $expire_at, $create_by, $is_allday, $target_filename), $db_conn))
	{
		if($event_id=='')
		{
			$event_id = mysql_insert_id($db_conn);
		}
	}
	if ($event_id > 0)
	{
		// Check if there exists "Not joining" option, append one if not
		$has_not_joining_option = false;
		for($i = 0; $i<count($arr_option_id); $i++)
		{
			if($arr_option_name[$i] == "Not joining"){
				$has_not_joining_option = true;
				break;
			}
		}
		if(!$has_not_joining_option){
			$arr_option_name[] = "Not joining";
			$arr_option_id [] = "";
		}
		for($i = 0; $i<count($arr_option_id); $i++)
		{
			//loop through all options
			// - Delete if option_name/option_desc is empty
			// - Create/Update otherwise
			if($arr_option_id[$i] == '' && $arr_option_name[$i] == ''){
				continue;
			}
			$arr_sql = create_or_update_option_sql($event_id, $arr_option_id[$i], $arr_option_name[$i], $arr_option_desc[$i]);
			mysql_query($arr_sql[0]);
			mysql_query($arr_sql[1]);
		}
		mysql_query(remove_invitee_sql($event_id));
		for($i = 0; $i<count($arr_invitee_id); $i++)
		{		
			//remove all invitee, and add back based on latest list of invitees
			if($arr_invitee_id[$i] != '' && $arr_invitee_id[$i] != 0){
				echo create_event_user_pair_sql($event_id, $arr_invitee_id[$i]);
				mysql_query(create_event_user_pair_sql($event_id, $arr_invitee_id[$i]), $db_conn);
			}
		}
	}
	return $event_id;
}

function get_event_detail($db_conn, $event_id)
{

	$event_sql = get_event_by_event_id_sql($event_id);
	$event_query_result = mysql_query($event_sql);
	$result = '';
	
	if($event_query_row = mysql_fetch_assoc($event_query_result)) 
	{
		$result['event']['event_id'] = $event_query_row['event_id'];
		$result['event']['event_name'] = $event_query_row['event_name'];
		$result['event']['event_desc'] = $event_query_row['event_desc'];
		$result['event']['event_create_at'] = $event_query_row['event_create_at'];
		$result['event']['event_start_at'] = $event_query_row['event_start_at'];
		$result['event']['event_expire_at'] = $event_query_row['event_expire_at'];
		$result['event']['event_create_by'] = $event_query_row['event_create_by'];
		$result['event']['is_allday'] = $event_query_row['is_allday'];
		$result['event']['event_profile_filename'] = $event_query_row['event_profile_filename'];
	}
	mysql_free_result($event_query_result);
	
	//******<invitees>*********	
	$user_sql = get_user_sql($event_id);
	$user_list_query_result = mysql_query($user_sql);
	$result['invitees_num'] = mysql_num_rows($user_list_query_result);	
	
	for($i=0; $user_list_query_row = mysql_fetch_assoc($user_list_query_result); $i++)  
	{		
		$result['invitee'][$i]['user_name'] = $user_list_query_row['user_name'];
		$result['invitee'][$i]['user_id'] = $user_list_query_row['user_id'];
	}
	mysql_free_result($user_list_query_result);
	
	//******<options>*********
	$option_sql = get_option_sql($event_id);
	$option_list_query_result = mysql_query($option_sql);
	$result['options_num'] = mysql_num_rows($option_list_query_result);	
	
	for($i=0; $options_query_row = mysql_fetch_assoc($option_list_query_result); $i++) 
	{
		$result['option'][$i]['option_id'] = $options_query_row['option_id'];
		$result['option'][$i]['option_name'] = $options_query_row['option_name'];
		$result['option'][$i]['option_desc'] = $options_query_row['option_desc'];			
	}
	mysql_free_result($option_list_query_result);

	return $result;
}

function create_event_option_pair($db_conn, $event_id, $option_id)
{
	return mysql_query(create_event_option_pair_sql($event_id, $option_id), $db_conn);	
}
function remove_event_option_pair($db_conn, $event_id, $option_id)
{
	return mysql_query(remove_event_option_pair_sql($event_id, $option_id), $db_conn);	
}
function create_event_user_pair($db_conn, $event_id, $user_id)
{
	$result = mysql_query(create_event_user_pair_sql($event_id, $user_id), $db_conn);	
	if(!$result)
	{
		echo mysql_error();
	}
	else
	{
		return $result;
	}
}
function remove_event_user_pair($db_conn, $event_id, $user_id)
{
	return mysql_query(remove_event_option_pair_sql($event_id, $user_id), $db_conn);	
}
function create_event($db_conn, $event_name, $event_desc, $start_at, $expire_at, $create_by)
{
	$event_id = 0;
	if (mysql_query(create_event_sql($event_name, $event_desc, $start_at, $expire_at, $create_by), $db_conn))
	{
		$event_id = mysql_insert_id($db_conn);
	}
	return $event_id;
}
function remove_event($db_conn, $event_id)
{
	return mysql_query(remove_event_sql($event_id), $db_conn);	
}
function create_option($db_conn, $option_name, $option_desc)
{
	$option_id = 0;
	if (mysql_query(create_option_sql($option_name, $option_desc), $db_conn))
	{
		$option_id = mysql_insert_id($db_conn);
	}
	return $option_id;
}
function remove_option_event($db_conn, $option_id)
{
	return mysql_query(remove_option_sql($option_id), $db_conn);	
}
function create_vote($db_conn, $user_id, $option_id)
{
	//TODO notification
	return mysql_query(create_vote_sql($option_id, $user_id), $db_conn);
}
function remove_vote($db_conn, $user_id, $option_id)
{
	return mysql_query(remove_vote_sql($option_id, $user_id), $db_conn);
}
function reset_verify_code_and_state($db_conn, $user_id)
{
	$verification_code = rand(100000,999999);
	mysql_query(reset_verify_code_and_state_sql($user_id, $verification_code, ""), $db_conn);
	return array($user_id, $verification_code);
}
function enrich_user($db_conn, $user_id, $user_name, $user_avatar_filename, $user_email, $user_status)
{
	return mysql_query(update_user_sql($user_id, $user_name, $user_avatar_filename, $user_email, $user_status), $db_conn);
}
function remove_user($db_conn, $user_id)
{
	return mysql_query(remove_user_sql($user_id), $db_conn);	
}
function is_valid_file_upload($file_object)
{
	$allowedExts = array("gif", "jpeg", "jpg", "png");
	$temp = explode(".", $file_object["name"]);
	$extension = end($temp);
	if ((($file_object["type"] == "image/gif")
			|| ($file_object["type"] == "image/jpeg")
			|| ($file_object["type"] == "image/jpg")
			|| ($file_object["type"] == "image/pjpeg")
			|| ($file_object["type"] == "image/x-png")
			|| ($file_object["type"] == "image/png"))
		&& ($file_object["size"] < 200000)
		&& in_array($extension, $allowedExts))
	{
		return true;
	}
	else
	{
		return false;
	}
}
function create_verify_code($db_conn, $user_mobile, $device_id)
{
	$user_id = 0;
	$verification_code = rand(100000,999999);
	$exist_user_query_result = mysql_query(check_existing_user_sql($user_mobile), $db_conn);
	$exist_user_query_row = mysql_fetch_assoc($exist_user_query_result);
	if (mysql_num_rows($exist_user_query_result) == 0)
	{
		if (mysql_query(create_verify_code_sql($user_mobile, $verification_code, $device_id), $db_conn))
		{
			$user_id = mysql_insert_id($db_conn);
		}
		return array($user_id, $verification_code);
	} else {
		$user_id = $exist_user_query_row['user_id'];
		mysql_query(reset_verify_code_and_state_sql($user_id, $verification_code, $device_id), $db_conn);
		return array($user_id, $verification_code);
	}
}
//primitive functions by Erwin
function get_verify_state($db_conn, $user_id)
{
	$verify_status_query_result = mysql_query(get_verify_state_sql($user_id), $db_conn);
	$verify_status_query_row = mysql_fetch_assoc($verify_status_query_result);
	if (mysql_num_rows($verify_status_query_result) <> 0)
	{
		$return_result = $verify_status_query_row['has_verified'];
	} else {
		$return_result = 0;
	}
	return $return_result;
}
function get_verify_state_mobile($db_conn, $user_mobile)
{
	$verify_status_query_result = mysql_query(get_verify_state_mobile_sql($user_mobile), $db_conn);
	$verify_status_query_row = mysql_fetch_assoc($verify_status_query_result);
	if (mysql_num_rows($verify_status_query_result) <> 0)
	{
		$return_result = $verify_status_query_row['has_verified'];
	} else {
		$return_result = 0;
	}
	return $return_result;
}
function create_comments($db_conn, $user_comment, $create_by)
{
	//TODO notification
	$comment_id = 0;
	if (mysql_query(create_comments_sql($user_comment, $create_by), $db_conn))
	{
		$comment_id = mysql_insert_id($db_conn);
	}
	return $comment_id;
}
function create_comment_event_pair($db_conn, $comment_id, $event_id)
{
	$result = mysql_query(create_comment_event_pair_sql($comment_id, $event_id), $db_conn);	
	if(!$result)
	{
		echo mysql_error();
	}
	else
	{
		return $result;
	}
}
function create_comment_detail($db_conn, $user_comment, $create_by, $event_id)
{
	$comment_id = create_comments($db_conn, $user_comment, $create_by);
	if ($comment_id > 0)
	{	
		create_comment_event_pair($db_conn, $comment_id, $event_id);
	}
	return $event_id;
}
function get_verify_code($db_conn, $user_mobile)
{
	$verify_code_query_result = mysql_query(get_verify_code_sql($user_mobile), $db_conn);
	$verify_code_query_row = mysql_fetch_assoc($verify_code_query_result);
	if (mysql_num_rows($verify_code_query_result) <> 0)
	{
		$return_result = $verify_code_query_row['verification_code'];
	} else {
		$return_result = 0;
	}
	return $return_result;
}
function get_device_id($db_conn, $user_id)
{
	$device_id_query_result = mysql_query(get_device_id_sql($user_id), $db_conn);
	$device_id_query_row = mysql_fetch_assoc($device_id_query_result);
	if (mysql_num_rows($device_id_query_result) <> 0)
	{
		$return_result = $device_id_query_row['device_id'];
	} else {
		$return_result = 0;
	}
	return $return_result;
}
function get_vote_status($db_conn, $user_id, $option_id)
{
	$vote_status_query_result = mysql_query(get_vote_status_sql($user_id, $option_id), $db_conn);
	$vote_status_query_row = mysql_fetch_assoc($vote_status_query_result);
	if (mysql_num_rows($vote_status_query_result) <> 0)
	{
		$return_result = 1;
	} else {
		$return_result = 0;
	}
	return $return_result;
}
?>