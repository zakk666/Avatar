<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	
	include('youtube.inc.php');
	
	$isGettingVideoID = true;
	
	$ytObject = new youTube();
	if($ytObject->search('metallica', 10)){ // Here 'test' is a  string input which can be take  by User also and '2' no of record you want
		if( count($ytObject->RKT_requestResult->entry) > 0 ){
			// 	Get the detail info about the video.
			if(!$isGettingVideoID){
				$return = '<ul style="list-style-type:decimal;">';
				foreach ($ytObject->RKT_requestResult->entry as $video) {
					$return .= $ytObject->parseVideoRow($video);
				}
				$return .= '</ul>';
			}
			// 	Only get the video ID for embed
			else{
				$return = '';
				$randomVid = rand(0,9);
				$return = $ytObject->getVideoID($ytObject->RKT_requestResult->entry[$randomVid]);
			}
			
			echo $return;
		}else{
			echo 'Video Not Found! Please Recheck Your Search.';
		}
	}else{
		echo $ytObject->getRKT_errorMessage();
	}
?>
