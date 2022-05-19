// register enterKey event
(function($) {
	$.fn.catchEnter = function(sel) {
		return this.each(function() {
			$(this).on('keyup', sel, function(e) {
				if (e.keyCode == 13)
					$(this).trigger('enterkey');
			})
		});
	};
})(jQuery);


$(document).ready(() => {
	// set input events
	$('#update-btn').on('click', updateAcc);
	$('#password').catchEnter().on('enterkey', () => {
		auth(intent)
	});
	$('#password').on('keydown', () => {
		$('#resp-msg').hide()
	});

});

function toggleInputs(state) {
	$("#password").prop("disabled", state);
	$("#update-btn").prop("disabled", state);
}


async function updateAcc() {

	toggleInputs(true); // disable inputs

	// prepare alert
	let card = $("#resp-msg");
	card.attr("class", "alert alert-info");
	card.hide();

	// validate
	let pass = $("#password").val();
	if ($.trim(pass) === '') {
		toggleInputs(false);
		card.text("Password field cannot be blank!");
		card.attr("class", "alert alert-danger");
		card.show();
		return;
	}

	const data = {
		password: pass
	};

	await fetch(`/api/user/password`, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify(data),
		})
		.then((response) => response.json()
			.then((resp) => {
				card.attr("class", "alert alert-danger");
				if (response.status == 200) {
					card.attr("class", "alert alert-success");
				}
				card.text(resp.message);
				card.show();
			}))
		.catch((error) => {
			card.text(error);
			card.attr("class", "alert alert-danger");
			card.show();
		});
	toggleInputs(false); // enable inputs
}