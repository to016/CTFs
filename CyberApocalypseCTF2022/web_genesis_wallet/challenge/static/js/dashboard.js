$(document).ready(() => {
	$('#send-btn').on('click', createTransaction);
	window.easyMDE = new EasyMDE({
		element: $('#trx-cmt')[0],
		renderingConfig: {
			singleLineBreaks: false,
			sanitizerFunction: (renderedHTML) => {
				return DOMPurify.sanitize(renderedHTML, {ALLOWED_TAGS: ['strong', 'em', 'img', 'a', 's', 'ul', 'ol', 'li']})
			},
		},
		toolbar: ['strikethrough', 'bold', 'unordered-list', 'ordered-list', 'link', 'image', 'code', 'preview'],
	});
});

const switchLang = () => {
    if (location.pathname.includes('/xq/')) {
        window.location.href = location.pathname.replace('/xq/','/');
    }
    else {
        window.location.href = '/xq' + location.pathname;
    }
}

const showTrx = (id) => {
	$(`#${id}`).toggle();
}

const showNote = (id) => {
	$(`#${id}`).toggle();
}

const verifyOTP = async(trxid) => {
	let card = $('#otp-resp');
	card.attr("class", "alert alert-success");
	card.hide();

	let otp = $(`#otp_${trxid}`).val();

	if ($.trim(otp) === '') {
		card.text("Please input OTP first!");
		card.attr("class", "alert alert-danger");
		card.show();
		return;
	}

	await fetch(`/api/transactions/verify`, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({trxid, otp}),
		})
		.then((response) => response.json()
			.then((resp) => {
				if (response.status == 200) {
					card.text(resp.message);
					card.attr("class", "alert alert-success");
					card.show();
					location.reload();
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
}


const createTransaction = async () => {

	$('#send-btn').prop('disabled', true);

	let card = $('#send-resp');
	card.attr("class", "alert alert-success");
	card.text('Please wait...');
	card.show();


	let receiver = $('#send-addr').val();
	let amount = $('#send-amt').val();
	let note = window.easyMDE.value();

	if ($.trim(receiver) === '' || $.trim(amount) === '') {
		card.text("Provide sender address and amount first!");
		card.attr("class", "alert alert-danger");
		card.show();
		$('#send-btn').prop('disabled', false);
		return;
	}

	await fetch(`/api/transactions/create`, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({receiver, amount, note}),
		})
		.then((response) => response.json()
			.then((resp) => {
				if (response.status == 200) {
					card.text(resp.message);
					card.attr("class", "alert alert-success");
					card.show();
					window.location.href = '/transactions';
					return;
				}
				card.text(resp.message);
				card.attr("class", "alert alert-danger");
				card.show();
				$('#send-btn').prop('disabled', false);
			}))
		.catch((error) => {
			$('#send-btn').prop('disabled', false);
			card.text(error);
			card.attr("class", "alert alert-danger");
			card.show();
		});
}