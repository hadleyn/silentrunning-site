$(document).ready(function(){
    var menuwidth = $('#menu').width();
    $('#menuLeftBlock').animate({
        right: '+='+menuwidth
    }, 1000, 'swing', function() {
        // Animation complete.
        });
});