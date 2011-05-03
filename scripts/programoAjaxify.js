
/* ========================================================================== */
/* = Ajaxify the programO, so that the bot can chat without page refreshing = */
/* ========================================================================== */
var chatForm;
var isAjax = true;

var OPENLINK = 1;
var SHOWVIDEO = 2;
var SHOWMAP = 3;

var favoriteBand;

$(document).ready(function(){
	
	var formData = {};
	
	formData['isAjax'] = isAjax;
	
	if(isAjax){
		
		var ajaxChatForm = '<p style=\"\">'+
								'<div class="demouser">'+
									'&nbsp;'+
								'</div>'+
								'<div class="demobot">'+
									'&nbsp;'+
								'</div>'+
								'<div class="demouser">'+
									'&nbsp;'+
								'</div>'+
								'<div class="demobot">'+
									'&nbsp;'+
								'</div>'+
								'<br/>'+
								'&nbsp;&nbsp;&nbsp;&nbsp;'+
								'<form name="chat" id="chat_form" method="post" action="">'+
									'<a name="chat">&nbsp;</a>'+
									'<input class="chat_input" type="text" name="chat" id="chat" size="35" maxlength="50" />'+
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
									'<input type="submit" name="submit" value="SAY">'+
								'</form>'+
							'</p>';
		
		$("#ajaxtest").html(ajaxChatForm);
		
		chatForm = $("#chat_form");
	
		$("#chat").focus();

		
		chatForm.live("submit", function(){
			// 	Wrap all the input field into the formData object and send to php
			$(".chat_input").each(function(){
				var dataKey = $(this).attr("id");
				formData[dataKey] = $(this).val();
			});

			$.post("bot/chat.php",
					formData,
					function(data){
						// alert(data);
						$("#ajaxtest").html(data);
						
						var commandType = $("#response_Array\\[commandType\\]").val();
						
						// switch(commandType){
							// case 1:
								// setTimeout('window.open($(".demobot_1 a").attr("href"));', 1500);
							// break;
						// }
						
						if($(".favband").html() != ""){
							favoriteBand = $(".favband").html();
						}
						
						executeCommand(commandType);
												
						$("#chat").focus();
					});
			
			return false;
		});
	}
	
	function executeCommand(cmd){
		
		if(cmd == OPENLINK){
			setTimeout('window.open($(".demobot_1 a").attr("href"));', 1500);
		}
		else if(cmd == SHOWMAP){
			generateGoogleMap();
		}
		else if(cmd == SHOWVIDEO){
			generateYoutubeVideo();
		}

	}
	
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
	
	function takeASnapShot(){
		
	}
	
});






















