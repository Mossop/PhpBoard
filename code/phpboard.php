<?php

	# This is the main php file of the board system. Almost all the
	# intelligent code is included here, and this is the only page that
	# is ever called by the web server. Its main tasks are to handle the
	# logging in and out of the board and creating the functions needed
	# by the display pages.

	# Sends the header to the browser.
  function send_header()
  {
  	global $themeroot,$webroot,$boardinfo;
  	
		# Convince browsers not to cache the page.
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: ".gmdate("D, d m y H:i:s")." GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");

		# Send the default header.
		include $themeroot."siteheader.php";
  }
	
	# Sends the footer to the browser.
	function send_footer()
	{
		global $themeroot,$webroot,$boardinfo;
		# Send the default footer.
		include $themeroot."sitefooter.php";
	}
	
	# Sends an error page to the user
	function error($message)
	{
		global $themeroot,$folder;
		$folder=-1;
		include $themeroot."error.php";
	}
	
	# Converts a timestamp to a nice display date.
	function to_nice_date($timestamp)
	{
		return date("g:ia D, jS F, Y",$timestamp);
	}
	
	# Coverts a timestamp to something we can pass to mysql.
	function to_mysql_date($timestamp)
	{
		return date("Y-m-d H:i:s",$timestamp);
	}
	
	# Converts a date from mysql to a timestamp.
	function from_mysql_date($datestr)
	{
		if (ereg("([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})",$datestr,$regs))
		{
			return mktime($regs[4],$regs[5],$regs[6],$regs[2],$regs[3],$regs[1]);
		}
		else
		{
			return time();
		}
	}
	
	# Converts a mysql date to a nice date, convenience method.
	function mysql_to_nice($datestr)
	{
		return to_nice_date(from_mysql_date($datestr));
	}
	
	# Asks if a thread contains any unread messages for the current user.
	function is_thread_unread($threadid)
	{
		global $unreadtbl,$msgtbl,$threadtbl,$loginid,$connection;
		$query=mysql_query("SELECT $msgtbl.id FROM $unreadtbl,$msgtbl,$threadtbl"
			." WHERE $unreadtbl.message_id=$msgtbl.id AND $msgtbl.thread=$threadtbl.id"
			." AND $unreadtbl.user_id=\"$loginid\" AND $threadtbl.id=$threadid;",$connection);
		if (mysql_num_rows($query)>0)
		{
			return mysql_num_rows($query);
		}	
		else
		{
			return false;
		}
	}
	
	# Marks all messages in the thread as read.
	function mark_thread_read($threadid)
	{
		global $unreadtbl,$msgtbl,$threadtbl,$loginid,$connection;
		$query=mysql_query("SELECT $msgtbl.id FROM $unreadtbl,$msgtbl,$threadtbl"
			." WHERE $unreadtbl.message_id=$msgtbl.id AND $msgtbl.thread=$threadtbl.id"
			." AND $unreadtbl.user_id=\"$loginid\" AND $threadtbl.id=$threadid;",$connection);
		while ($msg=mysql_fetch_row($query))
		{
			mysql_query("DELETE FROM $unreadtbl WHERE user_id=\"$loginin\" AND message_id=".$msg[0].";",$connection);
		}
	}
	
	# Asks if a particular message is unread for the current user.
	function is_msg_unread($messageid)
	{
		global $unreadtbl,$connection,$loginid;
		$query=mysql_query("SELECT message_id FROM $unreadtbl WHERE user_id=\"$loginid\" AND message_id=$messageid;",$connection);
		if (mysql_num_rows($query)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	# Marks a message as read by this user.
	function mark_msg_read($messageid)
	{
		global $unreadtbl,$loginid,$connection;
		mysql_query("DELETE FROM $unreadtbl WHERE message_id=$messageid AND user_id=\"$loginid\";",$connection);
	}
	
	# Asks if the current user is in the specified group.
	function is_in_group($group, $user="")
	{
		global $usergrptbl,$connection,$loginid;
		if (strlen($user)==0)
		{
			$user=$loginid;
		}
		$query=mysql_query("SELECT user_id FROM $usergrptbl WHERE user_id=\"$user\" AND (group_id=\"$group\" OR group_id=\"admin\");",$connection);
		if (mysql_num_rows($query)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	# Checks if the user is in any of the groups listed (comma seperated).
	function check_groups($groups)
	{
		global $connection,$themeroot,$loginid,$usergrptbl,$folder;
		$list="('admin',";
		$grouplist=split(",",$groups);
		while ($thisgroup=each($grouplist))
		{
			$list=$list."'".$thisgroup[1]."',";
		}
		$list=substr($list,0,-1).")";
		$query=mysql_query("SELECT group_id FROM $usergrptbl WHERE user_id=\"$loginid\" AND group_id IN $list;",$connection);
		if (mysql_num_rows($query)>0)
		{
			return true;
		}
		else
		{
			include $themeroot."noaccess.php";
			$folder=-1;
			return false;
		}
	}
	
	# Prints a url link to a function of the board.
	#
	# $function is the function to be called.
	# $params are any extra params to pass "" for none.
	# $description is the text of the link.
	function print_link($function,$description,$params = "", $class = "")
	{
		global $boardinfo,$webroot;
		if (strlen($boardinfo['codedir'])>0)
		{
			$url=$webroot.$boardinfo['codedir']."/phpboard.php?function=$function";
		}
		else
		{
			$url=$webroot."phpboard.php?function=$function";
		}
		if (strlen($params)>0)
		{
			$url=$url."&$params";
		}
		if ($encodesession)
		{
			$url=$url."&sessionurl=$session";
		}
		if (strlen($class)>0)
		{
			echo "<a href=\"$url\" class=\"$class\">$description</a>";
		}
		else
		{
			echo "<a href=\"$url\">$description</a>";
		}
	}
	
	# Prints a form header for a function of the board.
	function print_form_header($function,$params = "",$enctype="")
	{
		if (strlen($enctype)>0)
		{
			$enctype="enctype=\"$enctype\"";
		}
		echo "<form $enctype action=\"".$webroot."phpboard.php\" method=post>\n";
		echo "<input type=hidden name=\"function\" value=\"$function\">\n";
		settype($name,"string");
		settype($val,"string");
		if (strlen($params)>0)
		{
			$paramlist=split("&",$params);
			while ($thisone=each($paramlist))
			{
				list($name,$value)=split("=",$thisone['value'],2);
				echo "<input type=hidden name=\"$name\" value=\"$value\">\n";
			}
		}
	}
	
	# Lists all threads in a folder.
	function print_threads($folder)
	{
		global $threadtbl,$peopletbl,$themeroot,$connection,$peoplefields,$userinfo;
		$query=mysql_query("SELECT $threadtbl.id,$threadtbl.name,$threadtbl.created,$threadtbl.owner,$peoplefields "
			."FROM $threadtbl,$peopletbl WHERE $threadtbl.folder=$folder "
			."AND $threadtbl.owner=$peopletbl.id ORDER BY $threadtbl.created DESC;",$connection);
		if (mysql_num_rows($query))
		{
			while ($thread=mysql_fetch_array($query))
			{
				include $themeroot."thread.php";
			}
		}
	}
	
	# Lists all messages in a thread.
	function print_messages($thread)
	{
		global $msgtbl,$connection,$peopletbl,$themeroot,$peoplefields,$userinfo;
		$query=mysql_query("SELECT $msgtbl.id,$msgtbl.content,$msgtbl.created,$msgtbl.author,$peoplefields "
			."FROM $msgtbl,$peopletbl WHERE $msgtbl.thread=$thread "
			."AND $msgtbl.author=$peopletbl.id "
			."ORDER BY $msgtbl.created;",$connection);
		if (mysql_num_rows($query))
		{
			while ($message=mysql_fetch_array($query))
			{
				include $themeroot."message.php";
			}
		}
	}
	
	# Lists all announcements, newest first.
	function print_announcements()
	{
		global $threadtbl,$msgtbl,$connection,$board,$peopletbl,$themeroot,$peoplefields,$userinfo;
		$query=mysql_query("SELECT $msgtbl.content,$threadtbl.name,$threadtbl.created,$peoplefields "
			."FROM $threadtbl,$msgtbl,$peopletbl WHERE $threadtbl.id=$msgtbl.thread "
			."AND $threadtbl.owner=$peopletbl.id "
			."AND $threadtbl.board=\"$board\" AND $threadtbl.folder=0 ORDER BY $msgtbl.created DESC;",$connection);
		if (mysql_num_rows($query))
		{
			while ($announcement=mysql_fetch_array($query))
			{
				include $themeroot."announcement.php";
			}
		}
	}
	
	function msg_has_attachments($message)
	{
		global $filetbl,$themeroot,$connection;
		$query=mysql_query("SELECT * FROM $filetbl WHERE message=$message;",$connection);
		return mysql_num_rows($query);
	}
	
	function list_files($message)
	{
		global $filetbl,$themeroot,$connection;
		$query=mysql_query("SELECT * FROM $filetbl WHERE message=$message;",$connection);
		while ($file=mysql_fetch_array($query))
		{
			include $themeroot."file.php";
		}
	}
	
	# Lists all contacts.
	function list_contacts()
	{
		global $peopletbl,$connection,$themeroot,$userinfo,$usertbl;
		$query=mysql_query("select $peopletbl.*, $usertbl.id as user from "
			."$peopletbl LEFT JOIN $usertbl ON $usertbl.person=$peopletbl.id "
			."GROUP BY $peopletbl.id;",$connection);
		if (mysql_num_rows($query))
		{
			while ($contact=mysql_fetch_array($query))
			{
				include $themeroot."contact.php";
			}
		}
	}
	
	# Lists all users.
	function list_users()
	{
		global $peopletbl,$connection,$themeroot,$userinfo,$usertbl,$board,$loginid;
		$query=mysql_query("select $usertbl.id,$usertbl.lastaccess,$peopletbl.fullname from "
			."$usertbl,$peopletbl WHERE $usertbl.person=$peopletbl.id AND board_id=\"$board\";",$connection);
		if (mysql_num_rows($query))
		{
			while ($user=mysql_fetch_array($query))
			{
				include $themeroot."user.php";
			}
		}
	}
	
	# Prints a tree view of folders starting from a given folder.
	function print_folder_tree($root, $openfolder=-1, $indent=0)
	{
		global $connection,$foldertbl,$webroot,$unreadtbl,$msgtbl,$loginid,$threadtbl;
		$query=mysql_query("SELECT name FROM $foldertbl WHERE id=$root;",$connection);
		if (mysql_num_rows($query)>0)
		{
			$name=mysql_fetch_array($query);
			$query=mysql_query("SELECT $msgtbl.id FROM $unreadtbl,$msgtbl,$threadtbl "
				."WHERE $unreadtbl.message_id=$msgtbl.id AND $msgtbl.thread=$threadtbl.id AND $threadtbl.folder=$root "
				."AND $unreadtbl.user_id=\"$loginid\";",$connection);
			if (mysql_num_rows($query)>0)
			{
				$foldername="<em>".htmlentities($name['name'])."</em>";
			}
			else
			{
				$foldername=htmlentities($name['name']);
			}
			echo "<tr><td style=\"padding-left: ".$indent."px\">";
			if ($root==$openfolder)
			{
				echo "<img src=\"".$webroot."images/openfolder.gif\" align=top> ";
				print_link("folderview",$foldername,"folder=$root","openfolder");
			}
			else
			{
				echo "<img src=\"".$webroot."images/closedfolder.gif\" align=top> ";
				print_link("folderview",$foldername,"folder=$root","closedfolder");
			}
			if (mysql_num_rows($query)>0)
			{
				echo " (".mysql_num_rows($query).")";
			}
			echo "</td></tr>\n";
			$query=mysql_query("SELECT id FROM $foldertbl WHERE parent=$root ORDER BY name;",$connection);
			if (mysql_num_rows($query)>0)
			{
				while ($folder = mysql_fetch_array($query))
				{
					print_folder_tree($folder['id'],$openfolder,$indent+18);
				}
			}
		}
	}
	
	# Prints the entire folder tree.
	function print_root_folder_tree($openfolder=-1)
	{
		global $boardinfo,$connection,$board,$foldertbl,$webroot;
		echo "<tr><td style=\"padding-left: ".$indent."px\">";
		if ($openfolder==0)
		{
			echo "<img src=\"".$webroot."images/openfolder.gif\" align=top> ";
			print_link("boardview",$boardinfo['name'],"","openfolder");
		}
		else
		{
			echo "<img src=\"".$webroot."images/closedfolder.gif\" align=top> ";
			print_link("boardview",$boardinfo['name'],"","closedfolder");
		}
		echo "</td></tr>\n";
		$query=mysql_query("SELECT id FROM $foldertbl WHERE parent=0 AND board=\"$board\" ORDER BY name;",$connection);
		if (mysql_num_rows($query)>0)
		{
			while ($folder = mysql_fetch_array($query))
			{
				print_folder_tree($folder['id'],$openfolder,18);
			}
		}
	}
	
	# Check the login credentials and present with login pages if necessary.
	#
	# Returns true if everything is ok to continue.
	function check_login()
	{
		global $board,$boardinfo,$session,$sessiontbl,$loginid,$passwd,$usertbl,$themeroot,$connection,$function,$webroot,$HTTP_GET_VARS,$HTTP_POST_VARS;
		if ((isset($session))&&(strlen($session)>0))
		{
			# Check the existence of the session.
			$query=mysql_query("SELECT user_id FROM $sessiontbl WHERE id=$session AND board_id=\"$board\";",$connection);
			if (mysql_num_rows($query)>0)
			{
				$loginid=mysql_result($query,0,0);
				if ($function=="logout")
				{
					mysql_query("DELETE FROM $sessiontbl WHERE id=$session;",$connection);
					SetCookie("session","",time()-3600);
					send_header();
					include $themeroot."logout.php";
					send_footer();
					return false;
				}
				else
				{
					# Update the expiry time of the session.
					$expiry=to_mysql_date(time()+$boardinfo['timeout']*60);
					mysql_query("UPDATE $sessiontbl SET expiry=\"$expiry\" WHERE id=$session;",$connection);
					return true;
				}
			}
			else
			{
				SetCookie("session","",time()-3600);
				send_header();
				include $themeroot."relogin.php";
				send_footer();
				return false;
			}
		}
		else
		{
			if ((isset($loginid))&&(isset($passwd)))
			{
				$loginid=strtolower($loginid);
				$query=mysql_query("SELECT id FROM $usertbl WHERE id=\"$loginid\" AND password=PASSWORD(\"$passwd\") AND board_id=\"$board\";",$connection);
				if (mysql_num_rows($query)==1)
				{
					$expiry=to_mysql_date(time()+$boardinfo['timeout']*60);
					# Check for an old session that can be reused
					$query=mysql_query("SELECT id FROM $sessiontbl WHERE user_id=\"$loginid\" AND board_id=\"$board\";",$connection);
					if (mysql_num_rows($query)>0)
					{
						$session=mysql_result($query,0,0);
						mysql_query("UPDATE $sessiontbl SET expiry=\"$expiry\" WHERE id=$session;",$connection);
					}
					else
					{
						# Create a session
						mysql_query("INSERT INTO $sessiontbl (user_id,board_id,expiry) VALUES (\"$loginid\",\"$board\",\"$expiry\");",$connection);
						$session=mysql_insert_id($connection);
					}
					SetCookie("session",$session);
					return true;
				}
				else
				{
					# User not in database
					send_header();
					include $themeroot."badlogin.php";
					send_footer();
					return false;
				}
			}
			else
			{
				send_header();
				include $themeroot."login.php";
				send_footer();
				return false;
			}
		}
	}
	
	# Deletes a message and all files associated with it.
	function delete_message($message)
	{
		global $msgtbl,$filetbl,$connection,$unreadtbl,$boardinfo;
		mysql_query("DELETE FROM $unreadtbl WHERE message_id=$message;",$connection);
		mysql_query("DELETE FROM $msgtbl WHERE id=$message;",$connection);
		$query=mysql_query("SELECT name FROM $filetbl WHERE message=$message;",$connection);
		while ($msg=mysql_fetch_array($query))
		{
			unlink($boardinfo['docroot']."/".$boardinfo['filedir']."/".$msg['name']);
		}
		mysql_query("DELETE FROM $filetbl WHERE message=$message;",$connection);
	}
	
	# Deletes a thread and all messages associated with it.
	function delete_thread($thread)
	{
		global $threadtbl,$msgtbl,$connection;
		mysql_query("DELETE FROM $threadtbl WHERE id=$thread;",$connection);
		$query=mysql_query("SELECT id FROM $msgtbl WHERE thread=$thread;",$connection);
		while ($msg=mysql_fetch_array($query))
		{
			delete_message($msg['id']);
		}
	}
	
	# Deletes a folder and all threads and messages associated with it.
	function delete_folder($folder)
	{
		global $foldertbl,$threadtbl,$connection;
		mysql_query("DELETE FROM $foldertbl WHERE id=$folder;",$connection);
		$query=mysql_query("SELECT id FROM $threadtbl WHERE folder=$folder;",$connection);
		while ($thread=mysql_fetch_row($query))
		{
			delete_thread($thread[0]);
		}
		$query=mysql_query("SELECT id FROM $foldertbl WHERE parent=$folder;",$connection);
		while ($sub=mysql_fetch_row($query))
		{
			delete_folder($sub[0]);
		}
	}
	
	function board_view()
	{
		global $themeroot,$boardinfo,$folder,$mode;
		$folder=0;
		include $themeroot."boardview.php";
	}
	
	function folder_view($thisfolder)
	{
		global $connection,$themeroot,$boardinfo,$foldertbl,$folder,$mode;
		if ($thisfolder==0)
		{
			board_view();
		}
		else
		{
			$query=mysql_query("SELECT * FROM $foldertbl WHERE id=$thisfolder;",$connection);
			if ($folderinfo=mysql_fetch_array($query))
			{
				$folder=$thisfolder;
				include $themeroot."folderview.php";
			}
			else
			{
				error("The folder could not be found");
			}
		}
	}
	
	function thread_view($thread)
	{
		global $connection,$themeroot,$threadtbl,$boardinfo,$folder;
		$query=mysql_query("SELECT * FROM $threadtbl WHERE id=$thread;",$connection);
		if ($threadinfo=mysql_fetch_array($query))
		{
			$folder=$threadinfo['folder'];
			include $themeroot."threadview.php";
		}
		else
		{
			error("The thread does not exist");
		}
	}
	
	# Load the board information
  include "init.php";

	# Opens a db connection, then fetches the board information.
	
	$connection=mysql_pconnect("localhost",$username,$password);
	mysql_select_db($database,$connection);
	$query=mysql_query("SELECT * FROM $boardtbl WHERE id=\"$board\"",$connection);

	# Check we got a result of some sort
	if ($boardinfo=mysql_fetch_array($query))
	{	
		if (strlen($boardinfo['themedir'])>0)
		{
			$themeroot=$boardinfo['docroot'].'/'.$boardinfo['themedir'].'/';
		}
		else
		{
			$themeroot=$boardinfo['docroot'].'/';
		}
		$webroot=$boardinfo['webroot'].'/';

		# Delete expired sessions
		mysql_query("DELETE FROM $sessiontbl WHERE expiry<NOW() OR ISNULL(expiry);",$connection);

		# Is there no session cookie, but a url encoded session?
		if (!isset($session))
		{
			if (isset($sessionurl))
			{
				$session=$sessionurl;
			}
			$encodesession=true;
		}
		else
		{
			$encodesession=false;
		}
		
		if (check_login())
		{
			# Update the users last access.
			mysql_query("UPDATE $usertbl SET lastaccess=NOW() WHERE id=\"$loginid\" AND board_id=\"$board\";",$connection);
			$query=mysql_query("SELECT $peopletbl.*,$usertbl.lastaccess FROM $peopletbl,$usertbl WHERE $usertbl.person=$peopletbl.id AND $usertbl.id=\"$loginid\" AND $usertbl.board_id=\"$board\";");
			$userinfo=mysql_fetch_array($query);
			
			if (($function=="downloadfile")&&(isset($file)))
			{
				$query=mysql_query("SELECT * FROM $filetbl WHERE id=$file;",$connection);
				if ($fileinfo=mysql_fetch_array($query))
				{
					#header("Location: ".$boardinfo['webroot']."/".$boardinfo['filedir']."/".$fileinfo['name']);
					Header("Content-type: ".$fileinfo['mimetype']);
					Header("Content-Disposition: attachment; filename=\"".$fileinfo['name']."\"");
					$fn=fopen($boardinfo['docroot']."/".$boardinfo['filedir']."/".$fileinfo['name'],"r");
					fpassthru($fn);
				}
				else
				{
					send_header();
					include $themeroot."boardheader.php";
					error("File not found");
					include $themeroot."boardfooter.php";
					send_footer();
				}
			}
			else
			{
			if ($function=="logout")
			{
				SetCookie("session",time()-3600);
				mysql_query("DELETE FROM $sessiontbl WHERE user_id=\"$loginid\" AND board_id=\"$board\";",$connection);
			}
				
			send_header();
			include $themeroot."boardheader.php";
				
			# Include the relevant file for the requested function
			if ((!isset($function))||($function=="boardview"))
			{
				board_view();
			}
			else if ($function=="contactlist")
			{
				include $themeroot."contactlist.php";
				$folder=-1;
			}
			else if ($function=="userlist")
			{
				include $themeroot."userlist.php";
				$folder=-1;
			}
			else if (($function=="folderview")&&(isset($folder)))
			{
				folder_view($folder);
			}
			else if (($function=="threadview")&&(isset($thread)))
			{
				thread_view($thread);
			}
			else if (($function=="updatecontact")&&(isset($person)))
			{
				if (check_groups("contactadmin"))
				{
					$query="UPDATE $peopletbl SET  ";
					if (isset($fullname))
					{
						$query=$query."fullname=\"".$fullname."\",";
					}
					if (isset($email))
					{
						$query=$query."email=\"".$email."\",";
					}
					if (isset($nickname))
					{
						$query=$query."nickname=\"".$nickname."\",";
					}
					if (isset($fullname))
					{
						$query=$query."phone=\"".$phone."\",";
					}
					$query=substr($query,0,-1);
					$query=$query." WHERE id=\"$person\";";
					$query=mysql_query("$query",$connection);
					include $themeroot."contactlist.php";
					$folder=-1;
				}
			}
			else if ($function=="updateboard")
			{
				if (check_groups("boardadmin"))
				{
					$query="UPDATE $boardtbl SET  ";
					if (isset($name))
					{
						$query=$query."name=\"".$name."\",";
					}
					if (isset($timeout))
					{
						$query=$query."timeout=$timeout,";
					}
					$query=substr($query,0,-1);
					$query=$query." WHERE id=\"$board\";";
					$query=mysql_query("$query",$connection);
					$query=mysql_query("SELECT * FROM $boardtbl WHERE id=\"$board\";",$connection);
					$boardinfo=mysql_fetch_array($query);
					board_view();
				}
			}
			else if (($function=="addfolder")&&(isset($folder))&&(isset($name)))
			{
				if (check_groups("folderadmin"))
				{
					$query=mysql_query("INSERT INTO $foldertbl (parent,board,name) VALUES ($folder,\"$board\",\"".$name."\");",$connection);
					$folder=mysql_insert_id($connection);
					folder_view($folder);
				}
			}
			else if (($function=="updatefolder")&&(isset($folder)))
			{
				if (check_groups("folderadmin"))
				{
					$query="UPDATE $foldertbl SET  ";
					if (isset($name))
					{
						$query=$query."name=\"".$name."\",";
					}
					$query=substr($query,0,-1);
					$query=$query." WHERE id=\"$folder\";";
					$query=mysql_query($query,$connection);
					folder_view($folder);
				}
			}
			else if (($function=="updatemessage")&&(isset($message)))
			{
				if (check_groups("messageadmin"))
				{
					$query=mysql_query("SELECT thread FROM $msgtbl WHERE id=$message",$connection);
					$thread=mysql_fetch_array($query);
					$query="UPDATE $msgtbl SET  ";
					if (isset($content))
					{
						$query=$query."content=\"".$content."\",";
					}
					$query=substr($query,0,-1);
					$query=$query." WHERE id=\"$message\";";
					$query=mysql_query($query,$connection);
					mysql_query("INSERT INTO $editedtbl (message_id,person,altered) VALUES ($message,".$userinfo['id'].",NOW());",$connection);
					thread_view($thread['thread']);
				}
			}
			else if (($function=="deletecontact")&&(isset($person)))
			{
				if (check_groups("contactadmin"))
				{
					$query=mysql_query("DELETE FROM $peopletbl WHERE id=$person;",$connection);
					include $themeroot."contactlist.php";
					$folder=-1;
				}
			}
			else if (($function=="deleteuser")&&(isset($user)))
			{
				if (check_groups("useradmin"))
				{
					$query=mysql_query("DELETE FROM $usertbl WHERE id=\"$user\";",$connection);
					$query=mysql_query("DELETE FROM $sessiontbl WHERE user_id=\"$user\";",$connection);
					$query=mysql_query("DELETE FROM $unreadtbl WHERE user_id=\"$user\";",$connection);
					include $themeroot."userlist.php";
					$folder=-1;
				}
			}
			else if (($function=="deletefolder")&&(isset($folder)))
			{
				if (check_groups("folderadmin"))
				{
					$query=mysql_query("SELECT parent FROM $foldertbl WHERE id=$folder;",$connection);
					if ($row=mysql_fetch_row($query))
					{
						delete_folder($folder);
						$folder=$row[0];
						if ($folder!=0)
						{
							folder_view($folder);
						}
						else
						{
							board_view();
						}
					}
					else
					{
						error("The folder could not be found");
					}
				}
			}
			else if (($function=="editmessage")&&(isset($message)))
			{
				$folder=-1;
				$query=mysql_query("SELECT $msgtbl.id,$msgtbl.content,$msgtbl.created,$msgtbl.author "
					."FROM $msgtbl WHERE $msgtbl.id=$message;",$connection);
				if (mysql_num_rows($query))
				{
					$message=mysql_fetch_array($query);
					include $themeroot."editmessage.php";
				}
				else
				{
					error("Could not find the message you are trying to edit.");
				}
			}
			else if (($function=="editcontact")&&(isset($person)))
			{
				$folder=-1;
				$query=mysql_query("SELECT id,fullname,email,nickname,phone "
					."FROM $peopletbl WHERE id=$person;",$connection);
				if (mysql_num_rows($query))
				{
					$contact=mysql_fetch_array($query);
					include $themeroot."editcontact.php";
				}
				else
				{
					error("Could not find the person you are trying to edit.");
				}
			}
			else if (($function=="addcontact")&&(isset($fullname)))
			{
				mysql_query("INSERT INTO $peopletbl (fullname) VALUES (\"$fullname\");",$connection);
				$folder=-1;
				include $themeroot."contactlist.php";
			}
			else if (($function=="edituser")&&(isset($user)))
			{
				$folder=-1;
				$query=mysql_query("SELECT id FROM $usertbl WHERE id=\"$user\";",$connection);
				if (mysql_num_rows($query))
				{
					$user=mysql_fetch_array($query);
					include $themeroot."edituser.php";
				}
				else
				{
					error("Could not find the person you are trying to edit.");
				}
			}
			else if (($function=="deletemessage")&&(isset($message)))
			{
				$query=mysql_query("SELECT author,thread FROM $msgtbl WHERE id=$message;",$connection);
				$msginfo=mysql_fetch_array($query);
				if ((is_in_group("messageadmin"))||($msginfo[author]==$userinfo['id']))
				{
					delete_message($message);
					thread_view($msginfo['thread']);
				}
				else
				{
					$folder=-1;
					include $themeroot."noaccess.php";
				}
			}
			else if (($function=="deletethread")&&(isset($thread)))
			{
				$query=mysql_query("SELECT owner,folder FROM $threadtbl WHERE id=$thread;",$connection);
				$threadinfo=mysql_fetch_array($query);
				if ((is_in_group("messageadmin"))||($threadinfo['owner']==$userinfo['id']))
				{
					delete_thread($thread);
					folder_view($threadinfo['folder']);
				}
				else
				{
					$folder=-1;
					include $themeroot."noaccess.php";
				}
			}
			else if (($function=="addthread")&&(isset($folder)))
			{
				if (($folder!=0)||(check_groups("boardadmin")))
				{
					if (!isset($request))
					{
						$query=mysql_query("INSERT INTO $threadtbl (folder,board,name,created,owner) VALUES ($folder,\"$board\",\"$subject\",NOW(),".$userinfo['id'].");",$connection);
						$thread=mysql_insert_id($connection);
						mysql_query("INSERT INTO $msgtbl (thread,author,created,content) VALUES ($thread,".$userinfo['id'].",NOW(),\"$content\");",$connection);
						$mesg=mysql_insert_id($connection);
						mysql_query("INSERT INTO $unreadtbl (user_id,message_id) "
							."SELECT id,$mesg FROM $usertbl WHERE board_id=\"$board\" AND id!=\"$loginid\";",$connection);
						folder_view($folder);
					}
					else
					{
						$folder=-1;
						include $themeroot."previewthread.php";
					}
				}
			}
			else if (($function=="addmessage")&&(isset($thread)))
			{
				if (!isset($request))
				{
					mysql_query("INSERT INTO $msgtbl (thread,author,created,content) VALUES ($thread,".$userinfo['id'].",NOW(),\"$content\");",$connection);
					$mesg=mysql_insert_id($connection);
					mysql_query("INSERT INTO $unreadtbl (user_id,message_id) "
						."SELECT id,$mesg FROM $usertbl WHERE board_id=\"$board\" AND id!=\"$loginid\";",$connection);
					thread_view($thread);
				}
				else
				{
					$folder=-1;
					include $themeroot."previewmessage.php";
				}
			}
			else if ($function=="changepassword")
			{
				$folder=-1;
				include $themeroot."changepassword.php";
			}
			else if (($function=="uploadfile")&&(isset($message))&&(isset($description)))
			{
				if (is_uploaded_file($HTTP_POST_FILES['file']['tmp_name']))
				{
					$newname=$HTTP_POST_FILES['file']['name'];
					if (preg_match("/(.*?)\.(.*)/",$newname,$regs))
					{
						$start=$regs[1];
						$end=".".$regs[2];
					}
					else
					{
						$start=$newname;
						$end="";
					}
					$fileroot=$boardinfo['docroot']."/".$boardinfo['filedir']."/";
					$count=0;
					$extra="";
					while (file_exists($fileroot.$start.$extra.$end))
					{
						$count++;
						$extra="$count";
						while (strlen($extra)<3)
						{
							$extra="0".$extra;
						}
					}
					$fullname=$start.$extra.$end;
					$query=mysql_query("INSERT INTO $filetbl (name,message,mimetype,description,filename) "
						."VALUES (\"$fullname\",$message,\"".$HTTP_POST_FILES['file']['type']."\",\"$description\",\"$newname\");",$connection);
					move_uploaded_file($HTTP_POST_FILES['file']['tmp_name'],$fileroot.$fullname);
					$query=mysql_query("SELECT thread FROM $msgtbl WHERE id=$message;",$connection);
					$thread=mysql_fetch_array($query);
					thread_view($thread['thread']);
				}
				else
				{
					error("Error uploading file");
				}
			}
			else if (($function=="attachfile")&&(isset($message)))
			{
				$folder=-1;
				include $themeroot."attachfile.php";
			}
			else if (($function=="updatepassword")&&(isset($newpass1))&&(isset($newpass2)))
			{
				if ($newpass1==$newpass2)
				{
					if ((isset($user))&&(is_in_group("useradmin")))
					{
						mysql_query("UPDATE $usertbl SET password=PASSWORD(\"$newpass1\") WHERE id=\"$user\";",$connection);
						board_view();
					}
					else if (isset($oldpass))
					{
						$query=mysql_query("SELECT * FROM $usertbl WHERE id=\"$loginid\" AND password=PASSWORD(\"$oldpass\");",$connection);
						if (mysql_num_rows($query)==1)
						{
							mysql_query("UPDATE $usertbl SET password=PASSWORD(\"$newpass1\") WHERE id=\"$loginid\";",$connection);
							board_view();
						}
						else
						{
							error("You entered the wrong password.");
						}
					}
				}
				else
				{
					error("You retyped the wrong password.");
				}
			}
			else
			{
				# Allows a theme to add its own functions.
				include $themeroot."themefunc.php";
			}
			
			include $themeroot."boardfooter.php";
			send_footer();
		}
		}
	}
	else
	{
		# Couldn't get the board details. Something is screwed.
		include "badsetup.php";
	}

?>
