$(document).ready(function(){
	var message = $("<div/>");
	
	function printMessage(setClass, setText){
		message.removeClass().addClass(setClass).text(setText).insertBefore("#manage_cats");
	}

	//Зміна мови категорії
	$("#edit_" + $("#sel_lang input:not(:checked):eq(0)").val()).hide();
	$("#sel_lang input").change(function(){
		$(".editor_cat_block").hide();
		$("#edit_" + $(this).val()).show();
	});

	$("#add_cat").click(function(e){
		var cat_parent  = $("#add_cat_parent").val();
		var cat_name_en = $("#add_cat_name_en").val();
		var cat_name_ua = $("#add_cat_name_ua").val();
		var cat_class   = $("#add_cat_class").val();
		
		//Якщо заповнене ім'я категорії
		if(cat_name_en != "" && cat_name_ua != ""){
			$.post("add_cat.php", {add_cat_parent: cat_parent, add_cat_name_en: cat_name_en, add_cat_name_ua: cat_name_ua, add_cat_class: cat_class}, function(data){
				switch(data){
					case "CAT" :
						printMessage("ok", "Категорія успішно додана!");
						break;
					case "DOUBLE CAT" :
						printMessage("error", "Ця категорія вже є в обраній батьківській категорії, змініть ім'я!");
						break;
					default :
						printMessage("error", "Щось пішло не так, спробуйте пізніше!");
						break;
				}
			});
		} else {//Если нет 
			printMessage("warning", "Ім'я категорії на задано!");
		}
		
		e.preventDefault();
	});
});