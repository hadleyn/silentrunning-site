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
    $('.commentCount').live('click', function(){
        showComments(this); 
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
                submitComment(this);
            }
        }
    });
}

function submitComment(dialog) {
    $.ajax({
        type: 'post',
        dataType: 'json',
        data: $('#commentForm').serialize(),
        url: '/sr/hive/addComment',
        success: function(data){
            if (data.errors.length == 0) {
                $('#comment').val('');
                $(dialog).dialog('close');
            } else {
                $('#commentMessages').html(data.errors);
            }
            refreshHiveContent();
        }
    });
}

function showComments(clicked) {
    var parentid = $($(clicked).parent('div')).attr('id');
    $.ajax({
        type: 'post',
        dataType: 'json',
        data: 'parentid='+parentid,
        url: '/sr/hive/showComments',
        success: function(data) {
            $('#hiveDisplay').html(data.hiveContent);
            rebind();
            $('.hiveContentBox').bind('drag', function(){
               updateHiveGraphics(); 
            });
            updateHiveGraphics();
        }
    });
}

function updateHiveGraphics() {
    var c=document.getElementById("hiveGraphics");
    c.width = c.width; //clear the graphics
    var ctx=c.getContext("2d");
    var rootX = $('.root').position().left + ($('.root').width()/2);
    var rootY = $('.root').position().top + ($('.root').height()/2);
    $('.child').each(function(){
        var childX = $(this).position().left + ($(this).width()/2);
        var childY = $(this).position().top + ($(this).height()/2);
        ctx.moveTo(rootX,rootY);
        ctx.lineTo(childX,childY);
        ctx.stroke();
    });
}