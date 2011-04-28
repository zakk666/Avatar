<?PHP
//-----------------------------------------------------------------------------------------------
//Program-o Version 1.0.4
//PHP MYSQL AIML interpreter
//Written by Elizabeth Perreau
//Feb 2010
//for more information and support please visit www.program-o.com
//-----------------------------------------------------------------------------------------------
include_once("bot/chat.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Program-O AIML Chat Bot</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:12px;
}
.demouser{
	color:blue;
}
.demobot{
	color:green;
}
-->
</style></head>

<body OnLoad="document.chat.chat.focus();">
<div>
  <p style=\"\">
  <?php 
  echo $res; 
  echo "<br/>";
  echo $formchat;
  ?></p>
</div>
<p>&nbsp;</p>
</body>
</html>
