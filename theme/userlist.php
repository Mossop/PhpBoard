<table border=0>
	<tr>
		<td valign=top colspan=3 width=578>
			<h1><?= $boardinfo['name']; ?> users</h1>
		</td>
	</tr>
	<tr>
		<td><b>Username</b></td>
		<td><b>Last on</b></td>
		<td><b>Full Name</b></td>
		<td></td>
		<td></td>
	</tr>
			<? list_users(); ?>
</table>
