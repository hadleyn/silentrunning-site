var scale = 1;
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
    var hiveDisplayWrapper = document.getElementById("hiveDisplayWrapper"); 
    if (hiveDisplayWrapper.addEventListener)
        hiveDisplayWrapper.addEventListener('mousewheel',function(event){
            wheel(event);
            return false;
        }, false);

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
    $('#hiveDisplay > *').unbind();
    
    $('#hiveDisplay').draggable({
        //        containment: [0,0,-4100, -5600] //this basically means the upper left corner can go all the way to 0, and as far up and left as the width of the pan-able area - the side of the view port
        });
    
    //    $('#hiveDisplay').bind('mousewheel', function(evt){
    //        console.log(evt);
    //    });
    
    $('.commentCount').live('click', function(){
        showComments(this); 
    });
    $('.addComment').live('click', function(){
        var parentID = $(this).parents('.hiveContentBox').get(0).id;
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
        var expand = $(this).find('.addComment').get(0);
        console.log(expand);
        $(expand).animate({opacity: 1}, 200);
//        if ($(this).css('opacity') == 1) {
//            var expand = $(this).children('.expandContent').get(0);
//            $(expand).fadeIn('fast');
//        }
    }, function() {
        //mouse out
        var expand = $(this).find('.addComment').get(0);
        $(expand).animate({opacity: 0}, 200);
//        if ($(this).css('opacity') == 1) {
//            var expand = $(this).children('.expandContent').get(0);
//            $(expand).fadeOut('fast');
//        }
    });
    
    $('.hiveContentBox').bind('drag', function(){
//        updateHiveGraphics(); 
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
    var parentid = $($(clicked).parents('div')).attr('id');
    $.ajax({
        type: 'post',
        dataType: 'json',
        data: 'parentid='+parentid,
        url: '/sr/hive/showComments',
        success: function(data) {
            $('#hiveDisplay').html(data.hiveContent);
//            updateHiveGraphics();
            rebind();
        }
    });
}

function updateHiveGraphics() {
    var c=document.getElementById("hiveGraphics");
    c.width = c.width; //clear the graphics
    var ctx=c.getContext("2d");
    if ($('.root').position()) {
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
}


function handle(delta) {
    console.log('mouse delta '+delta);
    if (delta > 0) {
        //zoom in
        if (scale < 1) {
            scale = scale * 1.05;
        }
    } else {
    //zoom out
        if (scale > 0) {
            scale = scale * 0.95;
        }
    }
//    $('#hiveDisplay').css('transform', 'scale('+scale+')');
//    $('#hiveDisplay').css('-ms-transform', 'scale('+scale+')');
//    $('#hiveDisplay').css('-webkit-transform', 'scale('+scale+')');
//    $('#hiveDisplay').css('-o-transform', 'scale('+scale+')');
//    $('#hiveDisplay').css('-moz-transform', 'scale('+scale+')');
}

function wheel(event){
    var delta = 0;
    if (!event) event = window.event;
    if (event.wheelDelta) {
        delta = event.wheelDelta/120; 
    } else if (event.detail) {
        delta = -event.detail/3;
    }
    if (delta)
        handle(delta);
    if (event.preventDefault)
        event.preventDefault();
    event.returnValue = false;
}

