				<table>
					<tr>
						<td><? print_link("boardview","Announcements"); ?></td>
						<td><? print_link("contactlist","Contacts"); ?></td>
						<td><? print_link("userlist","Users"); ?></td>
					</tr>
				</table>
			</TD>
			<TD align=right>
				Currently logged in as <?= $loginid; ?> (<?= $userinfo['fullname']; ?>)
			</TD>
		</TR>
		<TR>
			<TD colspan=3 width=778>
				<HR>
<table border=0>
	<tr>
		<td height=0></td>
		<td width=578 rowspan=2 valign=top>
