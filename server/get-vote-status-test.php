<b>Get vote status</b>
<form action="get-vote-status.php" method="post">
	<input type="hidden" name="action" value="get-vote-status"/>
	<table>		
		<tr>
			<td>User ID:</td>
			<td><input type="text" name="user_id"/></td>
		</tr>
		<tr>
			<td>Option ID:</td>
			<td><input type="text" name="option_id"/></td>
		</tr>		
		<tr>
			<td><input type="submit" value="Submit"></td>
			<td></td>
		</tr>
	</table>
</form>