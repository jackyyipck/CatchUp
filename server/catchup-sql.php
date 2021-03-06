<?php 

function get_device_token_sql($p_user_id)
{
	$sql = 'SELECT device_token
			FROM tbl_user
			WHERE 1=1
			AND user_id = '.$p_user_id;
	return $sql;
}
function get_option_sql($p_event_id)
{
	$sql = 'SELECT tbl_option.option_id, option_name, option_desc
			FROM tbl_option, tbl_event_option
			WHERE 1=1
			AND tbl_option.option_id = tbl_event_option.option_id
			AND tbl_event_option.event_id = '.$p_event_id;
	return $sql;
}
function get_option_latest_timestamp_sql($p_event_id)
{
	$sql = 'SELECT max(tbl_option_user.create_at) as latest_timestamp
			FROM tbl_event_option, tbl_option_user
			WHERE 1=1
			AND tbl_option_user.option_id = tbl_event_option.option_id
			AND tbl_event_option.event_id = '.$p_event_id;
	return $sql;
}
function get_voter_sql($p_option_id)
{
	$sql = 'SELECT tbl_user.user_id, user_name, create_at
			FROM tbl_user, tbl_option_user 
			WHERE 1=1
			AND tbl_user.user_id = tbl_option_user.user_id
			AND tbl_option_user.option_id = '.$p_option_id;
	return $sql;
}
function get_rsvp_sql($p_event_id)
{
	$sql = 'SELECT tbl_user.user_id, user_name
			FROM tbl_user, tbl_event_user
			WHERE 1=1
			AND tbl_user.user_id = tbl_event_user.user_id
			AND tbl_event_user.event_id = '.$p_event_id.'
			AND tbl_event_user.user_id NOT IN
				(
					SELECT user_id
					FROM tbl_option_user
					WHERE 1=1
					AND option_id IN
						(
							SELECT DISTINCT option_id
							FROM tbl_event_option
							WHERE 1=1
							AND event_id = '.$p_event_id.'
						)
				)';
	return $sql;
}
function get_distinct_respondent_sql($p_event_id)
{
	$sql = 'SELECT DISTINCT user_name
			FROM tbl_user, tbl_option_user
			WHERE 1=1
			AND tbl_user.user_id = tbl_option_user.user_id
			AND tbl_option_user.option_id in 
				(
					SELECT option_id
					FROM tbl_event_option
					WHERE 1=1
					AND tbl_event_option.event_id = '.$p_event_id.'
				)';			
	return $sql;
}	
function get_user_sql_by_event_id($p_event_id)
{
	$sql = 'SELECT tbl_user.user_id, user_name
			FROM tbl_user, tbl_event_user
			WHERE 1=1
			AND tbl_user.user_id= tbl_event_user.user_id
			AND tbl_event_user.event_id = '.$p_event_id;
	return $sql;
}
function get_user_sql_by_user_mobile($arr_user_mobile)
{
	$str_user_mobile = "";
	foreach($arr_user_mobile as $user_mobile) 
	{
		$str_user_mobile .= ",'".$user_mobile."'";
	}
	$sql = 'SELECT user_id, user_name, user_mobile, user_status
			FROM tbl_user
			WHERE 1=1
			AND has_verified = 1
			AND user_name IS NOT NULL
			AND user_mobile IN ('.substr($str_user_mobile,1).')
			ORDER BY user_name';
	return $sql;
}
function get_event_sql($p_user_id, $no_of_records, $is_expired)
{
	$sql = 'SELECT tbl_event.event_id, event_name, event_desc, event_create_at, event_updated_at, event_start_at, event_end_at, event_expire_at, event_create_by, is_allday, event_profile_filename, is_public, allow_vote
			FROM tbl_event, tbl_event_user
			WHERE 1=1
			AND tbl_event.event_id = tbl_event_user.event_id
			AND tbl_event_user.user_id = '.$p_user_id;
	
	if($is_expired)
	{
		$sql .= ' AND tbl_event.event_expire_at < NOW()';
	}
	else
	{
		$sql .= ' AND tbl_event.event_expire_at > NOW()';
	}		
	
	if($no_of_records > 0)
	{
		$sql .= ' ORDER BY tbl_event.event_updated_at DESC LIMIT '.$no_of_records;
	}
	else
	{
		$sql .= ' ORDER BY tbl_event.event_updated_at DESC';
	}
	
	return $sql;
}

function get_event_by_event_id_sql($p_event_id)
{
	$sql = 'SELECT event_id, event_name, event_desc, event_create_at, event_start_at, event_end_at, event_expire_at, event_create_by, is_allday, event_profile_filename, is_public, allow_vote
			FROM tbl_event
			WHERE 1=1
			AND event_id = '.$p_event_id;
	return $sql;
}

function get_user_vote_sql($p_option_id)
{
	$sql = 'SELECT tbl_user.user_id, user_name, create_at
			FROM tbl_user, tbl_option_user
			WHERE 1=1
			AND tbl_user.user_id = tbl_option_user.user_id
			AND tbl_option_user.option_id = '.$p_option_id;
	return $sql;
}
function create_event_sql($event_name, $event_desc, $start_at, $end_at, $expire_at, $create_by)
{
	$sql = "INSERT INTO tbl_event 
			(event_name, event_desc, event_start_at, event_end_at, event_expire_at, event_create_by) 
			VALUES 
			(
				'".$event_name."', 
				'".$event_desc."',
				'".$start_at."', 
				'".$end_at."',
				'".$expire_at."', 
				'".$create_by."'
			)";
	return $sql;		
}
function create_or_update_event_sql($event_id, $event_name, $event_desc, $create_at, $updated_at, $start_at, $end_at, $expire_at, $create_by, $is_allday, $is_public, $allow_vote, $event_profile_filename)
{
	$sql = "INSERT INTO tbl_event 
			(event_id, event_name, event_desc, event_create_at, event_updated_at, event_start_at, event_end_at, event_expire_at, event_create_by, is_allday, is_public, allow_vote, event_profile_filename) 
			VALUES 
			(
				'".$event_id."',
				'".$event_name."', 
				'".$event_desc."',
				'".$create_at."',
				'".$updated_at."',
				'".$start_at."', 
				'".$end_at."',
				'".$expire_at."', 
				'".$create_by."',
				'".$is_allday."',
				'".$is_public."',
				'".$allow_vote."',
				'".$event_profile_filename."'
				
			)
			ON DUPLICATE KEY UPDATE
			event_id = VALUES(event_id), 
			event_name = VALUES(event_name), 
			event_desc = VALUES(event_desc), 
			event_create_at = VALUES(event_create_at),
			event_updated_at = VALUES(event_updated_at), 
			event_start_at = VALUES(event_start_at), 
			event_end_at = VALUES(event_end_at),
			event_expire_at = VALUES(event_expire_at), 
			event_create_by = VALUES(event_create_by),	
			is_allday = VALUES(is_allday),
			allow_vote = VALUES(allow_vote),
			event_profile_filename = VALUES(event_profile_filename)
			";
	return $sql;		
}
function remove_event_sql($event_id)
{
	$sql = "DELETE FROM tbl_event WHERE event_id = ".$event_id;
	return $sql;
}
function create_option_sql($option_name, $option_desc)
{
	$sql = "INSERT INTO tbl_option 
			(option_name, option_desc) 
			VALUES 
			(
				'".$option_name."', 
				'".$option_desc."'
			)";
	return $sql;		
}
function delete_event_sql($event_id)
{
	$sql[] = "DELETE FROM tbl_option WHERE option_id IN (SELECT option_id FROM tbl_event_option WHERE event_id = '".$event_id."');";
	$sql[] = "DELETE FROM tbl_option_user WHERE option_id IN (SELECT option_id FROM tbl_event_option WHERE event_id = '".$event_id."');";
	$sql[] = "DELETE FROM tbl_event_user WHERE event_id = '".$event_id."';";
	$sql[] = "DELETE FROM tbl_event_option WHERE event_id = '".$event_id."';";
	$sql[] = "DELETE FROM tbl_event WHERE event_id = '".$event_id."';";
	$sql[] = "DELETE FROM tbl_comment WHERE comment_id in (SELECT comment_id FROM tbl_comment_event WHERE event_id = '".$event_id."');";
	$sql[] = "DELETE FROM tbl_comment_event WHERE event_id = '".$event_id."';";
	return $sql;
}
function create_or_update_option_sql($event_id, $option_id, $option_name, $option_desc)
{
	//If option name and desc are empty, delete the option id
	if($option_id != '' && $option_name == '' && $option_desc == ''){
		$sql[0] = "DELETE FROM tbl_option WHERE option_id = '".$option_id."';";
		$sql[1] = "DELETE FROM tbl_event_option WHERE option_id = '".$option_id."';";
	}else{
		$sql[0] = "INSERT INTO tbl_option 
				(option_id, option_name, option_desc) 
				VALUES 
				(
					'".$option_id."', 
					'".$option_name."', 
					'".$option_desc."'
				)
				ON DUPLICATE KEY UPDATE
				option_id = VALUES(option_id), 
				option_name = VALUES(option_name),
				option_desc = VALUES(option_desc),
				option_id = LAST_INSERT_ID(option_id);";
		$sql[1] = "INSERT INTO tbl_event_option 
				(event_id, option_id) 
				VALUES 
				(
					'".$event_id."', 
					LAST_INSERT_ID()
				)
				ON DUPLICATE KEY UPDATE
				event_id = VALUES(event_id), 
				option_id = VALUES(option_id)
				";
	}
	return $sql;		
}

function remove_option_sql($option_id)
{
	$sql = "DELETE FROM tbl_option WHERE option_id = ".$option_id;
	return $sql;
}
function create_event_option_pair_sql($event_id, $option_id)
{
	$sql = "INSERT INTO tbl_event_option 
			(event_id, option_id) 
			VALUES 
			(
				'".$event_id."', 
				'".$option_id."'
			)";
	return $sql;		
}
function remove_event_option_pair_sql($event_id, $option_id)
{
	$sql = "DELETE FROM tbl_event_option WHERE event_id = ".$event_id." AND option_id = ".$option_id;
	return $sql;
}
function create_event_user_pair_sql($event_id, $user_id)
{
	$sql = "INSERT INTO tbl_event_user
			(event_id, user_id) 
			VALUES 
			(
				'".$event_id."', 
				'".$user_id."'
			)";
	return $sql;		
}
function remove_event_user_pair_sql($event_id, $user_id)
{
	$sql = "DELETE FROM tbl_event_user WHERE event_id = ".$event_id." AND user_id = ".$user_id;
	return $sql;
}
function remove_invitee_sql($event_id)
{
	$sql = "DELETE FROM tbl_event_user WHERE event_id = ".$event_id;
	return $sql;
}
function create_verify_code_sql($user_mobile, $verification_code, $device_id, $device_token)
{
	$sql = "INSERT INTO tbl_user
			(user_mobile, verification_code, device_id, device_token, has_verified) 
			VALUES 
			(
				'".$user_mobile."',
				'".$verification_code."',
				'".$device_id."',
				'".$device_token."',
				0
			)";
	return $sql;		
}
function update_code_and_reset_state_sql($p_user_id, $verification_code, $device_id, $device_token)
{
	$sql = "UPDATE tbl_user
			SET verification_code = '".$verification_code."', 
			device_id = '".$device_id."',
			device_token = '".$device_token."',
			has_verified = 0
			WHERE 1=1
			AND user_id = '".$p_user_id."'";
	return $sql;		
}
function get_verify_state_sql($user_id)
{
	$sql = 'SELECT user_id, verification_code, has_verified
			FROM tbl_user
			WHERE 1=1
			AND user_id = '.$user_id;
	return $sql;		
}
function update_user_sql($user_id, $user_name, $user_avatar_filename, $user_email, $user_status)
{
	$sql = "UPDATE tbl_user
			SET user_name = '".$user_name."', 
			    user_avatar_filename = '".$user_avatar_filename."',
				user_email = '".$user_email."',
				user_status = '".$user_status."'
			WHERE user_id = '".$user_id."'";
	return $sql;		
}
function verify_user_sql($user_id, $verification_code)
{
	$sql = 'UPDATE tbl_user
			SET has_verified = 1
			WHERE 1=1
			AND user_id = '.$user_id.'
			AND verification_code = '.$verification_code.'
			AND has_verified = 0';
	return $sql;		
}
function remove_user_sql($user_id)
{
	$sql = "DELETE FROM tbl_user WHERE user_id = ".$user_id;
	return $sql;
}
function create_vote_sql($option_id, $user_id)
{
	$sql = "INSERT INTO tbl_option_user
			(option_id, user_id) 
			VALUES 
			(
				'".$option_id."', 
				'".$user_id."'
			)";
	return $sql;		
}
function remove_vote_sql($option_id, $user_id)
{
	$sql = "DELETE FROM tbl_option_user WHERE option_id = ".$option_id." AND user_id = ".$user_id;
	return $sql;
}
//primitive functions by Erwin
function check_existing_user_sql($user_mobile)
{
 	$sql = "SELECT user_id, user_mobile
 			FROM tbl_user
 			WHERE 1=1
 			AND user_mobile = '".$user_mobile."'";
 	return $sql;
}
function get_verify_state_mobile_sql($user_mobile)
{
	$sql = "SELECT user_mobile, verification_code, has_verified
			FROM tbl_user
			WHERE 1=1
			AND user_mobile = '".$user_mobile."'";
	return $sql;		
}
function get_comment_by_event_id_sql($p_event_id, $p_last_comment_id)
{
	$sql = 'SELECT tbl_comment.comment_id, create_by, create_at, comment
			FROM tbl_comment, tbl_comment_event
			WHERE 1=1
			AND tbl_comment.comment_id = tbl_comment_event.comment_id
			AND tbl_comment_event.event_id = '.$p_event_id.'
			AND tbl_comment.comment_id > '.$p_last_comment_id.'
			ORDER BY create_at';
	return $sql;
}
function get_comment_after_last_comment_id_sql($p_user_id, $p_last_comment_id)
{
	$sql = 'SELECT tbl_comment_event.event_id, tbl_comment.comment_id, create_by, create_at, comment
			FROM tbl_comment, tbl_comment_event
			WHERE 1=1
			AND tbl_comment.comment_id = tbl_comment_event.comment_id
			AND tbl_comment_event.event_id IN 
			(
				SELECT event_id
				FROM tbl_event_user
				WHERE user_id = '.$p_user_id.'
			)
			AND tbl_comment.comment_id > '.$p_last_comment_id.'
			ORDER BY event_id, comment_id';
	return $sql;
}
function get_userdetails_sql($p_user_id)
{
	$sql = 'SELECT tbl_user.user_id, user_name, user_avatar_filename, user_mobile, user_email, user_status
			FROM tbl_user
			WHERE tbl_user.user_id = '.$p_user_id;
	return $sql;
}
function create_comments_sql($user_comment, $create_by)
{
	$sql = "INSERT INTO tbl_comment
			(comment, create_by)
			VALUES
			(
				'".$user_comment."',
				'".$create_by."'
			)";
	return $sql;
}
function create_comment_event_pair_sql($comment_id, $event_id)
{
	$sql = "INSERT INTO tbl_comment_event
			(comment_id, event_id) 
			VALUES 
			(
				'".$comment_id."', 
				'".$event_id."'
			)";
	return $sql;		
}
function get_verify_code_sql($user_mobile)
{
	$sql = "SELECT tbl_user.verification_code
			FROM tbl_user
			WHERE tbl_user.user_mobile = '".$user_mobile."'";
	return $sql;
}
function get_device_id_sql($user_id)
{
	$sql = "SELECT tbl_user.device_id
			FROM tbl_user
			WHERE tbl_user.user_id = '".$user_id."'";
	return $sql;
}
function get_vote_status_sql($user_id, $p_option_id)
{
	$sql = "SELECT user_id, option_id, create_at
			FROM tbl_option_user 
			WHERE user_id = '".$user_id."'
			AND option_id = '".$p_option_id."'";
	return $sql;
}
function get_user_name_sql($p_user_id)
{
	$sql = "SELECT user_name 
			FROM tbl_user 
			WHERE user_id = '".$p_user_id."'";
	return $sql;
}
function get_unread_by_event_id_sql($p_event_id, $p_last_comment_id, $p_user_id)
{
	$sql = 'SELECT tbl_comment.comment_id, create_by, create_at, comment
			FROM tbl_comment, tbl_comment_event
			WHERE 1=1
			AND tbl_comment.comment_id = tbl_comment_event.comment_id
			AND tbl_comment_event.event_id = '.$p_event_id.'
			AND tbl_comment.comment_id > '.$p_last_comment_id.'
			AND tbl_comment.create_by <> '.$p_user_id.'
			ORDER BY create_at';
	return $sql;
}
function get_user_id_in_group_has_comment_sql($p_comment_id)
{
	$sql = "SELECT tbl_event.event_name, tbl_event.event_id, tbl_event_user.user_id
			FROM tbl_comment_event, tbl_event, tbl_event_user
			WHERE tbl_comment_event.event_id = tbl_event_user.event_id
			AND tbl_event.event_id = tbl_event_user.event_id
			AND comment_id = '".$p_comment_id."'";
	return $sql;
}
function get_user_id_in_group_has_option_sql($p_option_id)
{
	$sql = "SELECT tbl_event.event_name, tbl_event.event_id, tbl_event_user.user_id
			FROM tbl_event_option, tbl_event, tbl_event_user
			WHERE tbl_event.event_id = tbl_event_user.event_id
			AND tbl_event_option.event_id = tbl_event_user.event_id
			AND tbl_event_option.option_id = '".$p_option_id."'";
	return $sql;
}
function get_verified_user_id_sql()
{
	$sql = "SELECT user_id, device_id
			FROM tbl_user
			WHERE has_verified = 1";
	return $sql;
}
function get_badge_count_sql($p_user_id)
{
	$sql = "SELECT badge_count
			FROM tbl_user
			WHERE user_id = '".$p_user_id."'";
	return $sql;
}
function update_badge_count_sql($badge_count, $p_user_id)
{
	$sql = "UPDATE tbl_user
			SET badge_count = '".$badge_count."' 
			WHERE user_id = '".$p_user_id."'";
	return $sql;
}
function update_time_sql($event_id, $updated_at)
{
	$sql = "UPDATE tbl_event
			SET event_updated_at = '".$updated_at."'
			WHERE event_id = '".$event_id."'";
	return $sql;
}
function get_comment_sql_by_event_id($p_event_id, $p_user_id)
{
	$sql = "SELECT tbl_comment.comment_id
			FROM tbl_comment, tbl_comment_event
			WHERE 1=1
			AND tbl_comment.comment_id= tbl_comment_event.comment_id
			AND tbl_comment_event.event_id = '".$p_event_id."'
			AND tbl_comment.create_by <> '".$p_user_id."'";
	return $sql;
}
function trigger_vote_sql($event_id, $vote_status)
{
	$sql = "UPDATE tbl_event
			SET allow_vote = '".$vote_status."'
			WHERE event_id = '".$event_id."'";
	return $sql;
}
function update_profile_pic_sql($event_id, $target_filename)
{
	$sql = "UPDATE tbl_event 
			set event_profile_filename = '".$target_filename."'
			WHERE event_id ='".$event_id."'";
	return $sql;		
}
function add_event_media_sql($event_id, $create_by, $target_filename)
{
	$sql = "INSERT INTO tbl_event_media 
			(event_id, user_id, media_filename)
			VALUES
			(
				'".$event_id."',
				'".$create_by."',
				'".$target_filename."'
			)";
	return $sql;		
}
function get_media_by_event_id_sql($p_event_id)
{
	$sql = "SELECT media_id, user_id, create_at, media_filename 
			FROM tbl_event_media
			WHERE event_id = '".$p_event_id."'";
	return $sql;
}
function get_all_event_sql($p_user_id)
{
	$sql = "SELECT tbl_event.event_id, event_name, event_desc, event_create_at, event_updated_at, event_start_at, event_end_at, event_expire_at, event_create_by, is_allday, event_profile_filename, is_public, allow_vote
			FROM tbl_event, tbl_event_user
			WHERE 1=1
			AND tbl_event.event_id = tbl_event_user.event_id
			AND tbl_event_user.user_id = '".$p_user_id."'
			ORDER BY tbl_event.event_updated_at DESC";
	return $sql;
}
function create_feedback_sql($user_id, $feedback)
{
	$sql = "INSERT INTO tbl_feedback 
			(user_id, feedback) 
			VALUES 
			(
				'".$user_id."', 
				'".$feedback."'
			)";
	return $sql;	
}
?>