$(document).ready(function(){
    $('#colonyLinkExpires').datepicker();
   
    $('#createColonyLink').click(function(){
        generateLink(); 
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
           console.log(data);
       }
    });
}