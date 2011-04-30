<?php 
	
	require("info.php");
	
	class FacebookAPIs{
		public $facebook;
		
		public $profile;
		public $uid;
		public $at;
		
		public $botPersonalityTableName = 'botpersonality';
		
		public $fbProfiles;
		public $botPersonalities;
		
		public function __construct(Facebook $fb){
			$this->facebook = $fb;
			$uid = $this->facebook->getUser();
			$at = $this->facebook->getAccessToken();			
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
		
		
		public function viewProfileImage($uid){
			$fql = "SELECT pic_big FROM user WHERE uid=$uid";
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
			$this->profile = $this->facebook->api('/me');
			
			$this->fbProfiles[0] = $this->profile['interested_in'][0];
			$this->fbProfiles[1] = $this->profile['education'][0]['degree']['name'];
			$this->fbProfiles[2] = $this->profile['email'];
			$this->fbProfiles[3] = $this->profile['name'];
			$this->fbProfiles[4] = $this->profile['gender'];
			$this->fbProfiles[5] = $this->profile['birthday'];
			$this->fbProfiles[6] = $this->profile['hometown']['name'];
			$this->fbProfiles[7] = $this->profile['location']['name'];
			
			return $this->profile;
		}
		
		public function convertProfileToRobotPersonality(){
				
			$dbconnection = $this->dbopen();
			
			$sql = "SELECT name FROM $this->botPersonalityTableName WHERE isFB=1";
			
			$result = mysql_query($sql);
			
			$i = 0;
			
			// 	Get the columns in botpersonality table which are related to the facebook profiles.
			while($row = mysql_fetch_row($result)){
				$this->botPersonalities[$i] = $row[0];
				echo($this->botPersonalities[$i] . "<br/>");
				$i++;
			}
			
			// 	Save the facebook profiles to the botpersonality talbe
			for($i=0; $i<count($this->botPersonalities); $i++){
				$sql = "UPDATE ". $this->botPersonalityTableName . " SET value = '". $this->fbProfiles[$i] . "' WHERE name = '" . $this->botPersonalities[$i] . "'";
				echo($sql . '</br>');
				mysql_query($sql);
			}
		}
	}
?>