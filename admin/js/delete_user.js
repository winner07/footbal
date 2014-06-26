$(document).ready(function(){
	var user_id;
	var user_row;
	
	//Функція видалення новини
	function del_user(){
		$.post("delete_user.php", {user_id : user_id}, function(data){
			if(data == "OK"){
				$("#del_user_dialog").dialog("close");
				user_row.css("background-color", "#FF5E5E")
						.animate({
							"background-color" : "#FFF"
						}, 1000)
						.fadeOut();
			}
		});
	}
	
	//Налаштування діалогу видалення
	$("#del_user_dialog").dialog({
		autoOpen: false,
		modal: true,
		buttons: [
			{text: "Delete", click: del_user},
			{text: "Cancel", click: function(){
				$(this).dialog("close");
			}}
		]
	});
	
	//Обробник кліку на кнопці видалення
	$("a.delete").click(function(e){
		//Рядок користувача
		user_row = $(this).closest("tr");
		//Збереження ID користувача для видалення
		user_id = user_row.data("userId");
		//Показати діалог
		$("#del_user_dialog").dialog("open");
		e.preventDefault();
	});
});