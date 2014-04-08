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
	return mysql_query(create_vote_sql($option_id, $user_id), $db_conn);
}
function remove_vote($db_conn, $user_id, $option_id)
{
	return mysql_query(remove_vote_sql($option_id, $user_id), $db_conn);
}
function reset_verify_code_and_state($db_conn, $user_id)
{
	$verification_code = rand(100000,999999);
	mysql_query(reset_verify_code_and_state_sql($user_id, $verification_code), $db_conn);
	return array($user_id, $verification_code);
}
function enrich_user($db_conn, $user_id, $user_name, $user_avatar_filename, $user_email)
{
	return mysql_query(update_user_sql($user_id, $user_name, $user_avatar_filename, $user_email), $db_conn);
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
?>