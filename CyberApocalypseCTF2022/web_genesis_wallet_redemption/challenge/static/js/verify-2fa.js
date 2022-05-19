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
	$("#submit-btn").on('click', verifyOTP);
	$("#otp-token").catchEnter().on('enterkey', () => verifyOTP);

});


const verifyOTP = async () => {


	// prepare alert
	let card = $(`#otp-resp-msg`);
	card.attr("class", "alert alert-info");
	card.hide();

	let otp = $('#otp-token').val();
	if ($.trim(otp) === '') {
		card.text("Input OTP token first!");
		card.attr("class", "alert alert-danger");
		card.show();
		return;
	}

	await fetch(`/api/2fa/verify`, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({otp}),
		})
		.then((response) => response.json()
			.then((resp) => {
				if (response.status == 200) {
                    window.location.href = '/dashboard';
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

}