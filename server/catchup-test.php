<table border="1">

<?php
include 'catchup-lib.php';
include 'xml-lib.php';

set_time_limit(300);

$_SESSION['security_key'] = get_security_key();
init_db();


test_data_cleanup();
create_test_user();


test_create_update_delete_event();

//event operation
test_empty_event();
test_multiple_invitee();
test_option();
test_vote();

// general query 
test_get_user_by_mobile();
test_create_and_verify_user();
test_get_comment();

function get_security_key()
{
	return sha1(date("YmdHi",time()).'deviceid001TACHYON');
}

function test_create_update_delete_event()
{
	$url = get_full_url("create-event-detail.php?");
	$event_name = "ABC".time();
	$url .= "event_name=".$event_name."&";
	$url .= "event_desc=TestEventCreationDesc&";
	$url .= "create_by=999005&";
	$url .= "create_at=20131022000000&";
	$url .= "start_at=20131022000011&";
	$url .= "expire_at=20131022000022&";
	$url .= "user_id[]=999007&";
	$url .= "is_allday=N&";
	$option_name1 = "O1".time();
	$option_name2 = "O2".time();
	$url .= "option_id[]=&option_name[]=".$option_name1."&";
	$url .= "option_id[]=&option_name[]=".$option_name2."&";
	$url .= "security_key=".get_security_key();
	file_get_contents($url);
	$query_result = mysql_fetch_assoc(mysql_query("SELECT event_id FROM tbl_event WHERE event_name = '".$event_name."'"));
	$event_id = $query_result['event_id'];
	$query_result = mysql_fetch_assoc(mysql_query("SELECT option_id FROM tbl_option WHERE option_name = '".$option_name1."'"));
	$option_id1 = $query_result['option_id'];
	$query_result = mysql_fetch_assoc(mysql_query("SELECT option_id FROM tbl_option WHERE option_name = '".$option_name2."'"));
	$option_id2 = $query_result['option_id'];
	if($event_id != 0)
	{
		printStr("Creating event using form");
		myAssertPass($url);		
		$url = get_full_url("get-all-detail-by-user-id.php?user_id=999005&security_key=".get_security_key());
		$actual = new SimpleXMLElement (file_get_contents($url));	
		$expected = new SimpleXMLElement('<events>
											<event event_id="'.$event_id.'" event_create_at="2013-10-22 00:00:00" event_start_at="2013-10-22 00:00:11" event_expire_at="2013-10-22 00:00:22" is_allday="0">
												<event_name>'.$event_name.'</event_name>
												<event_desc>TestEventCreationDesc</event_desc>
												<invitor><event_create_by invitor_id="999005">Tester5</event_create_by></invitor>
												<event_invitees>2</event_invitees>
												<event_respondents>0</event_respondents>
												<invitees>
													<invitees_name invitees_id="999005">Tester5</invitees_name>
													<invitees_name invitees_id="999007">Tester7</invitees_name>
												</invitees>
												<options_num>2</options_num>
												<options option_latest_timestamp="">
													<option option_id="'.$option_id1.'">
														<option_name>'.$option_name1.'</option_name><option_desc/>
														<users/>
													</option>
													<option option_id="'.$option_id2.'">
														<option_name>'.$option_name2.'</option_name><option_desc/>
														<users/>
													</option>
												</options>
											</event>
										</events>');
		printStr("Verifying event details ");
		myAssert($url, $actual, $expected);
		
		/** Test event attribute update **/
		printStr("Updating event invitee and option");
		$url = get_full_url("create-event-detail.php?");
		$event_name = "ABD".time();
		$url .= "event_id=".$event_id."&";
		$url .= "event_name=".$event_name."&";
		$url .= "event_desc=TestEventCreationDescB&";
		$url .= "create_by=999006&";
		$url .= "create_at=20131022000100&";
		$url .= "start_at=20131022000111&";
		$url .= "expire_at=20131022000122&";
		$url .= "user_id[]=999008&";		
		$url .= "is_allday=N&";
		$option_name1 = "O1".time();
		$option_name3 = "O3".time();
		$url .= "option_id[]=".$option_id1."&option_name[]=".$option_name1."&";
		$url .= "option_id[]=".$option_id2."&option_name[]=&";
		$url .= "option_id[]=&option_name[]=".$option_name3."&";
		$url .= "&security_key=".get_security_key();
		file_get_contents($url);
		$query_result = mysql_fetch_assoc(mysql_query("SELECT option_id FROM tbl_option WHERE option_name = '".$option_name3	."'"));
		$option_id3 = $query_result['option_id'];
		
		$url = get_full_url("get-all-detail-by-user-id.php?user_id=999006&security_key=".get_security_key());
		$actual = new SimpleXMLElement (file_get_contents($url));	
		$expected = new SimpleXMLElement('<events>
											<event event_id="'.$event_id.'" event_create_at="2013-10-22 00:01:00" event_start_at="2013-10-22 00:01:11" event_expire_at="2013-10-22 00:01:22" is_allday="0">
												<event_name>'.$event_name.'</event_name>
												<event_desc>TestEventCreationDescB</event_desc>
												<invitor><event_create_by invitor_id="999006">Tester6</event_create_by></invitor>
												<event_invitees>2</event_invitees>
												<event_respondents>0</event_respondents>
												<invitees>
													<invitees_name invitees_id="999006">Tester6</invitees_name>
													<invitees_name invitees_id="999008">Tester8</invitees_name>
												</invitees>
												<options_num>2</options_num>
												<options option_latest_timestamp="">
													<option option_id="'.$option_id1.'">
														<option_name>'.$option_name1.'</option_name><option_desc/>
														<users/>
													</option>
													<option option_id="'.$option_id3.'">
														<option_name>'.$option_name3.'</option_name><option_desc/>
														<users/>
													</option>
												</options>
											</event>
										</events>');
										
		myAssert($url, $actual, $expected);
		
		/** Test event deletion **/
		printStr("Deleting event");
		$url = get_full_url("create-event-detail.php?");
		$url .= "action=delete&";
		$url .= "event_id=".$event_id."&";
		$url .= "&security_key=".get_security_key();
		file_get_contents($url);
		
		$url = get_full_url("get-all-detail-by-user-id.php?user_id=999006&security_key=".get_security_key());
		$actual = new SimpleXMLElement (file_get_contents($url));	
		$expected = new SimpleXMLElement('<events/>');		
		myAssert($url, $actual, $expected);
		if (mysql_fetch_assoc(mysql_query("SELECT option_id FROM tbl_option WHERE option_id IN ('".$option_id1."','".$option_id2."','".$option_id3."')")))
			myAssertFail("Option Cleanup","");
		else
			myAssertPass("Option Cleanup");
		if (mysql_fetch_assoc(mysql_query("SELECT event_id FROM tbl_event_option WHERE option_id IN ('".$option_id1."','".$option_id2."','".$option_id3."')")))
			myAssertFail("Event-Option Cleanup","");
		else
			myAssertPass("Event-Option Cleanup");
		if (mysql_fetch_assoc(mysql_query("SELECT event_id FROM tbl_event WHERE event_id ='".$event_id."'")))
			myAssertFail("Event Cleanup","");
		else
			myAssertPass("Event Cleanup");
		if (mysql_fetch_assoc(mysql_query("SELECT event_id FROM tbl_event_user WHERE event_id ='".$event_id."'")))
			myAssertFail("Event-User Cleanup","");
		else
			myAssertPass("Event-User Cleanup");
	}
	else
	{
		myAssertFail($url, $event_id);
	}
}
function test_get_comment()
{
	$comment_user_id = array('999001','999001','999001','999002','999001','999003','999002');
	$comment_event_id = array('99981','99981','99982','99982','99982','99983','99983');
	for($i=0; $i<1; $i++)
	{
		$comment[$i] = 'Comment message '.time().$i;
		create_comment_detail($_SESSION["db_conn"], $comment[$i], $comment_user_id[$i], $comment_event_id[$i]);	
		$query_result = mysql_fetch_assoc(mysql_query("SELECT comment_id, create_at FROM tbl_comment WHERE comment = '".$comment[$i]."'"));
		$comment_create_at[$i] = $query_result['create_at'];
		$comment_id[$i] = $query_result['comment_id'];		
	}
	
	printStr("Created one test comment msg");
	
	$url = get_full_url("get-comment-by-event-id.php?event_id=99981&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<comment comment_id="'.$comment_id[0].'">
											<comment_string>'.$comment[0].'</comment_string>
											<commenter create_by="999001" create_at="'.$comment_create_at[0].'">Tester1</commenter>
										</comment>
									  </response>');
									
	myAssert($url, $actual, $expected);

	for($i=1; $i<3; $i++)
	{
		$comment[$i] = 'Comment message '.time().$i;
		create_comment_detail($_SESSION["db_conn"], $comment[$i], $comment_user_id[$i], $comment_event_id[$i]);	
		$query_result = mysql_fetch_assoc(mysql_query("SELECT comment_id, create_at FROM tbl_comment WHERE comment = '".$comment[$i]."'"));
		$comment_create_at[$i] = $query_result['create_at'];
		$comment_id[$i] = $query_result['comment_id'];		
	}
	printStr("Created one more test comment msg");					
	
	$url = get_full_url("get-comment-by-event-id.php?event_id=99981&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<comment comment_id="'.$comment_id[0].'">
											<comment_string>'.$comment[0].'</comment_string>
											<commenter create_by="999001" create_at="'.$comment_create_at[0].'">Tester1</commenter>
										</comment>
										<comment comment_id="'.$comment_id[1].'">
											<comment_string>'.$comment[1].'</comment_string>
											<commenter create_by="999001" create_at="'.$comment_create_at[1].'">Tester1</commenter>
										</comment>
									  </response>');
									  
	myAssert($url, $actual, $expected);	
	
	$url = get_full_url("get-comment-by-event-id.php?event_id=99981&last_comment_id=".$comment_id[0]."&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<comment comment_id="'.$comment_id[1].'">
											<comment_string>'.$comment[1].'</comment_string>
											<commenter create_by="999001" create_at="'.$comment_create_at[1].'">Tester1</commenter>
										</comment>
									  </response>');
									  
	myAssert($url, $actual, $expected);	

	/**********************************************************/
	// Testing by user id
	mysql_query("INSERT INTO tbl_event_user
				(event_id, user_id)
				VALUES 
				('99982',  '999001');");
	mysql_query("INSERT INTO tbl_event_user
				(event_id, user_id)
				VALUES 
				('99983',  '999001');");	
	
	for($i=3; $i<7; $i++)
	{
		$comment[$i] = 'Comment message '.time().$i;
		create_comment_detail($_SESSION["db_conn"], $comment[$i], $comment_user_id[$i], $comment_event_id[$i]);	
		$query_result = mysql_fetch_assoc(mysql_query("SELECT comment_id, create_at FROM tbl_comment WHERE comment = '".$comment[$i]."'"));
		$comment_create_at[$i] = $query_result['create_at'];
		$comment_id[$i] = $query_result['comment_id'];		
	}
	
	printStr("Created multiple events and messages by multiple users");					
	
	$url = get_full_url("get-comment-by-user-id.php?user_id=999001&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?> 
				<response>
					<event event_id="99981">
						<comment comment_id="'.$comment_id[0].'"
												 create_at="'.$comment_create_at[0].'"
												 create_by="999001"
												 commenter="Tester1">'.$comment[0].'
						</comment>
						<comment comment_id="'.$comment_id[1].'"
												 create_at="'.$comment_create_at[1].'"
												 create_by="999001"
												 commenter="Tester1">'.$comment[1].'
						</comment>
					</event>
					<event event_id="99982">
						<comment comment_id="'.$comment_id[2].'"
												 create_at="'.$comment_create_at[2].'"
												 create_by="999001"
												 commenter="Tester1">'.$comment[2].'
						</comment>
						<comment comment_id="'.$comment_id[3].'"
												 create_at="'.$comment_create_at[3].'"
												 create_by="999002"
												 commenter="Tester2">'.$comment[3].'
						</comment>
						<comment comment_id="'.$comment_id[4].'"
												 create_at="'.$comment_create_at[4].'"
												 create_by="999001"
												 commenter="Tester1">'.$comment[4].'
						</comment>
					</event>
					<event event_id="99983">
						<comment comment_id="'.$comment_id[5].'"
												 create_at="'.$comment_create_at[5].'"
												 create_by="999003"
												 commenter="Tester3">'.$comment[5].'
						</comment>
						<comment comment_id="'.$comment_id[6].'"
												 create_at="'.$comment_create_at[6].'"
												 create_by="999002"
												 commenter="Tester2">'.$comment[6].'
						</comment>
					</event>
				</response>');
									  
	myAssert($url, $actual, $expected);	
	
	$url = get_full_url("get-comment-by-user-id.php?user_id=999001&last_comment_id=".$comment_id[2]."&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?> 
					<response>
						<event event_id="99982">
						<comment comment_id="'.$comment_id[3].'"
												 create_at="'.$comment_create_at[3].'"
												 create_by="999002"
												 commenter="Tester2">'.$comment[3].'
						</comment>
						<comment comment_id="'.$comment_id[4].'"
												 create_at="'.$comment_create_at[4].'"
												 create_by="999001"
												 commenter="Tester1">'.$comment[4].'
						</comment>
					</event>
					<event event_id="99983">
						<comment comment_id="'.$comment_id[5].'"
												 create_at="'.$comment_create_at[5].'"
												 create_by="999003"
												 commenter="Tester3">'.$comment[5].'
						</comment>
						<comment comment_id="'.$comment_id[6].'"
												 create_at="'.$comment_create_at[6].'"
												 create_by="999002"
												 commenter="Tester2">'.$comment[6].'
						</comment>
					</event>
					</response>');
									  
	myAssert($url, $actual, $expected);	
}
function create_test_user()
{
	mysql_query("INSERT INTO tbl_user
				(user_id, user_name, user_avatar_filename, user_mobile, user_email, user_create_at, has_verified, device_id, device_token) 
				VALUES 
				('999001', 'Tester1', 'Avatar1', '852123456001', 'Tester1@test.com', '2012-03-04 05:06:07', 1, 'deviceid001', 'devicetoken001'),
				('999002', 'Tester2', 'Avatar2', '852123456002', 'Tester2@test.com', '2012-03-04 05:06:08', 1, 'deviceid002', 'devicetoken002'),
				('999003', 'Tester3', 'Avatar3', '852123456003', 'Tester3@test.com', '2012-03-04 05:06:09', 1, 'deviceid003', 'devicetoken003'),
				('999004', 'Tester4', 'Avatar4', '852123456004', 'Tester4@test.com', '2012-03-04 05:06:10', 1, 'deviceid004', 'devicetoken004'),
				('999005', 'Tester5', 'Avatar5', '852123456005', 'Tester5@test.com', '2012-03-04 05:06:11', 0, 'deviceid005', 'devicetoken005'),
				('999006', 'Tester6', 'Avatar6', '852123456006', 'Tester6@test.com', '2012-03-04 05:06:12', 1, 'deviceid006', 'devicetoken006'),
				('999007', 'Tester7', 'Avatar7', '852123456007', 'Tester7@test.com', '2012-03-04 05:06:13', 0, 'deviceid007', 'devicetoken007'),
				('999008', 'Tester8', 'Avatar8', '852123456008', 'Tester8@test.com', '2012-03-04 05:06:14', 1, 'deviceid008', 'devicetoken008');");
}
function test_create_and_verify_user()
{
	printStr("Creating and verifying accounts");
	
	$url = get_full_url("create-verify-user.php?action=create-verify-code&user_mobile=111111&device_id=111111&device_token=111111&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$actual_user_id = (string) $actual->user_id;
	$actual_verification_code = (string) $actual->to_be_removed->verification_code;
	if(strlen($actual_user_id) > 3 && strlen($actual_user_id) < 8 && strlen($actual_verification_code) == 6)
	{
		myAssertPass($url);
		
		$url = get_full_url("create-verify-user.php?action=verify-user&user_id=000000&verification_code=000000&device_id=111111&device_token=111111&security_key=".get_security_key());
		$actual = new SimpleXMLElement (file_get_contents($url));
		$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
											<verification_status>0</verification_status>
											<failure_reason>User ID not found</failure_reason>
										</response>');
		myAssert($url, $actual, $expected);
		
		$url = get_full_url("create-verify-user.php?action=verify-user&user_id=".$actual_user_id."&verification_code=000000&device_id=111111&device_token=111111&security_key=".get_security_key());
		$actual = new SimpleXMLElement (file_get_contents($url));
		if (((string) $actual->verification_status) == 0 && $actual->failure_reason == "Verification code does not match, status and code has been reset")
		{
			myAssertPass($url);
			$actual_verification_code = (string) $actual->to_be_removed->verification_code;
		}
		
		$url = get_full_url("create-verify-user.php?action=verify-user&user_id=".$actual_user_id."&verification_code=".$actual_verification_code."&device_id=111111&device_token=111111&security_key=".get_security_key());
		$actual = new SimpleXMLElement (file_get_contents($url));
		$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
											<verification_status>1</verification_status>
										</response>');
		myAssert($url, $actual, $expected);
		
		$url = get_full_url("create-verify-user.php?action=verify-user&user_id=".$actual_user_id."&verification_code=".$actual_verification_code."&device_id=111111&device_token=111111&security_key=".get_security_key());
		$actual = new SimpleXMLElement (file_get_contents($url));
		if (((string) $actual->verification_status) == 0 && $actual->failure_reason == "Duplicated verification, status and code has been reset")
		{
			myAssertPass($url);
		}
		
		$url = get_full_url("create-verify-user.php?action=enrich-user&user_id=".$actual_user_id."&user_name=AAA&user_email=abc&user_avatar_filename=abc&user_status=1&security_key=".get_security_key());
		$actual = new SimpleXMLElement (file_get_contents($url));
		$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
											<enrichment_status>1</enrichment_status>
										</response>');
		myAssert($url, $actual, $expected);
		
		$url = get_full_url("create-verify-user.php?action=unlink-user&user_id=".$actual_user_id."&security_key=".get_security_key());
		$actual = new SimpleXMLElement (file_get_contents($url));
		$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
											<unlink_status>1</unlink_status>
										</response>');
		myAssert($url, $actual, $expected);		
		
		mysql_query("DELETE FROM tbl_user WHERE user_id in (".$actual_user_id.");");		
	}
	else
	{
		myAssertFail($url, $actual);
	}
}

function test_get_user_by_mobile()
{
	printStr("Created user mobile session variables");
				
	$url = get_full_url("get-user-id-by-user-mobile.php?arr_user_mobile[]=852123456001&arr_user_mobile[]=852123456004&arr_user_mobile[]=000000000000&arr_user_mobile[]=852123456005&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<user_name user_id="999001" user_mobile="852123456001" user_status="">Tester1</user_name>
										<user_name user_id="999004" user_mobile="852123456004" user_status="">Tester4</user_name>
									</response>');
	myAssert($url, $actual, $expected);
}

function test_empty_event()
{			
	mysql_query("INSERT INTO tbl_event 
				(event_id, event_name, event_desc, event_create_at, event_start_at, event_expire_at, event_create_by) 
				VALUES 
				('99981', 'TestEvent1', 'TestEvent1 Description', '2013-10-22 00:00:00', '2013-10-30 00:00:01', '2013-10-30 00:00:02', '999001');");
				
	mysql_query("INSERT INTO tbl_event_user 
				(event_id, user_id)
				VALUES 
				('99981',  '999001');");
			
				
	printStr("Created empty event without option, and only creator as invitee");
				
	$url = get_full_url("get-all-detail-by-user-id.php?user_id=999001&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<events>
										<event event_id="99981" event_create_at="2013-10-22 00:00:00" event_start_at="2013-10-30 00:00:01" event_expire_at="2013-10-30 00:00:02" is_allday="0">
											<event_name>TestEvent1</event_name>
											<event_desc>TestEvent1 Description</event_desc>
											<invitor><event_create_by invitor_id="999001">Tester1</event_create_by></invitor>
											<event_invitees>1</event_invitees>
											<event_respondents>0</event_respondents>
											<invitees>
												<invitees_name invitees_id="999001">Tester1</invitees_name>
											</invitees>
											<options_num>0</options_num>
											<options option_latest_timestamp=""/>
										</event>
									</events>');
									
	
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-option-by-event-id.php?event_id=99981&user_id=0&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<event_name>TestEvent1</event_name>
										<event_desc>TestEvent1 Description</event_desc>
										<start_date>2013-10-30 00:00:01</start_date>
										<expiry_date>2013-10-30 00:00:02</expiry_date>
										<is_allday>0</is_allday>
										<option_name option_id="N" option_desc="" voters_num="1" vote_status="0">Pending RSVP</option_name>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-event-by-user-id.php?user_id=999003&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-event-by-user-id.php?user_id=999001&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<event_name>TestEvent1</event_name>
										<event_desc>TestEvent1 Description</event_desc>
										<is_allday>0</is_allday>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-rsvp-by-event-id.php?event_id=99981&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<rsvp_name rsvp_id="999001" rsvp_mobile="852123456001" rsvp_status="">Tester1</rsvp_name>
									</response>');
										
	myAssert($url, $actual, $expected);
										
}
function test_multiple_invitee()
{
	
	mysql_query("INSERT INTO tbl_event_user 
				(event_id, user_id)
				VALUES 
				('99981',  '999002'), ('99981',  '999004');");
				
	printStr("Added 2 more invitee");
	
	$url = get_full_url("get-all-detail-by-user-id.php?user_id=999001&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));	
	$expected = new SimpleXMLElement('<events>
										<event event_id="99981" event_create_at="2013-10-22 00:00:00" event_start_at="2013-10-30 00:00:01" event_expire_at="2013-10-30 00:00:02" is_allday="0">
											<event_name>TestEvent1</event_name>
											<event_desc>TestEvent1 Description</event_desc>
											<invitor><event_create_by invitor_id="999001">Tester1</event_create_by></invitor>
											<event_invitees>3</event_invitees>
											<event_respondents>0</event_respondents>
											<invitees>
												<invitees_name invitees_id="999001">Tester1</invitees_name>
												<invitees_name invitees_id="999002">Tester2</invitees_name>
												<invitees_name invitees_id="999004">Tester4</invitees_name>
											</invitees>
											<options_num>0</options_num>
											<options option_latest_timestamp=""/>
										</event>
									</events>');
									
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-option-by-event-id.php?event_id=99981&user_id=0&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<event_name>TestEvent1</event_name><event_desc>TestEvent1 Description</event_desc>
										<start_date>2013-10-30 00:00:01</start_date><expiry_date>2013-10-30 00:00:02</expiry_date>
										<is_allday>0</is_allday>
										<option_name option_id="N" option_desc="" voters_num="3" vote_status="0">Pending RSVP</option_name>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-rsvp-by-event-id.php?event_id=99981&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<rsvp_name rsvp_id="999001" rsvp_mobile="852123456001" rsvp_status="">Tester1</rsvp_name>
										<rsvp_name rsvp_id="999002" rsvp_mobile="852123456002" rsvp_status="">Tester2</rsvp_name>
										<rsvp_name rsvp_id="999004" rsvp_mobile="852123456004" rsvp_status="">Tester4</rsvp_name>
									</response>');
										
	myAssert($url, $actual, $expected);
	
}
function test_option()
{
	mysql_query("INSERT INTO tbl_option
				(option_id, option_name, option_desc) 
				VALUES 
				('99881', 'Option 1', 'Option Desc 1'),('99882', 'Option 2', 'Option Desc 2'),('99883', 'Option 3', 'Option Desc 3');");
				
	mysql_query("INSERT INTO tbl_event_option 
				(event_id, option_id)
				VALUES 
				('99981',  '99881'), ('99981',  '99882'), ('99981',  '99883');");
	
	printStr("Added 3 option");
	
	$url = get_full_url("get-all-detail-by-user-id.php?user_id=999001&security_key=".get_security_key());	
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<events>
										<event event_id="99981" event_create_at="2013-10-22 00:00:00" event_start_at="2013-10-30 00:00:01" event_expire_at="2013-10-30 00:00:02" is_allday="0">
											<event_name>TestEvent1</event_name>
											<event_desc>TestEvent1 Description</event_desc>
											<invitor><event_create_by invitor_id="999001">Tester1</event_create_by></invitor>
											<event_invitees>3</event_invitees>
											<event_respondents>0</event_respondents>
											<invitees>
												<invitees_name invitees_id="999001">Tester1</invitees_name>
												<invitees_name invitees_id="999002">Tester2</invitees_name>
												<invitees_name invitees_id="999004">Tester4</invitees_name>
											</invitees>
											<options_num>3</options_num>
											<options option_latest_timestamp="">
												<option option_id="99881">
													<option_name>Option 1</option_name>
													<option_desc>Option Desc 1</option_desc>
													<users/>
												</option>
												<option option_id="99882">
													<option_name>Option 2</option_name>
													<option_desc>Option Desc 2</option_desc>
													<users/>
												</option>
												<option option_id="99883">
													<option_name>Option 3</option_name>
													<option_desc>Option Desc 3</option_desc>
													<users/>
												</option>
											</options>
										</event>
									</events>');
									
	myAssert($url, $actual, $expected);
	//TODO: why by-event-id requires user-id?
	$url = get_full_url("get-option-by-event-id.php?event_id=99981&user_id=0&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<event_name>TestEvent1</event_name><event_desc>TestEvent1 Description</event_desc>
										<start_date>2013-10-30 00:00:01</start_date><expiry_date>2013-10-30 00:00:02</expiry_date><is_allday>0</is_allday>
										<option_name option_id="99881" option_desc="Option Desc 1" voters_num="0"  vote_status="0">Option 1</option_name>
										<option_name option_id="99882" option_desc="Option Desc 2" voters_num="0" vote_status="0">Option 2</option_name>
										<option_name option_id="99883" option_desc="Option Desc 3" voters_num="0" vote_status="0">Option 3</option_name>
										<option_name option_id="N" option_desc="" voters_num="3" vote_status="0">Pending RSVP</option_name>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-voter-by-option-id.php?option_id=99881&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><option/>');
										
	myAssert($url, $actual, $expected);
}
function test_vote()
{
		
	/*mysql_query("INSERT INTO tbl_option_user 
				(option_id, user_id, create_at)
				VALUES 
				('99881',  '999001', '2013-10-22 00:01:00');");
	*/
	create_vote($_SESSION['db_conn'], '999001', '99881');
	$query_result = mysql_fetch_assoc(mysql_query("SELECT create_at FROM tbl_option_user 
													WHERE option_id = '99881'
													AND	user_id = '999001'"));
	$vote_create_at[0] = $query_result['create_at'];	
		
	printStr("Added one vote");
		
		
		
	$url = get_full_url("get-all-detail-by-user-id.php?user_id=999001&security_key=".get_security_key());	
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<events>
										<event event_id="99981" event_create_at="2013-10-22 00:00:00" event_start_at="2013-10-30 00:00:01" event_expire_at="2013-10-30 00:00:02" is_allday="0">
											<event_name>TestEvent1</event_name>
											<event_desc>TestEvent1 Description</event_desc>
											<invitor><event_create_by invitor_id="999001">Tester1</event_create_by></invitor>
											<event_invitees>3</event_invitees>
											<event_respondents>1</event_respondents>
											<invitees>
												<invitees_name invitees_id="999001">Tester1</invitees_name>
												<invitees_name invitees_id="999002">Tester2</invitees_name>
												<invitees_name invitees_id="999004">Tester4</invitees_name>
											</invitees>
											<options_num>3</options_num>
											<options option_latest_timestamp="'.$vote_create_at[0].'">
												<option option_id="99881">
													<option_name>Option 1</option_name>
													<option_desc>Option Desc 1</option_desc>
													<users>
														<user_name create_at="'.$vote_create_at[0].'" user_id="999001">Tester1</user_name>
													</users>
												</option>
												<option option_id="99882">
													<option_name>Option 2</option_name>
													<option_desc>Option Desc 2</option_desc>
													<users/>
												</option>
												<option option_id="99883">
													<option_name>Option 3</option_name>
													<option_desc>Option Desc 3</option_desc>
													<users/>
												</option>
											</options>
										</event>
									</events>');
									
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-option-by-event-id.php?event_id=99981&user_id=0&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<event_name>TestEvent1</event_name><event_desc>TestEvent1 Description</event_desc>
										<start_date>2013-10-30 00:00:01</start_date>
										<expiry_date>2013-10-30 00:00:02</expiry_date>
										<is_allday>0</is_allday>
										<option_name option_id="99881" option_desc="Option Desc 1"  voters_num="1" vote_status="0">Option 1</option_name>
										<option_name option_id="99882" option_desc="Option Desc 2" voters_num="0" vote_status="0">Option 2</option_name>
										<option_name option_id="99883" option_desc="Option Desc 3" voters_num="0" vote_status="0">Option 3</option_name>
										<option_name option_id="N" option_desc="" voters_num="2" vote_status="0">Pending RSVP</option_name>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-rsvp-by-event-id.php?event_id=99981&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<rsvp_name rsvp_id="999002" rsvp_mobile="852123456002" rsvp_status="">Tester2</rsvp_name>
										<rsvp_name rsvp_id="999004" rsvp_mobile="852123456004" rsvp_status="">Tester4</rsvp_name>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-voter-by-option-id.php?option_id=99881&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><option>
										<voter_name voter_id="999001" create_at="'.$vote_create_at[0].'" voter_mobile="852123456001" voter_status="">Tester1</voter_name>
									</option>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-voter-by-option-id.php?option_id=99882&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><option/>');
										
	myAssert($url, $actual, $expected);

	//test_vote_by_same_invitee()

		
	/*mysql_query("INSERT INTO tbl_option_user 
				(option_id, user_id, create_at)
				VALUES 
				('99882',  '999001', '2013-10-22 00:02:00');");
	*/			
	create_vote($_SESSION['db_conn'], '999001', '99882');
	$query_result = mysql_fetch_assoc(mysql_query("SELECT create_at FROM tbl_option_user 
													WHERE option_id = '99882'
													AND	user_id = '999001'"));
	$vote_create_at[1] = $query_result['create_at'];
	printStr("Added one more vote from same invitee");
	
	$url = get_full_url("get-all-detail-by-user-id.php?user_id=999001&security_key=".get_security_key());	
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<events>
										<event event_id="99981" event_create_at="2013-10-22 00:00:00" event_start_at="2013-10-30 00:00:01" event_expire_at="2013-10-30 00:00:02" is_allday="0">
											<event_name>TestEvent1</event_name>
											<event_desc>TestEvent1 Description</event_desc>
											<invitor><event_create_by invitor_id="999001">Tester1</event_create_by></invitor>
											<event_invitees>3</event_invitees>
											<event_respondents>1</event_respondents>
											<invitees>
												<invitees_name invitees_id="999001">Tester1</invitees_name>
												<invitees_name invitees_id="999002">Tester2</invitees_name>
												<invitees_name invitees_id="999004">Tester4</invitees_name>
											</invitees>
											<options_num>3</options_num>
											<options option_latest_timestamp="'.$vote_create_at[1].'">
												<option option_id="99881">
													<option_name>Option 1</option_name>
													<option_desc>Option Desc 1</option_desc>
													<users>
														<user_name create_at="'.$vote_create_at[0].'" user_id="999001">Tester1</user_name>
													</users>
												</option>
												<option option_id="99882">
													<option_name>Option 2</option_name>
													<option_desc>Option Desc 2</option_desc>
													<users>
														<user_name create_at="'.$vote_create_at[1].'" user_id="999001">Tester1</user_name>
													</users>
												</option>
												<option option_id="99883">
													<option_name>Option 3</option_name>
													<option_desc>Option Desc 3</option_desc>
													<users/>
												</option>
											</options>
										</event>
									</events>');
									
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-option-by-event-id.php?event_id=99981&user_id=0&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<event_name>TestEvent1</event_name><event_desc>TestEvent1 Description</event_desc>
										<start_date>2013-10-30 00:00:01</start_date><expiry_date>2013-10-30 00:00:02</expiry_date><is_allday>0</is_allday>
										<option_name option_id="99881" option_desc="Option Desc 1" voters_num="1"  vote_status="0">Option 1</option_name>
										<option_name option_id="99882" option_desc="Option Desc 2" voters_num="1"  vote_status="0">Option 2</option_name>
										<option_name option_id="99883" option_desc="Option Desc 3" voters_num="0"  vote_status="0">Option 3</option_name>
										<option_name option_id="N" option_desc="" voters_num="2" vote_status="0">Pending RSVP</option_name>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-rsvp-by-event-id.php?event_id=99981&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<rsvp_name rsvp_id="999002" rsvp_mobile="852123456002" rsvp_status="">Tester2</rsvp_name>
										<rsvp_name rsvp_id="999004" rsvp_mobile="852123456004" rsvp_status="">Tester4</rsvp_name>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	
	// test_vote_by_multiple_invitee()

		
	/*mysql_query("INSERT INTO tbl_option_user 
				(option_id, user_id, create_at)
				VALUES 
				('99882',  '999002', '2013-10-22 00:03:00'), ('99883',  '999004', '2013-10-22 00:04:00');");
	*/
	create_vote($_SESSION['db_conn'], '999002', '99882');
	$query_result = mysql_fetch_assoc(mysql_query("SELECT create_at FROM tbl_option_user 
													WHERE option_id = '99882'
													AND	user_id = '999002'"));
	$vote_create_at[2] = $query_result['create_at'];
	
	create_vote($_SESSION['db_conn'], '999004', '99883');
	$query_result = mysql_fetch_assoc(mysql_query("SELECT create_at FROM tbl_option_user 
													WHERE option_id = '99883'
													AND	user_id = '999004'"));
	$vote_create_at[3] = $query_result['create_at'];
	
	printStr("Added two more vote from multiple invitee");
	
	$url = get_full_url("get-all-detail-by-user-id.php?user_id=999001&security_key=".get_security_key());	
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<events>
										<event event_id="99981" event_create_at="2013-10-22 00:00:00" event_start_at="2013-10-30 00:00:01" event_expire_at="2013-10-30 00:00:02" is_allday="0">
											<event_name>TestEvent1</event_name>
											<event_desc>TestEvent1 Description</event_desc>
											<invitor><event_create_by invitor_id="999001">Tester1</event_create_by></invitor>
											<event_invitees>3</event_invitees>
											<event_respondents>3</event_respondents>
											<invitees>
												<invitees_name invitees_id="999001">Tester1</invitees_name>
												<invitees_name invitees_id="999002">Tester2</invitees_name>
												<invitees_name invitees_id="999004">Tester4</invitees_name>
											</invitees>
											<options_num>3</options_num>
											<options option_latest_timestamp="'.$vote_create_at[3].'">
												<option option_id="99881">
													<option_name>Option 1</option_name>
													<option_desc>Option Desc 1</option_desc>
													<users>
														<user_name create_at="'.$vote_create_at[0].'" user_id="999001">Tester1</user_name>
													</users>
												</option>
												<option option_id="99882">
													<option_name>Option 2</option_name>
													<option_desc>Option Desc 2</option_desc>
													<users>
														<user_name create_at="'.$vote_create_at[1].'" user_id="999001">Tester1</user_name>
														<user_name create_at="'.$vote_create_at[2].'" user_id="999002">Tester2</user_name>
													</users>
												</option>
												<option option_id="99883">
													<option_name>Option 3</option_name>
													<option_desc>Option Desc 3</option_desc>
													<users>
														<user_name create_at="'.$vote_create_at[3].'" user_id="999004">Tester4</user_name>
													</users>
												</option>
											</options>
										</event>
									</events>');
									
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-option-by-event-id.php?event_id=99981&user_id=0&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<event_name>TestEvent1</event_name><event_desc>TestEvent1 Description</event_desc>
										<start_date>2013-10-30 00:00:01</start_date><expiry_date>2013-10-30 00:00:02</expiry_date><is_allday>0</is_allday>
										<option_name option_id="99881" option_desc="Option Desc 1" voters_num="1" vote_status="0">Option 1</option_name>
										<option_name option_id="99882" option_desc="Option Desc 2" voters_num="2" vote_status="0">Option 2</option_name>
										<option_name option_id="99883" option_desc="Option Desc 3" voters_num="1" vote_status="0">Option 3</option_name>
										<option_name option_id="N" option_desc="" voters_num="0" vote_status="0">Pending RSVP</option_name>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-rsvp-by-event-id.php?event_id=99981&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-voter-by-option-id.php?option_id=99882&security_key=".get_security_key());
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><option>
										<voter_name voter_id="999001" create_at="'.$vote_create_at[1].'" voter_mobile="852123456001" voter_status="">Tester1</voter_name>
										<voter_name voter_id="999002" create_at="'.$vote_create_at[2].'" voter_mobile="852123456002" voter_status="">Tester2</voter_name>
									</option>');
										
	myAssert($url, $actual, $expected);
}
function test_data_cleanup()
{	
	mysql_query("DELETE FROM tbl_user WHERE user_id in (999001,999002,999003,999004,999005,999006,999007,999008);");
	mysql_query("DELETE FROM tbl_event WHERE event_id in (SELECT event_id FROM tbl_event_user WHERE user_id IN (999005,999006));");
	mysql_query("DELETE FROM tbl_event WHERE event_id in (99981, 99982, 99983);");
	mysql_query("DELETE FROM tbl_event_user WHERE event_id IN (99981, 99982, 99983);");	
	mysql_query("DELETE FROM tbl_option WHERE option_id in (99881, 99882, 99883);");	
	mysql_query("DELETE FROM tbl_option_user WHERE option_id in (SELECT option_id FROM tbl_event_option WHERE event_id = 99981);");
	mysql_query("DELETE FROM tbl_event_option WHERE event_id IN (99981,99982, 99983);");
	mysql_query("DELETE FROM tbl_comment WHERE comment_id in (SELECT comment_id FROM tbl_comment WHERE event_id = (99981, 99982));");
	mysql_query("DELETE FROM tbl_comment_event WHERE event_id IN (99981,99982, 99983);");
}

function get_full_url($filename)
{
	return ((@$_SERVER["HTTPS"] == "on") ? "https://" : "http://").$_SERVER['HTTP_HOST'].$_SERVER["CONTEXT_PREFIX"].dirname($_SERVER["PHP_SELF"])."/".$filename;
}
function myAssert($function_name, $actual, $expected)
{
	$result = xml_is_equal($actual, $expected);
	if ($result === true) {
	   echo '<tr><td>'.date("His").'</td><td colspan="2" bgcolor="99FF99">'.$function_name.': Pass</td></tr>';
	} else {
	   echo '<tr><td>'.date("His").'</td><td colspan="2" bgcolor="FF9999">'.$function_name.': Fail<br><a href="'.$function_name.'" target="_blank">View Page</a><br>'.$result.'</td></tr>';
		echo '<tr><td/><td>Actual</td><td>'.htmlentities($actual->asXml()).'</td></tr>';
		echo '<tr><td/><td>Expected</td><td>'.htmlentities($expected->asXml()).'</td></tr>';
	}
}
function myAssertPass($function_name)
{
	echo '<tr><td>'.date("His").'</td><td colspan="2" bgcolor="99FF99">'.$function_name.': Pass</td></tr>';	
}
function myAssertFail($function_name, $actual)
{
	echo '<tr><td>'.date("His").'</td><td colspan="2" bgcolor="FF9999">'.$function_name.': Fail</td></tr>';
	echo '<tr><td/><td>Actual</td><td>'.htmlentities($actual->asXml()).'</td></tr>';
}
function printStr($str)
{
	echo '<tr><td>'.date("His").'</td><td colspan="2">'.$str.'</td></tr>';
}
?>

</table>