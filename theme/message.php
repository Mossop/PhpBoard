<table border=1 cellspacing=0 cellpadding=1>
	<tr>
		<td colspan=2 align=left>
			<h2>
<?php

	if (is_unread($message['id']))
	{
		echo "<em>".$message['subject']."</em>";
		mark_as_read($message['id']);
	}
	else
	{
		echo $message['subject'];
	}
	
?>
			</h2>
		</td>
	</tr>
	<tr>
		<td align=left>Posted by <?= $message['nickname']; ?></td>
		<td align=right>Posted at <?= mysql_to_nice($message['created']); ?></td>
	</tr>
	<tr>
		<td colspan=2 align=left>
			<?= $message['content']; ?>
		</td>
	</tr>
</table>
