<table border=0>
	<tr>
		<td valign=top colspan=6 width=578>
			<h1><?= $boardinfo['name']; ?> contacts</h1>
		</td>
	</tr>
	<tr>
		<td><b>Name</b></td>
		<td><b>Email</b></td>
		<td><b>Phone</b></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
			<? list_contacts(); ?>
	<?php
		if (is_in_group("contactadmin"))
		{
	?>
	<tr>
		<td colspan=6>
			<hr>
			<h2>Add a new contact:</h2>
			<? print_form_header("addcontact"); ?>
				Name: <input type="text" name="fullname"> <input type="submit" value="Add">
			</form>
		</td>
	</tr>
	<?php
		}
	?>
</table>
