<table border=1 cellspacing=0 cellpadding=1>
	<tr>
		<td>
			<table>
				<tr>
					<td align=left>Announcement by <?= $announcement['nickname']; ?></td>
					<td align=right><?= mysql_to_nice($announcement['created']); ?></td>
				</tr>
				<tr>
					<td colspan=2 width=578>
						<b><?= $announcement['name']; ?></b>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<?= nl2br($announcement['content']); ?>
		</td>
	</tr>
</table>
<br>