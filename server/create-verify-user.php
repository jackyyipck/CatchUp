<?php
include 'catchup-lib.php';
init_db();
header("Content-Type: text/xml; charset=utf-8");

$response_row_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');
$action = $_REQUEST["action"];


if($action == "create-verify-code")
{
	$return_value = "";
	$return_value = create_verify_code($_SESSION["db_conn"],
										$_REQUEST["user_mobile"], $_REQUEST["device_id"], $_REQUEST["device_token"]);	

	//mail("jackyyipck@gmail.com", "Catchup! User Registration", "Verification code: ".$verification_code, "From: noreply@seayu.hk");
	$response_row_node->addChild('user_id', $return_value[0]);
	$to_be_removed_node = $response_row_node->addChild('to_be_removed');
	$to_be_removed_node->addChild('verification_code',$return_value[1]);
}
elseif($action == "verify-user")
{
	$user_id = '';
	$input_verify_code = '';
	$user_id = $_REQUEST["user_id"];
	$user_mobile = $_REQUEST["user_mobile"];
	$device_id = $_REQUEST["device_id"];
	$device_token = $_REQUEST["device_token"];
	$input_verify_code = $_REQUEST["verification_code"];
	
	$verify_state_query_result = mysql_query(get_verify_state_sql($user_id), $_SESSION["db_conn"]);
	if ($verify_state_query_row = mysql_fetch_assoc($verify_state_query_result))
	{
		//Found the user ID
		$has_verified = $verify_state_query_row["has_verified"];
		$verification_code = $verify_state_query_row["verification_code"];
		
		if ($has_verified == 0 && $input_verify_code == $verification_code)
		{
			mysql_query(verify_user_sql($user_id, $input_verify_code), $_SESSION["db_conn"]);
			$response_row_node->addChild('verification_status','1');
		}
		else
		{
			//Something went wrong, reset status and verification code
			if ($input_verify_code != $verification_code)
			{				
				$response_row_node->addChild('failure_reason','Verification code does not match, status and code has been reset');				
			}
			elseif ($has_verified == 1)
			{
				$response_row_node->addChild('failure_reason','Duplicated verification, status and code has been reset');
			}
			
			$response_row_node->addChild('verification_status','0');
			$return_value = create_verify_code($_SESSION["db_conn"],$user_mobile, $device_id, $device_token);		
			$response_row_node->addChild('user_id', $user_id);
			$to_be_removed_node = $response_row_node->addChild('to_be_removed');
			$to_be_removed_node->addChild('verification_code',$return_value[1]);
		}
	}
	else
	{
		$response_row_node->addChild('verification_status','0');
		$response_row_node->addChild('failure_reason','User ID not found');
	}
}
elseif($action == "enrich-user")
{
	$return_value = "";
	$user_id = $_REQUEST["user_id"];
	$target_filename = "";
	if(isset($_FILES["user_avatar_filename"]["name"]))
	{
		$target_filename = "avatars/" . $user_id.time().$_FILES["user_avatar_filename"]["name"];
		move_uploaded_file($_FILES["user_avatar_filename"]["tmp_name"], $target_filename);
	}
	$return_value = enrich_user($_SESSION["db_conn"],
								 $_REQUEST["user_id"],
								 $_REQUEST["user_name"],
								 $target_filename,								 
								 $_REQUEST["user_email"],
								 $_REQUEST["user_status"]);
	$response_row_node->addChild('enrichment_status',$return_value);
}
elseif($action == "unlink-user")
{
	$user_id = $_REQUEST["user_id"];
	mysql_query(update_code_and_reset_state_sql($user_id, "", "", ""), $_SESSION["db_conn"]);
	$response_row_node->addChild('unlink_status',1);
}
elseif($action == "get-verify-state")
{
	$user_mobile = $_REQUEST["user_mobile"];	
	$return_value = get_verify_state_mobile($_SESSION["db_conn"], $user_mobile);
	$response_row_node->addChild('verify_state',$return_value);
}
echo $response_row_node->asXML();
?>