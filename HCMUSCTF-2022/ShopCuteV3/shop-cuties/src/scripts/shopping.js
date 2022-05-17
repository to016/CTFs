$( document ).ready(function() {
    $('.alert').hide();
    document.getElementsByClassName('warning-container')[0].style.visibility = 'hidden';
});

function showBuy() {
  $('.alert').show();
  document.querySelector(".alert").scrollIntoView({behavior: 'smooth'});
}

function hideBuy() {
  $('.alert').hide();
}