<tr>
	<td>
		<?php
			$unreadcount=is_thread_unread($thread['id']);
			if ($unreadcount>0)
			{
				echo "<em>";
			}
		?>
		<?php print_link("threadview",$thread['name'],"thread=".$thread['id']); ?>
		<?php
			if ($unreadcount>0)
			{
				echo "</em> ($unreadcount)";
			}
		?>
	</td>
	<td>
		<?= $thread['nickname']; ?>
	</td>
	<td align=right>
		<?= mysql_to_nice($thread['created']); ?>
	</td>
</tr>
