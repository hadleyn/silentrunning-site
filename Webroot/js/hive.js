$(document).ready(function(){
    rebind();
    $('#depthSlider').slider({
        orientation: 'vertical',
        min: 0,
        max: 480,
        step: 12,
        value: 0,
        slide: function( event, ui ) {
            updateHiveDisplay(ui.value);
        }
    });
});

function updateHiveDisplay(value) {
    $.ajax({
        type: 'post',
        dataType: 'json',
        data: 'depth='+value,
        url: '/sr/hive/updateHiveDepth',
        success: function(data) {
            $('#hiveDisplay').html(data.newhive);
            rebind();
        }
    });
}

function rebind() {
    $('.expandContent').live('click', function(){
        var parentID = $(this).parent('.hiveContentBox').get(0).id;
        $('#parentID').val(parentID);
        $("#addComment").dialog( "open" );
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
    
    $('#addComment').dialog({
        autoOpen: false,
        height: 300,
        width: 500,
        modal: true,
        buttons: {
            "Add Comment": function() {
                $( this ).dialog( "close" );
            }
        }
    });
}
