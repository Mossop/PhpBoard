<? 
if (!isset($count))
{
	$count=1;
}
?>
<tr>
	<td width=20>
		<?php
			echo $count.":";
			$count++;
		?>
	</td>
	<td width=400>
		<?= $file['description'] ?>
	</td>
	<td align=right width=158>
		<? print_link("downloadfile",$file['filename'],"file=".$file['id']); ?>
	</td>
</tr>
