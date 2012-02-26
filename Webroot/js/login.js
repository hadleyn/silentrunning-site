$(document).ready(function(){
   $('#captcha').hide(); 
   $('#registerHandle').keyup(function(){
      checkHandleAvailable();
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
