			<h1><?= $folderinfo['name']; ?></h1>
			<? print_threads($folder) ?>
<?php

	if (is_in_group("admin")||is_in_group("folderadmin"))
	{
		echo "<hr>";
		print_form_header("addfolder","folder=$folder");
?>
				Enter new folder name: <input type="text" name="name" value="">
				<input type="submit" value="Create">
			</form>
			<? print_form_header("deletefolder","folder=$folder"); ?>
				<input type="submit" value="Delete">
			</form>
<?php
	}
	
?>
