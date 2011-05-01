
var chatForm;
var isAjax = true;

$(document).ready(function(){
	chatForm = $("#chat_form");
	
	$("#chat").focus();
	
	var formData = {};
	
	formData['isAjax'] = isAjax;
	
	if(isAjax){
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
						// $(".chat_input#chat").val("");
						$("#chat").focus();
					});
			
			return false;
		}, "json");
	}
		
});

function getChatForm(){
}
