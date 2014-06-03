$(document).ready(function(){
	var message = $("<div/>");
	
	function printMessage(setClass, setText){
		message.removeClass().addClass(setClass).text(setText).insertBefore("#manage_cats");
	}

	$("#add_cat").click(function(e){
		var cat_parent = $("#add_cat_parent").val();
		var cat_name   = $("#add_cat_name").val();
		var cat_class  = $("#add_cat_class").val();
		
		//���� ��������� ��'� �������
		if(cat_name != ""){
			$.post("add_cat.php", {add_cat_parent: cat_parent, add_cat_name: cat_name, add_cat_class: cat_class}, function(data){
				switch(data){
					case "CAT" :
						printMessage("ok", "�������� ������ ������!");
						break;
					case "DOUBLE CAT" :
						printMessage("error", "�� �������� ��� � � ������ ���������� �������, ����� ��'�!");
						break;
					default :
						printMessage("error", "���� ���� �� ���, ��������� �����!");
						break;
				}
			});
		} else {//���� ��� 
			printMessage("warning", "��'� ������� �� ������!");
		}
		
		e.preventDefault();
	});
});