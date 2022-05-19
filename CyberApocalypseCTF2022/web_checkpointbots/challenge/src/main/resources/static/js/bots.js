$(document).ready(() => {

	// set input events
	$('#bots-switch-btn').on('click', showBotsList);
    $('#deploy-bot-btn').on('click', deploy);
    loadDeployedBots();

});

const showBotsList = async () => {
    if ($('#bots-list-container').is(":visible")){
		$('#bots-list-container').slideUp();
	}
    else {
        $('#bots-list-container').slideDown();
    }
}

const saveToken = async (token) => {
    if (window.localStorage.getItem('tokens') === null) {
        tokens = [token];
    }
    else {
        tokens = JSON.parse(window.localStorage.getItem('tokens'));
        tokens.push(token);
    }
    window.localStorage.setItem('tokens', JSON.stringify(tokens));
}

const loadDeployedBots = async () => {
    if (window.localStorage.getItem('tokens') === null) {
        return;
    }
    tokens = JSON.parse(window.localStorage.getItem('tokens'));
    totalbots = 0;
    for (let token of tokens) {
        totalbots += 1;
        row = `<li class="bot-row">
            <div class="bot-info-l1">
                    <p class="bot-token">${token}</p>
                    <p class="lst-checkin"> Last Check-in: <span class="last-check-in" id="${token}_chk">N/A</span></p>
                    <a href="#" class="add-checkin-bot-btn" onclick="addCheckIn('${token}')">+ Add Check IN</a>
                    <a href="#" class="checkin-bot-btn" onclick="showSheet('${token}')">Check-in Sheet</a>
                </div>
            </li>`;
        $('#bot-row-container').append(row);
    }
    $('#total-bots').text(totalbots);

}

const deploy = async () => {
    totalbots = $('#total-bots').text();
    totalbots = parseInt(totalbots) + 1;
	await fetch(`/api/checkpointbot`, {
			method: 'GET',
            credentials: 'include'
		})
		.then((response) => response.json()
			.then((resp) => {
				row = `<li class="bot-row">
                    <div class="bot-info-l1">
                            <p class="bot-token">${resp.token}</p>
                            <p class="lst-checkin"> Last Check-in: <span class="last-check-in" id="${resp.token}_chk">N/A</span></p>
                            <a href="#" class="add-checkin-bot-btn" onclick="addCheckIn('${resp.token}')">+ Add Check IN</a>
                            <a href="#" class="checkin-bot-btn" onclick="showSheet('${resp.token}')">Check-in Sheet</a>
                        </div>
                    </li>`;
                $('#bot-row-container').append(row);
                $('#total-bots').text(totalbots);
                saveToken(resp.token);
			}))
		.catch((error) => {
            console.log(error);
		});
}

const addCheckIn = async (token) => {
    await fetch(`/api/checkpointbot/check-in?token=${token}`, {
        method: 'GET',
        credentials: 'include'
    })
    .then((response) => response.json()
        .then((resp) => {
            $(`#${token}_chk`).text(resp.check_in);
        }))
    .catch((error) => {
        console.log(error);
    });
}

const showSheet = async(token) => {
    window.open(`/api/checkpointbot/sheet?token=${token}`, '_blank');
}