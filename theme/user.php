<tr>
	<td><?= $user['id']; ?></td>
	<td><?= mysql_to_nice($user['lastaccess']); ?></td>
	<td><?= $user['fullname']; ?></td>
	<td>
		<?php
			if ((is_in_group("useradmin"))&&($user['id']!=$loginid))
			{
				print_link("deleteuser","Delete","user=".$user['id']);
			}
		?>
	</td>
</tr>
