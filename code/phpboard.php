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
		include $themeroot."header.php";
  }
	
	# Sends the footer to the browser.
	function send_footer()
	{
		global $themeroot,$webroot,$boardinfo;
		# Send the default footer.
		include $themeroot."footer.php";
	}
	
	# Converts a timestamp to a nice display date.
	function to_nice_date($timestamp)
	{
		return date("g:ia D, jS F, Y");
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
			return mktime($regs[3],$regs[4],$regs[5],$regs[1],$regs[2],$regs[0]);
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
			return true;
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
	function is_in_group($group)
	{
		global $loginid,$usergrptbl,$connection;
		$query=mysql_query("SELECT user_id FROM $usergrptbl WHERE user_id=\"$loginid\" AND group_id=\"$group\";",$connection);
		if (mysql_num_rows($query)>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	# Prints a url link to a function of the board.
	#
	# $function is the function to be called.
	# $params are any extra params to pass "" for none.
	# $description is the text of the link.
	function print_link($function,$description,$params = "")
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
		echo "<a href=\"$url\">$description</a>";
	}
	
	# Displays a given message using the theme.
	function print_messages($messages)
	{
		global $connection,$msgtbl,$usertbl,$themeroot;
		while ($thisid=each($messages))
		{
			$query=mysql_query("SELECT $msgtbl.id,$msgtbl.created,$msgtbl.author,$usertbl.nickname,$msgtbl.subject,$msgtbl.content "
				."FROM $msgtbl,$usertbl WHERE $msgtbl.id=".$thisid[1]." AND $usertbl.id=$msgtbl.author;",$connection);
			$message=mysql_fetch_array($query);
			include $themeroot."message.php";
		}
	}
	
	# Lists all announcements, newest first, by calling print_message.
	function print_announcements()
	{
		global $threadtbl,$msgtbl,$connection,$board;
		$query=mysql_query("SELECT $msgtbl.id "
			."FROM $threadtbl,$msgtbl WHERE $threadtbl.id=$msgtbl.thread "
			."AND $threadtbl.board=\"$board\" AND $threadtbl.folder=0 ORDER BY $msgtbl.created;",$connection);
		if (mysql_num_rows($query))
		{
			while ($thisid=mysql_fetch_array($query))
			{
				$messages[]=$thisid['id'];
			}
			print_messages($messages);
		}
	}
	
	# Prints a tree view of folders starting from a given folder.
	function print_folder_tree($root)
	{
		global $connection,$foldertbl,$unreadtbl,$msgtbl,$loginid,$threadtbl;
		$query=mysql_query("SELECT name FROM $foldertbl WHERE id=$root;",$connection);
		if (mysql_num_rows($query)>0)
		{
			$name=mysql_fetch_array($query);
			$query=mysql_query("SELECT $msgtbl.id FROM $unreadtbl,$msgtbl,$threadtbl "
				."WHERE $unreadtbl.message_id=$msgtbl.id AND $msgtbl.thread=$threadtbl.id AND $threadtbl.folder=$root "
				."AND $unreadtbl.user_id=\"$loginid\";",$connection);
			if (mysql_num_rows($query)>0)
			{
				$foldername="<em>".$name['name']."</em>";
			}
			else
			{
				$foldername=$name['name'];
			}
			print_link("folderview",$foldername,"folder=$root");
			if (mysql_num_rows($query)>0)
			{
				echo " (".mysql_num_rows($query).")";
			}
			$query=mysql_query("SELECT id FROM $foldertbl WHERE parent=$root;",$connection);
			if (mysql_num_rows($query)>0)
			{
				echo "<ul>\n";
				while ($folder = mysql_fetch_array($query))
				{
					echo "<li>";
					print_folder_tree($folder['id']);
					echo "</li>\n";
				}
				echo "</ul>\n";
			}
		}
	}
	
	# Prints the entire folder tree.
	function print_root_folder_tree()
	{
		global $boardinfo,$connection,$board,$foldertbl;
		print_link("boardview",$boardinfo['name']);
		$query=mysql_query("SELECT id FROM $foldertbl WHERE parent=0 AND board=\"$board\";",$connection);
		if (mysql_num_rows($query)>0)
		{
			echo "<ul>\n";
			while ($folder = mysql_fetch_array($query))
			{
				echo "<li>";
				print_folder_tree($folder['id']);
				echo "</li>\n";
			}
			echo "</ul>\n";
		}
	}
	
	# Check the login credentials and present with login pages if necessary.
	#
	# Returns true if everything is ok to continue.
	function check_login()
	{
		global $board,$boardinfo,$session,$sessiontbl,$loginid,$passwd,$usertbl,$themeroot,$connection,$function,$webroot;
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

			if ($function=="logout")
			{
				SetCookie("session",time()-3600);
				mysql_query("DELETE FROM $sessiontbl WHERE user_id=\"$loginid\" AND board_id=\"$board\";",$connection);
			}
				
			send_header();
				
			# Include the relevant file for the requested function
			if ((!isset($function))||($function=="boardview"))
			{
				include $themeroot."boardview.php";
			}
			else if (($function=="folderview")&&(isset($folder)))
			{
				include $themeroot."folderview.php";
			}
			else if (($function=="threadview")&&(isset($thread)))
			{
				include $themeroot."threadview.php";
			}
			else
			{
				# Allows a theme to add its own functions.
				include $themeroot."themefunc.php";
			}
			
			send_footer();
		}
	}
	else
	{
		# Couldn't get the board details. Something is screwed.
		include "badsetup.php";
	}

?>
