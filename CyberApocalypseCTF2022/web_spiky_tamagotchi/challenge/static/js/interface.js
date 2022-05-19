
$(document).ready(function() {
    $('#feed-btn').on('click', feed);
    $('#play-btn').on('click', play);
    $('#sleep-btn').on('click', sleep);
    window.currentMood = 'awkward';
});

const changeBtnStates = (state) => {
    $('#feed-btn').prop('disabled', state);
    $('#play-btn').prop('disabled', state);
    $('#sleep-btn').prop('disabled', state);
}

const changeMood = (mood) => {
    $('.spiky').removeClass('active');
    $(`.${mood}`).addClass('active');
}

const sendActivity = async (activity) => {
    await fetch(`/api/activity`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            activity,
            'health': $('#health').text(),
            'weight': $('#weight').text(),
            'happiness': $('#happiness').text()
        }),
    })
    .then((response) => response.json()
        .then((resp) => {
            if (response.status == 200) {
                $('#health').text(resp.health);
                $('#weight').text(resp.weight);
                $('#happiness').text(resp.happiness);
                window.currentMood = resp.mood;
                return;
            }
        }))
    .catch((error) => {
        console.error(error);
    });
}


const feed = async () => {
    changeBtnStates(true);
    setTimeout(() => { changeBtnStates(false) }, 3000);

    $('#treat').show();
    setTimeout(() => { $('#treat').hide(); }, 3000);
    changeMood('shocked');
    setTimeout(() => {changeMood('sleep')}, 300);
    setTimeout(() => {changeMood('shocked')}, 600);
    setTimeout(() => {changeMood('sleep')}, 900);
    setTimeout(() => {changeMood('shocked')}, 1200);
    setTimeout(() => {changeMood('sleep')}, 1500);
    setTimeout(() => {changeMood('shocked')}, 1800);
    setTimeout(() => {changeMood('sleep')}, 2100);
    setTimeout(() => {changeMood('shocked')}, 2400);
    setTimeout(() => {changeMood('sleep')}, 2700);
    setTimeout(() => {changeMood(currentMood)}, 3000);

    sendActivity('feed');
}

const play = async () => {
    changeBtnStates(true);
    setTimeout(() => { changeBtnStates(false) }, 3000);
    changeMood('dancing');
    setTimeout(() => {changeMood(currentMood)}, 3000);

    sendActivity('play');
}

const sleep = async () => {
    changeBtnStates(true);
    setTimeout(() => { changeBtnStates(false) }, 3000);
    changeMood('sleep');
    setTimeout(() => {changeMood(currentMood)}, 3000);

    sendActivity('sleep');
}