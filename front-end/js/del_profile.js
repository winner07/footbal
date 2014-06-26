$(document).ready(function(){
	var message = $("<div/>");
	
	//Вывод сообщений об ошибке
	function printMessage(setClass, setText){
		message.removeClass().addClass(setClass).text(setText).prependTo("#content");
	}

	//Settings del dialog
	$("#del_profile").dialog({
		autoOpen: false,
		modal: true,
		buttons: [
			{text: "Delete", click: del_profile},
			{text: "Cancel", click: function(){
				$(this).dialog("close");
			}}
		]
	});

	//Del function
	function del_profile(){
		$.get("delete_profile.php", {user_id: $("#del_profile").data("uid")}, function(data){
			if(data){
				var data  = data.split(": ", 2);
				var style = data[0].toLowerCase();
				var mess  = data[1];

				$("#del_profile").dialog("close");
				printMessage(style, mess);
			}
		});
	}

	//Click handler on del link
	$("#del_profile_link").click(function(){
		$("#del_profile").dialog("open");
	});
});