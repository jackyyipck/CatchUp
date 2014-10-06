<?php
include 'catchup-lib.php';
$_SESSION['security_key'] = sha1(date("YmdHi",time()).'deviceid001TACHYON');
init_db();

?>
<form action="upload-media.php" method="post" enctype="multipart/form-data">
<table>
	<tr>
		<td>Type:</td>
		<td>
			<select name="media_type">
			  <option value="event-profile-pic" selected>event-profile-pic</option>
			  <option value="event-media">event-media</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Event ID:</td>
		<td><input type="text" name="event_id" /></td>
	</tr>
	<tr>
		<td>User ID:</td>
		<td><input type="text" name="create_by" /></td>
	</tr>
	<tr>
		<td>Updated at:</td>
		<td><input type="text" name="updated_at" value="2013-10-26 09:27:26"/></td>
	</tr>
	<tr>
		<td>Media:</td>
		<td><input type="file" name="media_file[]" /></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="file" name="media_file[]" /></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="file" name="media_file[]" /></td>
	</tr>
	<tr>
		<td><input type="submit" value="Submit"></td>
		<td></td>
	</tr>
</table>
</form>
		