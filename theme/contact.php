<tr>
	<td><?= $contact['fullname']; ?></td>
	<td><a href="mailto:<?= $contact['email']; ?>"><?= $contact['email']; ?></a></td>
	<td><?= $contact['phone']; ?></td>
	<td>
	<?php
		if ((is_in_group("useradmin"))&&($contact['user']==null))
		{
			print_link("adduser","Create Login","person=".$contact['id']);
		}
	?>
	</td>
	<td>
	<?php 
		if ((is_in_group("contactadmin"))||($contact['id']==$userinfo['id']))
		{
			print_link("editcontact","Edit","person=".$contact['id']);
		}
	?>
	</td>
	<td>
	<?php
		if ((is_in_group("contactadmin"))&&($contact['user']==null))
		{
			print_link("deletecontact","Delete","person=".$contact['id']);
		}
	?>
	</td>
</tr>
