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
       data: 'test=testing',
       url: 'hive/ajaxtest',
       success: function(data){
           console.log(data);
       },
       error: function(data){
           console.log(data);
       }
   });
}
