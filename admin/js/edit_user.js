$(document).ready(function(){
	var $current_user;
	var default_color;

	//Функція редагування користувача
	function edit_user(){
		var user_data = {
				u_id      : $("#u_id").val(),
				u_login   : $("#u_new_login").val(),
				u_name    : $("#u_new_name").val(),
				u_surname : $("#u_new_surname").val(),
				u_email   : $("#u_new_email").val(),
				u_pass    : $("#u_new_pass").val(),
				u_role    : $("#u_new_role").val()
		};

		$.post("edit_user.php", user_data,
			function(data){
				if (data == "OK") {
					$(".u_login", $current_user).text(user_data["u_login"]);
					$(".u_name", $current_user).text(user_data["u_name"]);
					$(".u_surname", $current_user).text(user_data["u_surname"]);
					$(".u_email", $current_user).text(user_data["u_email"]);
					$(".u_role", $current_user).text(user_data["u_role"]);

					$("#edit_dialog").dialog("close");
					$current_user.css("background-color", "#3CDB3C")
						 .animate({
							"background-color" : default_color
						 }, 1000);
				}
			});
	}

	//Налаштування діалогу редагування
	$("#edit_dialog").dialog({
		autoOpen: false,
		modal: true,
		buttons: [
			{text: "Edit", click: edit_user},
			{text: "Cancel", click: function(){
				$(this).dialog("close");
			}}
		]
	});

	//Оброблювач кліка на кнопці редагування користувача
	$(".edit_block a.edit").click(function(e){
		$current_user = $(this).closest("tr");
		default_color = $current_user.css("background-color");

		$("#u_new_login").val($(".u_login", $current_user).text());
		$("#u_new_name").val($(".u_name", $current_user).text());
		$("#u_new_surname").val($(".u_surname", $current_user).text());
		$("#u_new_email").val($(".u_email", $current_user).text());
		$("#u_new_role option").filter("[value='" + $(".u_role", $current_user).text() + "']").attr("selected", "selected");
		$("#u_id").val($current_user.data("userId"));

		$("#edit_dialog").dialog("open");
		e.preventDefault();
	});

});
