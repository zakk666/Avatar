<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	
	include('info.php');
	
	
	$dbconn = mysql_connect($dbLocation,$dbUsername,$dbPassword);
	if (!$dbconn){
		die('Could not connect: ' . mysql_error());
	}
	
	mysql_select_db($dbName, $dbconn);
	
	$sql = "SELECT filename FROM snapshots ORDER BY time DESC";
	
	$i = 0;
	$result = mysql_query($sql);
	while($row = mysql_fetch_row($result)){
		$return[$i] = $row[0];
		$i++;
	};
	
	$return = json_encode($return);
	
	echo($return);
		
	mysql_close($dbconn);
	
?>