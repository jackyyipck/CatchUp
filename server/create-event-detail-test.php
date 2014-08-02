<?php
include 'catchup-lib.php';
$_SESSION['security_key'] = sha1(date("YmdHi",time()).'deviceid001TACHYON')
init_db();

if(isset($_REQUEST['event_id']))
{
	$arr_event_detail = get_event_detail($_SESSION["db_conn"], $_REQUEST['event_id']);
?>
	<form action="create-event-detail.php" method="post" enctype="multipart/form-data">
	<table>
		<tr>
			<td>Event ID:</td>
			<td><input type="text" name="event_id" readonly value="<?php echo $arr_event_detail['event']['event_id']; ?>"/></td>
		</tr>
		<tr>
			<td>Event Name:</td>
			<td><input type="text" name="event_name" value="<?php echo $arr_event_detail['event']['event_name']; ?>"/></td>
		</tr>
		<tr>
			<td>Event Desc:</td>
			<td><input type="text" name="event_desc" value="<?php echo $arr_event_detail['event']['event_desc']; ?>"/></td>
		</tr>
		<tr>
			<td>Create by:</td>
			<td><input type="text" name="create_by" value="<?php echo $arr_event_detail['event']['event_create_by']; ?>"/></td>
		</tr>
		<tr>
			<td>Create at:</td>
			<td><input type="text" name="create_at" value="<?php echo $arr_event_detail['event']['event_create_at']; ?>"/></td>
		</tr>		
		<tr>
			<td>Start at:</td>
			<td><input type="text" name="start_at" value="<?php echo $arr_event_detail['event']['event_start_at']; ?>"/></td>
		</tr>
		<tr>			
		<tr>
			<td>Expire at:</td>
			<td><input type="text" name="expire_at" value="<?php echo $arr_event_detail['event']['event_expire_at']; ?>"/></td>
		</tr>
		<tr>
			<td>is All Day:</td>
			<td><input type="text" name="is_allday" value="<?php echo $arr_event_detail['event']['is_allday']; ?>"/></td>
		</tr>
		<tr>
			<td>Event Profile Pic:</td>
			<td>
				<input type="file" name="event_profile_pic" id="event_profile_pic" />
				<input type="hidden" name="event_profile_pic_filename" id="event_profile_pic_filename" value="<?php echo $arr_event_detail['event']['event_profile_filename']; ?>" />
			</td>
		</tr>
		<?php
			for($i=0; $i<$arr_event_detail['invitees_num']; $i++)
			{
			?>
				<tr>
					<td>Invitee:</td>
					<td><input type="text" name="user_id[]" value="<?php echo $arr_event_detail['invitee'][$i]['user_id']; ?>"/></td>
				</tr>
			<?php
			}			
			?>
			<tr>
				<td>Invitee:</td>
				<td><input type="text" name="user_id[]"/></td>
			</tr>
			<?php
			for($i=0; $i<$arr_event_detail['options_num']; $i++)
			{
			?>
				<tr>
					<td>Option ID:</td>
					<td><input type="text" name="option_id[]" value="<?php echo $arr_event_detail['option'][$i]['option_id']; ?>" readonly/></td>
				</tr>
				<tr>
					<td>Option Name:</td>
					<td><input type="text" name="option_name[]" value="<?php echo $arr_event_detail['option'][$i]['option_name']; ?>"/></td>
				</tr>
				<tr>
					<td>Option Desc:</td>
					<td><input type="text" name="option_desc[]" value="<?php echo $arr_event_detail['option'][$i]['option_desc']; ?>"/></td>
				</tr>
			<?php
			}		
			?>
			<tr>
				<td>Option ID:</td>
				<td><input type="text" name="option_id[]" readonly /></td>
			</tr>
			<tr>
				<td>Option Name:</td>
				<td><input type="text" name="option_name[]"/></td>
			</tr>
			<tr>
				<td>Option Desc:</td>
				<td><input type="text" name="option_desc[]"/></td>
			</tr>
			<?php			
		?>
		
		<tr>
			<td><input type="submit" value="Submit"></td>
			<td></td>
		</tr>
	</table>
	</form>
<?php
	}
	else
	{
?>
	<form action="create-event-detail.php" method="post" enctype="multipart/form-data">
		<table>
			<tr>
				<td>Event ID:</td>
				<td><input type="text" name="event_id" readonly/></td>
			</tr>
			<tr>
				<td>Event Name:</td>
				<td><input type="text" name="event_name"/></td>
			</tr>
			<tr>
				<td>Event Desc:</td>
				<td><input type="text" name="event_desc"/></td>
			</tr>
			<tr>
				<td>Create by:</td>
				<td><input type="text" name="create_by"/></td>
			</tr>	
			<tr>
				<td>Start at:</td>
				<td><input type="text" name="start_at"/></td>
			</tr>			
			<tr>
				<td>Expire at:</td>
				<td><input type="text" name="expire_at" value="2013-10-26 09:27:26"/></td>
			</tr>
		<tr>
			<td>is All Day:</td>
			<td><input type="text" name="is_allday" /></td>
		</tr>
		<tr>
			<td>Event Profile Pic:</td>
			<td>
				<input type="file" name="event_profile_pic" id="event_profile_pic" />				
			</td>
		</tr>
			<tr>
				<td>Option ID:</td>
				<td><input type="text" name="option_id[]" readonly/></td>
			</tr>
			<tr>
				<td>Option Name:</td>
				<td><input type="text" name="option_name[]"/></td>
			</tr>
			<tr>
				<td>Option Desc:</td>
				<td><input type="text" name="option_desc[]"/></td>
			</tr>
			<tr>
				<td>Option ID:</td>
				<td><input type="text" name="option_id[]" readonly/></td>
			</tr>
			<tr>
				<td>Option Name:</td>
				<td><input type="text" name="option_name[]"/></td>
			</tr>
			<tr>
				<td>Option Desc:</td>
				<td><input type="text" name="option_desc[]"/></td>
			</tr>
			<tr>
				<td>Invitee:</td>
				<td><input type="text" name="user_id[]"/></td>
			</tr>
			<tr>
				<td>Invitee:</td>
				<td><input type="text" name="user_id[]"/></td>
			</tr>
			<tr>
				<td>Invitee:</td>
				<td><input type="text" name="user_id[]"/></td>
			</tr>
			<tr>
				<td><input type="submit" value="Submit"></td>
				<td></td>
			</tr>
		</table>
	</form>
<?php
	}
?>

<form action="create-event-detail.php" method="post">
	<table>
		<tr>
			<td>Event ID:</td>
			<td><input type="hidden" name="action" value="delete"/><input type="text" name="event_id"/></td>
		</tr>
		<tr>
			<td><input type="submit" value="Submit"></td>
			<td></td>
		</tr>
	</table>
</form>