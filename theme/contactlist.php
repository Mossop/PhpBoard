<table border=0>
	<tr>
		<td valign=top colspan=3 width=578>
			<h1><?= $boardinfo['name']; ?> contacts</h1>
		</td>
	</tr>
	<tr>
		<td><b>Name</b></td>
		<td><b>Email</b></td>
		<td><b>Phone</b></td>
	</tr>
			<? list_contacts(); ?>
</table>
