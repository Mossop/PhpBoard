<table>
	<tr>
		<td>
			<h1>Messages in the thread "<?= $threadinfo['name']; ?>"</h1>
		</td>
	</tr>
	<tr width=578>
		<td align=center>
			<? print_messages($thread) ?>
		</td>
	</tr>
	<tr>
		<td>
			<hr>
		</td>
	</tr>
	<tr>
		<td>
			<h2>Add a new reply to this thread:</h2>
		</td>
	</tr>
	<tr>
		<td align=center>
			<? include $themeroot."msgadd.php"; ?>
		</td>
	</tr>
</table>

			