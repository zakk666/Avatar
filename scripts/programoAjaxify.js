
/* ========================================================================== */
/* = Ajaxify the programO, so that the bot can chat without page refreshing = */
/* ========================================================================== */
var chatForm;
var chatInput;
var isAjax = true;

var OPENLINK = 1;
var SHOWVIDEO = 2;
var SHOWMAP = 3;
var OPENCAMERA = 4;
var SNAPSHOT = 5;
var SHOWHISTORY = 6;

var countDownPanel;
var snapshotHistory;

var favoriteBand;

$(document).ready(function(){
	var formData = {};
	
	formData['isAjax'] = isAjax;
	
	countDownPanel = $("#snapshotCountDown");
	snapshotHistory = $("#snapshotHistory");
	// showHistory();
	
	if(isAjax){
		// 	Building the initial chat form
		var ajaxChatForm = '<p style=\"\">'+
								'<div class="chatlog demouser">'+
									'You: Hi there!!'+
								'</div>'+
								'<div class="chatlog demobot">'+
									'Yang: Hey, How are you'+
								'</div>'+
								'<div class="chatlog demouser">'+
									'You: Can you see me'+
								'</div>'+
								'<div class="chatlog demobot">'+
									'Yang: Of course'+
								'</div>'+
								'<br/>'+
								'&nbsp;&nbsp;&nbsp;&nbsp;'+
								'<form name="chat" id="chat_form" method="post" action="">'+
									'<a name="chat">&nbsp;</a>'+
									'<input class="chat_input" type="text" name="chat" id="chat" size="35" maxlength="50" autocomplete="off"/>'+
									'<input class="chat_input" type="hidden" name="action" id="action" value="checkresponse">'+
									'<input class="chat_input" type="hidden" name="response_Array[sessionid]" id="response_Array[sessionid]" value="rdgh2bbgi7mamifo2q4iff54v1">'+
									'<input class="chat_input" type="hidden" name="response_Array[userid]" id="response_Array[userid]" value="1">'+
									'<input class="chat_input" type="hidden" name="response_Array[top]" id="response_Array[top]" value="om">'+
									'<input class="chat_input" type="hidden" name="response_Array[second]" id="response_Array[second]" value="om">'+
									'<input class="chat_input" type="hidden" name="response_Array[third]" id="response_Array[third]" value="om">'+
									'<input class="chat_input" type="hidden" name="response_Array[fourth]" id="response_Array[fourth]" value="om">'+
									'<input class="chat_input" type="hidden" name="response_Array[fifth]" id="response_Array[fifth]" value="om">'+
									'<input class="chat_input" type="hidden" name="response_Array[sixth]" id="response_Array[sixth]" value="om">'+
									'<input class="chat_input" type="hidden" name="response_Array[seventh]" id="response_Array[seventh]" value="om">'+
									'<input class="chat_input" type="hidden" name="response_Array[last]" id="response_Array[last]" value="om">'+
									'<input class="chat_input" type="hidden" name="response_Array[rname]" id="response_Array[rname]" value="Array#0">'+
								'</form>'+
							'</p>';
		
		$("#dialogPanel").html(ajaxChatForm);
		
		chatForm = $("#chat_form");
		
		chatInput = $("#chat");
		
		chatInput.focus();		

		// 	Submit the form to programO php scripts.
		chatForm.live("submit", function(){
			// 	Wrap all the input field into the formData object and send to php
			$(".chat_input").each(function(){
				var dataKey = $(this).attr("id");
				formData[dataKey] = $(this).val();
			});
			
			$("#dummyInput").html($("#chat").val());
			
			$("#chat").val("");
			
			$("#dummyInput").animate({scale: 3.5, opacity: 0}, 
										500,
										function(){
											$("#dummyInput").html("");
											$("#dummyInput").css({transform: 'scale(1)', opacity: 1});
										});
			
			$.post("bot/chat.php",
					formData,
					function(data){
						// alert(data);
						$("#dialogPanel").html(data);
						
						var commandType = $("#response_Array\\[commandType\\]").val();
						
						if($(".favband").html() != ""){
							favoriteBand = $(".favband").html();
						}
						
						executeCommand(commandType);
						
						// 	Autofocus to the input area			
						$("#chat").focus();
						
					});
			
			return false;
		});
	}
	
	// 	Execute different command according to the bot response.
	function executeCommand(cmd){
		
		if(cmd == OPENLINK){
			setTimeout('window.open($(".demobot_1 a").attr("href"));', 1000);
		}
		else if(cmd == SHOWMAP){
			generateGoogleMap();
		}
		else if(cmd == SHOWVIDEO){
			generateYoutubeVideo();
		}
		else if(cmd == OPENCAMERA){
			openCamera();
		}
		else if(cmd == SNAPSHOT){
			takeASnapShot();
		}
		else if(cmd == SHOWHISTORY){
			showHistory();
		}

	}
	
	// 	Show a map for the question like "Where are you?"
	function generateGoogleMap(){
		
		var lat = $("#lat").html();
		var lng = $("#long").html();
		
		$(".demobot_0").append("<div class='mapHolder'></div>");
		
		var latlng = new google.maps.LatLng(lat, lng);
		
		var myOptions = {
			zoom: 14,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
	    };
		var map = new google.maps.Map($(".mapHolder")[0], myOptions);
		
		var marker = new google.maps.Marker({
			position: latlng, 
			map: map, 
			title:"Here I am!!"
		}); 
	}
	
	// 	Show a video
	function generateYoutubeVideo(){
		$.post(
			"getYoutubeVideo.php",
			{keyword : favoriteBand},
			function(vid){
				
				var videoEmbed = "<div class='videoEmbed'>"+
									"<iframe width='480' height='390' src='http://www.youtube.com/embed/" + 
									vid + 
									"?autoplay=1' frameborder='0' allowfullscreen>"+
									"</iframe>"
									"</div>";
				
				$(".demobot_0").append(videoEmbed);
			}
		);
	}
	
	// 	Open up the camera
	function openCamera(){
		$("#camera").webcam({
	        width: 320,
	        height: 240,
	        mode: "save",
	        swffile: "jscam.swf",
	        onTick: function(remain) {
	        	countDownPanel.html(remain);
	        },
	        onSave: function() {},
	        onCapture: function() {
	        	countDownPanel.html("Captured!!!");
	        	
	        	window.webcam.save("saveSnapshot.php");
	        },
	        debug: function() {},
	        onLoad: function() {
	        	countDownPanel.html("camera ready!!!");
	        }
		});

	}
	
	// 	Take a snap shot for the user
	function takeASnapShot(){
		window.webcam.capture(3);
	}
	
	// 	Show the previous snapshots
	function showHistory(){
		$.post(
			"getSnapshotHistory.php",
			{},
			function(data){
				var historyLimit = (data.length < 10) ? data.length : 10;
				var sshistory = "<ul id='historyList'>";

				for(var i=0; i<historyLimit; i++){
					sshistory +="<li class='historyElement'>"+
									"<img class='historyImage' src='snapshots/" + data[i] + "'>"+
								"</li>"
				}
				sshistory += "</ul>";
				snapshotHistory.html(sshistory);
			},
			"json"
		)
	}
});






















