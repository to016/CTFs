$(document).ready(() => {

    $("#ok-btn").on('click', () => {
        if (location.pathname.includes('/xq/')) {
            window.location.href = '/xq/dashboard';
        }
        else {
            window.location.href = '/dashboard';
        }
	});

});

const genQRCode = async (otpkey) => {

	await fetch(`/api/2fa/qrcode`, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify({otpkey}),
		})
		.then((response) => response.json()
			.then((qrData) => {
				if (response.status == 200) {
					$('#qrImg').attr('src', qrData.qr);
				}
			}))
		.catch((error) => {
			console.log(error);
		});

}

const switchLang = () => {
    if (location.pathname.includes('/xq/')) {
        window.location.href = location.pathname.replace('/xq/','/');
    }
    else {
        window.location.href = '/xq' + location.pathname;
    }
}
