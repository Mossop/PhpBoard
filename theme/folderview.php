<?php

	if ($mode!="admin")
	{
?>
			<h1><?= htmlentities($folderinfo['name']); ?> messages</h1>
			<? print_threads($folder) ?>
<?php

		if (is_in_group("admin")||is_in_group("folderadmin"))
		{
			print_link("folderview","Administration","mode=admin&folder=$folder");
		}
	}
	else
	{
?>
			<h1><?= htmlentities($folderinfo['name']); ?> administration</h1>
			<table>
				<tr>
					<? print_form_header("addfolder","folder=$folder"); ?>
					<td>Create a new subfolder:</td>
					<td><input type="text" name="name" value=""></td>
					<td><input type="submit" value="Create"></td>
					</form>
				</tr>
				<tr>
					<? print_form_header("deletefolder","folder=$folder"); ?>
					<td>Delete this folder:</td>
					<td colspan=2><input type="submit" value="Delete"></td>
				</tr>
			</table>
<?php
		print_link("folderview","View Messages","folder=$folder");
	}

?>
