$(document).ready(function(){
   $('.hiveContentBox').draggable({ containment: 'parent' }); 
});

function rebind() {
    $('.hiveContentBox').draggable({ containment: 'parent' });
}
