			<h1><?= $folderinfo['name']; ?></h1>
			<? print_threads($folder) ?>
<?php

	if (is_in_group("admin")||is_in_group("folderadmin"))
	{
		echo "<hr>";
	}
	
?>
