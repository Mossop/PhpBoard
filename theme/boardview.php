			<h1><?= $boardinfo['name']; ?></h1>
			<? print_announcements() ?>
<?php

	if (is_in_group("admin")||is_in_group("folderadmin"))
	{
		echo "<hr>";
	}
	
?>
