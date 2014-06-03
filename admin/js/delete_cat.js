$(document).ready(function(){
	var cat_id;
	var cat_row;
	
	//Функція видалення категорії
	function del_cat(){
		$.post("delete_cat.php", {cat_id: cat_id}, function(data){
			if(data){
				$("#del_cat_dialog").dialog("close");
				cat_row.css("background-color", "#FF5E5E")
						.animate({
							"background-color" : "#FFF"
						}, 1000)
						.fadeOut();
			}
		});
	}
	
	//Налаштування діалогу видалення
	$("#del_cat_dialog").dialog({
		autoOpen: false,
		modal: true,
		buttons: [
			{text: "Видалити", click: del_cat},
			{text: "Скасувати", click: function(){
				$(this).dialog("close");
			}}
		]
	});
	
	$("#manage_cats a.delete").click(function(e){
		//Збереження ID категорії для видалення
		cat_id = $(this).data("catId");
		//Вивід назви категорії
		cat_row = $(this).closest("tr");
		var cat_name = cat_row.find(".cat_name").text();
		$("#del_cat_name").text(cat_name);
		//Показати діалог
		$("#del_cat_dialog").dialog("open");
		e.preventDefault();
	});
});