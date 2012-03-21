$(document).ready(function(){
    rebind();    
});

function updateHiveDisplay(value) {
    $.ajax({
        type: 'post',
        dataType: 'json',
        data: 'depth='+value,
        url: '/sr/hive/updateHiveDepth',
        success: function(data) {
           
        }
    });
}

function rebind() {
    $('.hiveContentBox').draggable({
        containment: 'parent'
    }); 
    $('#depthSlider').slider({
        orientation: 'vertical',
        min: 0,
        max: 10,
        step: 1,
        value: 10,
        slide: function( event, ui ) {
            updateHiveDisplay(ui.value);
        }
    });
    $('.hiveContentBox').draggable({
        containment: 'parent'
    });
    $( ".hiveContentBox" ).bind( "dragstop", function(event, ui) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: 'x='+ui.position.left+'&y='+ui.position.top+'&id='+ui.helper[0].id,
            url: '/sr/hive/updateContentCoords'
        });
    });
    $('.hiveContentBox').hover(function() {
        //mouse in
        if ($(this).css('opacity') == 1) {
            var expand = $(this).children('.expandContent').get(0);
            $(expand).fadeIn('fast');
        }
    }, function() {
        //mouse out
        if ($(this).css('opacity') == 1) {
            var expand = $(this).children('.expandContent').get(0);
            $(expand).fadeOut('fast');
        }
    });
}
