			<h2>Edit message:</h2>
			<?php print_form_header("updatemessage","message=".$message['id']); ?>
				<table>
					<tr>
						<td>
							<textarea name="content" rows=15 cols=60><?= $message['content']; ?></textarea>
						</td>
					</tr>
					<tr>
						<td align=center>
							<input type="submit" value="Update">
						</td>
					</tr>
				</table>
			</form>
