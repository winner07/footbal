$(document).ready(function(){
	var comment_id;
	var comment_row;
	
	//Функція видалення новини
	function del_comment() {
		$.post("delete_comment.php", {comment_id : comment_id}, function(data){
			if (data == "Comment has been deleted") {
				$("#del_comm_dialog").dialog("close");
				comment_row.css("background-color", "#FF5E5E")
						.animate({
							"background-color" : "#FFF"
						}, 1000)
						.fadeOut();
			}
		});
	}
	
	//Налаштування діалогу видалення
	$("#del_comm_dialog").dialog({
		autoOpen: false,
		modal: true,
		buttons: [
			{text: "Delete", click: del_comment},
			{text: "Cancel", click: function() {
				$(this).dialog("close");
			}}
		]
	});
	
	//Обробник кліку на кнопці видалення
	$("a.delete").click(function(e){
		//Збереження ID категорії для видалення
		comment_id = $(this).data("commentId");
		//Вивід назви категорії
		comment_row = $(this).closest("tr");
		$("#del_comm_dialog").dialog("open");
		
		e.preventDefault();
	});
});