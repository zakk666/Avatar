<?PHP
//-----------------------------------------------------------------------------------------------
//Program-o Version 1.0.4
//PHP MYSQL AIML interpreter
//Written by Elizabeth Perreau
//Feb 2010
//for more information and support please visit www.program-o.com
//-----------------------------------------------------------------------------------------------
// include_once ("bot/chat.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
<script type="text/javascript" src="scripts/programoAjaxify.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&key=ABQIAAAAw6UYDscAzQWabetoB3_B_RRETdkkv1XUJhh2psM06QxrD1EsQBQPcC7iJwS7B1EsVDFoBoPBFoJkvA"></script>
<script type="text/javascript" src="scripts/jquery.webcam.js"></script>
<script type="text/javascript" src="scripts/jquery-css-transform.js"></script>
<script type="text/javascript" src="scripts/jquery-animate-css-rotate-scale.js"></script>

<link rel="stylesheet" href="css/avatarChild.css" />
<style type="text/css">
	body {
		margin-left: 0px;
		margin-top: 0px;
		margin-right: 0px;
		margin-bottom: 0px;
	}
</style></head>
<div id="avatarProfileImage"><img src="images/head.png" /></div>
<div id="dummyInput"></div>

<!-- <div>
  <p style=\"\">
  <?php 
   echo $res; 
   echo "<br/>";
   echo $formchat;
  ?></p>
</div> -->
<div id="dialogPanel">
</div>
<div class="inputUnderline"><img src="images/inputunder.png" /></div>

<div id="camera"></div>
<div id="flash"></div>
<div id="snapshotCountDown"></div>
<div id="snapshotHistory"></div>
</body>
</html>
