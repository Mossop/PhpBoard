<table border=1 cellspacing=0 cellpadding=1>
	<tr>
		<td>
			<table>
				<tr>
					<td align=left>Posted by <?= $message['nickname']; ?></td>
					<td align=right><?= mysql_to_nice($message['created']); ?></td>
				</tr>
				<tr>
					<td colspan=2 width=578></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width=578>
			<?= nl2br($message['content']); ?>
		</td>
	</tr>
</table>
<br>