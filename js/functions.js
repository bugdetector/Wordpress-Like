function like(button) {
	var id = button.id.replace( /^\D+/g, '');
	var text =  document.getElementById("text-"+button.id);

    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : ajaxElement.ajaxurl,
        data : {action: "like_button_clicked",id:id,state:text.innerHTML},
        success: function(response) {
            text.innerHTML = response.state == 1 ? "Beğenildi":"Beğenilmedi" ;
         }
    });
}
