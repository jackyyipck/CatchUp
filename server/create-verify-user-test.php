<?php
date_default_timezone_set("Asia/Hong_Kong"); 
?>
<b>Verify Code Creation</b>
<form action="create-verify-user.php" method="post">
	<input type="hidden" name="action" value="create-verify-code"/>
	<table>		
		<tr>
			<td>Mobile:</td>
			<td><input type="text" name="user_mobile"/></td>
		</tr>		
		<tr>
			<td>Device ID:</td>
			<td><input type="text" name="device_id"/></td>
		</tr>			
		<tr>
			<td><input type="hidden" name="security_key" value="<?php echo sha1(date("YmdHi",time()).'TACHYON'); ?>"><input type="submit" value="Submit"></td>
			<td></td>
		</tr>
	</table>
</form>
<b>Account Verification</b>
<form action="create-verify-user.php" method="post">
	<input type="hidden" name="action" value="verify-user"/>
	<table>
		<tr>
			<td>User ID:</td>
			<td><input type="text" name="user_id"/></td>
		</tr>
		<tr>
			<td>Verification Code:</td>
			<td><input type="text" name="verification_code"/></td>
		</tr>	
		<tr>
			<td><input type="submit" value="Submit"></td>
			<td></td>
		</tr>		
	</table>
</form>
<b>Account Enrichment</b>
<form action="create-verify-user.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="action" value="enrich-user"/>
	<table>
		<tr>
			<td>User ID:</td>
			<td><input type="text" name="user_id"/></td>
		</tr>
		<tr>
			<td>Name:</td>
			<td><input type="text" name="user_name"/></td>
		</tr>
		<tr>
			<td>Avatar Filename:</td>
			<td><input type="file" name="user_avatar_filename" id="user_avatar_filename"><br></td>
		</tr>
		<tr>
			<td>eMail:</td>
			<td><input type="text" name="user_email"/></td>
		</tr>
		<tr>
			<td><input type="submit" value="Submit"></td>
			<td></td>
		</tr>
	</table>
</form>
<b>Account Unlink</b>
<form action="create-verify-user.php" method="post">
	<input type="hidden" name="action" value="unlink-user"/>
	<table>
		<tr>
			<td>User ID:</td>
			<td><input type="text" name="user_id"/></td>
		</tr		
		<tr>
			<td><input type="submit" value="Submit"></td>
			<td></td>
		</tr>		
	</table>
</form>
<b>Check Verification State</b>
<form action="create-verify-user.php" method="post">
	<input type="hidden" name="action" value="get-verify-state"/>
	<table>
		<tr>
			<td>User Mobile:</td>
			<td><input type="text" name="user_mobile"/></td>
		</tr		
		<tr>
			<td><input type="submit" value="Submit"></td>
			<td></td>
		</tr>		
	</table>
</form>