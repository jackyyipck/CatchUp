<form action="create-comment-detail.php" method="post">
	<table>
		<tr>
			<td>Event Id:</td>
			<td><input type="text" name="event_id"/></td>
		</tr>
		<tr>
			<td>Create by:</td>
			<td><input type="text" name="create_by"/></td>
		</tr>		
		<tr>
			<td>Comment:</td>
			<td>
				<textarea rows="4" cols="50" name="user_comment">
				</textarea>
			</td>
		</tr>
		<tr>
			<td><input type="submit" value="Submit"></td>
			<td></td>
		</tr>
	</table>
</form>