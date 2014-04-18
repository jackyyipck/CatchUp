<table border="1">

<?php
include 'catchup-lib.php';
include 'xml-lib.php';

set_time_limit(60);

init_db();

test_data_cleanup();
create_test_user();
test_empty_event();
test_multiple_invitee();
test_option();
test_one_vote();
test_vote_by_same_invitee();
test_vote_by_multiple_invitee();
test_get_user_by_mobile();
test_create_and_verify_user();
test_get_comment();

function test_get_comment()
{
	mysql_query("INSERT INTO tbl_comment
				(comment_id, create_by, create_at, comment)
				VALUES 
				('9901',  '999001', '2012-03-04 05:06:11', 'Comment message');");
				
	mysql_query("INSERT INTO tbl_comment_event 
				(event_id, comment_id)
				VALUES 
				('99981',  '9901');");
				
	printStr("Created one test comment msg");
	
	$url = get_full_url("get-comment-by-event-id.php?event_id=99981&user_id=000000");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<comment comment_id="9901">
											<comment_string>Comment message</comment_string>
											<commenter create_by="999001" create_at="2012-03-04 05:06:11">Tester1</commenter>
										</comment>
									  </response>');
									
	myAssert($url, $actual, $expected);
	
	
	
	mysql_query("INSERT INTO tbl_comment
				(comment_id, create_by, create_at, comment)
				VALUES 
				('9902',  '999001', '2012-03-04 05:06:12', 'Comment message 2');");
				
	mysql_query("INSERT INTO tbl_comment_event 
				(event_id, comment_id)
				VALUES 
				('99981',  '9902');");				
				
	printStr("Created one more test comment msg");					
	
	$url = get_full_url("get-comment-by-event-id.php?event_id=99981");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<comment comment_id="9901">
											<comment_string>Comment message</comment_string>
											<commenter create_by="999001" create_at="2012-03-04 05:06:11">Tester1</commenter>
										</comment>
										<comment comment_id="9902">
											<comment_string>Comment message 2</comment_string>
											<commenter create_by="999001" create_at="2012-03-04 05:06:12">Tester1</commenter>
										</comment>
									  </response>');
									  
	myAssert($url, $actual, $expected);		
}
function create_test_user()
{
	mysql_query("INSERT INTO tbl_user
				(user_id, user_name, user_avatar_filename, user_mobile, user_email, user_create_at) 
				VALUES 
				('999001', 'Tester1', 'Avatar1', '852123456001', 'Tester1@test.com', '2012-03-04 05:06:07'),
				('999002', 'Tester2', 'Avatar2', '852123456002', 'Tester2@test.com', '2012-03-04 05:06:08'),
				('999003', 'Tester3', 'Avatar3', '852123456003', 'Tester3@test.com', '2012-03-04 05:06:09'),
				('999004', 'Tester4', 'Avatar4', '852123456004', 'Tester4@test.com', '2012-03-04 05:06:10');");
}
function test_create_and_verify_user()
{
	printStr("Creating and verifying accounts");
	
	$url = get_full_url("create-verify-user.php?action=create-verify-code&user_mobile=111111&device_id=000000");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$actual_user_id = (string) $actual->user_id;
	$actual_verification_code = (string) $actual->to_be_removed->verification_code;
	if(strlen($actual_user_id) > 3 && strlen($actual_user_id) < 8 && strlen($actual_verification_code) == 6)
	{
		myAssertPass($url);
		
		$url = get_full_url("create-verify-user.php?action=verify-user&user_id=000000&verification_code=000000&device_id=000000");
		$actual = new SimpleXMLElement (file_get_contents($url));
		$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
											<verification_status>0</verification_status>
											<failure_reason>User ID not found</failure_reason>
										</response>');
		myAssert($url, $actual, $expected);
		
		$url = get_full_url("create-verify-user.php?action=verify-user&user_id=".$actual_user_id."&verification_code=000000&device_id=000000");
		$actual = new SimpleXMLElement (file_get_contents($url));
		if (((string) $actual->verification_status) == 0 && $actual->failure_reason == "Verification code does not match, status and code has been reset")
		{
			myAssertPass($url);
			$actual_verification_code = (string) $actual->to_be_removed->verification_code;
		}
		
		$url = get_full_url("create-verify-user.php?action=verify-user&user_id=".$actual_user_id."&verification_code=".$actual_verification_code);
		$actual = new SimpleXMLElement (file_get_contents($url));
		$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
											<verification_status>1</verification_status>
										</response>');
		myAssert($url, $actual, $expected);
		
		$url = get_full_url("create-verify-user.php?action=verify-user&user_id=".$actual_user_id."&verification_code=".$actual_verification_code);
		$actual = new SimpleXMLElement (file_get_contents($url));
		if (((string) $actual->verification_status) == 0 && $actual->failure_reason == "Duplicated verification, status and code has been reset")
		{
			myAssertPass($url);
		}
		
		$url = get_full_url("create-verify-user.php?action=enrich-user&user_id=".$actual_user_id."&user_name=AAA&user_email=abc&user_avatar_filename=abc");
		$actual = new SimpleXMLElement (file_get_contents($url));
		$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
											<enrichment_status>1</enrichment_status>
										</response>');
		myAssert($url, $actual, $expected);
		
		$url = get_full_url("create-verify-user.php?action=unlink-user&user_id=".$actual_user_id);
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
				
	$url = get_full_url("get-user-id-by-user-mobile.php?arr_user_mobile[]=852123456001&arr_user_mobile[]=852123456004&arr_user_mobile[]=000000000000");
	$actual = new SimpleXMLElement (file_get_contents($url));
	
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<user_name user_id="999001" user_mobile="852123456001">Tester1</user_name>
										<user_name user_id="999004" user_mobile="852123456004">Tester4</user_name>
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
				
	$url = get_full_url("get-all-detail-by-user-id.php?user_id=999001");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<events>
										<event event_id="99981" event_create_at="2013-10-22 00:00:00" event_start_at="2013-10-30 00:00:01" event_expire_at="2013-10-30 00:00:02">
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
	
	$url = get_full_url("get-option-by-event-id.php?event_id=99981");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<option_name option_id="N" option_desc="" voters_num="1">Pending RSVP</option_name>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-event-by-user-id.php?user_id=999003");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-event-by-user-id.php?user_id=999001");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<event_name>TestEvent1</event_name>
										<event_desc>TestEvent1 Description</event_desc>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-rsvp-by-event-id.php?event_id=99981");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<rsvp_name rsvp_id="999001">Tester1</rsvp_name>
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
	
	$url = get_full_url("get-all-detail-by-user-id.php?user_id=999001");
	$actual = new SimpleXMLElement (file_get_contents($url));	
	$expected = new SimpleXMLElement('<events>
										<event event_id="99981" event_create_at="2013-10-22 00:00:00" event_start_at="2013-10-30 00:00:01" event_expire_at="2013-10-30 00:00:02">
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
	
	$url = get_full_url("get-option-by-event-id.php?event_id=99981");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<option_name option_id="N" option_desc="" voters_num="3">Pending RSVP</option_name>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-rsvp-by-event-id.php?event_id=99981");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<rsvp_name rsvp_id="999001">Tester1</rsvp_name>
										<rsvp_name rsvp_id="999002">Tester2</rsvp_name>
										<rsvp_name rsvp_id="999004">Tester4</rsvp_name>
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
	
	$url = get_full_url("get-all-detail-by-user-id.php?user_id=999001");	
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<events>
										<event event_id="99981" event_create_at="2013-10-22 00:00:00" event_start_at="2013-10-30 00:00:01" event_expire_at="2013-10-30 00:00:02">
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
	
	$url = get_full_url("get-option-by-event-id.php?event_id=99981");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<option_name option_id="99881" option_desc="Option Desc 1" voters_num="0">Option 1</option_name>
										<option_name option_id="99882" option_desc="Option Desc 2" voters_num="0">Option 2</option_name>
										<option_name option_id="99883" option_desc="Option Desc 3" voters_num="0">Option 3</option_name>
										<option_name option_id="N" option_desc="" voters_num="3">Pending RSVP</option_name>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-voter-by-option-id.php?option_id=99881");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><option/>');
										
	myAssert($url, $actual, $expected);
}
function test_one_vote()
{
		
	mysql_query("INSERT INTO tbl_option_user 
				(option_id, user_id, create_at)
				VALUES 
				('99881',  '999001', '2013-10-22 00:01:00');");
	
	printStr("Added one vote");
	
	$url = get_full_url("get-all-detail-by-user-id.php?user_id=999001");	
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<events>
										<event event_id="99981" event_create_at="2013-10-22 00:00:00" event_start_at="2013-10-30 00:00:01" event_expire_at="2013-10-30 00:00:02">
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
											<options option_latest_timestamp="2013-10-22 00:01:00">
												<option option_id="99881">
													<option_name>Option 1</option_name>
													<option_desc>Option Desc 1</option_desc>
													<users>
														<user_name create_at="2013-10-22 00:01:00" user_id="999001">Tester1</user_name>
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
	
	$url = get_full_url("get-option-by-event-id.php?event_id=99981");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<option_name option_id="99881" option_desc="Option Desc 1" voters_num="1">Option 1</option_name>
										<option_name option_id="99882" option_desc="Option Desc 2" voters_num="0">Option 2</option_name>
										<option_name option_id="99883" option_desc="Option Desc 3" voters_num="0">Option 3</option_name>
										<option_name option_id="N" option_desc="" voters_num="2">Pending RSVP</option_name>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-rsvp-by-event-id.php?event_id=99981");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<rsvp_name rsvp_id="999002">Tester2</rsvp_name>
										<rsvp_name rsvp_id="999004">Tester4</rsvp_name>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-voter-by-option-id.php?option_id=99881");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><option>
										<voter_name voter_id="999001" create_at="2013-10-22 00:01:00">Tester1</voter_name>
									</option>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-voter-by-option-id.php?option_id=99882");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><option/>');
										
	myAssert($url, $actual, $expected);
	
}
function test_vote_by_same_invitee()
{
		
	mysql_query("INSERT INTO tbl_option_user 
				(option_id, user_id, create_at)
				VALUES 
				('99882',  '999001', '2013-10-22 00:02:00');");
	
	printStr("Added one more vote from same invitee");
	
	$url = get_full_url("get-all-detail-by-user-id.php?user_id=999001");	
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<events>
										<event event_id="99981" event_create_at="2013-10-22 00:00:00" event_start_at="2013-10-30 00:00:01" event_expire_at="2013-10-30 00:00:02">
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
											<options option_latest_timestamp="2013-10-22 00:02:00">
												<option option_id="99881">
													<option_name>Option 1</option_name>
													<option_desc>Option Desc 1</option_desc>
													<users>
														<user_name create_at="2013-10-22 00:01:00" user_id="999001">Tester1</user_name>
													</users>
												</option>
												<option option_id="99882">
													<option_name>Option 2</option_name>
													<option_desc>Option Desc 2</option_desc>
													<users>
														<user_name create_at="2013-10-22 00:02:00" user_id="999001">Tester1</user_name>
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
	
	$url = get_full_url("get-option-by-event-id.php?event_id=99981");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<option_name option_id="99881" option_desc="Option Desc 1" voters_num="1">Option 1</option_name>
										<option_name option_id="99882" option_desc="Option Desc 2" voters_num="1">Option 2</option_name>
										<option_name option_id="99883" option_desc="Option Desc 3" voters_num="0">Option 3</option_name>
										<option_name option_id="N" option_desc="" voters_num="2">Pending RSVP</option_name>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-rsvp-by-event-id.php?event_id=99981");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<rsvp_name rsvp_id="999002">Tester2</rsvp_name>
										<rsvp_name rsvp_id="999004">Tester4</rsvp_name>
									</response>');
										
	myAssert($url, $actual, $expected);
}
function test_vote_by_multiple_invitee()
{
		
	mysql_query("INSERT INTO tbl_option_user 
				(option_id, user_id, create_at)
				VALUES 
				('99882',  '999002', '2013-10-22 00:03:00'), ('99883',  '999004', '2013-10-22 00:04:00');");
	
	printStr("Added two more vote from multiple invitee");
	
	$url = get_full_url("get-all-detail-by-user-id.php?user_id=999001");	
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<events>
										<event event_id="99981" event_create_at="2013-10-22 00:00:00" event_start_at="2013-10-30 00:00:01" event_expire_at="2013-10-30 00:00:02">
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
											<options option_latest_timestamp="2013-10-22 00:04:00">
												<option option_id="99881">
													<option_name>Option 1</option_name>
													<option_desc>Option Desc 1</option_desc>
													<users>
														<user_name create_at="2013-10-22 00:01:00" user_id="999001">Tester1</user_name>
													</users>
												</option>
												<option option_id="99882">
													<option_name>Option 2</option_name>
													<option_desc>Option Desc 2</option_desc>
													<users>
														<user_name create_at="2013-10-22 00:02:00" user_id="999001">Tester1</user_name>
														<user_name create_at="2013-10-22 00:03:00" user_id="999002">Tester2</user_name>
													</users>
												</option>
												<option option_id="99883">
													<option_name>Option 3</option_name>
													<option_desc>Option Desc 3</option_desc>
													<users>
														<user_name create_at="2013-10-22 00:04:00" user_id="999004">Tester4</user_name>
													</users>
												</option>
											</options>
										</event>
									</events>');
									
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-option-by-event-id.php?event_id=99981");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response>
										<option_name option_id="99881" option_desc="Option Desc 1" voters_num="1">Option 1</option_name>
										<option_name option_id="99882" option_desc="Option Desc 2" voters_num="2">Option 2</option_name>
										<option_name option_id="99883" option_desc="Option Desc 3" voters_num="1">Option 3</option_name>
										<option_name option_id="N" option_desc="" voters_num="0">Pending RSVP</option_name>
									</response>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-rsvp-by-event-id.php?event_id=99981");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><response/>');
										
	myAssert($url, $actual, $expected);
	
	$url = get_full_url("get-voter-by-option-id.php?option_id=99882");
	$actual = new SimpleXMLElement (file_get_contents($url));
	$expected = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><option>
										<voter_name voter_id="999001" create_at="2013-10-22 00:02:00">Tester1</voter_name>
										<voter_name voter_id="999002" create_at="2013-10-22 00:03:00">Tester2</voter_name>
									</option>');
										
	myAssert($url, $actual, $expected);
}
function test_data_cleanup()
{	
	mysql_query("DELETE FROM tbl_user WHERE user_id in (999001,999002,999003,999004);");
	mysql_query("DELETE FROM tbl_event WHERE event_id in (99981);");
	mysql_query("DELETE FROM tbl_event_user WHERE event_id = 99981;");	
	mysql_query("DELETE FROM tbl_option WHERE option_id in (99881, 99882, 99883);");	
	mysql_query("DELETE FROM tbl_option_user WHERE option_id in (SELECT option_id FROM tbl_event_option WHERE event_id = 99981);");
	mysql_query("DELETE FROM tbl_event_option WHERE event_id = 99981;");
	mysql_query("DELETE FROM tbl_comment WHERE comment_id in (SELECT comment_id FROM tbl_comment WHERE event_id = 99981);");
	mysql_query("DELETE FROM tbl_comment_event WHERE event_id = 99981;");
}

function get_full_url($filename)
{
	return ((@$_SERVER["HTTPS"] == "on") ? "https://" : "http://").$_SERVER['HTTP_HOST'].$_SERVER["CONTEXT_PREFIX"].dirname($_SERVER["PHP_SELF"])."/".$filename;
}
function myAssert($function_name, $actual, $expected)
{
	$result = xml_is_equal($actual, $expected);
	if ($result === true) {
	   echo '<tr><td colspan="2" bgcolor="99FF99">'.$function_name.': Pass</td></tr>';
	} else {
	   echo '<tr><td colspan="2" bgcolor="FF9999">'.$function_name.': Fail<br><a href="'.$function_name.'" target="_blank">View Page</a><br>'.$result.'</td></tr>';
		echo '<tr><td>Actual</td><td>'.htmlentities($actual->asXml()).'</td></tr>';
		echo '<tr><td>Expected</td><td>'.htmlentities($expected->asXml()).'</td></tr>';
	}
}
function myAssertPass($function_name)
{
	echo '<tr><td colspan="2" bgcolor="99FF99">'.$function_name.': Pass</td></tr>';	
}
function myAssertFail($function_name, $actual)
{
	echo '<tr><td colspan="2" bgcolor="FF9999">'.$function_name.': Fail</td></tr>';
	echo '<tr><td>Actual</td><td>'.htmlentities($actual->asXml()).'</td></tr>';
}
function printStr($str)
{
	echo '<tr><td colspan="2">'.$str.'</td></tr>';
}
?>

</table>