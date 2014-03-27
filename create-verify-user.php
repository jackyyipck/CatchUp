<?php
include 'catchup-lib.php';
init_db();
header("Content-Type: text/xml; charset=utf-8");

$response_row_node = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');
$action = "";
if(isset($_GET["action"]))
{
	$action = $_GET["action"];
}
else
{
	$action = $_POST["action"];
}


if($action == "create-verify-code")
{
	$return_value = "";
	if(isset($_GET["action"]))
	{
		$return_value = create_verify_code($_SESSION["db_conn"],
								 $_GET["user_mobile"]);
	}
	else
	{
		$return_value = create_verify_code($_SESSION["db_conn"],
								 $_POST["user_mobile"]);
	}	

	//mail("jackyyipck@gmail.com", "Catchup! User Registration", "Verification code: ".$verification_code, "From: noreply@seayu.hk");
	$response_row_node->addChild('user_id', $return_value[0]);
	$to_be_removed_node = $response_row_node->addChild('to_be_removed');
	$to_be_removed_node->addChild('verification_code',$return_value[1]);
}
elseif($action == "verify-user")
{
	$user_id = '';
	$input_verfy_code = '';
	if(isset($_GET["action"]))
	{
		$user_id = $_GET["user_id"];
		$input_verfy_code = $_GET["verification_code"];
	}
	else
	{
		$user_id = $_POST["user_id"];
		$input_verfy_code = $_POST["verification_code"];
	}
	
	$verify_state_query_result = mysql_query(get_verify_state_sql($user_id), $_SESSION["db_conn"]);
	if ($verify_state_query_row = mysql_fetch_assoc($verify_state_query_result))
	{
		$has_verified = $verify_state_query_row["has_verified"];
		$verification_code = $verify_state_query_row["verification_code"];
		
		if ($has_verified == 0 && $input_verfy_code == $verification_code)
		{
			mysql_query(verify_user_sql($user_id, $input_verfy_code), $_SESSION["db_conn"]);
			$response_row_node->addChild('verification_status','1');
		}
		else
		{
			$response_row_node->addChild('verification_status','0');
			$return_value = reset_verify_code_and_state($_SESSION["db_conn"], $user_id);
			if ($input_verfy_code != $verification_code)
			{				
				$response_row_node->addChild('failure_reason','Verification code does not match, status and code has been reset');				
			}
			elseif ($has_verified == 1)
			{
				$response_row_node->addChild('failure_reason','Duplicated verification, status and code has been reset');
			}
			
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
	$user_id = '';
	if(isset($_GET["action"]))
	{
		$user_id = $_GET["user_id"];
	}
	else
	{
		$user_id = $_POST["user_id"];
	}
	if(isset($_GET["action"]))
	{
		$return_value = enrich_user($_SESSION["db_conn"],
								 $_GET["user_id"],
								 $_GET["user_name"],
								 "notestfile",								 
								 $_GET["user_email"]);
	}
	else
	{
		$target_filename = "avatars/" . $user_id.time().$_FILES["user_avatar_filename"]["name"];
		move_uploaded_file($_FILES["user_avatar_filename"]["tmp_name"], $target_filename);
		$return_value = enrich_user($_SESSION["db_conn"],
								 $_POST["user_id"],
								 $_POST["user_name"],
								 $target_filename,								 
								 $_POST["user_email"]);		
	}	
	$response_row_node->addChild('enrichment_status',$return_value);
}
elseif($action == "unlink-user")
{
	$user_id = '';
	if(isset($_GET["action"]))
	{
		$user_id = $_GET["user_id"];
	}
	else
	{
		$user_id = $_POST["user_id"];
	}
	reset_verify_code_and_state($_SESSION["db_conn"], $user_id);
	$response_row_node->addChild('unlink_status',1);
}
elseif($action == "get-verify-state")
{
	$user_mobile = '';
		if(isset($_GET["action"]))
	{
		$user_mobile = $_GET["user_mobile"];
	}
	else
	{
		$user_mobile = $_POST["user_mobile"];
	}
	$return_value = get_verify_state_mobile($_SESSION["db_conn"], $user_mobile);
	$response_row_node->addChild('verify_state',$return_value);
}
echo $response_row_node->asXML();
?>