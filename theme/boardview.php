<table border=0>
	<tr>
		<td></td>
		<td rowspan=2 valign=top>
			<h1><?= $boardinfo['name']; ?></h1>
			<? print_announcements() ?>
<?php

	if (is_group("admin")||is_group("folderadmin"))
	{
		echo "<hr>";
	}
	
?>
		</td>
	</tr>
	<tr>
		<td valign=top>
			<? print_root_folder_tree() ?>
			<br>
			<? print_link("logout","","Logout"); ?>
		</td>
	</tr>
</table>
