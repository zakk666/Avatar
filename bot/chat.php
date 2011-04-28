<?PHP
//-----------------------------------------------------------------------------------------------
//Program-o Version 1.0.4
//PHP MYSQL AIML interpreter
//Written by Elizabeth Perreau
//Feb 2010
//for more information and support please visit www.program-o.com
//-----------------------------------------------------------------------------------------------
//chat.php
//contains the interface used to chat to the bot
//-----------------------------------------------------------------------------------------------
include_once("response_handler.php");
//-----------------------------------------------------------------------------------------------
//Run program.. detect if form has been posted
//If so run program
//-----------------------------------------------------------------------------------------------
$res = "";



if( (isset($_POST['action'])) && (trim($_POST['chat'])!=""))
{
	if($_POST['action']=="checkresponse")
	{
		$savelook = minClean($_POST['chat']); //save the original string for displaying back to user
		
		
		//	Clean up the input string.
		
		$look = trim(strtolower($_POST['chat'])); //initially clean and trim the input
		
		$look = preg_replace("/\.|!|\?|,|:|;/",".",$look);
		$look = preg_replace("/\.+/",".",$look);
		
		$look = str_replace('"','',stripslashes($look));
		$look = cleanInput($look);	//quik clean
		$look = spellcheck($look);	//spell check
		
	
		if($look=="")
		{
			$look = "RANDOM PICKUP LINE";
		}
	

		
		if($look=="cleardefaults") //if this is the input then just clear all the bot memory
		{
			$response_Array=array();
			$response_Array['sessionid'] = $sessionid;
			$response_Array['userid'] = $userid;
			$response_Array['top']="om";
			$response_Array['second']="om";
			$response_Array['third']="om";
			$response_Array['fourth']="om";
			$response_Array['fifth']="om";
			$response_Array['sixth']="om";
			$response_Array['seventh']="om";
			$response_Array['last']="om";
			//debug
			runDebug("",3,"Post Detected","<br>Array Name = Not in array<br>All memory cleared");
		}
		else
		{
			//debug
			runDebug("",3,"Post Detected","<br>Array Name = Not in array<br>Starting program");
			
			//set the new array to any values we want to save up to now from the conversation
			$response_Array=$_POST['response_Array'];
			$response_Array['who']="human"; //this input is from the user and not an iteration
			$response_Array['lookingfor']=$look;
			$response_Array['masterlook']=$look;
			$response_Array = frontOfStack($response_Array,htmlentities(urlencode(stripslashes($savelook))) ,"input");
			$response_Array['biganswer']="";
			$response_Array['usersaid'] = $savelook;
			$response_Array['bot'] = $thisbot;

			if($userid!="")
			{
				$response_Array['sessionid'] = $sessionid;
				$response_Array['userid'] = $userid;
			}

		
			$line = trim($look);
			if($line!="")
			{
				
				if(strpos($line, ".")=== false)
				{
					$response_Array['lookingfor']=$line;
					$response_Array = checkresponse($response_Array,"human");
					if($response_Array['who'] == "human")
					{
						$response_Array['biganswer'] .= " ".$response_Array['answer'];
					}
				} 
				else
				{
					$lineparts = explode(".",$line);
					$tmpAns = "";
					foreach($lineparts as $index => $part)
					{
						$line = trim($part);
						
						if($line!="")
						{
							$response_Array['lookingfor']=$line;
							$response_Array = checkresponse($response_Array,"human");
							if($response_Array['who'] == "human")
							{
								$tmpAns .= " ".$response_Array['answer'];
							}
						}
					}
					
					if($response_Array['who'] == "human")
					{
							$response_Array['biganswer'] .= " ".$tmpAns;
					}
					
					
				}
			}
			
			//we could be on a recursion.... so we only want to display the answer if this a human interaction	
			if($response_Array['who'] == "human")
			{
				
				
				
				for($i=$convoLines;$i>=0;$i--)
				{
					if(isset($response_Array['input'][$i]))
					{
						$res .= "<div class=\"demouser\">You: ".stripslashes(urldecode($response_Array['input'][$i]))."</div>";
   						$res .= "<div class=\"demobot\">Bot: ".stripslashes(urldecode($response_Array['that'][$i]))."</div>";
					}
					else
					{
						$res .= "<div class=\"demouser\">&nbsp;</div>";
						$res .= "<div class=\"demobot\">&nbsp;</div>";
					}
				}
				
				
				logConvo($response_Array);
				updateUser($response_Array);
				if(strpos($response_Array['biganswer'],"tag")!==FALSE) // for debugin if error of unclosed tags send message
				{
					preg_match("/tag(.*?)\s/",$response_Array['biganswer'],$subj);
					$subj = $subj[0];
					emailthis($response_Array,"$location - Response Error - $subj");
				}	
				if((isset($response_Array['matchpattern']))&&(strpos($response_Array['matchpattern'],"RANDOM PICKUP LINE")!==FALSE))
				{
					logUnknown($response_Array);
				}
			}
		}

		endTime($response_Array,$time_start);
		//show the chat form
		$formchat = formchat($response_Array);
		
	}	
}
else
{
	for($i=0;$i<=$convoLines;$i++)
	{
		$res .= "<div class=\"demouser\">&nbsp;</div>";
		$res .= "<div class=\"demobot\">&nbsp;</div>";
	}
	
	//initialise the array
	$response_Array = array();
	//show the chat form
	if($userid!="")
	{
		$response_Array['sessionid'] = $sessionid;
		$response_Array['userid'] = $userid;
	}	
	$response_Array['top']="om";
	$response_Array['second']="om";
	$response_Array['third']="om";
	$response_Array['fourth']="om";
	$response_Array['fifth']="om";
	$response_Array['sixth']="om";
	$response_Array['seventh']="om";
	$response_Array['last']="om";	
	$formchat = formchat($response_Array);
}
mysql_close($dbconn);

echo "<pre>";
print_r($response_Array);
echo "</pre>";


?>