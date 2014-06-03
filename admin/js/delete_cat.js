$(document).ready(function(){
	var cat_id;
	var cat_row;
	
	//������� ��������� �������
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
	
	//������������ ������ ���������
	$("#del_cat_dialog").dialog({
		autoOpen: false,
		modal: true,
		buttons: [
			{text: "��������", click: del_cat},
			{text: "���������", click: function(){
				$(this).dialog("close");
			}}
		]
	});
	
	$("#manage_cats a.delete").click(function(e){
		//���������� ID ������� ��� ���������
		cat_id = $(this).data("catId");
		//���� ����� �������
		cat_row = $(this).closest("tr");
		var cat_name = cat_row.find(".cat_name").text();
		$("#del_cat_name").text(cat_name);
		//�������� �����
		$("#del_cat_dialog").dialog("open");
		e.preventDefault();
	});
});