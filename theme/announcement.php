<table border=0 cellspacing=0 cellpadding=1>
	<tr>
		<td class="messageheader">
			<table>
				<tr>
					<td align=left class="messageheader">Announcement by <?= $announcement['nickname']; ?></td>
					<td align=right class="messageheader"><?= mysql_to_nice($announcement['created']); ?></td>
				</tr>
				<tr>
					<td colspan=2 width=578 class="messageheader">
						<b><?= $announcement['name']; ?></b>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="messagebody">
			<?= nl2br($announcement['content']); ?>
		</td>
	</tr>
</table>
<br>