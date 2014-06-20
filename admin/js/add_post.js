$(document).ready(function(){
	//Создание редакторов
	CKEDITOR.replace("post_content_en");
	CKEDITOR.replace("post_content_ua");
	
	var message = $("<div/>");
	
	//Вывод сообщений об ошибке
	function printMessage(setClass, setText){
		message.removeClass().addClass(setClass).text(setText).insertBefore("#form_post");
	}

	//Смена языка новости
	$("#edit_" + $("#sel_lang input:not(:checked):eq(0)").val()).hide();
	$("#sel_lang input").change(function(){
		$(".editor_post_block").hide();
		$("#edit_" + $(this).val()).show();
	});

	//Отправка новостей
	$(".btn_post, .btn_draft").click(function(e){
		//переменные для английского языка
		var title_en    = $("#post_title_en").val();
		var content_en  = CKEDITOR.instances.post_content_en.getData();
		$("#post_content_en").val(content_en);

		//переменные для украинского языка
		var title_ua    = $("#post_title_ua").val();
		var content_ua  = CKEDITOR.instances.post_content_ua.getData();
		$("#post_content_ua").val(content_ua);
		var cat         = $(".categories :radio:checked").size();
		
		//Якщо форма заповнена
		if(title_en != "" && content_en != "" && title_ua != "" && content_ua != "" && cat != 0){
			var encodeForm = $("#form_post").serialize() + "&post_status=" + $(this).val();
			$.post("post.php", encodeForm, function(data){
				switch(data){
					case "POST" :
						printMessage("ok", "Новина успішно додана!");
						break;
					case "DRAFT" :
						printMessage("ok", "Новина збережена як чорновик!");
						break;
					default :
						printMessage("error", "Щось пішло не так, спробуйте пізніше!");
						break;
				}
			});
		} else {//Якщо ні
			printMessage("warning", "Не всі дані заповнені, перевірте форму!");
		}
		
		e.preventDefault();
	});
});