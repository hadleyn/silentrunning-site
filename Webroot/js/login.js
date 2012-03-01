$(document).ready(function(){
   $('#captcha').hide();
   $('#captcha').slideUp();
   $('#registerHandle').keyup(function(){
      checkHandleAvailable();
   });
   $('#registerPasswordConf').keyup(function(){
//      $('#captcha').show();
      $('#captcha').slideDown(); 
   });
});


function checkHandleAvailable(){
   $.ajax({
       type: 'post',
       dataType: 'json',
       data: 'handle='+$('#registerHandle').val(),
       url: 'hive/checkhandle',
       success: function(data){
           console.log(data);
           if (data.handleOk){
               console.log('good');
           } else {
               console.log('bad');
           }
       },
       error: function(data){
           console.log(data);
       }
   });
}
