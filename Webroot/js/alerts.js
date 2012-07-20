$(document).ready(function(){
    getAlerts();
    
    $('.alertLink').click(function(evt){
       evt.preventDefault();
       readAlert(this);
    });
});

function getAlerts() {
    $.ajax({
       type: 'post',
       dataType: 'json',
       url: '/sr/hive/getAlerts',
       success: function(data) {
           $('#alertIndicator').html(data.alertIndicator);
       },
       error: function(data) {
           console.log(data);
       }
    });
}

function readAlert(alertLink) {
   var alertid = $(alertLink).attr('id');
   var alertURL = $(alertLink).attr('href');
   $.ajax({
      type: 'post',
      data: 'alertID='+alertid,
      url: '/sr/comms/viewAlert',
      success: function(){
          window.location = alertURL;
      }
   });
}