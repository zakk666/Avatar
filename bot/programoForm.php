<?php
	$response_Array = $_POST["response_Array"];
	
	
	
	// $formData = explode("&", $formData);
// 	
	// foreach($formData as $key => $value){
		// $value = explode("=", $value);
		// $value[0] = explode("%5B", $value[0]);
// 		
		// foreach($value[0] as $k => $v){
			// $v = str_replace("%5D", "", $v);
			// $value[0][$k] = $v;
		// }
// 		
		// $formData[$key] = $value;
	// }
	
	
	echo("<pre>");
	print_r($response_Array);
	echo("</pre>");
?>