<?php

	$canadmin=(is_in_group("boardadmin"))||(is_in_group("folderadmin"));
	if (($mode!="admin")||(!$canadmin))
	{
?>

<table border=0>
	<tr>
		<td valign=top>
			<h1><?= $boardinfo['name']; ?> announcements</h1>
		</td>
		<td align=right valign=top>
<?php

		if ($canadmin)
		{
			print_link("boardview","Administration","mode=admin");
		}
?>
		</td>
	</tr>
	<tr>
		<td colspan=2 width=578>
			<? print_announcements() ?>
		</td>
	</tr>
<?php

	if (is_in_group("boardadmin"))
	{
		$folder=0;
?>
	<tr>
		<td colspan=2>
			<hr>
			<h2>Post a new announcement:</h2>
		</td>
	</tr>
	<tr>
		<td align=center colspan=2>
<?php
		include $themeroot."threadadd.php";
?>
		</td>
	</tr>
<?php
	}

?>
</table>

<?php
	}
	else
	{
?>

<table border=0>
	<tr>
		<td valign=top>
			<h1><?= $boardinfo['name']; ?> administration</h1>
		</td>
		<td align=right valign=top>
			<?php print_link("boardview","View Announcements"); ?>
		</td>
	</tr>
	<tr>
		<td colspan=2 width=578>
			<table>
<?php
			if (is_in_group("boardadmin"))
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
					<td colspan=3><hr></td>
				</tr>
<?php
			if (is_in_group("folderadmin"))
			{
?>
				<tr>
					<? print_form_header("addfolder","folder=0"); ?>
					<td>Create a new subfolder:</td>
					<td><input type="text" name="name" value=""></td>
					<td><input type="submit" value="Create"></td>
					</form>
				</tr>
<?php
			}
?>
			</table>
		</td>
	</tr>
</table>

<?php
		}
?>
