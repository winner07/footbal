$(document).ready(function(){
	var post_row;
	var post_id;
	
	//������� ��������� ������
	function del_post(){
		$.post("delete_post.php", {post_id : post_id}, function(data){
			if(data == "������ ��������"){
				$("#del_post_dialog").dialog("close");
				post_row.css("background-color", "#FF5E5E")
						.animate({
							"background-color" : "#FFF"
						}, 1000)
						.fadeOut();
			}
		});
	}
	
	//������������ ������ ���������
	$("#del_post_dialog").dialog({
		autoOpen: false,
		modal: true,
		buttons: [
			{text: "��������", click: del_post},
			{text: "���������", click: function(){
				$(this).dialog("close");
			}}
		]
	});
	
	//�������� ���� �� ������ ���������
	$("a.delete").click(function(e){
		//���������� ID ������� ��� ���������
		post_id = $(this).data("postId");
		//���� ����� �������
		post_row = $(this).closest("tr");
		var post_name = post_row.find("span:first").text();
		$("#del_post_name").text(post_name);
		//�������� �����
		$("#del_post_dialog").dialog("open");
		
		e.preventDefault();
	});
});