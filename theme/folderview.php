<?php

	$canadmin=(is_in_group("admin")||is_in_group("folderadmin"));
	if (($mode!="admin")||(!$canadmin))
	{
?>

<table border=0>
	<tr>
		<td valign=top>
			<h1><?= htmlentities($folderinfo['name']); ?> messages</h1>
		</td>
		<td valign=top align=right>
<?php
		if ($canadmin)
		{
			print_link("folderview","Administration","mode=admin&folder=$folder");
		}
?>
		</td>
	</tr>
	<tr>
		<td width=578 colspan=2>
			<? print_threads($folder) ?>
		</td>
	</tr>
</table>

<?php
	}
	else
	{
?>

<table border=0>
	<tr>
		<td valign=top>
			<h1><?= htmlentities($folderinfo['name']); ?> administration</h1>
		</td>
		<td valign=top align=right>
			<?php print_link("folderview","View Messages","folder=$folder"); ?>
		</td>
	</tr>
	<tr>
		<td colspan=2 width=578>
			<table>
				<tr>
					<? print_form_header("updatefolder","folder=$folder"); ?>
						<td>Name:</td>
						<td><input type="text" name="name" value="<?= $folderinfo['name'] ?>"></td>
						<td><input type="submit" value="Update"></td>
					</form>
				</tr>
				<tr>
					<? print_form_header("deletefolder","folder=$folder"); ?>
					<td>Delete this folder:</td>
					<td colspan=2><input type="submit" value="Delete"></td>
				</tr>
				<tr>
					<td colspan=3><hr></td>
				</tr>
				<tr>
					<? print_form_header("addfolder","folder=$folder"); ?>
					<td>Create a new subfolder:</td>
					<td><input type="text" name="name" value=""></td>
					<td><input type="submit" value="Create"></td>
					</form>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?php
	}
?>
