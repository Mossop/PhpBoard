<table border=0 cellspacing=0 cellpadding=1>
	<tr>
		<td class="messageheader">
			<table>
				<tr>
					<td align=left class="messageheader">Posted by <?= $message['nickname']; ?></td>
					<td align=right class="messageheader"><?= mysql_to_nice($message['created']); ?></td>
				</tr>
				<tr>
					<td colspan=2 width=578 class="messageheader">
						<?php
							if ((is_in_group("messageadmin"))||($message['author']==$userinfo['id']))
							{
								print_link("deletemessage","Delete","message=".$message['id']);
							}
						?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width=578 class="messagebody">
			<?= nl2br($message['content']); ?>
		</td>
	</tr>
</table>
<?php mark_msg_read($message['id']); ?>
<br>
