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
	<td>
		<?php
			if ((is_in_group("messageadmin"))||(is_in_group("admin"))||($thread['owner']==$userinfo['id']))
			{
				print_link("deletethread","Delete","thread=".$thread['id']);
			}
		?>
	</td>
</tr>
