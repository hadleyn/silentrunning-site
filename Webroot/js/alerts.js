$(document).ready(function(){
//    setInterval("getAlerts()", 5000);
    getAlerts();
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
