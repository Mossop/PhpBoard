<? print_form_header("updatecontact","person=".$contact['id']); ?>
	<table>
		<tr>
			<td>Full Name:</td>
			<td><input type=text name="fullname" value="<?= $contact['fullname']; ?>"></td>
		</tr>
		<tr>
			<td>Nickname:</td>
			<td><input type=text name="nickname" value="<?= $contact['nickname']; ?>"></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><input type=text name="email" value="<?= $contact['email']; ?>"></td>
		</tr>
		<tr>
			<td>Phone No.:</td>
			<td><input type=text name="phone" value="<?= $contact['phone']; ?>"></td>
		</tr>
		<tr>
			<td colspan=2 align=center><input type="submit" value="Update"></td>
		</tr>
	</table>
</form>
