$(document).ready(function(){
	//Object of errors form
	var errors = {};

	//Form validate
	$("#register_form").submit(function(e){
		//Clear errors message
		$("tr").each(function() {
			$("td:eq(2)", this).text("");
		});

		//Login validate
		if($("#r_login").val().length >= 30) {
			errors["r_login"] = "Too long login!";
		}

		//E-mail validate
		if($("#r_email").val().length >= 100) {
			errors["r_email"] = "Too long e-mail!";
		}
		else if(! /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test($("#r_email").val())) {
			errors["r_email"] = "E-mail is not valid!";
		}

		//Password validate
		if ($("#r_pass").val().length >= 6 && $("#r_pass").val().length <= 100) {
			if ($("#r_pass").val() != $("#r_repass").val()) {
				errors["r_pass"] = "Passwords do not match!!";
			}
		}
		else {
			errors["r_pass"] = "The password must be at least 6 and no more than 100 characters!";
		}

		//Print errors
		if (Object.keys(errors).length) {
			for (id in errors) {
				$("#" + id).parent().next().text(errors[id]);
			}

			errors = {};
			e.preventDefault();
		}

	});
});