<h2>Select a file to attach to this message.</h2>
<? print_form_header("uploadfile","message=$message","multipart/form-data"); ?>
	<table>
		<tr>
			<td>Description:</td>
			<td><input name="description" type="text"></td>
		</tr>
		<tr>
			<td>Send this file:</td>
			<td><input name="file" type="file"></td>
		</tr>
		<tr>
			<td colspan=2 align=center><input type="submit" value="Send File"></td>
		</tr>
	</table>
</form>
