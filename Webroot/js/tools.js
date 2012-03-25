$(document).ready(function(){
    $('#colonyLinkExpires').datetimepicker();
   
    $('#createColonyLink').click(function(){
        generateLink(); 
    });
    
    $('.deleteColonyLink').live('click', function(){
        deleteColonyLink(this.id); 
    });
});


function generateLink() {
    $.ajax({
        type: 'post',
        dataType: 'json',
        data: 'expires='+$('#colonyLinkExpires').val(),
        url: '/sr/tools/createColonyLink',
        success: function(data){
            $('#colonyLink').val(data.link);
            $('#colonyLinksList').html(data.colonyLinksList);
        }
    });
}

function deleteColonyLink(linkid) {
    $.ajax({
        type: 'post',
        data: 'linkid='+linkid,
        url: '/sr/tools/deleteColonyLink',
        success: function(data) {
           $('#colonyLink_'+linkid).fadeOut(300, function(){
               $('#colonyLink_'+linkid).remove();
           });
        }
    });
}