$(document).ready(function(){
    rebind();
    $('#hiveGraphics').svg({
        onLoad: updateHiveGraphics
    });
    $('.closeComments').live('click', function(evt){
        evt.preventDefault();
        var parentID = $(this).parents('.hiveContentBox').get(0).id;
        closeComments(parentID);
    });
    
    $('.commentCount').live('click', function(evt){
        evt.preventDefault();
        showComments(this); 
    });
    
    $('.addComment').live('click', function(evt){
        evt.preventDefault();
        var parentID = $(this).parents('.hiveContentBox').get(0).id;
        $('#parentID').val(parentID);
        $("#addComment").dialog( "open" );
    });
    
    $('.deleteContent').live('click', function(evt){
        evt.preventDefault();
        var contentID = $(this).parents('.hiveContentBox').get(0).id;
        deleteContent(contentID);
    });
    
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
        hiveDisplayWrapper.addEventListener('DOMMouseScroll',function(event){
            wheel(event);
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
    $('#hiveDisplay *').unbind();
    
    $('#hiveDisplay').draggable({
        //containment: [0,0,-4100, -5600] //this basically means the upper left corner can go all the way to 0, and as far up and left as the width of the pan-able area - the side of the view port
        });
    
    //    $('#hiveDisplay').bind('mousewheel', function(evt){
    //        console.log(evt);
    //    });
    
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
    
    
    $('.hiveContentBox').bind('drag', function(){
        $('#hiveGraphics').svg({
            onLoad: updateHiveGraphics
        }); 
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
    
    $('.hiveContentBox').hover(function() {
        //mouse in
        
        if ($(this).css('opacity') == 1) {
            var expand = $(this).find('.addComment, .deleteContent');
            $(expand).animate({
                opacity: 1
            }, 200);
        }
    }, function() {
        //mouse out
        
        if ($(this).css('opacity') == 1) {
            var expand = $(this).find('.addComment, .deleteContent');
            $(expand).animate({
                opacity: 0
            }, 200);
        }
    });
}

function submitComment(dialog) {
    var xpos = $('#hiveDisplay').position().left;
    var ypos = $('#hiveDisplay').position().top;
    $.ajax({
        type: 'post',
        dataType: 'json',
        data: $('#commentForm').serialize()+'&hiveTop='+ypos+'&hiveLeft='+xpos,
        url: '/sr/hive/addComment',
        success: function(data){
            if (data.errors.length == 0) {
                $('#comment').val('');
                $(dialog).dialog('close');
                showCommentsByID($('#parentID').val());
            } else {
                $('#commentMessages').html(data.errors);
            }
        }
    });
}

function showCommentsByID(id) {
    $.ajax({
        type: 'post',
        dataType: 'json',
        data: 'parentid='+id,
        url: '/sr/hive/showComments',
        success: function(data) {
            $('#hiveDisplay').html(data.hiveContent);
            $('#hiveGraphics').svg({
                onLoad: updateHiveGraphics
            });
            rebind();
            $('#depthSlider').slider( "option", "disabled", true );
        }
    });
}

function showComments(clicked) {
    var parentid = $($(clicked).parents('div')).attr('id');
    showCommentsByID(parentid);
}

var node = null;
var drawNodes = [];
function updateHiveGraphics(svg) {
    //    var c=document.getElementById("hiveGraphics");
    //    c.width = c.width; //clear the graphics
    //    var ctx=c.getContext("2d");
    //Clear the graphics
    $.each(drawNodes, function(){
        $(this).remove(); 
    });
    drawNodes = [];
    if ($('.root').position()) {
        var rootX = $('.root').position().left + ($('.root').width()/2);
        var rootY = $('.root').position().top + ($('.root').height()/2);
        $('.child').each(function(){
            var childX = $(this).position().left + ($(this).width()/2);
            var childY = $(this).position().top + ($(this).height()/2);
            node = svg.line(rootX, rootY, childX, childY, {
                stroke: "blue", 
                strokeWidth: 1
            });
            drawNodes[drawNodes.length] = node; 
        });
    }
}

var scale = 1;
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
    if (scale >= 1) {
        scale = 1;
        $('.hiveContentBox').draggable('enable');
    } else {
        $('.hiveContentBox').draggable('disable'); 
    }
    //    $('#hiveDisplay').animate({
    //        svgTransform: 'scale('+scale+')'
    //    }, 20);
    $('#hiveDisplay').css('transform', 'scale('+scale+')');
    $('#hiveDisplay').css('-ms-transform', 'scale('+scale+')');
    $('#hiveDisplay').css('-webkit-transform', 'scale('+scale+')');
    $('#hiveDisplay').css('-o-transform', 'scale('+scale+')');
    $('#hiveDisplay').css('-moz-transform', 'scale('+scale+')');
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

function closeComments(parentID) {
    $.ajax({
        type: 'post',
        dataType: 'json',
        data: 'parentID='+parentID+'&depth='+$('#depthSlider').slider('value'),
        url: '/sr/hive/closeComments',
        success: function(data) {
            console.log(data);
            $('#hiveDisplay').html(data.newhive);
            $('#hiveGraphics').svg({
                onLoad: updateHiveGraphics
            });
            rebind();
            if (data.atRoot == 'yes') {
                $('#depthSlider').slider( "option", "disabled", false );
            }
        }
    });
}

function deleteContent(contentID) {
    $.ajax({
        type: 'post',
        dataType: 'json',
        data: 'contentID='+contentID+'&depth='+$('#depthSlider').slider('value'),
        url: '/sr/hive/deleteContent',
        success: function(data) {
            $('#hiveDisplay').html(data.newhive);
            $('#hiveGraphics').svg({
                onLoad: updateHiveGraphics
            });
            rebind();
            if (data.atRoot == 'yes') {
                $('#depthSlider').slider( "option", "disabled", false );
            }
        }
    });
}

