<?php

	if ($mode!="admin")
	{
?>
			<h1><?= $boardinfo['name']; ?> announcements</h1>
			<? print_announcements() ?>
<?php

		if (is_in_group("admin")||is_in_group("boardadmin")||is_in_group("folderadmin"))
		{
			print_link("boardview","Administration","mode=admin");
		}
	}
	else
	{
		if (is_in_group("admin")||is_in_group("boardadmin")||is_in_group("folderadmin"))
		{
?>
			<h1><?= $boardinfo['name']; ?> administration</h1>
			<table>
<?php
			if (is_in_group("admin")||is_in_group("boardadmin"))
			{
?>
				<? print_form_header("updateboard"); ?>
					<tr>
						<td>Name:</td>
						<td colspan=2><input type="text" name="name" value="<?= $boardinfo['name'] ?>"></td>
					</tr>
					<tr>
						<td>Timeout:</td>
						<td colspan=2><input type="text" name="timeout" value="<?= $boardinfo['timeout'] ?>"></td>
					</tr>
					<tr>
						<td align=center colspan=3><input type="submit" value="Update"></td>
					</tr>
				</form>
<?php
			}
?>
				<tr>
					<td colspan="3"><hr></td>
				</tr>
				<tr>
					<? print_form_header("addfolder","folder=0"); ?>
					<td>Create a new subfolder:</td>
					<td><input type="text" name="name" value=""></td>
					<td><input type="submit" value="Create"></td>
					</form>
				</tr>
			</table>
<?php
		}
		print_link("boardview","View Announcements");
	}

?>
