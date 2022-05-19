// register enterKey event
(function($) {
	$.fn.catchEnter = function(sel) {
		return this.each(function() {
			$(this).on('keyup', sel, function(e) {
				if (e.keyCode == 13)
					$(this).trigger("enterkey");
			})
		});
	};
})(jQuery);

const switchLang = () => {
    if (location.pathname.includes('/xq/')) {
        window.location.href = location.pathname.replace('/xq/','/');
    }
    else {
        window.location.href = '/xq' + location.pathname;
    }
}

$(document).ready(() => {

	// set input events
	$("#login-btn").on('click', () => {
		auth('login')
	});
	$("#register-btn").on('click', () => {
		auth('register')
	});
	$("#username").catchEnter().on('enterkey', () => {
		auth('login')
	});
	$("#password").catchEnter().on('enterkey', () => {
		auth('login')
	});

});

const toggleInputs = (state) => {
	$("#register-username").prop("disabled", state);
	$("#register-password").prop("disabled", state);
    $("#login-username").prop("disabled", state);
	$("#login-password").prop("disabled", state);
	$("#login-btn").prop("disabled", state);
	$("#register-btn").prop("disabled", state);
}

const auth = async (intent) => {

	toggleInputs(true);

	// prepare alert
	let card = $(`#${intent}-resp-msg`);
	card.attr("class", "alert alert-info");
	card.hide();

	// validate
	let user = $(`#${intent}-username`).val();
	let pass = $(`#${intent}-password`).val();
	if ($.trim(user) === '' || $.trim(pass) === '') {
		toggleInputs(false);
		card.text("Input username and password first!");
		card.attr("class", "alert alert-danger");
		card.show();
		return;
	}

	const data = {
		username: user,
		password: pass
	};

	await fetch(`/api/${intent}`, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify(data),
		})
		.then((response) => response.json()
			.then((resp) => {
				if (response.status == 200) {
					card.text(resp.message);
					card.attr("class", "alert alert-success");
					card.show();
					if (intent == 'login'){
						window.location.href = '/dashboard';
					}
					return;
				}
				card.text(resp.message);
				card.attr("class", "alert alert-danger");
				card.show();
			}))
		.catch((error) => {
			card.text(error);
			card.attr("class", "alert alert-danger");
			card.show();
		});

	toggleInputs(false);
}