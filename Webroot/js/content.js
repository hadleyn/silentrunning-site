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
   var xpos = $('#hiveDisplay').position().left;
   var ypos = $('#hiveDisplay').position().top;
   $.ajax({
      type: 'post',
      dataType: 'json',
      data: $('#contentCreationForm').serialize()+'&hiveTop='+ypos+'&hiveLeft='+xpos,
      url: '/sr/hive/createContent',
      success: function(data){
          if (data.errors.length > 0){
              $('#contentErrors').html(data.errors);
          } else {
              refreshHiveContent();
          }
      }
   });
}

function refreshHiveContent() {
  $.ajax({
     type: 'post',
     dataType: 'json',
     url: '/sr/hive/refreshContent',
     success: function(data){
         $('#hiveDisplay').html(data.hiveContent);
         rebind();
     }
  });
}