<h2>Editing user <?= $user['id']; ?></h2>
<h3>Reset password:</h3>
<? print_form_header("updatepassword","user=".$user['id']); ?>
	<table>
		<tr>
			<td>Enter new password:</td>
			<td><input type=password name="newpass1"></td>
		</tr>
		<tr>
			<td>Retype new password:</td>
			<td><input type=password name="newpass2"></td>
		</tr>
		<tr>
			<td colspan=2 align=center><input type="submit" value="Reset"></td>
		</tr>
	</table>
</form>
<hr>
<h3>Groups:</h3>
