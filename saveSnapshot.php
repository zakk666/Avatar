<?php
	// error_reporting(E_ALL);
	// ini_set('display_errors', '1');
	
	include('info.php');
	
	$uniqeFilename = uniqid (rand (),true) . ".jpg";

	$str = file_get_contents("php://input");
	file_put_contents("snapshots/$uniqeFilename", pack("H*", $str));
	
	$dbconn = mysql_connect($dbLocation,$dbUsername,$dbPassword);
	if (!$dbconn){
		die('Could not connect: ' . mysql_error());
	}
	
	mysql_select_db($dbName, $dbconn);
	
	$sql = "INSERT INTO snapshots (filename) VALUES ('" . $uniqeFilename . "')";
	
	mysql_query($sql);
	
	mysql_close($dbconn);
	
?>