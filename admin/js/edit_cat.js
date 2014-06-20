$(document).ready(function(){
	//Посилання на елементи категорії
	var parent_tr;
	var default_color;
	var current_name;
	var current_class;
	
	//Функція редагування категорії
	function edit_cat(){
		var edit_name  = $("#cat_name").val();
		var edit_class = $("#cat_class").val();
		var cat_id     = $("#cat_id").val();
		
		$.post("edit_cat.php", {cat_name: edit_name, cat_class: edit_class, cat_id: cat_id}, function(data){
			current_name.text($("#cat_name").val());
			current_class.text($("#cat_class").val());
			$("#edit_dialog").dialog("close");
			parent_tr.css("background-color", "#3CDB3C")
					 .animate({
						"background-color" : default_color
					 }, 1000);
		});
	}

	//Оброблювач кліка на кнопці редагування категорії
	$("#manage_cats a.edit").click(function(e){
		parent_tr      = $(this).closest("tr");
		default_color  = parent_tr.css("background-color");
		current_name   = parent_tr.find(".cat_name");
		current_class  = parent_tr.find("td:last");
		var current_id = $(this).data("catId");
		
		$("#cat_name").val(current_name.text());
		$("#cat_class").val(current_class.text());
		$("#cat_id").val(current_id);
		
		$("#edit_dialog").dialog("open");
		e.preventDefault();
	});
	
	//Налаштування діалогу редагування
	$("#edit_dialog").dialog({
		autoOpen: false,
		modal: true,
		buttons: [
			{text: "Застосувати", click: edit_cat},
			{text: "Скасувати", click: function(){
				$(this).dialog("close");
			}}
		]
	});
});