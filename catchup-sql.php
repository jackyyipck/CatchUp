<?php 
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
function get_user_sql($p_event_id)
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
	$sql = 'SELECT user_id, user_name, user_mobile
			FROM tbl_user
			WHERE 1=1
			AND user_mobile IN ('.substr($str_user_mobile,1).')';
	return $sql;
}
function get_event_sql($p_user_id)
{
	$sql = 'SELECT tbl_event.event_id, event_name, event_desc, event_create_at, event_expire_at, event_create_by
			FROM tbl_event, tbl_event_user
			WHERE 1=1
			AND tbl_event.event_id = tbl_event_user.event_id
			AND tbl_event_user.user_id = '.$p_user_id;
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
function create_event_sql($event_name, $event_desc, $expire_at, $create_by)
{
	$sql = "INSERT INTO tbl_event 
			(event_name, event_desc, event_expire_at, event_create_by) 
			VALUES 
			(
				'".$event_name."', 
				'".$event_desc."', 
				'".$expire_at."', 
				'".$create_by."'
			)";
	return $sql;		
}
function remove_event_sql($event_id)
{
	$sql = "DELETE tbl_event WHERE event_id = ".$event_id;
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
function remove_option_sql($option_id)
{
	$sql = "DELETE tbl_option WHERE option_id = ".$option_id;
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
	$sql = "DELETE tbl_event_option WHERE event_id = ".$event_id." AND option_id = ".$option_id;
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
	$sql = "DELETE tbl_event_user WHERE event_id = ".$event_id." AND user_id = ".$user_id;
	return $sql;
}
function create_verify_code_sql($user_mobile, $verification_code)
{
	$sql = "INSERT INTO tbl_user
			(user_name, user_mobile, verification_code, has_verified) 
			VALUES 
			(
				'TBD', 
				'".$user_mobile."',
				'".$verification_code."',
				0
			)";
	return $sql;		
}
function reset_verify_code_and_state_sql($p_user_id, $verification_code)
{
	$sql = "UPDATE tbl_user
			SET verification_code = '".$verification_code."', has_verified = 0
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
function update_user_sql($user_id, $user_name, $user_avatar_filename, $user_email)
{
	$sql = "UPDATE tbl_user
			SET user_name = '".$user_name."', 
			    user_avatar_filename = '".$user_avatar_filename."',
				user_email = '".$user_email."'
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
	$sql = "DELETE tbl_user WHERE user_id = ".$user_id;
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
	$sql = "DELETE tbl_option_user WHERE option_id = ".$option_id." AND user_id = ".$user_id;
	return $sql;
}
//primitive functions by Erwin
function check_existing_user_sql($user_mobile)
{
 	$sql = 'SELECT user_id, user_mobile
 			FROM tbl_user
 			WHERE 1=1
 			AND user_mobile = '.$user_mobile;
 	return $sql;
}
function get_verify_state_mobile_sql($user_mobile)
{
	$sql = 'SELECT user_mobile, verification_code, has_verified
			FROM tbl_user
			WHERE 1=1
			AND user_mobile = '.$user_mobile;
	return $sql;		
}
function get_comment_sql($p_event_id)
{
	$sql = 'SELECT tbl_comment.comment_id, create_by, create_at, comment
			FROM tbl_comment, tbl_comment_event
			WHERE 1=1
			AND tbl_comment.comment_id = tbl_comment_event.comment_id
			AND tbl_comment_event.event_id = '.$p_event_id.'
			ORDER BY create_at DESC';
	return $sql;
}
function get_userdetails_sql($p_user_id)
{
	$sql = 'SELECT tbl_user.user_id, user_name, user_avatar_filename, user_mobile, user_email
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
			WHERE tbl_user.user_mobile = ".$user_mobile;
	return $sql;
}
?>