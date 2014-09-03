<?php 
include 'catchup-sql.php';
date_default_timezone_set("Asia/Hong_Kong"); 
define("ENABLE_SECURITY_CHECK", false);
define("ENABLE_PUSH_NOTIFICATION", true);

function init_db()
{
	if ($_SERVER["HTTP_HOST"] == "www.seayu.hk")
	{
		$_SESSION["db_conn"] = mysql_connect('localhost', 'seayu_catchup', 'anios');
	}
	else
	{
		$_SESSION["db_conn"] = mysql_connect('localhost', 'root', '');
	}
	mysql_select_db('seayu_catchup');
	mysql_set_charset('utf8');
	
	if(ENABLE_SECURITY_CHECK)
	{
		// Enable security check
		if(isset($_REQUEST['security_key']) or isset($_SESSION['security_key']))
		{
			if(isset($_REQUEST['action']))
			{
				//Skip device ID when action is create/verify user related
				if($_REQUEST['action'] == "create-verify-code" || $_REQUEST['action'] == "verify-user" || 
					$_REQUEST['action'] == "unlink-user" || $_REQUEST['action'] == "get-verify-code")
				{
					if(!verify_security_pass('', $_REQUEST['security_key'], false))
					{
						header( 'Location: error.php?error_msg=Invalid security key from page '.$_SERVER['PHP_SELF']) ;
						exit;
					}
				}
			}
			elseif(!verify_security_pass($_SESSION["db_conn"], isset($_REQUEST['security_key'])?$_REQUEST['security_key']:$_SESSION['security_key'], true))
			{
				//echo "Invalid security key (from ".$_SERVER['PHP_SELF'].")";
				header( 'Location: error.php?error_msg=Invalid security key from page '.$_SERVER['PHP_SELF']) ;
				exit;
				//header( 'Location: error.php');
			}
		}
		else
		{
			//echo "Security key not found (from ".$_SERVER['PHP_SELF'].")";
			header( 'Location: error.php?error_msg=Security key not found from page '.$_SERVER['PHP_SELF']) ;
			exit;
			//header( 'Location: error.php');
		}
	}
}
function verify_security_pass($db_conn, $security_key, $withDeviceId)
{
	$current_timestamp = time();
	$secret_key = "TACHYON";
	$security_status = false;
	if($withDeviceId)
	{
		$user_id_query_result = mysql_query(get_verified_user_id_sql(), $db_conn);
		while($user_id_query_row = mysql_fetch_assoc($user_id_query_result))
		{
			$user_id = $user_id_query_row['user_id'];
			$device_id = $user_id_query_row['device_id'];
			for($i=0; $i<3; $i++)
			{
				$enryption_key = date("YmdHi",$current_timestamp - 60*($i - 1)).$device_id.$secret_key;
				$security_valid_passcode = sha1($enryption_key);
				if(!strcmp($security_valid_passcode, $security_key))
				{
					$security_status = true;
					break;
				}
			}
			if($security_status)
			{
				break;
			}
		}
		mysql_free_result($user_id_query_result);
	}
	else
	{
		for($i=0; $i<3; $i++)
		{
			$enryption_key = date("YmdHi",$current_timestamp - 60*($i - 1)).$secret_key;
			$security_valid_passcode = sha1($enryption_key);
			if(!strcmp($security_valid_passcode, $security_key))
			{
				$security_status = true;
				break;
			}
		}
	}	
	return $security_status;
}
function create_event_detail($db_conn, 
							$event_name, 
							$event_desc, 
							$start_at,
							$end_at,
							$expire_at, 
							$create_by, 
							$arr_option_name, 
							$arr_option_desc, 
							$arr_user_id)
{
	$event_id = create_event($db_conn, $event_name, $event_desc, $start_at, $end_at, $expire_at, $create_by);
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
							$updated_at,
							$start_at,
							$end_at,							
							$expire_at, 
							$create_by, 
							$is_allday,
							$is_public,
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
	if (mysql_query(create_or_update_event_sql($event_id, $event_name, $event_desc, $create_at, $updated_at, $start_at, $end_at, $expire_at, $create_by, $is_allday, $is_public, $target_filename), $db_conn))
	{
		if($event_id=='')
		{
			$event_id = mysql_insert_id($db_conn);
		}
	}
	if ($event_id > 0)
	{
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
		$result['event']['event_end_at'] = $event_query_row['event_end_at'];
		$result['event']['event_expire_at'] = $event_query_row['event_expire_at'];
		$result['event']['event_create_by'] = $event_query_row['event_create_by'];
		$result['event']['is_allday'] = $event_query_row['is_allday'];
		$result['event']['is_public'] = $event_query_row['is_public'];
		$result['event']['allow_vote'] = $event_query_row['allow_vote'];
		$result['event']['event_profile_filename'] = $event_query_row['event_profile_filename'];
	}
	mysql_free_result($event_query_result);
	
	//******<invitees>*********	
	$user_sql = get_user_sql_by_event_id($event_id);
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
function create_event($db_conn, $event_name, $event_desc, $start_at, $end_at, $expire_at, $create_by)
{
	$event_id = 0;
	if (mysql_query(create_event_sql($event_name, $event_desc, $start_at, $end_at, $expire_at, $create_by), $db_conn))
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

	$result = true;
	if(mysql_query(create_vote_sql($option_id, $user_id), $db_conn))
	{
		//Notification
		$user_query_result = mysql_query(get_user_id_in_group_has_option_sql($option_id), $db_conn);
		while($user_query_row = mysql_fetch_assoc($user_query_result))
		{
			if($user_id != $user_query_row["user_id"])
			{
				push_msg($db_conn, $user_query_row["user_id"], 
									get_user_name($db_conn, $user_id)." @ ".$user_query_row["event_name"].": vote updated", $user_query_row["event_id"]);
			}
		}
		mysql_free_result($user_query_result);
	}
	else
	{
		$result = false;
	}
	return $result;
}
function remove_vote($db_conn, $user_id, $option_id)
{
	return mysql_query(remove_vote_sql($option_id, $user_id), $db_conn);
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
function create_verify_code($db_conn, $user_mobile, $device_id, $device_token)
{
	$user_id = 0;
	$verification_code = rand(100000,999999);
	$exist_user_query_result = mysql_query(check_existing_user_sql($user_mobile), $db_conn);
	$exist_user_query_row = mysql_fetch_assoc($exist_user_query_result);
	if (mysql_num_rows($exist_user_query_result) == 0)
	{
		if (mysql_query(create_verify_code_sql($user_mobile, $verification_code, $device_id, $device_token), $db_conn))
		{
			$user_id = mysql_insert_id($db_conn);
		}
		return array($user_id, $verification_code);
	} else {
		$user_id = $exist_user_query_row['user_id'];
		mysql_query(update_code_and_reset_state_sql($user_id, $verification_code, $device_id, $device_token), $db_conn);
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
function create_comment_detail($db_conn, $user_comment, $create_by, $updated_at, $event_id)
{
	$comment_id = create_comments($db_conn, $user_comment, $create_by);
	if ($comment_id > 0)
	{	
		create_comment_event_pair($db_conn, $comment_id, $event_id);
		//Notification
		$user_query_result = mysql_query(get_user_id_in_group_has_comment_sql($comment_id));
		while($user_query_row = mysql_fetch_assoc($user_query_result))
		{
			if($user_query_row["user_id"] != $create_by)
			{
				push_msg($db_conn, $user_query_row["user_id"], 
						get_user_name($db_conn, $create_by)." @ ".$user_query_row["event_name"].": ".$user_comment, $user_query_row["event_id"]);
			}
		}
		mysql_query(update_time_sql($event_id, $updated_at));
		mysql_free_result($user_query_result);
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
function get_user_name($db_conn, $user_id)
{
	$user_name_query_result = mysql_query(get_user_name_sql($user_id), $db_conn);
	if ($user_name_query_row = mysql_fetch_assoc($user_name_query_result))
	{
		$return_result = $user_name_query_row['user_name'];
	}
	else
	{
		$return_result = 0;
	}
	return $return_result;
}
function write_log($type, $msg)
{
	//Type
	//0: Notice, 1: Warning, 2: Error
	if($type >= 0)
	{
		
		file_put_contents("log/log.txt", date("c")."\t".$type."\t".$msg."\n", FILE_APPEND);
	}
}
function get_device_token($db_conn, $user_id)
{
	$device_token_query_result = mysql_query(get_device_token_sql($user_id), $db_conn);
	if($device_token_query_row = mysql_fetch_assoc($device_token_query_result))
	{
		$return_result = $device_token_query_row['device_token'];
	}else
	{
		$return_result = 0;
	}
	return $return_result;
}
function push_msg($db_conn, $user_id, $msg, $event_id)
{
	if(!ENABLE_PUSH_NOTIFICATION)
	{
		write_log(0, "TO:".get_device_token($db_conn, $user_id)." CONTENT:".$msg);
	}
	else
	{
		$badge_count = get_badge_count($db_conn, $user_id);
		$badge_count++;
		$tToken = get_device_token($db_conn, $user_id);
		$tAlert = $msg;
		$tBody['aps'] = array (
								'alert' => $tAlert,
								'badge' => $badge_count,
								'sound' => 'default',
								);
		$tBody ['payload'] = $event_id;
		$tBody = json_encode ($tBody);
		$tContext = stream_context_create ();
		stream_context_set_option ($tContext, 'ssl', 'local_cert', 'CatchUpCertificateKey.pem');
		stream_context_set_option ($tContext, 'ssl', 'passphrase', 'Lov627er');
		$tSocket = stream_socket_client ('	', $error, $errstr, 30, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $tContext);
		if (!$tSocket)
			write_log(3, "APNS Connection Failed: $error $errstr" . PHP_EOL);
		$tMsg = chr (0) . chr (0) . chr (32) . pack ('H*', $tToken) . pack ('n', strlen ($tBody)) . $tBody;
		$tResult = fwrite ($tSocket, $tMsg, strlen ($tMsg));
		if ($tResult)
		{	
			write_log(0,'Delivered Message to APNS');
			update_badge_count($db_conn, $badge_count, $user_id);
		}
		else
		{
			write_log(1,'Could not Deliver Message to APNS');
		}
		fclose ($tSocket);
	}
}
function get_badge_count($db_conn, $user_id)
{
	$badge_count_query_result = mysql_query(get_badge_count_sql($user_id), $db_conn);
	if ($badge_count_query_row = mysql_fetch_assoc($badge_count_query_result))
	{
		$return_result = $badge_count_query_row['badge_count'];
	}
	else
	{
		$return_result = 0;
	}
	return $return_result;
}	
function update_badge_count($db_conn, $badge_count, $user_id)
{
	return mysql_query(update_badge_count_sql($badge_count, $user_id), $db_conn);	
}	
function remind_user($db_conn, $user_id, $event_id)
{
	//Notification
	$event_query_result = mysql_query(get_event_sql($user_id, false));
	$event_query_row = mysql_fetch_assoc($event_query_result);
	push_msg($db_conn, $user_id, 
						get_user_name($db_conn, $user_id)." @ ".$event_query_row["event_name"].": Please respond!", $event_query_row["event_id"]);
	mysql_free_result($event_query_result);
}
function trigger_vote($db_conn, $event_id, $vote_status)
{
	return mysql_query(trigger_vote_sql($event_id, $vote_status), $db_conn);
}
?>