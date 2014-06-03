$(document).ready(function(){
	CKEDITOR.replace("post_content");
	
	var message = $("<div/>");
	
	function printMessage(setClass, setText){
		message.removeClass().addClass(setClass).text(setText).insertBefore("#form_post");
	}

	$(".btn_post, .btn_draft").click(function(e){
		var title    = $("#post_title").val();
		var content  = CKEDITOR.instances.post_content.getData();
		$("#post_content").val(content);
		var cat      = $(".categories :radio:checked").size();
		
		//���� ����� ���������
		if(title != "" && content != "" && cat != 0){
			var encodeForm = $("#form_post").serialize() + "&post_status=" + $(this).val();
			
			$.post("post.php", encodeForm, function(data){
				switch(data){
					case "POST" :
						printMessage("ok", "������ ������ ������!");
						break;
					case "DRAFT" :
						printMessage("ok", "������ ��������� �� ��������!");
						break;
					default :
						printMessage("error", "���� ���� �� ���, ��������� �����!");
						break;
				}
			});
		} else {//���� �
			printMessage("warning", "�� �� ��� ��������, �������� �����!");
		}
		
		e.preventDefault();
	});
});