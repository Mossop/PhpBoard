<?php

	# Initialisation for the board. This must be changed for each board
	# implemented.
	
	$board="wswym";		# The boards id in the database.
	$database="PhpBoard";	# The name of the MySql database.
	$username="phpboard";	# The username for MySql.
	$password="board379";	# The password for MySql.
	
	# The following set variables to the names of the tables in the database.
	# Change these to use different table names.
	
	$boardtbl="Board";
	$usertbl="User";
	$grouptbl="Groups";
	$foldertbl="Folder";
	$threadtbl="Thread";
	$msgtbl="Message";
	$filetbl="File";
	$usergrptbl="UserGroup";
	$unreadtbl="UnreadMessage";
	$editedtbl="EditedMessage";
	$sessiontbl="Session";
	
?>
