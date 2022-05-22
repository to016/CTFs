$(document).ready(() => {
	$('.trx-t1').toggleClass('active');
    $('.trx-inbound').hide();
    $('.trx-t1').on('click', () => {toggleTRX(1)});
    $('.trx-t2').on('click', () => {toggleTRX(2)});
});


const toggleTRX = (tid) => {
    if (tid == 1) {
        $('.trx-t2').removeClass('active');
        $('.trx-inbound').hide();
        $('.trx-t1').addClass('active');
        $('.trx-outbound').show();
    }
    else {
        $('.trx-t1').removeClass('active');
        $('.trx-outbound').hide();
        $('.trx-t2').addClass('active');
        $('.trx-inbound').show();
    }
}