<?PHP
//-----------------------------------------------------------------------------------------------
//Program-o Version 1.0.1
//PHP MYSQL AIML interpreter
//Written by Elizabeth Perreau
//Feb 2010
//for more information and support please visit www.program-o.com
//-----------------------------------------------------------------------------------------------
//chat.php
//contains the interface used to chat to the bot
//-----------------------------------------------------------------------------------------------
include("../../bot/chat.php");
//-----------------------------------------------------------------------------------------------
//Run program.. detect if form has been posted
//If so run program
//-----------------------------------------------------------------------------------------------
$res = "";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Bot Chat</title>
</head>

<body OnLoad="document.chat.chat.focus();">
<div id="Layer1">
  <p style=\"\"><?php echo $res; echo $formchat;?></p>
</div>
<p>&nbsp;</p>
</body>
</html>