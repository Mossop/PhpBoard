		</td>
	</tr>
	<tr>
		<td width=200 valign=top>
			<table>
				<?php
					if (isset($folder))
					{
						print_root_folder_tree($folder);
					}
					else
					{
						print_root_folder_tree();
					}
				?>
				<tr><td><hr></td></tr>
				<tr><td><? print_link("changepassword","Change Password"); ?></td></tr>
				<tr><td><? print_link("logout","Logout"); ?></td></tr>
			</table>
		</td>
	</tr>
</table>
