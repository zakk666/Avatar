<?php 
	
	require("info.php");
	
	class FacebookAPIs{
		public $facebook;
		
		public $isDebugging = true;
		
		public $profile;
		public $interests;
		public $music;
		public $favBand;
		public $checkins;
		public $statuses;
		
		public $uid;
		public $at;
		
		public $botPersonalityTableName = 'botpersonality';
		
		public $fbProfiles;
		public $botPersonalities;
		
		public function __construct(Facebook $fb){
			$this->facebook = $fb;
			$this->uid = $this->facebook->getUser();
			$this->at = $this->facebook->getAccessToken();			
		}
		
		function dbopen()
		{
			global $dbLocation,$dbUsername,$dbPassword,$dbName;
			$dbconn = mysql_connect($dbLocation,$dbUsername,$dbPassword);
			
			if (!$dbconn){
				die('Could not connect: ' . mysql_error());
			}
			else{
				mysql_select_db($dbName, $dbconn);
				return $dbconn;
			}
		}
		
		
		public function viewProfileImage(){
			$fql = "SELECT pic_big FROM user WHERE uid=$this->uid";
			$param = array('method' => 'fql.query',
							'query' => $fql,
							'callback' => '');
			$fql_result = $this->facebook->api($param);
			echo ($fql_result[0]['pic_big']);
		}
		
		public function getNewsFeed(){
			try {
				$feed = $this->facebook->api('/me/home');
				echo($feed);
			} catch (FacebookApiException $e) {
				error_log($e);
			}
		}
		
		public function getProfile(){
			// 	Facebook Profiles
			$this->profile = $this->facebook->api('/me');
			
			// 	Facebook Interests
			$this->interests = $this->facebook->api('/me/interests');
			$interestsString = "";
			
			foreach($this->interests['data'] as $part){
				$interestsString .= $part['name'] . ",";
			}
			
			// 	Facebook Musics
			$this->music = $this->facebook->api('/me/music');
			$favMusic = $this->music['data'][0]["name"];
			$favBand = $this->music['data'][count($this->music['data']) - 1]['name'];
			
			// 	Facebook Checkins. The checkin request on the api docs page doesn't have the user_checkins permission.
			$this->checkins = $this->facebook->api('/me/checkins');
			$recentCheckin = $this->checkins['data'][0];
			$recentPlace = $recentCheckin['place']['name'];
			$recentPlaceMsg = $recentCheckin['message'];
			$recentLat = $recentCheckin['place']['location']['latitude'];
			$recentLong = $recentCheckin['place']['location']['longitude'];
			
			//	Facebook status
			$this->statuses = $this->facebook->api('/me/statuses');
			$recentStatus = $this->statuses['data'][0]['message'];
			
			
			echo "<pre>";
			print_r($recentCheckin);
			print_r($recentPost);
			echo "</pre>";
			
			
			
			$this->fbProfiles[0] = $this->profile['interested_in'][0];
			$this->fbProfiles[1] = $this->profile['website'];
			$this->fbProfiles[2] = $this->profile['education'][0]['degree']['name'];
			$this->fbProfiles[3] = $this->profile['email'];
			$this->fbProfiles[4] = $this->profile['name'];
			$this->fbProfiles[5] = $this->profile['gender'];
			$this->fbProfiles[6] = $this->profile['birthday'];
			$this->fbProfiles[7] = $this->profile['hometown']['name'];
			$this->fbProfiles[8] = $favBand;
			$this->fbProfiles[9] = $favMusic;
			$this->fbProfiles[10] = $recentPlace;// . "<span class='hidden' id='lat'>$recentLat</span><span class='hidden' id='long'>$recentLong</span>";
			$this->fbProfiles[11] = $interestsString;
			$this->fbProfiles[12] = $recentLat;
			$this->fbProfiles[13] = $recentLong;
			$this->fbProfiles[14] = $recentStatus;
			
			return $this->profile;
		}
		
		public function getInterests(){
			$this->interests = $this->facebook->api('/me/interests');
			
			return $this->interests;
		}
		
		public function convertProfileToRobotPersonality(){
				
			$dbconnection = $this->dbopen();
			
			// 	Select all the botpersonalities which related to facebook progiles.
			$sql = "SELECT name FROM $this->botPersonalityTableName WHERE isFB=1";
			$result = mysql_query($sql);
			$i = 0;
			while($row = mysql_fetch_row($result)){
				$this->botPersonalities[$i] = $row[0];
				
				if($this->isDebugging){
					echo($this->botPersonalities[$i] . "<br/>");
				}
				$i++;
			}
			
			// 	Save the facebook profiles to the botpersonality talbe
			for($i=0; $i<count($this->botPersonalities); $i++){
				$sql = "UPDATE ". $this->botPersonalityTableName . " SET value = '". $this->fbProfiles[$i] . "' WHERE name = '" . $this->botPersonalities[$i] . "'";
				mysql_query($sql);
				
				if($this->isDebugging){
					echo($sql . '</br>');
				}
				
			}
		}
	}
?>