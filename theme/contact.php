<tr>
	<td><?= $contact['fullname']; ?></td>
	<td><a href="mailto:<?= $contact['email']; ?>"><?= $contact['email']; ?></a></td>
	<td><?= $contact['phone']; ?></td>
	<td>
	<?php 
		if ((is_in_group("contactadmin"))||($contact['id']==$userinfo['id']))
		{
			print_link("editcontact","Edit","person=".$contact['id']);
		}
	?>
	</td>
</tr>
