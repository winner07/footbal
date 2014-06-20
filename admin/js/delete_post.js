$(document).ready(function(){
	var post_row;
	var post_id;
	
	//Функція видалення новини
	function del_post(){
		$.post("delete_post.php", {post_id : post_id}, function(data){
			if(data == "Новина видалена"){
				$("#del_post_dialog").dialog("close");
				post_row.css("background-color", "#FF5E5E")
						.animate({
							"background-color" : "#FFF"
						}, 1000)
						.fadeOut();
			}
		});
	}
	
	//Налаштування діалогу видалення
	$("#del_post_dialog").dialog({
		autoOpen: false,
		modal: true,
		buttons: [
			{text: "Видалити", click: del_post},
			{text: "Скасувати", click: function(){
				$(this).dialog("close");
			}}
		]
	});
	
	//Обробник кліку на кнопці видалення
	$("a.delete").click(function(e){
		//Збереження ID категорії для видалення
		post_id = $(this).data("postId");
		//Вивід назви категорії
		post_row = $(this).closest("tr");
		var post_name = post_row.find("span:first").text();
		$("#del_post_name").text(post_name);
		//Показати діалог
		$("#del_post_dialog").dialog("open");
		
		e.preventDefault();
	});
});