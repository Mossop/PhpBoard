			<h1><?= $boardinfo['name']; ?></h1>
			<? print_announcements() ?>
<?php

	if (is_in_group("admin")||is_in_group("folderadmin"))
	{
		echo "<hr>";
		print_form_header("addfolder","folder=0");
?>
				Enter new folder name: <input type="text" name="name" value="">
				<input type="submit" value="Create">
			</form>
<?php
	}
	
?>
