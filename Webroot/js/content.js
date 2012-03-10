$(document).ready(function(){
   $('#insertContent').click(function(evt){
       evt.preventDefault();
       addContent();
   });
   $(document).ajaxSend(function(){
      $('#contentErrors').html(''); 
   });
});

function addContent(){
   $.ajax({
      type: 'post',
      dataType: 'json',
      data: $('#contentCreationForm').serialize(),
      url: '/sr/hive/createContent',
      success: function(data){
          if (data.errors.length > 0){
              $('#contentErrors').html(data.errors);
          }
      }
   });
}