$(document).ready(function(){
    var menuwidth = $('#menu').width();
    menuwidth = menuwidth - 15;
    $('#menuLeftBlock').animate({
        right: '+='+menuwidth
    }, 1000, 'swing', function() {
        // Animation complete.
        });
});