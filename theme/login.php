		<form action="phpboard.php" method="post">
<?php

	if (is_array($HTTP_POST_VARS))
	{
		while (list($key,$val)=each($HTTP_POST_VARS))
		{
			if (($key!="loginid")&&($key!="passwd"))
			{
				echo "			<input type=\"hidden\" name=\"$key\" value=\"$val\">\n";
			}
		}
	}
	if (is_array($HTTP_GET_VARS))
	{
		while (list($key,$val)=each($HTTP_GET_VARS))
		{
			if (($key!="loginid")&&($key!="passwd"))
			{
				echo "			<input type=\"hidden\" name=\"$key\" value=\"$val\">\n";
			}
		}
	}

?>
			<table align=center>
				<tr>
					<td>Username:</td>
					<td>
						<input type="text" name="loginid" value="">
					</td>
				</tr>
				<tr>
					<td>Password:</td>
					<td>
						<input type="password" name="passwd" value="">
					</td>
				</tr>
				<tr>
	  			<td colspan=2 align=center>
				  	<input type="submit" value="Login">
					</td>
				</tr>
			</table>
	  </form>
