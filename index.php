<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	
	require 'facebook.php';
	require 'fbapi.php';
	require 'info.php';
	
	// Create our Application instance (replace this with your appId and secret).
	$facebook = new Facebook(array(
	  'appId'  => $fbAppId,
	  'secret' => $fbSecret,
	  'cookie' => true,
	));
	
	$fbApiCall = new FacebookAPIs($facebook);
	
	$debug = 0;
	
	// We may or may not have this data based on a $_GET or $_COOKIE based session.
	//
	// If we get a session here, it means we found a correctly signed session using
	// the Application Secret only Facebook and the Application know. We dont know
	// if it is still valid until we make an API call using the session. A session
	// can become invalid if it has already expired (should not be getting the
	// session back in this case) or if the user logged out of Facebook.
	$session = $facebook->getSession();
	
	$me = null;
	$album = null;
	// Session based API call.
	if ($session) {
		try {
			$uid = $facebook->getUser();
			$me = $fbApiCall->getProfile();
			$at = $facebook->getAccessToken();
			// json_decode($me, true);
			echo("<pre>");
			print_r($fbApiCall->profile);
			// print_r($fbApiCall->getInterests());
			echo("</pre>");
			
			$fbApiCall->convertProfileToRobotPersonality();
		} catch (FacebookApiException $e) {
			error_log($e);
		}
		
		// $fbApiCall->getProfile();
		// if($fbApiCall->profile){
			// $me = $fbApiCall->profile;
			// $uid = $fbApiCall->uid;
			// $at = $fbApiCall->at;
		// }
	}
	
	// login or logout url will be needed depending on current user state. This is for the login without fbml
	if ($me) {
		$logoutUrl = $facebook->getLogoutUrl();
	} else {
		$loginUrl = $facebook->getLoginUrl();
	}

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
		<title>Avatar</title>
	</head>
	<body>
		<!--
		      We use the JS SDK to provide a richer user experience. For more info,
		      look here: http://github.com/facebook/connect-js
		    -->
		<div id="fb-root"></div>
		<script>
			var currentUid = <?php 
								if($me){echo "$uid";}
								else {echo "''";}
							?>;

			var fbRes;
			window.fbAsyncInit = function() {
				FB.init({
					appId   : '<?php echo $facebook->getAppId(); ?>',
					session : <?php echo json_encode($session); ?>, // don't refetch the session when PHP already has it
					status  : true, // check login status
					cookie  : true, // enable cookies to allow the server to access the session
					xfbml   : true // parse XFBML
				});
			
				// whenever the user logs in, we refresh the page
				FB.Event.subscribe('auth.login', function() {
					window.location.reload();
				});
			};
			
			(function() {
				var e = document.createElement('script');
				e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
				e.async = true;
				document.getElementById('fb-root').appendChild(e);
			}());
		</script>
		
		<!--Display login or logout button.-->
		<?php if ($me): ?>
			<a href="<?php echo $logoutUrl; ?>"> 
			<img src="http://static.ak.fbcdn.net/rsrc.php/z2Y31/hash/cxrz4k7j.gif"> </a>
		<?php else: ?>
			<div>
				Login with Facebook: <fb:login-button perms="user_photos, friends_photos, user_photo_video_tags, 
											friends_photo_video_tags, user_status, friends_status, user_likes, 
											friends_likes, publish_stream, read_stream, user_relationships, 
											user_relationship_details, email, user_birthday, user_about_me,
											user_birthday, user_website,user_interests, user_checkins"></fb:login-button>
			</div>
		<?php endif ?>
		
		<?php if ($me): ?>
			<div id="startBTN">
				<img src="<?php $fbApiCall->viewProfileImage(); ?>" />
				<div><?php echo $me['name']; ?></div>
			</div>
		<?php else: ?>
			<strong><em>You are not Connected.</em></strong>
		<?php endif ?>
		
	</body>
</html>
		
